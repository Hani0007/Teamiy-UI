<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Repositories\CompanyRepository;
use App\Requests\Company\CompanyRequest;
use App\Traits\CustomAuthorizesRequests;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IndustryType;

class CompanyController extends Controller
{
    use CustomAuthorizesRequests;
    private $view = 'admin.company.';

    private CompanyRepository $companyRepo;

    public function __construct(CompanyRepository $companyRepo)
    {
        $this->companyRepo = $companyRepo;
    }

    /**
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_company');
        try {
            $rec = Auth::guard('admin')->user();
            $companyDetail = Company::where('admin_id', $rec->id)->with('industry')->first();
            // $industritype =$companyDetail->industry_type;
            // $industries = IndustryType::where('id', $industritype)->get();
            
            if (!$companyDetail) {
                // If no company exists, redirect to create form
                $countries = Country::all();
                $currencies = Currency::all();
                $industries = IndustryType::all();
                return view($this->view . 'index', compact('companyDetail','industries', 'countries', 'currencies'));
            }
            
            // If company exists, show detail view
            return view($this->view . 'detail', compact('companyDetail'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function store(CompanyRequest $request)
    {
        $this->authorize('create_company');

        try {
            $validatedData = $request->validated();
            
            // FIX: Extract phone number from concatenated contact_number
            if (isset($validatedData['contact_number']) && isset($validatedData['country_code'])) {
                $concatenatedNumber = $validatedData['contact_number'];
                $countryCode = $validatedData['country_code'];
                
                // Simple approach: Remove country code from beginning of phone number
                $phoneNumber = $concatenatedNumber;
                
                // Remove country code with space
                if (strpos($phoneNumber, $countryCode . ' ') === 0) {
                    $phoneNumber = substr($phoneNumber, strlen($countryCode . ' '));
                }
                // Remove country code without space
                elseif (strpos($phoneNumber, $countryCode) === 0) {
                    $phoneNumber = substr($phoneNumber, strlen($countryCode));
                }
                // Remove country code with + sign
                elseif (strpos($phoneNumber, '+' . $countryCode) === 0) {
                    $phoneNumber = substr($phoneNumber, strlen('+' . $countryCode));
                }
                
                // Clean up any remaining spaces
                $phoneNumber = trim($phoneNumber);
                
                // Update validated data with clean phone number
                $validatedData['contact_number'] = $phoneNumber;
                
                file_put_contents(storage_path('logs/debug.log'), 
                    "STORE PHONE SPLIT DEBUG:\n" .
                    "Country code string: '" . $countryCode . "'\n" .
                    "Original concatenated: " . $concatenatedNumber . "\n" .
                    "Clean phone number: " . $phoneNumber . "\n" .
                    "==================\n", 
                    FILE_APPEND
                );
            }

            $validatedData['weekend'] = $validatedData['weekend'] ?? [];

            DB::beginTransaction();

           //$validatedData['contact_number'] = explode(' ', $validatedData['contact_number'])[1];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('uploads/company/logo'), $logoName);
                $validatedData['logo'] = $logoName;
            }

            if (empty($request->company_id)) {
                $validatedData['admin_id'] = auth()->guard('admin')->user()->id;
                Company::create($validatedData);
            } else {
                //dd($validatedData);
                // Handle logo removal if new logo is uploaded
                if ($request->hasFile('logo')) {
                    $existingCompany = Company::find($request->company_id);
                    if ($existingCompany && $existingCompany->logo) {
                        // Delete old logo
                        $oldLogoPath = public_path('uploads/company/logo/' . $existingCompany->logo);
                        if (file_exists($oldLogoPath)) {
                            unlink($oldLogoPath);
                        }
                    }
                }
                Company::where('id', $request->company_id)->update($validatedData);
            }

            DB::commit();

            return redirect()
                ->route('admin.company.index')
                ->with('success', __('message.add_company'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.company.index')
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }



    public function edit($id)
    {
        $this->authorize('edit_company');
        try {
            $companyDetail = $this->companyRepo->findOrFailCompanyDetailById($id);
            if (!$companyDetail) {
                throw new Exception('Company Detail Not Found', 404);
            }
            
            $countries = Country::all();
            $currencies = Currency::all();
            $industries = IndustryType::all();
            
            return view($this->view . 'index', compact('companyDetail','industries', 'countries', 'currencies'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(CompanyRequest $request, $id)
    {
        $this->authorize('edit_company');
        try {
            if (env('DEMO_MODE', false)) {
                throw new Exception(__('message.add_company_warning'),400);
            }
            $validatedData = $request->validated();

            // DEBUG: Log the incoming request data in a simple way
            file_put_contents(storage_path('logs/debug.log'), 
                "=== UPDATE DEBUG ===\n" .
                "Time: " . date('Y-m-d H:i:s') . "\n" .
                "contact_number: " . ($request->input('contact_number') ?? 'NULL') . "\n" .
                "country_code: " . ($request->input('country_code') ?? 'NULL') . "\n" .
                "final_contact_number: " . ($request->input('final_contact_number') ?? 'NULL') . "\n" .
                "final_country_code: " . ($request->input('final_country_code') ?? 'NULL') . "\n" .
                "All request data: " . json_encode($request->all()) . "\n" .
                "Validated data: " . json_encode($validatedData) . "\n" .
                "==================\n", 
                FILE_APPEND
            );

            // FIX: Extract phone number from concatenated contact_number
            if (isset($validatedData['contact_number']) && isset($validatedData['country_code'])) {
                $concatenatedNumber = $validatedData['contact_number'];
                $countryCode = $validatedData['country_code'];
                
                // Simple approach: Remove country code from beginning of phone number
                $phoneNumber = $concatenatedNumber;
                
                // Remove country code with space
                if (strpos($phoneNumber, $countryCode . ' ') === 0) {
                    $phoneNumber = substr($phoneNumber, strlen($countryCode . ' '));
                }
                // Remove country code without space
                elseif (strpos($phoneNumber, $countryCode) === 0) {
                    $phoneNumber = substr($phoneNumber, strlen($countryCode));
                }
                // Remove country code with + sign
                elseif (strpos($phoneNumber, '+' . $countryCode) === 0) {
                    $phoneNumber = substr($phoneNumber, strlen('+' . $countryCode));
                }
                
                // Clean up any remaining spaces
                $phoneNumber = trim($phoneNumber);
                
                // Update validated data with clean phone number
                $validatedData['contact_number'] = $phoneNumber;
                
                file_put_contents(storage_path('logs/debug.log'), 
                    "PHONE SPLIT DEBUG:\n" .
                    "Country code string: '" . $countryCode . "'\n" .
                    "Original concatenated: " . $concatenatedNumber . "\n" .
                    "Clean phone number: " . $phoneNumber . "\n" .
                    "==================\n", 
                    FILE_APPEND
                );
            }

            $validatedData['weekend'] = $validatedData['weekend'] ?? [];
            $companyDetail = $this->companyRepo->findOrFailCompanyDetailById($id);
            if (!$companyDetail) {
                throw new Exception('Company Detail Not Found', 404);
            }
            
            DB::beginTransaction();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('uploads/company/logo'), $logoName);
                $validatedData['logo'] = $logoName;
                
                // Delete old logo
                if ($companyDetail->logo) {
                    $oldLogoPath = public_path('uploads/company/logo/' . $companyDetail->logo);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }
            }

            // DEBUG: Log the validated data before update
            file_put_contents(storage_path('logs/debug.log'), 
                "BEFORE UPDATE - Validated Data: " . json_encode($validatedData) . "\n" .
                "BEFORE UPDATE - Company Detail: " . json_encode($companyDetail->toArray()) . "\n" .
                "==================\n", 
                FILE_APPEND
            );

            $this->companyRepo->update($companyDetail, $validatedData);
            
            // DEBUG: Log the company data after update
            file_put_contents(storage_path('logs/debug.log'), 
                "AFTER UPDATE - Company Data: " . json_encode($companyDetail->fresh()->toArray()) . "\n" .
                "==================\n", 
                FILE_APPEND
            );
            
            DB::commit();
            return redirect()->route('admin.company.index')
                ->with('success', __('message.update_company'));

        } catch (Exception $e) {
            DB::rollBack();
            file_put_contents(storage_path('logs/debug.log'), 
                "ERROR: " . $e->getMessage() . "\n" .
                "==================\n", 
                FILE_APPEND
            );
            return redirect()
                ->route('admin.company.index')
                ->with('danger', $e->getMessage())
                ->withInput();

        }
    }
}
