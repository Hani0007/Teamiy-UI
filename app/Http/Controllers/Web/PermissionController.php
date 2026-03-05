<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private $view = 'admin.permission.';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->filled('slug'))
            $guard = ($request->slug === 'a') ? 'admin' : 'web';
        else
            $guard = config('auth.defaults.guard');

        $permissions = Permission::where('guard_name', $guard)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view($this->view . 'index', compact('permissions', 'guard'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->filled('slug'))
            $guard = ($request->slug === 'a') ? 'admin' : 'web';
        else
            $guard = config('auth.defaults.guard');

        $groups = Permission::select('group')->distinct()->pluck('group');
        return view($this->view. 'create', compact('groups', 'guard'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permission_group' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            Permission::create([
                'name' => $validatedData['name'],
                'guard_name' => isset($request->guard) ? $request->guard : config('auth.defaults.guard'),
                'group' => $validatedData['permission_group']
            ]);

            DB::commit();

            Artisan::call('cache:clear');

            $guardName = ($request->guard === 'admin') ? 'a' : 'e';

            return redirect()
                ->route('admin.permissions.index' , ['slug' => $guardName])
                ->with('success', __('message.add_permission'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $permission = Permission::where('id', $id)->first();
            $groups = Permission::select('group')->distinct()->pluck('group');
            
            if (!$permission) {
                throw new Exception(__('message.permission_not_found'), 204);
            }
            
            return view($this->view . 'edit', compact('permission', 'groups'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permission_group' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            Permission::where('id', $id)->update([
                'name' => $validatedData['name'],
                'group' => $validatedData['permission_group']
            ]);

            DB::commit();

            Artisan::call('cache:clear');

            $guardName = ($request->guard === 'admin') ? 'a' : 'e';

            return redirect()
                ->route('admin.permissions.index', ['slug' => $guardName])
                ->with('success', __('message.add_permission'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $permission = Permission::find($id);
            if (!$permission) {
                throw new Exception(__('message.permission_not_found'), 404);
            }
           
            DB::beginTransaction();

            $permission->delete();

            DB::commit();

            Artisan::call('cache:clear');

            return redirect()
            ->route('admin.permissions.index', ['slug' => $request->guard])
            ->with('success', __('message.permission_delete'));
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
