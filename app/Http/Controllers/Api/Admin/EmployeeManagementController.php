<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeDetailStep2Request;
use App\Http\Requests\EmployeePersonalDetail;
use App\Http\Resources\EmployeeManagementResource;
use App\Http\Resources\EmployeePersonalDetailResource;
use App\Http\Resources\EmployeesListingResource;
use App\Jobs\SendEmployeeCredentialsMailJob;
use App\Mail\EmployeeCredentialsMail;
use App\Models\Admin;
use App\Models\Company;
use App\Models\EmployeeAccount;
use App\Models\EmployeeDocument;
use App\Models\EmployeeLeaveType;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ImageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class EmployeeManagementController extends Controller
{
    use ImageService;

    public function fetchEmployees()
    {
        $user = auth()->guard('admin-api')->user();

        $roleName = $user->getRoleNames()->first();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $employees = User::where('company_id', $companyId)
                ->with([
                    'roles:id,name',
                    'branch',
                    'department'
                ])
                ->whereHas('branch', function ($q) {
                    $q->where('is_active', 1);
                })
                ->whereHas('department', function ($q) {
                    $q->where('is_active', 1);
                })
                ->get();

            if ($employees->isEmpty()) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => EmployeesListingResource::collection($employees)
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function employeeDetail($id)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $roleName = $user->getRoleNames()->first();
            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $employee = User::where('id', $id)->where('company_id', $companyId)
                ->with([
                    'roles:id,name',
                    'branch:id,name',
                    'department:id,dept_name',
                    'post:id,post_name',
                    'employeeDocuments',
                    'accountDetail'
                ])
                ->first();

            if (!$employee) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => new EmployeeManagementResource($employee)
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function employeeCode()
    {
        try {
            $user = auth()->guard('admin-api')->user();

            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $employeeCode = AppHelper::getEmployeeCode();

            if (empty($employeeCode)) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => ['code' => $employeeCode]
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    // public function personalDetail(EmployeePersonalDetail $request)
    // {
    //     $user = auth()->guard('admin-api')->user();

    //     DB::beginTransaction();

    //     try {
    //         $roleName = $user->getRoleNames()->first();

    //         if ($roleName !== 'super-admin') {
    //             $company = Company::with('admin')->where('admin_id', $user->parent_id)->first();
    //             $planId = $company->admin->plan_id ?? null;
    //         } else {
    //             $user->load('company');
    //             $company = $user->company;
    //         }

    //         if ($roleName === 'super-admin') {
    //             $planId = $user->plan_id ?? null;
    //             $trialExpiry = $user->trial_expiry ?? null;

    //             if ($planId == 2 && $trialExpiry && Carbon::parse($trialExpiry)->lt(Carbon::today())) {
    //                 return response()->json([
    //                     'message' => 'You are not allowed to activate or create new member before upgrading your plan.'
    //                 ], 400);
    //             }
    //         }

    //         if ($planId != 2 && $company) {
    //             $noOfEmployees = $company->no_of_employees ?? 0;

    //             $maxAllowed = AppHelper::allowedAdmins($noOfEmployees);

    //             $activeEmployees = User::where('admin_id', $user->id)
    //                 ->where('is_active', 1)
    //                 ->count();

    //             if ($activeEmployees >= $maxAllowed) {
    //                 return response()->json([
    //                     'message' => 'Your are not allowed to add new member. Allowed members are: ' . $maxAllowed,
    //                 ], 400);
    //             }
    //         }

    //         $validatedData = $request->validated();
    //         $validatedData['post_id'] = $validatedData['designation_id'];
    //         $rawPassword   = $validatedData['password'] ?? null;

    //         if (isset($validatedData['avatar'])) {
    //             $validatedData['avatar'] = $this->storeImage(
    //                 $validatedData['avatar'],
    //                 User::AVATAR_UPLOAD_PATH,
    //                 500,
    //                 500
    //             );
    //         }

    //         if ($rawPassword) {
    //             $validatedData['password'] = bcrypt($rawPassword);
    //         } else {
    //             unset($validatedData['password']);
    //         }

    //         $validatedData['company_id'] = $company->id ?? null;
    //         $validatedData['admin_id']   = $user->id;
    //         if (isset($validatedData['dob'])) {
    //             $validatedData['dob'] = Carbon::parse($validatedData['dob'])->format('Y-m-d');
    //         }

    //         if ($request->filled('employee_id')) {
    //             $employee = User::findOrFail($request->employee_id);
    //             $employee->update($validatedData);
    //             $employee->refresh();
    //         } else {
    //             $employee = User::create($validatedData);
    //         }

    //         if ($request->filled('role_id')) {
    //             $role = Role::findOrFail($request->role_id);
    //             $employee->syncRoles([$role->name]);
    //         }

    //         DB::commit();

    //         $employee->load('role', 'branch', 'department', 'post');

    //         if (!$request->filled('employee_id') && !empty($employee->work_email) && $rawPassword) {
    //             // Mail::to($employee->work_email)->send(
    //             //     new EmployeeCredentialsMail($employee, $rawPassword)
    //             // );
    //             Mail::send('emails.employee_login_credentials', [
    //                 'employeeName' => $employee->name,
    //                 'email'    => $employee->email,
    //                 'password'     => $rawPassword,
    //                 'companyName'  => $employee->company->name ?? '',
    //             ], function ($message) use ($employee) {
    //                 $message->to($employee->work_email)
    //                     ->subject('Your Employee Login Credentials');
    //             });
    //         }

    //         return response()->json([
    //             'message' => $request->filled('employee_id')
    //                 ? __('employee_updated_success')
    //                 :  __('employee_created_success'),
    //             'data'    => new EmployeePersonalDetailResource($employee)
    //         ], 200);
    //     } catch (\Exception $ex) {
    //         DB::rollBack();
    //         return response()->json(['message' => $ex->getMessage()], 400);
    //     }
    // }

    public function personalDetail(EmployeePersonalDetail $request)
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        DB::beginTransaction();

        try {
            $roleName = $user->getRoleNames()->first();
            $company  = $this->getCompanyByUser($user, $roleName);
            $planId   = $this->getPlanId($user, $company, $roleName);

            if ($this->isTrialExpired($user, $roleName, $planId)) {
                return $this->errorResponse(__('not_allowed_before_account_upgrade'));
            }

            $activeEmployees = User::where(function ($query) use ($user) {
                $query->where('admin_id', $user->id);

                if (!is_null($user->parent_id)) {
                    $query->orWhere('admin_id', $user->parent_id);
                }
            })
            ->where('is_active', 1)
            ->count();

            $maxAllowedEmployees = AppHelper::allowedEmployees($company->no_of_employees ?? 0);
            //$maxAllowedAdmins = AppHelper::allowedAdmins($company->no_of_employees ?? 0);

            $activeAdmins = Admin::where(function ($query) use ($user) {
                    $query->where('parent_id', $user->id);

                    if (!is_null($user->parent_id)) {
                        $query->orWhere('parent_id', $user->parent_id);
                    }
                })
                ->where('is_active', 1)
                ->count();

            if (
                !$request->filled('employee_id') &&
                $this->hasReachedEmployeeLimit($user, $company, $planId, $activeEmployees)
            ) {
                return $this->errorResponse(
                    // __('You have reached your employee limit. Maximum allowed employees for your plan: ') .
                    // $maxAllowedEmployees
                     __('employee_limit_exceeded') .
                    $maxAllowedEmployees
                );
            }

            $totalActiveMembers = $activeEmployees + $activeAdmins;

            if (
                !$request->filled('employee_id') &&
                ($totalActiveMembers + 1) > $company->no_of_employees
            ) {
                return $this->errorResponse(
                    // __('You are not allowed to add more members. Your plan allows a total of ') .
                    // $company->no_of_employees .
                    // __(' active members')
                    __('active_member_limit_exceeded') .
                    $company->no_of_employees .
                    __('active_members')
                );
            }

            $validated = $request->validated();
            $validated['post_id']     = $validated['designation_id'];
            $validated['company_id']  = $company->id ?? null;
            $validated['admin_id']    = $user->id;
            $validated['dob']         = isset($validated['dob'])
                ? Carbon::parse($validated['dob'])->format('Y-m-d')
                : null;

            if (!empty($validated['avatar'])) {
                $validated['avatar'] = $this->storeImage(
                    $validated['avatar'],
                    User::AVATAR_UPLOAD_PATH,
                    500,
                    500
                );
            }

            $rawPassword = $validated['password'] ?? null;
            $validated['password'] = $rawPassword ? bcrypt($rawPassword) : null;

            $employee = $request->filled('employee_id')
                ? tap(User::findOrFail($request->employee_id))
                    ->update(collect($validated)->filter()->toArray())
                : User::create(collect($validated)->filter()->toArray());

            if ($request->filled('role_id')) {
                $role = Role::findOrFail($request->role_id);
                $employee->syncRoles([$role->name]);
            }

            DB::commit();

            if (!$request->filled('employee_id') && !empty($employee->work_email) && $rawPassword) {
                dispatch(new SendEmployeeCredentialsMailJob($employee, $rawPassword));
            }

            $employee->load('role', 'branch', 'department', 'post');

            return response()->json([
                'message' => $request->filled('employee_id')
                    ? __('employee_updated_success')
                    : __('employee_created_success'),
                'data'    => new EmployeePersonalDetailResource($employee)
            ], 200);

        } catch (\Throwable $ex) {
            DB::rollBack();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    private function hasReachedEmployeeLimit($user, $company, $planId, $activeEmployees)
    {
        if ($planId == 2 || !$company) {
            return false;
        }

        $maxAllowed = AppHelper::allowedEmployees($company->no_of_employees ?? 0);
        return $activeEmployees >= $maxAllowed;
    }

    private function getCompanyByUser($user, $roleName)
    {
        if ($roleName === 'super-admin') {
            return $user->company()->first();
        }

        return Company::with('admin')
            ->where('admin_id', $user->parent_id)
            ->first();
    }

    private function getPlanId($user, $company, $roleName)
    {
        return $roleName === 'super-admin'
            ? ($user->plan_id ?? null)
            : ($company->admin->plan_id ?? null);
    }

    private function isTrialExpired($user, $roleName, $planId)
    {
        if ($roleName !== 'super-admin' || $planId != 2) {
            return false;
        }

        return $user->trial_expiry && Carbon::parse($user->trial_expiry)->lt(Carbon::today());
    }

    // private function sendEmployeeCredentialsMail($employee, $rawPassword)
    // {
    //     Mail::send('emails.employee_login_credentials', [
    //         'employeeName' => $employee->name,
    //         'email'        => $employee->email,
    //         'password'     => $rawPassword,
    //         'companyName'  => $employee->company->name ?? '',
    //     ], function ($message) use ($employee) {
    //         $message->to($employee->work_email)
    //             ->subject('Your Employee Login Credentials');
    //     });
    // }

    private function errorResponse($message, $code = 400)
    {
        return response()->json(['message' => $message], $code);
    }


    public function employeeDetailStep2(EmployeeDetailStep2Request $request)
    {
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();

            $empContract = null;
            $documents   = [];

            if (isset($validatedData['upload_contract'])) {
                $empContract = $this->storeEmpDocuments(
                    $validatedData['upload_contract'],
                    'uploads/user/emp-documents'
                );
                unset($validatedData['upload_contract']);
            }

            if (isset($validatedData['employee_document'])) {
                foreach ($validatedData['employee_document'] as $document) {
                    $documents[] = $this->storeEmpDocuments(
                        $document,
                        'uploads/user/emp-documents'
                    );
                }
                unset($validatedData['employee_document']);
            }

            $user = User::findOrFail($request->employee_id);
            $user->update($validatedData);

            $accountData = [
                'bank_name'             => $validatedData['bank_name'] ?? null,
                'bank_account_no'       => $validatedData['bank_account_no'] ?? null,
                'bank_account_type'     => $validatedData['bank_account_type'] ?? null,
                'account_holder'        => $validatedData['account_holder'] ?? null,
                'user_id'               => $user->id
            ];

            if (isset($validatedData['bank_name']) && isset($validatedData['bank_account_no'])) {
                EmployeeAccount::create($accountData);
            }

            $employeeDoc = EmployeeDocument::firstOrNew(['employee_id' => $user->id]);

            if ($empContract !== null) {
                $employeeDoc['employee_contract'] = $empContract;
            }

            if (!empty($documents)) {
                $employeeDoc['employee_document'] = $documents;
            }

            $employeeDoc['employee_id'] = $user->id;
            $employeeDoc->save();

            DB::commit();

            $responseData = $user->toArray();

            $responseData['avatar'] = $user->avatar
                ? asset('uploads/user/avatar/' . $user->avatar)
                : null;

            $responseData['employee_documents'] = [
                'employee_contract' => $employeeDoc->employee_contract
                    ? asset('uploads/user/emp-documents/' . $employeeDoc->employee_contract)
                    : null,
                'employee_document' => $employeeDoc->employee_document
                    ? collect($employeeDoc->employee_document)
                    ->map(fn($doc) => asset('uploads/user/emp-documents/' . $doc))
                    ->toArray()
                    : [],
            ];

            $responseData['workspace_type'] = (int) $responseData['workspace_type'];

            return response()->json([
                'message' =>  __('success'),
                'data'    => $responseData,
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' =>  __('update_failed'),
                'error'   => $ex->getMessage(),
            ], 400);
        }
    }

    public function deleteEmployee($id)
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        try {
            $rec = User::where('id', $id)->first();

            if (!$rec) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            DB::beginTransaction();

            if ($rec->avatar) {
                $this->removeImage(User::AVATAR_UPLOAD_PATH, $rec->avatar);
            }

            EmployeeDocument::where('employee_id', $id)->delete();

            $rec->forceDelete();

            DB::commit();

            return response()->json(['message' => __('record_deleted')], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $user = auth()->guard('admin-api')->user();
        $user->load('company');

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 400);
            }

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::with('admin')->where('admin_id', $user->parent_id)->first();
                $planId = $company->admin->plan_id ?? null;
            } else {
                $user->load('company');
                $company = $user->company;
            }

            if ($roleName === 'super-admin') {
                $planId = $user->plan_id ?? null;
                $trialExpiry = $user->trial_expiry ?? null;

                if ($planId == 2 && $trialExpiry && Carbon::parse($trialExpiry)->lt(Carbon::today())) {
                    return response()->json([
                        // 'message' => 'You are not allowed to activate any employee before upgrading your plan.'
                        'message' => __('employee_activation_requires_plan_upgrade')
                    ], 400);
                }
            }

            if ($planId != 2 && isset($company)) {
                $noOfEmployees = $company->no_of_employees ?? 0;

                $maxAllowedEmployees = AppHelper::allowedEmployees($noOfEmployees);
                $maxAllowedAdmin = AppHelper::allowedAdmins($noOfEmployees);

                $activeEmployees = User::where(function ($query) use ($user) {
                    $query->where('admin_id', $user->id);

                    if (!is_null($user->parent_id)) {
                        $query->orWhere('admin_id', $user->parent_id);
                    }
                })
                ->where('is_active', 1)
                ->count();

                $activeAdmins = Admin::where(function ($query) use ($user) {
                    $query->where('parent_id', $user->id);
                    if (!is_null($user->parent_id)) {
                        $query->orWhere('parent_id', $user->parent_id);
                    }
                })
                ->where('is_active', 1)
                ->count();

                if ($request->is_active == 1 && $activeEmployees + 1 > $maxAllowedEmployees) {
                    return response()->json([
                        'message' => __('employee_limit_exceeded') . $noOfEmployees - $maxAllowedAdmin,
                    ], 400);
                }
                else if($request->is_active == 1 && ($activeEmployees + $activeAdmins + 1) > $noOfEmployees)
                {
                    return response()->json([
                        // 'message' => __('You are not allowed to activate member more than ') . $activeEmployees,
                        'message' => __('member_activation_limit_exceeded', ['limit' => $activeEmployees])
                    ], 400);
                }
            }

            User::where('id', $request->id)->update(['is_active' => $request->is_active]);

            return response()->json([
                'message' => __('updated_success'),
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function filteredEmployeesList(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $employees = User::where('company_id', $companyId)
                ->where('branch_id', $request->branch_id)
                ->where('department_id', $request->department_id)
                ->with([
                    'roles:id,name',
                    'branch',
                    'department'
                ])
                ->get();

            if ($employees->isEmpty()) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => EmployeesListingResource::collection($employees)
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function updateLanguage(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 400);
            }

            $emp = User::where('id', $request->emp_id)->first();

            if (!$emp) {
                return response()->json([
                    'message' =>  __('record_not_found'),
                    'data'    => []
                ], 200);
            }

            $emp->update(['lang_code' => $request->lang_code]);

            return response()->json([
                'message' =>  __('updated_success')
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }   
    }
}
