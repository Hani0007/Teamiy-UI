<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;
use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\Company;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{
    private function getCompany($user)
    {
        return $user->hasRole('super-admin')
            ? $user->company()->first()
            : Company::where('admin_id', $user->parent_id)->first();
    }

    private function createOrGetStripeCustomer($user, $company)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if ($user->stripe_customer_id) {
            try {
                return Customer::retrieve($user->stripe_customer_id);
            } catch (\Exception $e) {}
        }

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    public function createSubscription(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|integer',
            'total_employees' => 'required|integer|min:1',
            'cycle' => 'required|in:monthly,yearly',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('validation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $company = $this->getCompany($user);
            $customer = $this->createOrGetStripeCustomer($user, $company);

            $planId = $request->plan_id;
            $plan = Package::where('id', $planId)->first();

            if (!$plan) {
                // throw new \Exception('Invalid plan selected');
                throw new \Exception(__('invalid_plan_selected'));
            }

            $employees = $request->total_employees;
            $cycle      = strtolower($request->cycle);
            $pricePerEmployee =  $cycle === 'monthly' ? $plan->price_per_month : $plan->price_per_year;
            $amount = $pricePerEmployee * $employees;

            $planName   = strtolower($plan->name);
            $key        = "{$planName}_{$cycle}";

            $stripeProductId = config("services.stripe.product_id.$key");

            $subscription = \Stripe\Subscription::create([
                'customer' => $customer->id,
                'items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => (int) ($amount * 100),
                        'recurring' => ['interval' => $cycle === 'monthly' ? 'month' : 'year'],
                        'product' => $stripeProductId,
                    ],
                ]],
                'metadata' => [
                    'plan_id' => $planId,
                    'employees' => $employees,
                    'cycle' => $cycle,
                    'amount' => $amount,
                ],
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => [
                    'save_default_payment_method' => 'on_subscription',
                ],
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            $paymentIntent = $subscription->latest_invoice->payment_intent ?? null;

            return response()->json([
                'subscription_id' => $subscription->id,
                'client_secret' => $paymentIntent?->client_secret ?? null,
            ]);

        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                // 'message' => 'Stripe error',
                'message' => __('stripe_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|string',
        ]);

        $user = auth()->guard('admin')->user();
        $company = $this->getCompany($user);

        Stripe::setApiKey(config('services.stripe.secret'));
        $stripeSub = StripeSubscription::retrieve($request->subscription_id);

        if ($stripeSub->status !== 'active') {
            throw new \Exception('Subscription not active');
        }

        DB::transaction(function () use ($stripeSub, $user, $company) {
            $meta = $stripeSub->metadata;

            $subscription = Subscription::create([
                'admin_id' => $user->id,
                'plan_id' => $meta->plan_id,
                'total_employees' => $meta->employees,
                'amount' => $meta->amount,
                'cycle' => $meta->cycle,
                'status' => 'active',
                'stripe_subscription_id' => $stripeSub->id,
                'last_payment_date' => Carbon::now(),
                'next_payment_date' =>
                    Carbon::createFromTimestamp($stripeSub->current_period_end),
            ]);

            if ($company) {
                $company->update([
                    'no_of_employees' => $meta->employees,
                ]);
            }

            $user->update(['plan_id' => $meta->plan_id]);

            $adminIds = Admin::where('is_active', 1)
                ->where(function ($query) use ($user) {
                    $query->where('id', $user->id)
                        ->orWhere('parent_id', $user->id)
                        ->orWhereIn('parent_id', function ($q) use ($user) {
                            $q->select('id')
                                ->from('admins')
                                ->where('parent_id', $user->id);
                        });
                })
                ->pluck('id');

            $userCount = User::where('is_active', 1)
                ->whereIn('admin_id', $adminIds)
                ->count();

            $adminCount = $adminIds->filter(fn($id) => $id != $user->id)->count();
            $totalCount = $adminCount + $userCount;

            if ($totalCount > (int)$subscription->employees) {
                User::whereIn('admin_id', $adminIds)->update(['is_active' => 0]);
                $adminIds = $adminIds->filter(fn($id) => $id != $user->id)->values();
                Admin::whereIn('id', $adminIds)->update(['is_active' => 0]);
                Log::info("Deactivated $totalCount records for admin ID {$user->id} due to employee limit.");
            }
        });

        session(['subscription_success' => true]);

        return response()->json(['success' => true]);
    }

    public function thankYou()
    {
        if (!session()->pull('subscription_success')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.subscription.thank-you');
    }
}
