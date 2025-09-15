<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Curricula;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Modules\Core\Enums\PositionTypeEnum;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;
use Digipemad\Sia\Academic\Models\EmployeeTeacher;
use Modules\HRMS\Models\Employee;
use App\Models\References\GradeLevel;

use Digipemad\Sia\Administration\Http\Requests\Curricula\Meet\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Curricula\Meet\UpdateRequest;

class MeetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('access', User::class);
        $this->authorize('access', AcademicSemester::class);

        $trashed = $request->get('trash');

        $acsems = AcademicSemester::openedByDesc()->get();
        $grades = GradeLevel::where('grade_id', userGrades())->pluck('id');

        $meets = AcademicSubjectMeet::withCount('plans')->whereHas('classroom', function ($classroom) use ($request, $grades) {
            return $classroom->where('name', 'like', '%'.$request->get('search').'%')
            ->whereIn('level_id', $grades);
        })->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->where('semester_id', $request->get('academic', $acsems->first()->id))->paginate($request->get('limit', 10));

        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id));

        if ($acsem) {
            $meets_count = AcademicSubjectMeet::where('semester_id', $request->get('academic', $acsem->id))->count();

            return view('administration::curriculas.meets.index', compact('acsems', 'acsem', 'meets', 'meets_count'));
        }

        return abort(404);
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('access', AcademicSemester::class);

        $grades = GradeLevel::where('grade_id', auth()->user()->employee->grade_id)->pluck('id');
        $acsems = AcademicSemester::with(['classrooms' => function($classroom) use ($grades){
            $classroom->whereIn('level_id', $grades);
        }])->openedByDesc()->get();

        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id))->load(['majors', 'superiors', 
        'subjects' => function($query) use ($grades) {
            $query->whereIn('level_id', $grades); 
         }, 'classrooms']);

        $teachers = Employee::whereHas('position.position', fn($p) => $p->where('type', PositionTypeEnum::GURU))
        ->where('grade_id', auth()->user()->employee->grade_id)
        ->whereNull('deleted_at')->get();

        return view('administration::curriculas.meets.create', compact('acsems', 'acsem', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicSemester::class);

        $data = $request->only('semester_id', 'subject_id', 'classroom_id', 'teacher_id', 'props');

        if (empty($data['props']['color'])) {
            $c = AcademicSubjectMeet::$colors;
            $data['props']['color'] = $c[array_rand($c)];
        }

        $meet = new AcademicSubjectMeet($data);

        if($meet->save()){
            $meet->createAllMetasFromSemesters();
            Auth::user()->log(
                ' Pertemuan untuk mata pelajaran bernama '.$meet->subject->name.' telah dibuat '.
                ' <strong>[ID: ' . $meet->id . ']</strong>',
                AcademicSubjectMeet::class,
                $meet->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Pertemuan <strong>'.$meet->name.'</strong> berhasil dibuat');
        }

        return redirect($request->get('next', url()->previous()))->with('danger', 'Pertemuan <strong>'.$meet->name.'</strong> berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicSubjectMeet $meet, Request $request)
    {
        $this->authorize('update', AcademicSemester::class);
        $grades = GradeLevel::where('grade_id', auth()->user()->employee->grade_id)->pluck('id');

        $acsem = $meet->semester->load(['subjects' => function($query) use ($grades) {
            $query->whereIn('level_id', $grades); 
        }
        , 'classrooms']);

        $teachers = Employee::whereHas('position.position', fn($p) => $p->where('type', PositionTypeEnum::GURU))
        ->where('grade_id', auth()->user()->employee->grade_id)->whereNull('deleted_at')->get();

        return view('administration::curriculas.meets.edit', compact('meet', 'acsem', 'teachers'));
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicSubjectMeet $meet, UpdateRequest $request)
    {
        $this->authorize('update', AcademicSemester::class);
        $data = $request->only('subject_id', 'classroom_id', 'teacher_id', 'props');

        if (empty($data['props']['color'])) {
            $c = AcademicSubjectMeet::$colors;
            $data['props']['color'] = $c[array_rand($c)];
        }

        if($meet->update($data)){
            Auth::user()->log(
                ' Pertemuan untuk mata pelajaran bernama '.$meet->subject->name.' telah diperbarui '.
                ' <strong>[ID: ' . $meet->id . ']</strong>',
                AcademicSubjectMeet::class,
                $meet->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Pertemuan <strong>'.$meet->name.'</strong> berhasil diperbarui');
        } 
        
        return redirect($request->get('next', url()->previous()))->with('danger', 'Pertemuan <strong>'.$meet->name.'</strong> gagal diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicSubjectMeet $meet)
    {
        $this->authorize('show', AcademicSemester::class);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicSubjectMeet $meet)
    {
        $this->authorize('destroy', AcademicSemester::class);
        // $this->authorize('remove', $meet);

        if($meet->delete()){
            Auth::user()->log(
                ' Pertemuan untuk mata pelajaran bernama '.$meet->subject->name.' telah hapus '.
                ' <strong>[ID: ' . $meet->id . ']</strong>',
                AcademicSubjectMeet::class,
                $meet->id
            );

            return redirect()->back()->with('success', 'Pertemuan <strong>'.$meet->name.'</strong> berhasil dihapus');
        } 

        return redirect()->back()->with('danger', 'Pertemuan <strong>'.$meet->name.'</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(AcademicSubjectMeet $meet)
    {
        $this->authorize('restore', AcademicSemester::class);

        $meet->restore();

        return redirect()->back()->with('success', 'Pertemuan <strong>'.$meet->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(AcademicSubjectMeet $meet)
    {
        $this->authorize('kill', $meet);

        $tmp = $meet;
        $meet->forceDelete();

        return redirect()->back()->with('success', 'Pertemuan <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
