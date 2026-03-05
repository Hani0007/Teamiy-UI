<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DesignationRequest;
use App\Http\Resources\DesignationResource;
use App\Models\Company;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function fetchDesignations(Request $request)
    {
        $user = auth()->guard('admin-api')->user();
        $roleName = $user->getRoleNames()->first();

        try {
            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            // $validated = $request->validated();

            $designations = Post::orderBy('id', 'desc')
            ->where('branch_id', $request->branch_id)
            ->where('dept_id', $request->department_id)
            ->where('company_id', $companyId)
            ->with(['branch', 'department'])
            ->whereHas('department', function($q) {
                    $q->where('is_active', 1);
                })
            ->get();

            if ($designations->isEmpty()) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => DesignationResource::collection($designations)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' =>  __('something_went_wrong'),
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function storeDesignation(DesignationRequest $request)
    {
        $user = auth()->guard('admin-api')->user();
        $roleName = $user->getRoleNames()->first();

        try{
            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $validated = $request->validated();

            if (!empty($request->designation_id)) {
                $designation = Post::where('id', $request->designation_id)
                    ->where('branch_id', $validated['branch_id'])
                    ->where('dept_id', $validated['department_id'])
                    ->first();

                if (!$designation) {
                    return response()->json(['message' => __('designation_not_found')], 404);
                }

                $designation->update([
                    'post_name' => $validated['designation_name'],
                    'is_active' => $validated['is_active'],
                    'branch_id' => $validated['branch_id'],
                    'dept_id'   => $validated['department_id'],
                ]);
            } else {
                $exists = Post::where('post_name', $validated['designation_name'])
                    ->where('branch_id', $validated['branch_id'])
                    ->where('dept_id', $validated['department_id'])
                    ->exists();

                if ($exists) {
                    return response()->json(['message' => __('designation_exist')], 422);
                }

                $designation = Post::create([
                    'post_name'  => $validated['designation_name'],
                    'branch_id'  => $validated['branch_id'],
                    'dept_id'    => $validated['department_id'],
                    'is_active'  => $validated['is_active'],
                    'company_id' => $companyId
                ]);
            }

            $designation->load(['branch', 'department']);

            return response()->json([
                'message' =>  __('success'),
                'data'    => new DesignationResource($designation)
            ], 200);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function deleteDesignation($id)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = Post::where('id', $id)->first();

            if($rec)
            {
                $rec->delete();

                return response()->json([
                    'message' =>  __('record_deleted'),
                ], 200);
            }

            return response()->json(['message' =>  __('record_not_found')], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
