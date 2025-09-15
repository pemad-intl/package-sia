<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Database\Manage\User;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Digipemad\Sia\Administration\Http\Requests\Database\Manage\User\Phone\UpdateRequest;

class PhoneController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->phone()->updateOrCreate([
            'user_id'       => $user->id
        ], [
            'number'        => $request->input('number'),
            'whatsapp'      => (bool) $request->input('whatsapp')
        ]);

        return redirect()->back()->with('success', 'Nomor HP pengguna <strong>'.$user->profile->name.' ('.$user->username.')</strong> berhasil diperbarui');
    }
}
