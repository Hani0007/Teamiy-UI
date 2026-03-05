<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserManagementResource;
use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use App\Requests\Admin\AdminRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function fetchUsers()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            $admins = Admin::with('roles:id,name')
                ->whereNotNull('parent_id')
                ->when($user->hasRole('super-admin'), function ($q) use ($user) {
                    $q->where(function ($sub) use ($user) {
                        $sub->where('parent_id', $user->id);

                        $childIds = Admin::where('parent_id', $user->id)->pluck('id');
                        if ($childIds->isNotEmpty()) {
                            $sub->orWhereIn('parent_id', $childIds);
                        }
                    });
                }, function ($q) use ($user) {
                    $q->where(function ($sub) use ($user) {
                        $sub->where('parent_id', $user->id)
                            ->orWhere('parent_id', $user->parent_id);
                    });
                })
                ->orderBy('id', 'desc')
                ->get();

            if ($admins->isEmpty()) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => UserManagementResource::collection($admins)
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function storeAdminUser(AdminRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();

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

                if ($planId == 2 && isset($trialExpiry) && Carbon::parse($trialExpiry)->lt(Carbon::today())) {
                    return response()->json([
                        // 'message' => 'You are not allowed to activate or create new member before upgrading your plan.'
                        'message' => __('member_activation_requires_plan_upgrade')

                    ], 400);
                }
            }

            if ($planId != 2 && $company) {
                $noOfEmployees = $company->no_of_employees ?? 0;

               // $maxAllowedEmployees = AppHelper::allowedEmployees($noOfEmployees);
                $maxAllowedAdmins = AppHelper::allowedAdmins($noOfEmployees);

                $activeAdmins = Admin::where(function ($query) use ($user) {
                    $query->where('parent_id', $user->id);

                    if (!is_null($user->parent_id)) {
                        $query->orWhere('parent_id', $user->parent_id);
                    }
                })
                ->where('is_active', 1)
                ->count();

                $activeEmployees = User::where(function ($query) use ($user) {
                    $query->where('admin_id', $user->id);

                    if (!is_null($user->parent_id)) {
                        $query->orWhere('admin_id', $user->parent_id);
                    }
                })
                ->where('is_active', 1)
                ->count();

                if ($request->is_active == 1 && ($activeAdmins + $activeEmployees + 1) > $noOfEmployees) {
                    return response()->json([
                        'message' => __('active_member_limit_exceeded', ['limit' => $noOfEmployees])
                    ], 400);
                }

                if ($request->is_active == 1 && ($activeAdmins + 1) > $maxAllowedAdmins) {
                    return response()->json([
                        'message' => __('admin_limit_exceeded', ['limit' => $maxAllowedAdmins])
                    ], 400);
                }
            }

            $isUpdate = !empty($request->id);

            if (!$isUpdate) {
                $validatedData['password']  = bcrypt($validatedData['password']);
                $validatedData['parent_id'] = $user->id;
                $validatedData['plan_id']   = 1;
            } else {
                if (!empty($validatedData['password'])) {
                    $validatedData['password'] = bcrypt($validatedData['password']);
                } else {
                    unset($validatedData['password']);
                }
            }

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $file = $request->file('avatar');
                $validatedData['avatar'] = AppHelper::storeImage(
                    $file,
                    Admin::AVATAR_UPLOAD_PATH,
                    500,
                    500
                );
            }

            DB::beginTransaction();

            if ($isUpdate) {
                $admin = Admin::findOrFail($request->id);
                $admin->update($validatedData);
            } else {
                $admin = Admin::create($validatedData);
            }

            if (!empty($validatedData['role_id'])) {
                $role = Role::find($validatedData['role_id']);
                if ($role) {
                    $admin->syncRoles([$role->name]);
                }
            }

            DB::commit();

            $admin->refresh();

            return response()->json([
                'message' => __('success'),
                'data'    => new UserManagementResource($admin),
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }


    public function deleteUser($id)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = Admin::where('id', $id)->first();

            if ($rec) {
                $rec->delete();

                return response()->json([
                    'message' =>  __('record_deleted'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function roles()
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        $roles = Role::where('guard_name', 'admin')
            ->whereNot('name', 'super-admin')
            ->select('id', 'name')
            ->get();

        if ($roles->isEmpty()) {
            return response()->json(['message' => __('record_not_found')], 404);
        }

        return response()->json([
            'message' =>  __('success'),
            'data'    => $roles
        ], 200);
    }

    public function updateLanguage(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 400);
            }

            $admin = Admin::where('id', $user->id)->first();

            if (!$admin) {
                return response()->json([
                    'message' =>  __('record_not_found'),
                    'data'    => []
                ], 200);
            }

            $admin->update(['lang_code' => $request->lang_code]);

            return response()->json([
                'message' =>  __('updated_success')
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
