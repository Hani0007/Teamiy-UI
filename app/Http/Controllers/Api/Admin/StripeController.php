<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePaymentIntentRequest;
use App\Mail\IncreaseEmployeesMail;
use App\Mail\SubscriptionConfirmationMail;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Account;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\EphemeralKey;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Webhook;
use Stripe\Subscription as StripeSubscription;
use Stripe\SubscriptionItem;

class StripeController extends Controller
{
    /**
     * Create or retrieve Stripe customer for current user
     */
    private function createOrGetStripeCustomer($user, $company)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if (!empty($user->stripe_customer_id)) {
            try {
                $customer = Customer::retrieve($user->stripe_customer_id);

                if (!empty($customer->id)) {
                    return $customer;
                }
            } catch (\Exception $e) {
                Log::warning("Stripe customer not found, creating new one: " . $e->getMessage());
            }
        }

        $customer = Customer::create([
            'name'  => $user->name ?? '',
            'email' => $user->email ?? '',
            'phone' => $user->phone ?? null,
            'address' => [
                'line1' => $company->address ?? 'N/A',
                'city' => $company->city ?? 'N/A',
                'postal_code' => $company->postal_code ?? '00000',
                'state' => $company->province ?? 'N/A',
                'country' => $company->countries->code ?? 'US',
            ],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    /**
     * Create an ephemeral key for mobile SDK
     */
    public function createEphemeralKey()
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $response = Account::retrieve();
            $stripeVersion = $response->getLastResponse()->headers['stripe-version'] ?? null;

            if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
            } else {
                $company = Company::where('admin_id', $user->parent_id)->first();
            }

            $customer = $this->createOrGetStripeCustomer($user, $company);

            $ephemeralKey = EphemeralKey::create(
                ['customer' => $customer->id],
                ['stripe_version' => $stripeVersion]
            );

            return response()->json([
                'customer_id' => $customer->id,
                'ephemeral_key' => $ephemeralKey->secret,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Stripe Ephemeral Key Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Create Stripe Subscription (recurring)
     */
    public function createSubscription(CreatePaymentIntentRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        try {
            $validated = $request->validated();

            $planId = $validated['plan_id'];
            $employees = $validated['total_employees'];
            $pricePerEmployee = $validated['price'];
            $cycle      = strtolower($validated['cycle']);
            $amount = $pricePerEmployee * $employees;
            $plan = Package::where('id', $planId)->first();

            if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
            } else {
                $company = Company::where('admin_id', $user->parent_id)->first();
            }

            Stripe::setApiKey(config('services.stripe.secret'));
            $customer = $this->createOrGetStripeCustomer($user, $company);

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
                'message' => __('success'),
                'data' => [
                    'subscription_id' => $subscription->id,
                    'client_secret' => $paymentIntent?->client_secret ?? null,
                    'customer_id' => $customer->id,
                    'amount' => $amount,
                ],
            ], 200);
        } catch (\Exception $ex) {
            Log::error('Stripe Subscription Error: ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|string',
        ]);

        $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        $user->load('company');
        $company = $user->company;

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $stripeSub = StripeSubscription::retrieve($request->subscription_id);

            if ($stripeSub->status !== 'active' && $stripeSub->status !== 'trialing') {
                return response()->json(['message' => __('payment_not_completed')], 400);
            }

            DB::beginTransaction();

            $meta = $stripeSub->metadata;

            $subscription = Subscription::create([
                'admin_id' => $user->id,
                'plan_id' => (int)$meta->plan_id,
                'total_employees' => (int)$meta->employees,
                'amount' => $meta->amount,
                'status' => 'active',
                'cycle' => $meta->cycle,
                'stripe_subscription_id' => $stripeSub->id,
                'last_payment_date' => Carbon::now(),
                'next_payment_date' => Carbon::createFromTimestamp($stripeSub->current_period_end),
            ]);

            $user->update([
                'plan_id' => (int)$meta->plan_id,
            ]);

            // $trialEmployees = $company->no_of_employees;

            if ($company) {
                //$totalEmployees = $company->no_of_employees + (int)$meta->employees;
                $company->update(['no_of_employees' => (int)$meta->employees]);
            }

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

            DB::commit();

            try {
                Mail::to($user->email)->send(new SubscriptionConfirmationMail(
                    $user->name ?? 'Admin',
                    $subscription->plan->name ?? 'N/A',
                    ucfirst($subscription->cycle ?? 'N/A'),
                    $subscription->id
                ));
            } catch (\Exception $e) {
                Log::error("Failed to send subscription email to {$user->email}: " . $e->getMessage());
            }

            return response()->json(['message' => __('success')], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Stripe Subscription Success Error: ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    /**
     * Stripe Webhook - Handles renewals automatically
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $subId = $invoice->subscription;
                $nextPayment = $invoice->next_payment_attempt
                    ? Carbon::createFromTimestamp($invoice->next_payment_attempt)
                    : null;

                $subscription = Subscription::where('stripe_subscription_id', $subId)->first();
                if ($subscription) {
                    $subscription->update([
                        'status' => 'active',
                        'last_payment_date' => Carbon::now(),
                        'next_payment_date' => $nextPayment,
                    ]);

                    Log::info("Subscription renewed for {$subId}");
                }
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                $subId = $invoice->subscription;
                Subscription::where('stripe_subscription_id', $subId)->update(['status' => 'past_due']);
                break;

            case 'customer.subscription.deleted':
                $subId = $event->data->object->id;
                Subscription::where('stripe_subscription_id', $subId)->update(['status' => 'cancelled']);
                break;
        }

        return response()->json(['received' => true], 200);
    }

    public function updateEmployeeCount(Request $request)
    {
         $user = auth()->guard('admin-api')->user();

        if (!$user) {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        $user->load('company');
        $company = $user->company;

        $validated = $request->validate([
            'subscription_id' => 'required|string',
            'new_employee_count' => 'required|integer|min:1',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $subscription =  \Stripe\Subscription::retrieve($validated['subscription_id']);
            $itemId = $subscription->items->data[0]->id;

            $updatedItem = SubscriptionItem::update($itemId, [
                'quantity' => $validated['new_employee_count'],
                'proration_behavior' => 'create_prorations',
            ]);

            $upcoming = Invoice::upcoming([
                'subscription' => $validated['subscription_id'],
            ]);

            if ($company) {
                $totalEmployees = $company->no_of_employees + (int)$validated['new_employee_count'];
                $company->update(['no_of_employees' => $totalEmployees]);
            }

            try {
                Mail::to($user->email)->send(new IncreaseEmployeesMail(
                    $user->name ?? 'Admin',
                ));
            } catch (\Exception $e) {
                Log::error("Failed to send subscription email to {$user->email}: " . $e->getMessage());
            }

            return response()->json([
                'message' => __('success'),
                'data' => [
                    'subscription_item' => $updatedItem->id,
                    'new_quantity' => $validated['new_employee_count'],
                    'next_invoice_amount' => $upcoming->total / 100,
                ],
            ], 200);

        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
