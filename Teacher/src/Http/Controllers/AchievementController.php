<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;
use Digipemad\Sia\Academic\Models\StudentSemesterReport;
use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\AcademicSubjectCompetence;

class AchievementController extends Controller
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
		//$subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($meet->subject_id);
        $classRooms = AcademicClassroom::where('supervisor_id', $teacher->id)->get();
        $classRoomsByLevel = $classRooms->groupBy('level_id');

		return view('teacher::achievement.show', compact('acsem', 'cls'));
	}

	public function update(AcademicClassroom $classroom, UpdateRequest $request)
	{
       // $this->authorize('update', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		foreach ($request->input('value') as $smt_id => $value) {
			StudentSemesterReport::updateOrCreate([
				'smt_id' => $smt_id,
				'subject_id' => $meet->subject_id,
			], [
				'ki3_comment' => $value['ki3_comment'] ?? null,
				'ki4_evaluation' => $value['ki4_evaluation'] ?? null,
			]);
		}

		return redirect()->back()->with('success', 'Nilai raport '.$meet->subject->name.' berhasil diperbarui.');
	}
}
