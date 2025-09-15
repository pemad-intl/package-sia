<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetPlan;
use Digipemad\Sia\Teacher\Http\Requests\Meet\StoreRequest;
use Digipemad\Sia\Teacher\Http\Requests\Meet\CopyRequest;
use Digipemad\Sia\Teacher\Http\Requests\Meet\UpdateRequest;

class MeetController extends Controller
{
	/**
	 * Show the meet details.
	 */
	public function show(AcademicSubjectMeet $meet, Request $request)
	{
       // $this->authorize('show', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$meet = $user->teacher->meets()
							->withCount('plans')
							->findOrFail($meet->id);

		$notall = $request->get('all', 0) == 0;

		$plans = $meet->plans()
					->when($notall, function ($query){
						return $query->whereNull('presence')
								 	 ->limit(5);
					})
					->orderBy('az')
					->get();

		$filledMeets = $user->teacher->meets()->withCount('plans')->where('subject_id', $meet->subject_id)->where('semester_id', $acsem->id)->has('plans')->get();

		return view('teacher::meets.show', compact('meet', 'acsem', 'plans', 'filledMeets'));
	}

	/**
	 * Store with the specified resource.
	 */
	public function store(AcademicSubjectMeet $meet, StoreRequest $request)
	{
     //   $this->authorize('store', AcademicSemester::class);
		$acsem = $this->acsem;

		$user = auth()->user();

		$meet = $user->teacher->meets()
							->withCount('plans')
							->findOrFail($meet->id);

		$hour = $request->input('count');

		$plans = [];
		for ($i = 1; $i <= $request->input('meets') ; $i++) {
			$plans[] = [
				'az'	=> $i,
				'hour'	=> $hour
			];
		}

		$meet->plans()->createMany($plans);

		return redirect()->back()->with('success', '<strong>'.$request->input('meets').' pertemuan</strong> berhasil dibuat!');
	}

	/**
	 * Copy with the specified resource.
	 */
	public function copy(AcademicSubjectMeet $meet, CopyRequest $request)
	{
        $this->authorize('access', AcademicSemester::class);
		$acsem = $this->acsem;

		$user = auth()->user();

		$meet = $user->teacher->meets()
							->withCount('plans')
							->findOrFail($meet->id);

		$source = $user->teacher->meets()->with('plans')
							->findOrFail($request->input('meet_id'));

		$plans = $source->plans->sortBy('az')->map(function ($plan) {
					return $plan->only('az', 'hour', 'comp_id', 'test');
				});

		$meet->plans()->createMany($plans);

		return redirect()->back()->with('success', '<strong>'.$plans->count().' pertemuan</strong> berhasil disalin dari rombel <strong>'.$source->classroom->full_name.'</strong>!');
	}

	/**
	 * Mange the plans.
	 */
	public function manage(AcademicSubjectMeet $meet, Request $request)
	{
        $this->authorize('access', AcademicSemester::class);
		$acsem = $this->acsem;

		$user = auth()->user();

		$meet = $user->teacher->meets()
							->withCount('plans')
							->findOrFail($meet->id);

		$all = $request->get('all', 0) == 0;

		$plans = $meet->plans()
					->orderBy('az')
					->get();

		return view('teacher::meets.manage', compact('meet', 'acsem', 'plans'));
	}

	/**
	 * Update the plans.
	 */
	public function update(AcademicSubjectMeet $meet, UpdateRequest $request)
	{
        $this->authorize('update', AcademicSemester::class);
		$acsem = $this->acsem;

		$user = auth()->user();

		$meet = $user->teacher->meets()->findOrFail($meet->id);

		foreach ($request->input('plans') as $plan => $data) {
			$data['test'] = isset($data['test']) ? 1 : 0;
			if($data['test'] == 1) {
				$data['comp_id'] = null;
			}
			$meet->plans()->find($plan)->update($data);
		}

		return redirect($request->get('next', route('teacher::meet', ['meet' => $meet->id])))->with('success', '<strong>Pertemuan</strong> berhasil diperbarui secara kolektif!');
	}
}
