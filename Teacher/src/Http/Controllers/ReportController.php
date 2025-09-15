<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;
use Auth;

use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;
use Digipemad\Sia\Academic\Models\StudentSemesterReport;
use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\AcademicSubjectCompetence;
use Digipemad\Sia\Teacher\Http\Requests\Report\UpdateRequest;

class ReportController extends Controller
{
	/**
	 * Show the meet details.
	 */
	public function show(AcademicSubjectMeet $meet, Request $request)
	{
     //   $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$meet = $user->teacher->meets()
							->with(['classroom.stsems' => function ($stsem) {
								return $stsem->with('student', 'reports');
							}])
							->findOrFail($meet->id);
		
		$teacher = $user->teacher;
		$subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($meet->subject_id);

		return view('teacher::reports.show', compact('meet', 'acsem', 'meet', 'subject'));
	}

	/**
	 * Update with the specified resource.
	 */
	public function update(AcademicSubjectMeet $meet, UpdateRequest $request)
	{
       // $this->authorize('update', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		foreach ($request->input('value') as $smt_id => $value) {
			

			$report = StudentSemesterReport::with('semester')->updateOrCreate([
				'smt_id' => $smt_id,
				'subject_id' => $meet->subject_id,
			], [
				'ki3_value' => $value['ki3_value'] ?? 0,
				'ki3_predicate' => $value['ki3_predicate'] ?? null,
				'ki3_description' => $value['ki3_description'] ?? null,
				'ki4_value' => $value['ki4_value'] ?? 0,
				'ki4_predicate' => $value['ki4_predicate'] ?? null,
				'ki4_description' => $value['ki4_description'] ?? null
			]);

			Auth::user()->log(
				' Nilai untuk mapel '.$meet->subject->name.' untuk siswa bernama '.$report->semester->student->user->name.' telah dibuat oleh '.$user->name.' '.
				' <strong>[ID: ' . $report->id . ']</strong>',
				StudentSemesterReport::class,
				$report->id
			);
		}

		return redirect()->back()->with('success', 'Nilai raport '.$meet->subject->name.' berhasil diperbarui.');
	}
}
