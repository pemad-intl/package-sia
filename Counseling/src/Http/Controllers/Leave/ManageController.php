<?php

namespace Digipemad\Sia\Counseling\Http\Controllers\Leave;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Boarding\Models\BoardingStudentsLeave;
use Digipemad\Sia\Boarding\Models\BoardingStudents;
use Modules\Core\Enums\ApprovableResultEnum;
use Digipemad\Sia\Boarding\Models\BoardingCompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Leave\Manage\UpdateRequest;
use Digipemad\Sia\Boarding\Notifications\Leave\Submission\SubmissionNotification;
use Digipemad\Sia\Boarding\Notifications\Leave\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Leave\Manage\RejectedNotification;

class ManageController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$user = $request->user();
	//	$employee = $user->employe;

		$leaves = BoardingStudentsLeave::with('student.user')
			->whenOnlyPending($request->get('pending'))
			->search($request->get('search'))
			->latest()
			->paginate($request->get('limit', 10));

		$pending_leaves_count = BoardingStudentsLeave::whenOnlyPending(true)
			->count();

		return view('counseling::leave.manage.index', compact('user', 'leaves', 'pending_leaves_count'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(BoardingStudentsLeave $leave, Request $request)
	{
		$user = $request->user();
		$student = $user->student;
        $employee = $user->employee;

        $parentBoard = BoardingStudents::with('employee')->where('student_id', $leave->student_id)->first();
		$results = config('modules.core.features.services.leaves_student.approvable_enum_available');

		$leave = $leave->load('approvables.userable.position');
		return view('counseling::leave.manage.show', compact('user', 'student', 'employee', 'leave', 'results', 'parentBoard'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(BoardingCompanyApprovable $approvable, UpdateRequest $request)
	{
		$approvable->update($request->transformed()->toArray());

		// Handle notifications
		$user = auth()->user()->employee;
	
		if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
            $approvable->modelable->student->user->notify(new ApprovedNotification($approvable->modelable, $approvable));
			if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn ($a) => $a->level > $approvable->level)->first()) {
				Auth::user()->log(
					'Perizinan disetujui oleh <strong>' . (auth()->user()->employee->user->name ?? '-') . '</strong>' .
					' <strong>[ID: ' . $superior->id . ']</strong>',
					BoardingStudentsLeave::class,
					$superior->id
				);
				$superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable, $approvable->userable));
			}
		}

		if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
			if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn ($a) => $a->level > $approvable->level)->first()) {
				Auth::user()->log(
					'Perizinan ditolak oleh <strong>' . (auth()->user()->employee->user->name ?? '-') . '</strong>' .
					' <strong>[ID: ' . $superior->id . ']</strong>',
					BoardingStudentsLeave::class,
					$superior->id
				);

				$approvable->modelable->student->user->notify(new RejectedNotification($approvable->modelable, $approvable));
			}
		}

		return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
	}
}
