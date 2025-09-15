<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Database\Manage;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Modules\Account\Models\Role;
use Modules\Account\Models\Permission;
use Digipemad\Sia\Administration\Http\Requests\Database\Manage\Role\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Database\Manage\Role\UpdateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Role::class);

        $trashed = $request->get('trash');

        $roles = Role::with('permissions')->withCount('users')->search($request->get('search'))->simplePaginate();
        $roles_count = Role::count();
        
        return view('administration::database.manage.roles.index', compact('roles', 'roles_count'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $role = new Role($request->only(['name', 'display_name']));

        $role->save();

        return redirect()->back()->with('success', 'Peran <strong>'.$role->display_name.' ('.$role->name.')</strong> berhasil dibuat');
    }

    /**
     * Show the specified resource.
     */
    public function show(Role $role)
    {
        $this->authorize('update', $role);

        $role->load('permissions')->loadCount('users');

        $permissions = Permission::where('module', config('account.permission_module'))->get();

        return view('administration::database.manage.roles.show', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $role->fill(array_merge($request->only(['name', 'display_name']), [
            'updated_at' => date('Y-m-d H:i:s')
        ]));

        if ($role->save()) {
            $role->permissions()->sync($request->input('permissions'));
        }

        return redirect()->back()->with('success', 'Peran <strong>'.$role->display_name.' ('.$role->name.')</strong> berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $tmp = $role;
        $role->delete();

        return redirect()->back()->with('success', 'Peran <strong>'.$role->display_name.' ('.$role->name.')</strong> berhasil dihapus');
    }
}
