<?php

namespace Digipemad\Sia\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\StudentSemesterCounseling;
use Digipemad\Sia\Academic\Models\AcademicCounselingCategory;
use Digipemad\Sia\Counseling\Http\Requests\Counseling\StoreRequest;
use Digipemad\Sia\Counseling\Http\Requests\Counseling\UpdateRequest;
use Digipemad\Sia\Academic\Models;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('access', StudentSemesterCounseling::class);
        
        $acsem = $this->acsem;

        $user = auth()->user();
    	$student = $user->student->semesters;
        $classroom = $student->first()->classroom_id;

        $students = StudentSemester::with('student')->where(['semester_id' => $acsem->id, 'classroom_id' => $classroom])->paginate($request->get('limit', 10));
        $studentsCount = StudentSemester::with('student')->where(['semester_id' => $acsem->id, 'classroom_id' => $classroom])->count();

        return view('academic::classroom', compact('acsem', 'students', 'studentsCount'));
    }
}