<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar\Student;

use Digipemad\Sia\Academic\Models\Student;
use Modules\Account\Models\UserStudy;

use Illuminate\Http\Request;
use Modules\Account\Http\Controllers\User\StudyController as Controller;
use Modules\Account\Http\Requests\User\Study\StoreRequest;
use Modules\Account\Http\Requests\User\Study\UpdateRequest;

class StudyController extends Controller
{
    /**
     * Store data.
     */
    public function store(Student $student, StoreRequest $request)
    {
        $this->authorize('store', UserStudy::class);

        if ($this->createStudy($student->user, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'studies'])))->with('success', 'Sukses, riwayat pendidikan telah berhasil ditambahkan.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Update data.
     */
    public function update(Student $student, UserStudy $study, UpdateRequest $request)
    {
        $this->authorize('update', UserStudy::class);
        if ($this->updateStudy($student->user, $study, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'studies'])))->with('success', 'Sukses, riwayat pendidikan telah berhasil diperbarui.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Destroy data.
     */
    public function destroy(Student $student, UserStudy $study, Request $request)
    {
        $this->authorize('destroy', UserStudy::class);
        if ($this->deleteStudy($student->user, $study)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'studies'])))->with('success', 'Sukses, riwayat pendidikan telah berhasil dihapus.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }
}
