<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Database\Manage\User;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Digipemad\Sia\Administration\Http\Requests\Database\Manage\User\Profile\UpdateRequest;

class ProfileController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $data['dob'] = date('Y-m-d', strtotime($data['dob']));

        if ($user->profile()->update($data)) {

            return redirect()->back()->with('success', 'Profil pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diperbarui');

        }
    }
}
