<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetPlan;
use Digipemad\Sia\Academic\Models\StudentSemesterAssessment;
use Digipemad\Sia\Teacher\Http\Requests\Plan\UpdateRequest;
use Digipemad\Sia\Teacher\Http\Requests\Plan\PresenceRequest;
use Digipemad\Sia\Teacher\Http\Requests\Plan\AssessmentRequest;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetEval;

class PlanController extends Controller
{
	/**
	 * Show the plan details.
	 */
	public function show(AcademicSubjectMeetPlan $plan, Request $request)
	{
      //  $this->authorize('show', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$plan = $user->teacher->plans()->with([
			'meet.classroom.students', 'assessments'
		])->findOrFail($plan->id);


		$meet = $plan->meet;

		$presenceList = AcademicSubjectMeetPlan::$presenceList;

		// $types = StudentSemesterAssessment::$type;
		//where('meet_id', $plan->meet_id)
		$types = AcademicSubjectMeetEval::
			orderBy('id', 'asc')
			->get();		
			
		return view('teacher::plans.show', compact('plan', 'meet', 'acsem', 'types', 'presenceList'));
	}

	/**
	 * Update the plan.
	 */
	public function update(AcademicSubjectMeetPlan $plan, UpdateRequest $request)
	{
       // $this->authorize('update', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$data = $request->validated();

		$plan = $user->teacher->plans()->findOrFail($plan->id);

		$data['test'] = isset($data['test']) ? 1 : 0;
		if($data['test'] == 1) {
			$data['comp_id'] = null;
		}

		if($plan->update($data)){
			Auth::user()->log(
				' Rencana '.$plan->meet->subject->name.' dibuat oleh '.$user->name.' telah ditambahkan '.
				' <strong>[ID: ' . $plan->id . ']</strong>',
				AcademicSubjectMeetPlan::class,
				$plan->id
			);

			return redirect($request->get('next', route('teacher::plan', ['plan' => $plan->id])))->with('success', 'Rencana pertemuan ke-'.$plan->az.' berhasil diperbarui.');
		}

		return redirect($request->get('next', route('teacher::plan', ['plan' => $plan->id])))->with('success', 'Rencana pertemuan gagal ke-'.$plan->az.' berhasil diperbarui.');
	}

	/**
	 * Update the presence plan.
	 */
	public function presence(AcademicSubjectMeetPlan $plan, PresenceRequest $request)
	{
       // $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$plan = $user->teacher->plans()->with('meet')->findOrFail($plan->id);

		if($plan->update([
			'plan_at' => $plan->plan_at ?: now(),
			'presence' => AcademicSubjectMeetPlan::transformPresenceFormat($request->input('presence')),
			'realized_at' => $plan->realized_at ?: now()
		])){

			Auth::user()->log(
				' Rencana pertemuan '.$plan->az.' oleh '.$user->name.' pada mata pelajaran '.$plan->meet->subject->name.' telah ditambahkan '.
				' <strong>[ID: ' . $plan->id . ']</strong>',
				AcademicSubjectMeetPlan::class,
				$plan->id
			);

			return redirect($request->get('next', route('teacher::plan', ['plan' => $plan->id])))->with('success', 'Presensi pertemuan ke-'.$plan->az.' berhasil diperbarui.');
		}

		return redirect($request->get('next', route('teacher::plan', ['plan' => $plan->id])))->with('danger', 'Presensi pertemuan ke-'.$plan->az.' gagal diperbarui.');
	}

	/**
	 * Update the assessment plan.
	 */
	public function assessment(AcademicSubjectMeetPlan $plan, AssessmentRequest $request)
	{
       // $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$plan = $user->teacher->plans()->findOrFail($plan->id);

		foreach ($request->input('value') as $smt_id => $value) {
			//dd(AcademicSemester::find($smt_id));
			$studentSmt = StudentSemester::find($smt_id);
			$smt = AcademicSemester::find($studentSmt->semester_id);

			Auth::user()->log(
				' Penilaian pada pertemuan '.$plan->id.', mata pelajaran '.
				$plan->meet->subject->name.
				' semester '.$smt->name.
				' tahun '.$smt->academic->name.
				' pada siswa '.StudentSemester::find($smt_id)->student->user->name.' telah ditambahkan'.
				' <strong>[ID: ' . $plan->id . ']</strong>',
				AcademicSubjectMeetPlan::class,
				$plan->id
			);

			$plan->assessments()->updateOrCreate([
				'smt_id' => $smt_id,
				'subject_id' => $plan->meet->subject_id,
				'plan_id'	=> $plan->id
			], [
				'type' => $request->input('type'),
				'value' => $value ?? 0,
			]);
		}

		return redirect($request->get('next', route('teacher::plan', ['plan' => $plan->id])))->with('success-asses', 'Nilai '.AcademicSubjectMeetEval::find($request->input('type')).' berhasil diperbarui.');
	}
}
