<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar\Student;

use Digipemad\Sia\Academic\Models\Student;
use Modules\Account\Models\UserOrganization;

use Illuminate\Http\Request;
use Modules\Account\Http\Controllers\User\OrganizationController as Controller;
use Modules\Account\Http\Requests\User\Organization\StoreRequest;
use Modules\Account\Http\Requests\User\Organization\UpdateRequest;

class OrganizationController extends Controller
{
    /**
     * Store data.
     */
    public function store(Student $student, StoreRequest $request)
    {
        $this->authorize('store', UserOrganization::class);

        if ($this->createOrganization($student->user, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'organizations'])))->with('success', 'Sukses, data organisasi telah berhasil ditambahkan.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Update data.
     */
    public function update(Student $student, UserOrganization $organization, UpdateRequest $request)
    {
        $this->authorize('update', UserOrganization::class);

        if ($this->updateOrganization($student->user, $organization, $request)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'organizations'])))->with('success', 'Sukses, data organisasi telah berhasil diperbarui.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }

    /**
     * Destroy data.
     */
    public function destroy(Student $student, UserOrganization $organization, Request $request)
    {
        $this->authorize('destroy', UserOrganization::class);

        if ($this->deleteOrganization($student->user, $organization)) {

            return redirect($request->get('next', route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'organizations'])))->with('success', 'Sukses, data organisasi telah berhasil dihapus.');

        }

        return redirect()->back()->with('danger', 'Terjadi kesalahan, silahkan hubungi operator pusat.');
    }
}
