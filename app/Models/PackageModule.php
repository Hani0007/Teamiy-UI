<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageModule extends Model
{
    use HasFactory;

    protected $table = 'package_modules';
    public $timestamps = false;

    public function plan()
    {
        return $this->belongsTo(Package::class, 'plan_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
