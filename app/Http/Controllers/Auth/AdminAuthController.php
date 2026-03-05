<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registeration;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Role;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Services\Admin\AdminService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role as ModelsRole;

class AdminAuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'admin/dashboard/';

    private UserRepository $userRepo;
    private AdminService $adminService;
    private CompanyRepository $companyRepo;

    protected $guardName = 'web'; // default guard (user)

    protected function guard()
    {
        return Auth::guard($this->guardName);
    }

    public function __construct(UserRepository $userRepo,CompanyRepository $companyRepo, AdminService $adminService)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->userRepo = $userRepo;
        $this->companyRepo = $companyRepo;
        $this->adminService = $adminService;
    }

    public function showAdminLoginForm(): View|Factory|Application|RedirectResponse
    {
        //$select = ['logo', 'name'];
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::check()) {
            return redirect('/'); // Redirect logged-in users to their own dashboard
        }

        //$companyDetail = $this->companyRepo->getCompanyDetail($select);
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);

            // Get user_type from form
            $type = $request->input('user_type', 'employee');

            // Map type → guard
            $this->guardName = $type === 'admin' ? 'admin' : 'web';

            $this->checkCredential($request);

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }

            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);

        } catch (Exception $e) {
            // Check if this is email verification error
            if ($e->getMessage() === __('auth.email_not_verified')) {
                // Get the admin user to set session
                $loginField = $request->get('email');
                $user = $this->adminService->getAdminByAdminEmail($loginField, ['id', 'name', 'email'])
                    ?? $this->adminService->getAdminByAdminName($loginField, ['id', 'name', 'email']);

                if ($user) {
                    session(['pending_verification_email' => $user->email]);
                    return redirect()->route('admin.verify.show')
                        ->with('danger', __('auth.email_not_verified'));
                }
            }

            return redirect()->back()->with('danger', $e->getMessage())->withInput();
        }

    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:admin,employee',
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function username()
    {
        return 'email';
    }

    /**
     * @throws Exception
     */
    public function checkCredential($request)
    {
        $select = ['id', 'name', 'email', 'username', 'password'];
        $isAdmin = $this->checkAdmin($request);
        $loginField = $request->get('email'); // Initially assume email

        // Fetch user/admin based on user_type
        if ($isAdmin) {
            $user = $this->adminService->getAdminByAdminEmail($loginField, $select)
                ?? $this->adminService->getAdminByAdminName($loginField, $select);
        } else {
            $user = $this->userRepo->getUserByUserEmail($loginField, $select)
                ?? $this->userRepo->getUserByUserName($loginField, $select);
        }
        $request['login_type'] = $user && $user->email === $loginField ? 'email' : 'username';

        if (!$user) {
            throw new Exception(__('auth.username_not_found'));
        }

        if (!Hash::check($request->get('password'), $user->password)) {
            throw new Exception(__('auth.invalid_credentials'));
        }

        // Check if admin email is verified - always check fresh database value
        if ($isAdmin) {
            $freshAdmin = \App\Models\Admin::where('id', $user->id)->first(['is_verified']);

            if (!$freshAdmin || !$freshAdmin->is_verified) {
                // Debug: Log verification status
                \Log::info('Admin login attempt - email not verified', [
                    'admin_id' => $user->id,
                    'email' => $user->email,
                    'cached_is_verified' => $user->is_verified,
                    'fresh_is_verified' => $freshAdmin ? $freshAdmin->is_verified : 'null'
                ]);

                throw new Exception(__('auth.email_not_verified'));
            }

            // Update the cached user object with fresh verification status
            $user->is_verified = $freshAdmin->is_verified;
        }

        // Adjust login field based on what was matched
        if ($request['login_type'] === 'username') {
            $request['username'] = $loginField;
        }

    }

    protected function attemptLogin(Request $request)
    {
        $isAdmin = $this->checkAdmin($request);
        $guard ='admin';

        if($isAdmin){
            return Auth::guard($guard)->attempt(
                $this->credentials($request),
                $request->boolean('remember')
            );
        }else{
            return $this->guard()->attempt(
                $this->credentials($request),
                $request->boolean('remember')
            );
        }
    }

    protected function credentials(Request $request)
    {
        return [$request['login_type'] => $request->get($request['login_type']), 'password' => $request->get('password')];
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        $isAdmin = $this->checkAdmin($request);


        if(!$isAdmin){
            $customResponse = $this->authenticated($request, Auth::guard('admin')->user());
        }else{
            $customResponse = $this->authenticated($request, Auth::guard()->user());
        }

        $redirectTo = $this->redirectTo;

        return $customResponse ?: redirect()->intended($redirectTo);
    }

    public function logout(Request $request)
    {

        if (Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
        }else{
            Auth::guard()->logout();
        }


        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login') ;
    }

    public function checkAdmin($request): bool
    {
        return $request->get('user_type') === 'admin';
    }

    public function register(Request $request)
    {
        ////$select = ['logo', 'name'];
        //$companyDetail = $this->companyRepo->getCompanyDetail($select);
        $companyDetail = null;
        // $roles = Role::all();
        return view('auth.register', compact('companyDetail'));
    }

    public function registerCompany(Registeration $request)
    {
        try {
            $validatedData = $request->validated();

            $adminUsaerData = [
                'name'     => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'email'    => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'plan_id'  => 1,
                'trial_expiry' => Carbon::today()->addDays(15)
            ];

            $admin = Admin::create($adminUsaerData);

            if ($admin) {
                $admin->assignRole('super-admin');

                Mail::send('emails.welcome_email', [
                    'adminName'   => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                    'companyName' => $validatedData['name'],
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
                    'adminName'   => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                    'otp'         => $otp,
                ], function ($message) use ($admin) {
                    $message->to($admin->email)
                            ->subject(__('verify_account'));
                });

                $companyDetail = [
                    'name'               => $validatedData['name'],
                    'no_of_employees'    => $validatedData['no_of_employees'],
                    'contact_number'     => $validatedData['contact_number'],
                    'terms_conditions'   => isset($validatedData['terms_conditions']) && $validatedData['terms_conditions'] === 'on' ? 1 : 0,
                    'admin_id'           => $admin->id,
                    'country_code'      => $validatedData['country_code']
                ];

                Company::create($companyDetail);
                session(['pending_verification_email' => $admin->email]);
                return redirect()->route('admin.verify.show')
                                 ->with('success', __('registration_otp_sent'));
            }

            return back()->with('error', 'Something went wrong. Please try again.');

        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }

    }

    public function showVerificationForm(): View|Factory|Application
    {
        $email = session('pending_verification_email');
        $admin = $email ? Admin::where('email', $email)->first() : null;
        $expiresMs = null;
        if ($admin && $admin->email_verification_expires_at) {
            try {
                $expiresMs = \Carbon\Carbon::parse($admin->email_verification_expires_at)->getTimestamp() * 1000;
            } catch (\Exception $e) {
                $expiresMs = null;
            }
        }
        return view('auth.verify', compact('email', 'expiresMs'));
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $email = $request->input('email');
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return redirect()->back()->with('danger', __('invalid_user'));
        }

        $code = $request->input('otp');
        if (!$admin->email_verification_otp ||
            !$admin->email_verification_expires_at ||
            now()->greaterThan($admin->email_verification_expires_at) ||
            (string)$admin->email_verification_otp !== (string)$code) {
            return redirect()->back()->with('danger', __('invalid_otp'));
        }

        $admin->update([
            'is_verified' => 1,
            'email_verification_otp' => null,
            'email_verification_expires_at' => null,
        ]);

        // Refresh the admin model to get updated data
        $admin->refresh();

        // Debug: Check if verification was successful
        \Log::info('Admin verification completed', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'is_verified' => $admin->is_verified,
            'verification_otp' => $admin->email_verification_otp
        ]);

        session()->forget('pending_verification_email');
        session()->forget('email_verification_error');

        // Clear any cached user data
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        return redirect()->route('admin.verification.success')->with('success', __('email_verified'));
    }

    public function showVerificationSuccess(): View|Factory|Application
    {
        return view('auth.verification-success');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->input('email', session('pending_verification_email'));
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return redirect()->back()->with('danger', __('invalid_user'));
        }

        if ($admin->email_verification_expires_at && now()->lessThan(\Carbon\Carbon::parse($admin->email_verification_expires_at))) {
            return redirect()->back()->with('danger', 'Please wait until the code expires to resend.');
        }

        $otp = rand(100000, 999999);
        $admin->update([
            'email_verification_otp' => $otp,
            'email_verification_expires_at' => Carbon::now()->addMinutes(3),
        ]);

        Mail::send('emails.account_verification_otp', [
            'adminName' => $admin->name,
            'otp' => $otp,
        ], function ($message) use ($admin) {
            $message->to($admin->email)->subject(__('verify_account'));
        });

        return redirect()->back()->with('success', __('otp_sent'));
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user instanceof Admin && !$user->is_verified) {
            Auth::guard('admin')->logout();
            session(['pending_verification_email' => $user->email]);
            return redirect()->route('admin.verify.show')
                ->with('danger', __('auth.email_not_verified'));
        }
        return null;
    }
}

