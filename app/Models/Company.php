<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'industry_type',
        'no_of_employees',
        'contact_number',
        'country',
        'province',
        'city',
        'postal_code',
        'address',
        'website_url',
        'currency_preference',
        'admin_id',
        'terms_conditions',
        'weekend',
        'logo',
        'country_code',
        'vat_number',
        'company_registration'
    ];

    protected $casts = [
        'weekend' => 'array',
    ];

    const RECORDS_PER_PAGE = 10;

    const UPLOAD_PATH = 'uploads/company/logo/';

//
//    public static function boot()
//    {
//        parent::boot();
//
//        static::updating(static function ($model) {
//            $model->updated_by = Auth::user()->id;
//        });
//    }

    protected function weekend(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value),
            set: fn($value) => json_encode($value),
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class,'company_id','id')
            ->select('id','name')
            ->where('is_active',1);
    }

    public function employee()
    {
        return $this->hasMany(User::class,'company_id','id')
            ->select('*')
            ->where('is_active',1)
            ->where('status','verified')
            ->orderBy('name');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_preference', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function departments()
    {
        return $this->hasManyThrough(Department::class, Branch::class);
    }
    // public function leaveTypes()
    // {
    //     return $this->hasMany(LeaveType::class,'company_id','id')
    //         ->select('id','name')
    //         ->where('is_active',1);
    // }
    public function LeaveTypes(){
        return $this->hasManyThrough(LeaveType::class, Branch::class);
    }

    public function industry()
    {
        return $this->belongsTo(IndustryType::class, 'industry_type', 'id');
    }
}
