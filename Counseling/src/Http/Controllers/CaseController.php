<?php

namespace Digipemad\Sia\Counseling\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use App\Models\References\GradeLevel;
use Digipemad\Sia\Academic\Models\StudentSemesterCounseling;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\StudentSemesterCase;
use Digipemad\Sia\Academic\Models\AcademicCaseCategory;
use Digipemad\Sia\Academic\Models\AcademicCaseCategoryDescription;
use Digipemad\Sia\Counseling\Http\Requests\Cases\StoreRequest;
use Digipemad\Sia\Counseling\Http\Requests\Cases\UpdateRequest;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', StudentSemesterCounseling::class);

        $acsem = $this->acsem;

        $cases = StudentSemesterCase::with('semester', 'employee')->where(function($query) use ($request) {
            return $query->where('description', 'like', '%'.$request->get('search').'%')
                         ->orWhereHas('semester.student', function ($student) use ($request) {
                             return $student->whereNameLike($request->get('search'));
                         });
        })->whereHas('employee', function ($employee) {
            return $employee->where('grade_id', userGrades());
        })->whereHas('semester', function ($semester) {
            return $semester->where('semester_id', $this->acsem->id)->whereHas('student');
        })->paginate($request->get('limit', 10));

        $cases_count = StudentSemesterCase::whereHas('semester', function ($semester) {
            return $semester->where('semester_id', $this->acsem->id);
        })->count();

        return view('counseling::cases.index', compact('acsem', 'cases', 'cases_count'));
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', StudentSemesterCounseling::class);
        $acsem = $this->acsem;
        $grades = GradeLevel::where('grade_id', userGrades())->pluck('id');

        $classrooms = StudentSemester::with('classroom')
        ->whereHas('classroom', function ($classroom) use ($request, $grades) {
            return $classroom->whereIn('level_id', $grades);
        })
        ->where('semester_id', $acsem->id)->get()->groupBy('classroom.name');
        $categories = AcademicCaseCategory::with('descriptions')->where('grade_id', userGrades())->get();

        return view('counseling::cases.create', compact('acsem', 'classrooms', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', StudentSemesterCounseling::class);
        $data = $request->validated();

        foreach ($data['smt_id'] as $semester) {
            $case = new StudentSemesterCase([
                'smt_id'    => $semester,
                'category_id'    => $data['category_id'],
                'description'    => $data['description'],
                'point'    => $data['point'],
                'witness'    => $data['witness'],
                'break_at'    => date('Y-m-d H:i:s', strtotime($data['break_at'])),
                'employee_id'   => auth()->user()->employee->id
            ]);
            $case->save();
        }

        return redirect($request->get('next', url()->previous()))->with('success', 'Sukses, '.count($request->input("smt_id")).' kasus berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(StudentSemesterCase $case, Request $request)
    {
        $this->authorize('update', StudentSemesterCounseling::class);
        $acsem = $this->acsem;

        $categories = AcademicCaseCategory::with('descriptions')->where('grade_id', userGrades())->get();

        return view('counseling::cases.edit', compact('acsem', 'categories', 'case'));
    }

    /**
     * Update the specified resource.
     */
    public function update(StudentSemesterCase $case, UpdateRequest $request)
    {
        $this->authorize('update', StudentSemesterCounseling::class);
        $data = $request->validated();

        $case->update([
            'category_id'    => $data['category_id'],
            'description'    => $data['description'],
            'point'    => $data['point'],
            'witness'    => $data['witness'],
            'break_at'    => date('Y-m-d H:i:s', strtotime($data['break_at'])),
            'employee_id'   => auth()->user()->employee->id
        ]);

        return redirect($request->get('next', url()->previous()))->with('success', 'Kasus <strong>'.$case->semester->student->full_name.'</strong> berhasil diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(StudentSemesterCase $case)
    {
        $this->authorize('show', StudentSemesterCounseling::class);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentSemesterCase $case)
    {
        $this->authorize('destroy', StudentSemesterCounseling::class);
        // $this->authorize('remove', $case);

        $tmp = $case;
        $case->delete();

        return redirect()->back()->with('success', 'Kasus <strong>'.$tmp->semester->student->full_name.'</strong> berhasil dihapus');
    }
}
