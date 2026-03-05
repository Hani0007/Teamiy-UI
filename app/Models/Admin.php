<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;


class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles, SoftDeletes, CanResetPasswordTrait;

    protected $table = 'admins';
    protected $guard_name = 'admin';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'password',
        'email',
        'username',
        'is_active',
        'avatar',
        'role_id',
        'parent_id',
        'plan_id',
        'email_verification_otp',
        'email_verification_expires_at',
        'is_verified',
        'password_reset_otp',
        'password_reset_expires_at',
        'lang_code',
        'trial_expiry'
    ];

    const AVATAR_UPLOAD_PATH = 'uploads/admin/avatar/';
    const RECORDS_PER_PAGE = 20;

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'admin_id');
    }

    public function branch()
    {
        return $this->hasMany(Branch::class, 'company_id');
    }

    public function department()
    {
        return $this->hasMany(Department::class, 'company_id');
    }

    public function plan()
    {
        return $this->belongsTo(Package::class, 'plan_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'admin_id')->latest();
    }

    public function superAdmin()
    {
        return $this->belongsTo(Admin::class, 'parent_id', 'id');
    }
}
