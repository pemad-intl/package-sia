<?php

namespace Digipemad\Sia\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use Digipemad\Sia\Boarding\Models\BoardingStudents;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\StudentSemesterCounseling;
use Digipemad\Sia\Academic\Models\AcademicCounselingCategory;
use Digipemad\Sia\Counseling\Http\Requests\Counseling\StoreRequest;
use Digipemad\Sia\Counseling\Http\Requests\Counseling\UpdateRequest;
use Digipemad\Sia\Academic\Models;

class BoardRoomController extends Controller
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

        $boardStatus = BoardingStudents::with('employee', 'room', 'room.building', 'student')->where('student_id', $user->student->id)->first(); 
        $boardFriends = BoardingStudents::with('employee', 'room', 'room.building', 'student')->where(['building_id' => $boardStatus->building_id, 'room_id' => $boardStatus->room_id])->get();

        return view('academic::boardroom', compact('boardStatus', 'boardFriends'));
    }
}