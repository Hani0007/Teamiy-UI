<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function fetchBranches()
    {
        $user = auth()->guard('admin-api')->user();

        if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
        } else {
            $company = Company::where('admin_id', $user->parent_id)->first();
        }

        try{
            $branches = Branch::with('attendanceMachine')->where('company_id', $company->id)->get();

            if ($branches->isEmpty()) {
                return response()->json([
                    'message' =>  __('no_branches_found'),
                    'data'    => []
                ], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => $branches
            ], 200);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function storeBranch(BranchRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        if ($user->hasRole('super-admin')) {
            $company = $user->company()->first();
        } else {
            $company = Company::where('admin_id', $user->parent_id)->first();
        }

        $validated = $request->validated();

        try {
            if (!empty($request->branch_id)) {
                $branch = Branch::where('id', $request->branch_id)
                    ->where('company_id', $company->id)
                    ->first();

                if (!$branch) {
                    return response()->json(['message' => __('no_branches_found')], 404);
                }

                $branch->update($validated + ['company_id' => $company->id]);

            } else {
                $branch = Branch::create($validated + ['company_id' => $company->id]);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => $branch
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function deleteBranch($id)
    {
        $user = auth()->guard('admin-api')->user();

        if ($user->hasRole('super-admin')) {
            $company = $user->company()->first();
        } else {
            $company = Company::where('admin_id', $user->parent_id)->first();
        }

        try{
            $rec = Branch::where('id', $id)->where('company_id', $company->id)->first();

            if($rec)
            {
                $rec->delete();

                return response()->json([
                    'message' => __('record_deleted'),
                ], 200);
            }

            return response()->json(['message' => __('no_branches_found')], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
