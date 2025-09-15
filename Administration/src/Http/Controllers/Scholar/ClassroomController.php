<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Models\EmployeeTeacher;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Modules\HRMS\Models\Employee;
use Modules\Core\Enums\PositionTypeEnum;
use Digipemad\Sia\Administration\Models\SchoolBuildingRoom;
use App\Models\References\GradeLevel;
use Digipemad\Sia\Administration\Http\Requests\Scholar\Classroom\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Scholar\Classroom\UpdateRequest;
use Digipemad\Sia\Administration\Http\Requests\Scholar\Classroom\SyncStudentsRequest;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicClassroom::class);

        $trashed = $request->get('trash');

        $acsems = AcademicSemester::openedByDesc()->get();
        $gradesLevels = GradeLevel::where('grade_id', userGrades())->pluck('id');
      
        $classrooms = AcademicClassroom::withCount('stsems')->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->where('semester_id', $request->get('academic', $acsems->first()->id))
        ->whereIn('level_id', $gradesLevels)
        ->orderBy('level_id')->orderBy('name')->paginate($request->get('limit', 10));

        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id));

        if ($acsem) {
            $classrooms_count = AcademicClassroom::where('semester_id', $request->get('academic', $acsem->id))->count();

            return view('administration::scholar.classrooms.index', compact('acsems', 'acsem', 'classrooms', 'classrooms_count'));
        }

        return abort(404);
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicClassroom::class);

        $acsems = AcademicSemester::openedByDesc()->get();
        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id))->load('majors', 'superiors');

        $rooms = SchoolBuildingRoom::where('grade_id', userGrades())->whereNull('deleted_at')->get();

        $supervisors = Employee::whereHas('contract.position', function ($query) {
            $query->where('position_id', PositionTypeEnum::GURU)
          ->orWhere('position_id', PositionTypeEnum::HUMAS);
        })->where('grade_id', userGrades())->get();

        return view('administration::scholar.classrooms.create', compact('acsems', 'acsem', 'rooms', 'supervisors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicClassroom::class);

        $classroom = new AcademicClassroom($request->only('semester_id', 'level_id', 'name', 'room_id', 'major_id', 'superior_id', 'supervisor_id'));

        if($classroom->save()){
             Auth::user()->log(
                ' Rombel bernama '.$classroom->name.' telah ditambahkan '.
                ' <strong>[ID: ' . $classroom->id . ']</strong>',
                AcademicClassroom::class,
                $classroom->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Rombel <strong>'.$classroom->name.'</strong> berhasil dibuat');
        }

        return redirect($request->get('next', url()->previous()))->with('danger', 'Rombel <strong>'.$classroom->name.'</strong> gagal dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicClassroom $classroom, Request $request)
    {
        $this->authorize('update', AcademicClassroom::class);

        $acsems = AcademicSemester::openedByDesc()->get();
        $acsem = $acsems->firstWhere('id', $classroom->semester_id)->load('majors', 'superiors');

        $rooms = SchoolBuildingRoom::where('grade_id', userGrades())->whereNull('deleted_at')->get();

        $supervisors = Employee::where('grade_id', userGrades())->whereHas('contract.position', function ($query) {
            $query->where('position_id', PositionTypeEnum::GURU);
        })->where('grade_id', userGrades())->get();

        return view('administration::scholar.classrooms.edit', compact('acsems', 'acsem', 'rooms', 'supervisors', 'classroom'));
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicClassroom $classroom, UpdateRequest $request)
    {
        $this->authorize('update', AcademicClassroom::class);

        if($classroom->update($request->only('level_id', 'name', 'room_id', 'major_id', 'superior_id', 'supervisor_id'))){
             Auth::user()->log(
                ' Rombel bernama '.$classroom->name.' telah diperbarui '.
                ' <strong>[ID: ' . $classroom->id . ']</strong>',
                AcademicClassroom::class,
                $classroom->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Rombel <strong>'.$classroom->name.'</strong> berhasil diperbarui');
        }

        return redirect($request->get('next', url()->previous()))->with('danger', 'Rombel <strong>'.$classroom->name.'</strong> gagal diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicClassroom $classroom)
    {
        $this->authorize('show', AcademicClassroom::class);

        if($classroom->trashed()) abort(404);

        $classroom = $classroom->load(['semester', 'stsems' => function ($stsems) use ($classroom) {
            return $stsems->where('semester_id', $classroom->semester_id);
        }]);

        $stsems = StudentSemester::with('student.user')->where('semester_id', $classroom->semester_id)->where(function($query) use ($classroom) {
            return $query->whereNull('classroom_id')
                      ->orWhere('classroom_id', $classroom->id);
        })->get();

        return view('administration::scholar.classrooms.show', compact('classroom', 'stsems'));
    }

    /**
     * Sync students.
     */
    public function students(AcademicClassroom $classroom, SyncStudentsRequest $request)
    {
        $this->authorize('access', AcademicClassroom::class);

        StudentSemester::where('classroom_id', $classroom->id)->update([
            'classroom_id' => null
        ]);

        $stsems = StudentSemester::whereIn('id', $request->input('stsems', []))->update([
            'classroom_id' => $classroom->id
        ]);

        return redirect($request->get('next', url()->previous()))->with('success', 'Data siswa rombel <strong>'.$classroom->name.'</strong> berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicClassroom $classroom)
    {
        $this->authorize('destroy', AcademicClassroom::class);

        // $this->authorize('remove', $classroom);

        if($classroom->delete()){
            Auth::user()->log(
                ' Rombel bernama '.$classroom->name.' telah dihapus '.
                ' <strong>[ID: ' . $classroom->id . ']</strong>',
                AcademicClassroom::class,
                $classroom->id
            );

            return redirect()->back()->with('success', 'Rombel <strong>'.$tmp->name.'</strong> berhasil dihapus');
        } 
        
        return redirect()->back()->with('danger', 'Rombel <strong>'.$tmp->name.'</strong> gagal dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(AcademicClassroom $classroom)
    {
        $this->authorize('restore', AcademicClassroom::class);

        // $this->authorize('delete', $classroom);

        $classroom->restore();

        return redirect()->back()->with('success', 'Rombel <strong>'.$classroom->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(AcademicClassroom $classroom)
    {
        $this->authorize('kill', AcademicClassroom::class);
        // $this->authorize('delete', $classroom);

        $tmp = $classroom;
        $classroom->forceDelete();

        return redirect()->back()->with('success', 'Rombel <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
