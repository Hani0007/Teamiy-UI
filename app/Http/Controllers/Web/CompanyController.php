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
            $companyDetail = Company::where('admin_id', $rec->id)->first();
            
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
            

            $validatedData['weekend'] = $validatedData['weekend'] ?? [];

            DB::beginTransaction();

           //$validatedData['contact_number'] = explode(' ', $validatedData['contact_number'])[1];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logo->storeAs('uploads/company/logo', $logoName, 'public');
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
                        $oldLogoPath = storage_path('app/public/uploads/company/logo/' . $existingCompany->logo);
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
                $logo->storeAs('uploads/company/logo', $logoName, 'public');
                $validatedData['logo'] = $logoName;
                
                // Delete old logo
                if ($companyDetail->logo) {
                    $oldLogoPath = storage_path('app/public/uploads/company/logo/' . $companyDetail->logo);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }
            }
            
            $this->companyRepo->update($companyDetail, $validatedData);
            DB::commit();
            return redirect()->route('admin.company.index')
                ->with('success', __('message.update_company'));

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.company.index')
                ->with('danger', $e->getMessage())
                ->withInput();

        }
    }
}
