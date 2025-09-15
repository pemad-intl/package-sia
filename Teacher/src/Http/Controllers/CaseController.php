<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

use App\Models\References\GradeLevel;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\StudentSemesterCase;
use Digipemad\Sia\Academic\Models\AcademicCaseCategory;
use Digipemad\Sia\Counseling\Http\Requests\Cases\StoreRequest;

class CaseController extends Controller
{
    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', StudentSemesterCase::class);

        $acsem = $this->acsem;
        $gradesLevels = GradeLevel::where('grade_id', userGrades())->pluck('id');


        $classrooms = StudentSemester::with('classroom')
        ->whereHas('classroom', function($query) use ($gradesLevels){
            $query->whereIn('level_id', $gradesLevels);
        })
        ->where('semester_id', $acsem->id)->get()->groupBy('classroom.name');
        $categories = AcademicCaseCategory::with('descriptions')->where('grade_id', userGrades())->get();

        return view('teacher::cases.create', compact('acsem', 'classrooms', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', StudentSemesterCase::class);

        $data = $request->validated();

        foreach ($data['smt_id'] as $semester) {
            $case = new StudentSemesterCase([
                'smt_id'    => $semester,
                'category_id'    => $data['category_id'],
                'description'    => $data['description'],
                'point'    => 0,
                'witness'    => $data['witness'],
                'break_at'    => date('Y-m-d H:i:s', strtotime($data['break_at'])),
                'employee_id'   => auth()->user()->employee->id
            ]);
            $case->save();
        }

        return redirect($request->get('next', url()->previous()))->with('success', 'Sukses, '.count($request->input("smt_id")).' kasus berhasil dibuat');
    }
}
