<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar\Student;

use Digipemad\Sia\Academic\Models\Student;
use Modules\Account\Models\UserAppreciation;

use Illuminate\Http\Request;
use Modules\Account\Http\Controllers\User\AppreciationController as Controller;
use Modules\Account\Http\Requests\User\Appreciation\StoreRequest;
use Modules\Account\Http\Requests\User\Appreciation\UpdateRequest;

class AppreciationController extends Controller
{
    /**
     * Store data.
     */
    public function store(Student $student, StoreRequest $request)
    {
        $this->authorize('access', UserAppreciation::class);

        if ($this->createAppreciation($student->user, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'appreciations'])))->with('success', 'Sukses, data penghargaan telah berhasil ditambahkan.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Update data.
     */
    public function update(Student $student, UserAppreciation $appreciation, UpdateRequest $request)
    {
        $this->authorize('update', UserAppreciation::class);

        if ($this->updateAppreciation($student->user, $appreciation, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'appreciations'])))->with('success', 'Sukses, data penghargaan telah berhasil diperbarui.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Destroy data.
     */
    public function destroy(Student $student, UserAppreciation $appreciation, Request $request)
    {
        $this->authorize('destroy', UserAppreciation::class);

        if ($this->deleteAppreciation($student->user, $appreciation)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'appreciations'])))->with('success', 'Sukses, data penghargaan telah berhasil dihapus.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }
}
