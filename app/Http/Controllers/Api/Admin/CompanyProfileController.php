<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyProfileRequest;
use App\Http\Resources\CompanyProfileResource;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use Exception;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function companyProfile()
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if ($user->hasRole('super-admin')) {
                $adminId = $user->id;
            } else {
                $adminId = $user->parent_id;
            }

            $companyDetails = Company::with('countries')->where('admin_id', $adminId)->first();

            if(!$companyDetails)
            {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => new CompanyResource($companyDetails)
            ], 200);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }


    public function updateCompanyProfile(CompanyProfileRequest $request)
    {
        $user = auth()->guard('admin-api')->user();
        $validated = $request->validated();

        try {
            $company = Company::where('admin_id', $user->id)->firstOrFail();

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $filename = 'company_' . time() . '.' . $logo->getClientOriginalExtension();
                $path = public_path('uploads/company');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $logo->move($path, $filename);

                if (!empty($company->logo) && file_exists($path . '/' . $company->logo)) {
                    unlink($path . '/' . $company->logo);
                }

                $validated['logo'] = $filename;
            }

            $company->update($validated);
            $company->loadMissing(['countries', 'currency']);

            return response()->json([
                'message' => __('success'),
                'data' => new CompanyProfileResource($company)
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
