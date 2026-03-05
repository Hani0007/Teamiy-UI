<?php

namespace App\Repositories;

use App\Models\AssetType;
use App\Models\Branch;
use App\Models\Company;

class AssetTypeRepository
{

    public function getAllAssetTypes($filterParameters,$select=['*'],$with=[])
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            $company = $user->company()->first();
        } else {
            $company = Company::where('admin_id', $user->parent_id)->first();
        }

        $branches = Branch::where('company_id', $company->id)->pluck('id')->toArray();

        return AssetType::select($select)->withCount($with)
            ->when(isset($filterParameters['branch_id']), function ($query) use ($filterParameters) {
                $query->where('branch_id', $filterParameters['branch_id']);
            })
            ->when(isset($filterParameters['type']), function($query) use ($filterParameters){
                $query->where('name', 'like', '%' . $filterParameters['type'] . '%');
            })
            ->whereIn('branch_id', $branches)
            ->get();
    }

    public function getAllActiveAssetTypes($select=['*'],$with=[])
    {
        return AssetType::select($select)->with($with)->where('is_active',1)->get();
    }

    public function getBranchAssetTypes($branchId,$select=['*'])
    {
        return AssetType::select($select)->where('branch_id',$branchId)->where('is_active',1)->get();
    }

    public function findAssetTypeById($id,$select=['*'],$with=[])
    {
        return AssetType::select($select)->with($with)->where('id',$id)->first();
    }

    public function create($validatedData)
    {
        return AssetType::create($validatedData)->fresh();
    }

    public function update($assetTypeDetail,$validatedData)
    {
        return $assetTypeDetail->update($validatedData);
    }

    public function delete($assetTypeDetail)
    {
        return $assetTypeDetail->delete();
    }

    public function toggleIsActiveStatus($assetTypeDetail)
    {
        return $assetTypeDetail->update([
            'is_active' => !$assetTypeDetail->is_active,
        ]);
    }
}
