<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Country;
use App\Models\Role;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role as ModelsRole;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:6|max:100',
        ], [
            'email.required' => __('email_required'),
            'email.email'    => __('email_invalid'),
            'email.max'      => __('email_max'),
        
            'password.required' => __('password_required'),
            'password.string'   => __('password_string'),
            'password.min'      => __('password_min'),
            'password.max'      => __('password_max'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = Admin::withTrashed()
            ->where('email', trim($request->email))
            ->where('is_active', 1)
            ->first();

        if ($admin && $admin->trashed()) {
            return response()->json(['message' => __('account_not_exist')], 404);
        }

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => __('invalid_credentials')], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Email verification checks
        |--------------------------------------------------------------------------
        */

        // Email not verified
        if (!$admin->is_verified) {
            return response()->json([
                // 'message' => 'Email not Verified. Please verify.'
                'message' => __('email_not_verified')
            ], 403);
        }

        // OTP exists but expired
        if (
            $admin->email_verification_otp &&
            $admin->email_verification_expires_at &&
            Carbon::now()->greaterThan($admin->email_verification_expires_at)
        ) {
            return response()->json([
                // 'message' => 'Otp has Expired'
                'message' => __('otp_expired')
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Role / company / subscription logic
        |--------------------------------------------------------------------------
        */

        $admin->loadMissing('role');
        $roleName = $admin->getRoleNames()->first();

        if ($roleName !== 'super-admin') {
            $company = $admin->superAdmin?->company()->first();
            $subscription = $admin->superAdmin?->subscription()->first();
        } else {
            $company = $admin->company()->first();
            $subscription = $admin->subscription()->first();
        }

        $admin->setRelation('company', $company);
        $admin->setRelation('subscription', $subscription);
        $admin->loadMissing('plan');

        try {
            $token = $admin->createToken('AdminAPI')->accessToken;
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => __('login_success'),
            'data'    => new AdminResource($admin),
            'token'   => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'required|email|unique:admins,email',
            'password'          => 'required|string|min:6|confirmed',

            // Company fields
            'name'              => 'required|string|max:255',
            'no_of_employees'   => 'required|integer|min:1',
            'contact_number'    => 'required|string|max:20',
            'country_code'      => 'required|string',
            'terms_conditions'  => 'required|accepted'
        ], [
     
             // Admin fields
             'first_name.required' => __('first_name_required'),
             'first_name.string'   => __('first_name_string'),
             'first_name.max'      => __('first_name_max'),
     
             'last_name.required'  => __('last_name_required'),
             'last_name.string'    => __('last_name_string'),
             'last_name.max'       => __('last_name_max'),
     
             'email.required'      => __('email_required'),
             'email.email'         => __('email_invalid'),
             'email.unique'        => __('email_unique'),
     
             'password.required'   => __('password_required'),
             'password.min'        => __('password_min'),
             'password.confirmed'  => __('password_confirmed'),
     
             // Company fields
             'name.required'            => __('company_name_required'),
             'no_of_employees.required' => __('employees_required'),
             'no_of_employees.integer'  => __('employees_integer'),
             'no_of_employees.min'      => __('employees_min'),
     
             'contact_number.required'  => __('contact_number_required'),
             'country_code.required'    => __('country_code_required'),
     
             'terms_conditions.required' => __('terms_required'),
             'terms_conditions.accepted' => __('terms_accepted'),
         ]);

        try {
            DB::beginTransaction();

            $admin = Admin::create([
                'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'plan_id'  => 1,
                'trial_expiry' => Carbon::today()->addDays(15),
            ]);

            if (!$admin) {
                DB::rollBack();
                return response()->json(['message' => __('faild_to_create_admin')], 400);
            }

            $admin->assignRole('super-admin');

            Mail::send('emails.welcome_email', [
                'adminName'   => $validated['first_name'] . ' ' . $validated['last_name'],
                'companyName' => $validated['name'],
            ], function ($message) use ($admin) {
                $message->to($admin->email)
                        ->subject(__('welcome_to_teamy'));
            });

            $otp = rand(100000, 999999);

            $admin->update([
                'email_verification_otp' => $otp,
                'email_verification_expires_at' => Carbon::now()->addMinutes(3),
            ]);

            Mail::send('emails.account_verification_otp', [
                'adminName'   => $validated['first_name'] . ' ' . $validated['last_name'],
                'otp'         => $otp,
            ], function ($message) use ($admin) {
                $message->to($admin->email)
                        ->subject(__('verify_account'));
            });

            $countryCode = Country::where('country_code', $validated['country_code'])->first();

            if($countryCode)
            {
                $validated['country'] = $countryCode->id;
            }

            $companyData = collect($validated)
                ->only([
                    'name',
                    'no_of_employees',
                    'contact_number',
                    'country_code',
                    'terms_conditions',
                    'country'
                ])
                ->toArray();

            $companyData['admin_id'] = $admin->id;

            if (!Company::create($companyData)) {
                DB::rollBack();
                return response()->json(['message' => __('failed_to_create_company')], 400);
            }

            DB::commit();

            $admin->loadMissing(['roles', 'company', 'company.countries', 'company.currency']);

            return response()->json([
                'message' => __('registration_success'),
                'data'    => new AdminResource($admin)
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::error('Registration error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            Log::error(__('registration_error') . ': ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function resendEmailOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:admins,email',
        ], [
            'email.required' => __('email_required'),
            'email.email'    => __('email_invalid'),
            'email.exists'   => __('email_not_exist'),
        ]);
    

        $admin = Admin::where('email', trim($validated['email']))
            ->where('is_active', 1)
            ->first();

        if (!$admin) {
            return response()->json([
                // 'message' => 'Invalid User'
                'message' => __('invalid_user')
            ], 404);
        }

        if ($admin->is_verified) {
            return response()->json([
                // 'message' => 'Email already Verified'
                'message' => __('email_already_verified')
            ], 200);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        Mail::send('emails.account_verification_otp', [
                'adminName'   => $admin->name,
                'otp'         => $otp,
            ], function ($message) use ($admin) {
                $message->to($admin->email)
                        ->subject(__('resend_otp'));
                        // ->subject('Resend OTP');
        });

        $admin->update([
            'email_verification_otp' => $otp,
            'email_verification_expires_at' => Carbon::now()->addMinutes(3),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send OTP Email (example)
        |--------------------------------------------------------------------------
        */

        return response()->json([
            // 'message' => 'Otp Resent Success',
            'message' => __('otp_resent_success'),
        ], 200);
    }

    public function verifyEmailOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:admins,email',
            'otp'   => 'required|string|size:6',
        ], [
            'email.required' => __('email_required'),
            'email.email'    => __('email_invalid'),
            'email.exists'   => __('email_not_found'),
    
            'otp.required'   => __('otp_required'),
            'otp.string'     => __('otp_string'),
            'otp.size'       => __('otp_size'),
        ]);

        $admin = Admin::where('email', $validated['email'])->first();

        if (!$admin) {
            return response()->json(['message' => __('invalid_user')], 404);
        }

        if (
            $admin->email_verification_otp !== $validated['otp'] ||
            Carbon::now()->greaterThan($admin->email_verification_expires_at)
        ) {
            return response()->json(['message' => __('invalid_otp')], 400);
        }

        $admin->update([
            'is_verified' => 1,
            'email_verification_otp' => null,
            'email_verification_expires_at' => null,
        ]);

        return response()->json(['message' => __('email_verified')], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:admins,email',
        ],[
            'email.required' => __('email_required'),
            'email.email'    => __('email_invalid'),
            'email.exists'   => __('email_not_found'),
        ]);

        $admin = Admin::where('email', $validated['email'])->first();

        $otp = rand(100000, 999999);
        $admin->update([
            'password_reset_otp' => $otp,
            'password_reset_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Mail::raw("Your password reset OTP is: {$otp}", function ($message) use ($admin) {
        //     $message->to($admin->email)
        //         ->subject('Password Reset OTP');
        // });

        Mail::send('emails.reset_password', [
            'adminName' => $admin->name,
            'otp'       => $otp,
        ], function ($message) use ($admin) {
            $message->to($admin->email)
                    ->subject(__('password_reset_otp'));
        });

        return response()->json([
            'message' => __('otp_sent')
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:admins,email',
            'otp'   => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed',
        ],[
            'email.required'    => __('email_required'),
            'email.email'       => __('email_invalid'),
            'email.exists'      => __('email_not_found'),
    
            'otp.required'      => __('otp_required'),
            'otp.size'          => __('otp_size'),
    
            'password.required'  => __('password_required'),
            'password.min'       => __('password_min'),
            'password.confirmed' => __('password_confirmed'),
        ]);

        $admin = Admin::where('email', $validated['email'])->first();

        if (
            $admin->password_reset_otp !== $validated['otp'] ||
            Carbon::now()->greaterThan($admin->password_reset_expires_at)
        ) {
            return response()->json([__('invalid_otp')], 400);
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
            'password_reset_otp' => null,
            'password_reset_expires_at' => null,
        ]);

        return response()->json(['message' => __('password_reset_success')], 200);
    }
}
