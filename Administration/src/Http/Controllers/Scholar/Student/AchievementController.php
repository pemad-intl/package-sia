<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar\Student;

use Digipemad\Sia\Academic\Models\Student;
use Modules\Account\Models\UserAchievement;

use Illuminate\Http\Request;
use Modules\Account\Http\Controllers\User\AchievementController as Controller;
use Modules\Account\Http\Requests\User\Achievement\StoreRequest;
use Modules\Account\Http\Requests\User\Achievement\UpdateRequest;

class AchievementController extends Controller
{
    /**
     * Store data.
     */
    public function store(Student $student, StoreRequest $request)
    {
     //   $this->authorize('access', UserAchievement::class);

        if ($this->createAchievement($student->user, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'achievements'])))->with('success', 'Sukses, data prestasi telah berhasil ditambahkan.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Update data.
     */
    public function update(Student $student, UserAchievement $achievement, UpdateRequest $request)
    {
     //   $this->authorize('update', UserAchievement::class);

        if ($this->updateAchievement($student->user, $achievement, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'achievements'])))->with('success', 'Sukses, data prestasi telah berhasil diperbarui.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Destroy data.
     */
    public function destroy(Student $student, UserAchievement $achievement, Request $request)
    {
       // $this->authorize('destroy', UserAchievement::class);

        if ($this->deleteAchievement($student->user, $achievement)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'achievements'])))->with('success', 'Sukses, data prestasi telah berhasil dihapus.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }
}
