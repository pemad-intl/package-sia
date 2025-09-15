<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

use Auth;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;
use Digipemad\Sia\Academic\Models\StudentSemesterReport;
use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\AcademicSubjectCompetence;
use Digipemad\Sia\Teacher\Http\Requests\Supervisor\UpdateRequest;
use Digipemad\Sia\Academic\Models\StudentAcademicEvaluation;
use Digipemad\Sia\Academic\Models\StudentSemester;

class SupervisorController extends Controller
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
//		$subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($cls->subject_id);
        $classRooms = AcademicClassroom::where('supervisor_id', $teacher->id)->get();
        $classRoomsByLevel = $classRooms->groupBy('level_id');

		return view('teacher::supervisor.show', compact('acsem', 'cls'));
	}

	public function update(AcademicClassroom $classroom, Request $request)
	{
       // $this->authorize('update', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		foreach ($request->input('value') as $smt_id => $value) {
			$evalUation = StudentAcademicEvaluation::updateOrCreate([
				'smt_id' => $smt_id,
			], [
				'subject_note' => $value['subject_note'] ?? null,
			]);

			$studentSmt = StudentSemester::find($smt_id);
			$smt = AcademicSemester::find($studentSmt->semester_id);

			Auth::user()->log(
				' Catatan untuk siswa '.$studentSmt->student->user->name.' telah dibuat oleh '.$user->teacher->user->name.' '.
				' <strong>[ID: ' . $evalUation->id . ']</strong>',
				StudentAcademicEvaluation::class,
				$evalUation->id
			);
		}

		return redirect()->back()->with('success', 'Catatan raport '.$classroom->name.' berhasil diperbarui.');
	}
}
