<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Database\Manage;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Modules\Account\Models\Role;
use Digipemad\Sia\Administration\Http\Requests\Database\Manage\User\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Database\Manage\User\UpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', User::class);

        $trashed = $request->get('trash');

        $users = User::search($request->get('search'))->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $users_count = User::count();
        
        return view('administration::database.manage.users.index', compact('users', 'users_count'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $password = User::generatePassword();

        $user = new User([
            'username' => $request->input('username'),
            'password' => bcrypt($password)
        ]);

        $user->save();
        $user->profile()->create([
            'name' => $request->input('name')
        ]);

        return redirect()->back()->with('success', 'Pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil dibuat dengan password <strong>'.$password.'</strong>');
    }

    /**
     * Show the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        if($user->trashed() || $user->id == auth()->id()) abort(404);

        $roles = Role::where('id', '!=', config('account.root_role'))->get();

        return view('administration::database.manage.users.show', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        if($user->trashed() || $user->id == auth()->id()) abort(404);

        $user->update([
            'username' => $request->input('username')
        ]);

        return redirect()->back()->with('success', 'Pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('remove', $user);

        $tmp = $user;
        $user->delete();

        return redirect()->back()->with('success', 'Pengguna <strong>'.$tmp->profile->name.' ('.$tmp->username.')</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(User $user)
    {
        $this->authorize('delete', $user);

        $user->restore();

        return redirect()->back()->with('success', 'Pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(User $user)
    {
        $this->authorize('delete', $user);

        $tmp = $user;
        $user->forceDelete();

        return redirect()->back()->with('success', 'Pengguna <strong>'.$tmp->profile->name.' ('.$tmp->username.')</strong> berhasil dihapus permanen dari sistem');
    }

    /**
     * Reset password from storage.
     */
    public function repass(User $user)
    {
        $this->authorize('update', $user);

        if($user->trashed()) abort(404);
        
        $password = $user->resetPassword();

        return redirect()->back()->with('success', 'Pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diatur ulang menjadi <strong>'.$password.'</strong>');
    }
}
