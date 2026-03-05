<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'plan_id', 'total_employees', 'amount',
                                        'payment_intend_id', 'status', 'cycle', 'stripe_subscription_id', 'last_payment_date', 'next_payment_date'];

    public function plan()
    {
        return $this->belongsTo(Package::class, 'plan_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
