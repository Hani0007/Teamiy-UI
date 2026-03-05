<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePayrollApi;
use App\Http\Requests\PayNowApiRequest;
use App\Http\Requests\PayrollApiRequest;
use App\Http\Resources\EmployeePayrollListingResource;
use App\Http\Resources\GeneratedPayrollResource;
use App\Http\Resources\PayrollApiResource;
use App\Models\Company;
use App\Models\EmployeeSalary;
use App\Models\GeneratedPayroll;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayrollManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        try {
            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
            } else {
                $user->load('company');
                $company = $user->company;
            }

            $employees = User::select('id', 'name', 'email', 'avatar')
                    ->with('employeeSalary')
                    ->where('company_id', $company->id)
                    ->when($request->branch_id, function ($query, $branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->when($request->department_id, function ($query, $departmentId) {
                        $query->where('department_id', $departmentId);
                    })
                    ->orderBy('id', 'desc')
                    ->get();

            return response()->json([
                'message' => __('success'),
                'data'    => EmployeePayrollListingResource::collection($employees)
            ], 200);

        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(PayrollApiRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        try {
             $validatedData = $request->validated();

             DB::beginTransaction();

            if ($request->filled('payroll_setup_id')) {
                $emplyeePayrollSetup = EmployeeSalary::findOrFail($request->payroll_setup_id);
                $emplyeePayrollSetup->update($validatedData);
                $message = __('updated_success');
            } else {
                $emplyeePayrollSetup = EmployeeSalary::create($validatedData);
                $message = __('created_success');
            }

             DB::commit();

             $emplyeePayrollSetup->load('employee');

             return response()->json([
                'message' => $message,
                'data'    => new PayrollApiResource($emplyeePayrollSetup)
            ], 200);

        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function generate(GeneratePayrollApi $request)
    {
        $user = auth('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $employeeIds = User::where('branch_id', $validatedData['branch_id'])
                ->where('department_id', $validatedData['department_id'])
                ->pluck('id');

            if ($employeeIds->isEmpty()) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            $salaryCycles = EmployeeSalary::whereIn('employee_id', $employeeIds)->get();

            $missingIds = $employeeIds->diff($salaryCycles->pluck('employee_id'));

            if ($missingIds->isNotEmpty()) {
                return response()->json([
                    'message' => __('create_salary_cycle'),
                ], 400);
            }

            [$payrolls, $updateData] = AppHelper::generatePayroll($salaryCycles, $validatedData);

            if (!empty($payrolls)) {
                GeneratedPayroll::insert($payrolls);
            }

            foreach ($updateData as $data) {
                $id = $data['id'];
                unset($data['id']);
                GeneratedPayroll::where('id', $id)->update($data);
            }

            DB::commit();

            return response()->json(['message' => __('success')], 200);
        } catch (Exception $ex) {
            DB::rollBack();

            Log::error('Payroll generation failed', [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);
            return response()->json(['message' => $ex->getMessage()], $ex->getCode() ?: 400);
        }
    }

    public function fetchPayrolls(Request $request)
    {
        $user = auth('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        $validatedData = $request->validate([
            'branch_id'     => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'year'          => 'nullable|integer',
            'month'         => 'nullable|string',
        ], [
            'branch_id.required'     => __('branch_id_required_new'),
            'branch_id.exists'       => __('branch_id_exists_new'),
            'department_id.required' => __('department_id_required'),
            'department_id.exists'   => __('department_id_exists'),
            'year.integer'           => __('year_integer'),
            'month.string'           => __('month_string'),
        ]);

        $query = GeneratedPayroll::query()
            ->where('branch_id', $validatedData['branch_id'])
            ->where('department_id', $validatedData['department_id']);

        if (!empty($validatedData['year']) && !empty($validatedData['month'])) {
            $startDate = Carbon::parse("first day of {$validatedData['month']} {$validatedData['year']}")->startOfDay()->toDateString();
            $endDate   = Carbon::parse("last day of {$validatedData['month']} {$validatedData['year']}")->endOfDay()->toDateString();

            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`range`, '$.start')) BETWEEN ? AND ?", [$startDate, $endDate])
                ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(`range`, '$.end')) BETWEEN ? AND ?", [$startDate, $endDate]);
            });
        }

        $payrolls = $query
            ->with(['employee:id,name,email', 'branch:id,name', 'department:id,dept_name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => __('success'),
            'data'    => GeneratedPayrollResource::collection($payrolls)
        ]);
    }

    public function payNow(PayNowApiRequest $request)
    {
        $user = auth('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        try {
            $validatedData = $request->validated();
            $validatedData['year'] = Carbon::now()->year;

            $query = GeneratedPayroll::query()
                ->where('branch_id', $validatedData['branch_id'])
                ->where('department_id', $validatedData['department_id']);

            if (!empty($validatedData['year']) && !empty($validatedData['month'])) {
                $startDate = Carbon::parse("first day of {$validatedData['month']} {$validatedData['year']}")->startOfDay()->toDateString();
                $endDate   = Carbon::parse("last day of {$validatedData['month']} {$validatedData['year']}")->endOfDay()->toDateString();

                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`range`, '$.start')) BETWEEN ? AND ?", [$startDate, $endDate])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(`range`, '$.end')) BETWEEN ? AND ?", [$startDate, $endDate]);
                });
            }

            $updatedCount = $query->update([
                'status'      => $validatedData['status'],
                'updated_at'  => now(),
            ]);

            if ($updatedCount > 0) {
                return response()->json([
                    'message' => __('updated_success'),
                ]);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], $ex->getCode() ?: 400);
        }
    }

    public function generateInvoice(Request $request)
    {
        $user = auth('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        $validatedData = $request->validate([
            'payroll_id' => 'required|exists:generated_payrolls,id'
        ], [
            'payroll_id.required' => __('payroll_id_required'),
            'payroll_id.exists'   => __('payroll_id_exists'),
        ]);

        try {
            $payroll = GeneratedPayroll::with([
                'employee.department',
                'employee.company.currency'
            ])->find($validatedData['payroll_id']);

            if (!$payroll) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            $currencySymbol = $payroll->employee->company->currency->symbol ?? '€';

            $pdf = Pdf::loadView('payroll.payslip', [
                'payroll' => $payroll,
                'currencySymbol' => $currencySymbol,
            ])->setPaper('A4', 'portrait');

            $directory = public_path('salary_slips');
            if (!file_exists($directory)) {
                mkdir($directory, 0775, true);
            }

            $fileName = 'payslip_' . $payroll->employee->employee_code . '_' . now()->format('YmdHis') . '.pdf';
            $filePath = $directory . '/' . $fileName;

            $pdf->save($filePath);
            $downloadUrl = url('salary_slips/' . $fileName);

            return response()->json([
                'message' => __('success'),
                'download_url' => $downloadUrl
            ], 200);

        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
