<?php

namespace Digipemad\Sia\Boarding\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\Academic;
use Digipemad\Sia\Academic\Models\EmployeeTeacher;
use Digipemad\Sia\Administration\Http\Requests\Employee\Teacher\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Employee\Teacher\UpdateRequest;
use Modules\HRMS\Models\EmployeeContract;
use Illuminate\Support\Arr;

class TeacherController extends Controller
{
	public function index(Request $request)
    {
        $trashed = $request->get('trash');

        $teachers = EmployeeTeacher::with('employee.user')->search($request->get('search'))->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->orderByDesc('id')->paginate($request->get('limit', 10));


        //$teachers = EmployeeTeacher::with('user')->get();

        $teacher_count = EmployeeTeacher::count();

        return view('administration::employees.teachers.index', compact('teachers','teacher_count'));
    }

    public function create(Request $request)
    {
        $acdmcs = Academic::orderByDesc('id')->get();

        return view('administration::employees.teachers.create', compact('acdmcs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $password = User::generatePassword();

        if ($request->get('user') == 1) {
            $user = User::find($request->input('user_id'));
            $teacher = EmployeeTeacher::insertFromUser($user, $request->all());
        } else {
            $teacher = EmployeeTeacher::completeInsert($request->all(), $password);
        }

        return redirect($request->get('next', url()->previous()))->with('success', 'Guru atas nama <strong>'.$teacher->employee->user->profile->name.' ('.$teacher->employee->user->username.')</strong> berhasil dibuat'.($request->get('user') == 1 ? '!' : 'dengan sandi <strong>'.$password.'</strong>'));
    }

    /**
     * Show the specified resource.
     */
    public function show(EmployeeTeacher $teacher)
    {
        if($teacher->trashed() || $teacher->id == auth()->id()) abort(404);

        $teacher = $teacher->load('employee.user');

        return view('administration::employees.teachers.show', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, EmployeeTeacher $teacher)
    {
        if($teacher->trashed() || $teacher->id == auth()->id()) abort(404);

        $teacher = EmployeeTeacher::completeUpdate($teacher, $request);

        return redirect()
            ->route('administration::employees.teachers.index')->with('success', 'Guru atas nama <strong>' . $teacher->employee->user->profile->name . ' (' . $teacher->employee->user->username . ')</strong> berhasil diperbarui');

        // return redirect()->back()->with('success', 'Guru atas nama <strong>'.$teacher->employee->user->profile->name.' ('.$teacher->employee->user->username.')</strong> berhasil diperbarui');
    }

    public function destroy(EmployeeTeacher $teacher)
    {

        $tmp = $teacher;
        $teacher->delete();

        return redirect()->back()->with('success', 'Guru atas nama <strong>'.$tmp->employee->user->profile->name.' ('.$tmp->employee->nip.')</strong> berhasil dihapus');
    }

    public function restore(EmployeeTeacher $teacher)
    {

        $teacher->restore();

        return redirect()->back()->with('success', 'Guru atas nama <strong>'.$teacher->employee->user->profile->name.' ('.$teacher->employee->nip.')</strong> berhasil dipulihkan');
    }

    public function kill(EmployeeTeacher $teacher)
    {

        $tmp = $teacher;
        $teacher->forceDelete();

        return redirect()->back()->with('success', 'Guru atas nama <strong>'.$tmp->employee->user->profile->name.' ('.$tmp->employee->nip.')</strong> berhasil dihapus permanen dari sistem');
    }
}
