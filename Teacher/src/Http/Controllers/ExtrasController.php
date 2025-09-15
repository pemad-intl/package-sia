<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;
use Digipemad\Sia\Academic\Models\StudentSemesterReport;
use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\StudentExtras;
use Digipemad\Sia\Academic\Models\AcademicSubjectCompetence;

class ExtrasController extends Controller
{
	public function show(AcademicClassroom $classroom, Request $request)
	{
     //   $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$cls = $classroom->with(['stsems' => function ($stsem) {
								return $stsem->with('student', 'reports');
							}])
							->findOrFail($classroom->id);
		
		$teacher = $user->teacher;
		// $subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($meet->subject_id);
        $classRooms = AcademicClassroom::where('supervisor_id', $teacher->id)->get();
        $classRoomsByLevel = $classRooms->groupBy('level_id');

		return view('teacher::extras.show', compact('acsem', 'cls'));
	}
}
