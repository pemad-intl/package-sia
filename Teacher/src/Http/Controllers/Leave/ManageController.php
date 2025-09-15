<?php

namespace Digipemad\Sia\Teacher\Http\Controllers\Leave;

use Illuminate\Http\Request;
use Digipemad\Sia\Boarding\Models\BoardingStudentsLeave;
use Digipemad\Sia\Boarding\Models\BoardingStudents;
use Modules\Core\Enums\ApprovableResultEnum;
use Digipemad\Sia\Boarding\Models\BoardingCompanyApprovable;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Leave\Manage\UpdateRequest;
use Digipemad\Sia\Boarding\Notifications\Leave\Submission\SubmissionNotification;
use Digipemad\Sia\Boarding\Notifications\Leave\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Leave\Manage\RejectedNotification;
use Auth;

class ManageController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$acsem = $this->acsem;
		$user = $request->user();

		$clasroom_id = $user->teacher->classroom->id;
	
		$leaves = BoardingStudentsLeave::with([
        		'student.user',
				'student.semesters'
			])
			->whereHas('student.semesters', function ($query) use ($clasroom_id) {
				if ($clasroom_id) {
					$query->where('classroom_id', $clasroom_id);
				}
			})
			->whenOnlyPending($request->get('pending'))
			->search($request->get('search'))
			->latest()
			->paginate($request->get('limit', 10));

		$pending_leaves_count = BoardingStudentsLeave::with([
        		'student.user',
				'student.semesters'
			])
			->whereHas('student.semesters', function ($query) use ($clasroom_id) {
				if ($clasroom_id) {
					$query->where('id', $clasroom_id);
				}
			})->whenOnlyPending(true)
			->count();

		return view('teacher::leave.manage.index', compact('user', 'leaves', 'pending_leaves_count', 'acsem'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(BoardingStudentsLeave $leave, Request $request)
	{
		$acsem = $this->acsem;

		$user = $request->user();
		$student = $user->student;
        $employee = $user->employee;

        $parentBoard = BoardingStudents::with('employee')->where('student_id', $leave->student_id)->first();
		$results = config('modules.core.features.services.leaves_student.approvable_enum_available');

		$leave = $leave->load('approvables.userable.position');
		return view('teacher::leave.manage.show', compact('user','acsem', 'student', 'employee', 'leave', 'results', 'parentBoard'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(BoardingCompanyApprovable $approvable, UpdateRequest $request)
	{
		$approvable->update($request->transformed()->toArray());

		// Handle notifications
		if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
			Auth::user()->log(
				'Perizinan disetujui oleh <strong>' . (auth()->user()->employee->user->name ?? '-') . '</strong>' .
				' <strong>[ID: ' . $approvable->id . ']</strong>',
				BoardingStudentsLeave::class,
				$approvable->id
			);

            $approvable->modelable->student->user->notify(new ApprovedNotification($approvable->modelable, $approvable));
			if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn ($a) => $a->level > $approvable->level)->first()) {
				$superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable, $approvable->userable));
			}
		}

		if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
			Auth::user()->log(
				'Perizinan ditolak oleh <strong>' . (auth()->user()->employee->user->name ?? '-') . '</strong>' .
				' <strong>[ID: ' . $approvable->id . ']</strong>',
				BoardingStudentsLeave::class,
				$approvable->id
			);

            $approvable->modelable->student->user->notify(new RejectedNotification($approvable->modelable, $approvable));
		}

		return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
	}
}
