<?php

namespace Digipemad\Sia\Counseling\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use App\Models\References\GradeLevel;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\StudentSemesterCounseling;
use Digipemad\Sia\Academic\Models\AcademicCounselingCategory;
use Digipemad\Sia\Counseling\Http\Requests\Counseling\StoreRequest;
use Digipemad\Sia\Counseling\Http\Requests\Counseling\UpdateRequest;

class CounselingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', StudentSemesterCounseling::class);

        $acsem = $this->acsem;

        $counselings = StudentSemesterCounseling::with('semester')->where(function($query) use ($request) {
            return $query->where('description', 'like', '%'.$request->get('search').'%')
                         ->orWhereHas('semester.student', function ($student) use ($request) {
                             return $student->whereNameLike($request->get('search'));
                         });
        })->whereHas('semester', function ($q) {
            $q->where('semester_id', $this->acsem->id)
            ->whereHas('student', function ($q2) {
                $q2->where('grade_id', userGrades()); // filter ke grade_id
            });
        })->paginate($request->get('limit', 10));

        $counselings_count = StudentSemesterCounseling::whereHas('semester', function ($semester) {
            return $semester->where('semester_id', $this->acsem->id);
        })->count();

        return view('counseling::counselings.index', compact('acsem', 'counselings', 'counselings_count'));
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
        $categories = AcademicCounselingCategory::all();

        return view('counseling::counselings.create', compact('acsem', 'classrooms', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', StudentSemesterCounseling::class);
        $data = $request->only('smt_id', 'category_id', 'description', 'follow_up');
        $data['employee_id'] = auth()->user()->employee->id;

        $counseling = new StudentSemesterCounseling($data);
        $counseling->save();

        return redirect($request->get('next', url()->previous()))->with('success', 'Sukses, konseling baru berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(StudentSemesterCounseling $counseling, Request $request)
    {
        $this->authorize('update', StudentSemesterCounseling::class);
        $acsem = $this->acsem;

        $categories = AcademicCounselingCategory::all();

        return view('counseling::counselings.edit', compact('acsem', 'categories', 'counseling'));
    }

    /**
     * Update the specified resource.
     */
    public function update(StudentSemesterCounseling $counseling, UpdateRequest $request)
    {
        $this->authorize('store', StudentSemesterCounseling::class);
        $data = $request->only('category_id', 'description', 'follow_up');
        $data['employee_id'] = auth()->user()->employee->id;

        $counseling->update($data);

        return redirect($request->get('next', url()->previous()))->with('success', 'Kasus <strong>'.$counseling->semester->student->full_name.'</strong> berhasil diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(StudentSemesterCounseling $counseling)
    {
        $this->authorize('show', StudentSemesterCounseling::class);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentSemesterCounseling $counseling)
    {
        // $this->authorize('remove', $counseling);
        $this->authorize('destroy', StudentSemesterCounseling::class);

        $tmp = $counseling;
        $counseling->delete();

        return redirect()->back()->with('success', 'Kasus <strong>'.$tmp->semester->student->full_name.'</strong> berhasil dihapus');
    }
}
