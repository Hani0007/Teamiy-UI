<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price_per_month', 'price_per_year'];

    public function packageModules()
    {
        return $this->hasMany(PackageModule::class, 'plan_id');
    }
}
