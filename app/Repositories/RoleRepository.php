<?php

namespace App\Repositories;

use App\Models\PermissionGroup;
use App\Models\PermissionGroupType;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as ModelsRole;

class RoleRepository
{
    const IS_ACTIVE = 1;

    public function getAllUserRoles($select=['*'])
    {
        return ModelsRole::select($select)->latest()->get();
    }

    public function getAllRolesExceptAdmin($select=['*'])
    {
        return ModelsRole::select($select)->where('guard_name','!=','admin')->get();
    }

    public function getAllActiveRoles($select=['*'], $guard = null)
    {
        return ModelsRole::select($select)->where('guard_name', $guard)->get();
    }

    public function getAllActiveRolesByPermission($permissionKey)
    {
        return ModelsRole::select('roles.id', 'roles.name')
            ->leftJoin('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
            ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('permissions.name', $permissionKey)
            ->get();
    }

    public function store($validatedData)
    {
        //$validatedData['created_by'] = getAuthUserCode() ?? null;
        //$validatedData['guard_name'] =   Str::slug($validatedData['name']);
        return ModelsRole::create([
            'name' => $validatedData['name'],
            'guard_name' => ($validatedData['role_for'] === 'employee') ? 'web' : 'admin'
            ])->fresh();
    }

    public function  getRoleById($id,$select=['*'],$with=[])
    {
        return ModelsRole::select($select)
            ->with($with)
            ->where('id',$id)
            ->first();
    }

    public function delete($roleDetail)
    {
        return $roleDetail->delete();
    }

    public function update($roleDetail,$validatedData)
    {
        $validatedData['slug'] =  Str::slug($validatedData['name']);
        return $roleDetail->update($validatedData);
    }

    public function toggleStatus($id)
    {
        $roleDetail = ModelsRole::where('id',$id)->first();
        if ($roleDetail->slug == 'admin') {
            throw new Exception('Sorry, admin role status cannot be changed.', 403);
        }
        return $roleDetail->update([
            'is_active' => !$roleDetail->is_active,
        ]);
    }

    public function getPermissionGroupDetail($select=['*'],$with=[])
    {
        return  PermissionGroup::select($select)
            ->with($with)
            ->get();
    }

    public function getPermissionGroupTypeDetails($select=['*'],$with=[])
    {
        return PermissionGroupType::select($select)
            ->with($with)
            ->get();
    }

    public function syncPermissionToRole($roleDetail,$permissions)
    {
        return $roleDetail->permission()->sync($permissions);
    }


}
