<?php



namespace App\Http\Controllers\Web;



use App\Helpers\AppHelper;

use App\Http\Controllers\Controller;

use App\Models\Admin;

use App\Models\Role;

use App\Requests\Admin\AdminRequest;

use App\Requests\User\ChangePasswordRequest;

use App\Requests\User\UserCreateRequest;

use App\Requests\User\UserUpdateRequest;

use App\Services\Admin\AdminService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

use Exception;

use Spatie\Permission\Models\Role as ModelsRole;



class AdminController extends Controller

{

    private $view = 'admin.users.';





    public function __construct(protected AdminService $adminService,)

    {

    }



    public function index(Request $request)

    {

        try {

            $admins = $this->adminService->getAllAdmin();

            return view($this->view . 'index', compact('admins'));

        } catch (Exception $exception) {

            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }



    public function create()

    {

        try {

            $roles = ModelsRole::where('guard_name', 'admin')->whereNotIn('name', ['super-admin'])

                            ->orderBy('id', 'desc')

                            ->get();

            return view($this->view . 'create', compact('roles'));

        } catch (Exception $exception) {

            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }



    public function store(AdminRequest $request)

    {

        try {

            $validatedData = $request->validated();



            $validatedData['password'] = bcrypt($validatedData['password']);

            $validatedData['is_active'] = 1;

            $validatedData['parent_id'] = auth()->guard('admin')->user()->id;

            $validatedData['plan_id'] = 1;

            $validatedData['is_verified'] = 0; // New admins need verification



            DB::beginTransaction();



            $admin = $this->adminService->saveAdmin($validatedData);



            if ($request->filled('role_id')) {

                $role = \Spatie\Permission\Models\Role::find($request->role_id);

                if ($role) {

                    $admin->assignRole($role->name);

                }

            }

            // Send welcome email
            try {
                Mail::send('emails.welcome_email', [
                    'adminName'   => $admin->name,
                    'companyName' => auth()->guard('admin')->user()->company->name ?? 'Teamiy',
                ], function ($message) use ($admin) {
                    $message->to($admin->email)
                            ->subject(__('welcome_to_teamy'));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email to admin', [
                    'admin_id' => $admin->id,
                    'email' => $admin->email,
                    'error' => $e->getMessage()
                ]);
            }

            // Generate and send verification OTP
            $otp = rand(100000, 999999);
            $admin->update([
                'email_verification_otp' => $otp,
                'email_verification_expires_at' => Carbon::now()->addMinutes(3),
            ]);

            try {
                Mail::send('emails.account_verification_otp', [
                    'adminName'   => $admin->name,
                    'otp'         => $otp,
                ], function ($message) use ($admin) {
                    $message->to($admin->email)
                            ->subject(__('verify_account'));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send verification OTP to admin', [
                    'admin_id' => $admin->id,
                    'email' => $admin->email,
                    'error' => $e->getMessage()
                ]);
            }



            DB::commit();

            return redirect()

                ->route('admin.users.index')

                ->with('success', __('message.add_user') . ' A verification email has been sent to ' . $admin->email);

        } catch (Exception $exception) {

            DB::rollBack();

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();

        }

    }



    public function show($id)

    {

        try {

            $userDetail = $this->adminService->findAdminById($id);

            return view($this->view . 'show2', compact('userDetail'));

        } catch (Exception $exception) {

            return redirect()->back()->with('danger', $exception->getFile());

        }

    }



    public function edit($id)

    {

        try {

            $roles = ModelsRole::where('guard_name', 'admin')->whereNotIn('name', ['super-admin'])

                            ->orderBy('id', 'desc')

                            ->get();



            $userDetail = $this->adminService->findAdminById($id);



            return view($this->view . 'edit', compact( 'userDetail', 'roles'));

        } catch (Exception $exception) {



            return redirect()->back()->with('danger', $exception->getFile());

        }

    }



    public function update(Request $request, $id)

    {

        try {



            $validatedData = $request->except('_method', '_token');



            if (env('DEMO_MODE', false) && (in_array($id, [1, 2]))) {

                throw new Exception(__('message.add_company_warning'), 400);

            }







            DB::beginTransaction();

            $this->adminService->updateAdmin($id, $validatedData);



            DB::commit();

            return redirect()

                ->route('admin.users.index')

                ->with('success', __('message.update_user'));

        } catch (Exception $exception) {

            DB::rollBack();

            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }



    public function toggleStatus($id)

    {

        try {

            if (env('DEMO_MODE', false)) {

                throw new Exception(__('message.add_company_warning'), 400);

            }

            DB::beginTransaction();

            $this->adminService->toggleIsActiveStatus($id);

            DB::commit();

            return redirect()->back()->with('success', __('message.user_is_active_changed'));

        } catch (Exception $exception) {

            DB::rollBack();

            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }



    public function delete($id)

    {

        try {



            if (env('DEMO_MODE', false)) {

                throw new Exception(__('message.add_company_warning'), 400);

            }

            $adminDetail = $this->adminService->findAdminById($id);



            if (!$adminDetail) {

                throw new Exception(__('message.user_not_found'), 404);

            }



            if ($adminDetail->id == auth('admin')->user()->id) {

                throw new Exception(__('message._delete_own'), 402);

            }



            DB::beginTransaction();

            $this->adminService->deleteAdmin($adminDetail);

            DB::commit();

            return redirect()->back()->with('success', __('message.user_remove'));

        } catch (Exception $exception) {

            DB::rollBack();

            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }









    public function changePassword(ChangePasswordRequest $request, $userId)

    {



        try {

            $validatedData = $request->validated();

            if (env('DEMO_MODE', false)) {

                throw new Exception(__('message.add_company_warning'), 400);

            }

            $validatedData['new_password'] = bcrypt($validatedData['new_password']);

            DB::beginTransaction();

            $this->adminService->updateAdmin($userId, ['password'=>$validatedData['new_password']]);

            DB::commit();

            return redirect()->back()->with('success', __('message.user_password_change'));



        } catch (Exception $exception) {

            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }



    public function profileEdit($id)

    {

        $userDetail = Admin::where('id', $id)->first();



        return view('admin.profile.edit', compact('userDetail'));

    }



    public function updateProfile($id, Request $request)

    {

        $admin = Admin::findOrFail($id);



        $validated = $request->validate([

            'name'   => 'required|string|max:255',

            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,

            //'email'  => 'required|email|max:255|unique:admins,email,' . $admin->id,

            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);



        $admin->name  = $validated['name'];

        //$admin->email = $validated['email'];

        $admin->username = $validated['username'];



        if ($request->hasFile('avatar')) {
 
            if ($admin->avatar && file_exists(public_path(\App\Models\Admin::AVATAR_UPLOAD_PATH . $admin->avatar))) {
 
                unlink(public_path(\App\Models\Admin::AVATAR_UPLOAD_PATH . $admin->avatar));
 
            }
 
 
 
            $file = $request->file('avatar');
 
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
 
            $file->move(public_path(\App\Models\Admin::AVATAR_UPLOAD_PATH), $filename);
 
 
 
            $admin->avatar = $filename;
 
        }


        $admin->save();



        return redirect()->back()->with('success', 'Admin updated successfully.');

    }



}

