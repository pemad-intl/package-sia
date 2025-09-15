<?php

namespace Digipemad\Sia\Academic\Http\Controllers\Leave;

use Auth;
use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Core\Models\CompanyStudentLeaveCategory;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\HRMS\Models\EmployeePosition;
use Digipemad\Sia\Academic\Models\Student;
use Modules\Boarding\Models\BoardingStudents;
use Modules\Boarding\Models\BoardingStudentsLeave;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Modules\Portal\Http\Controllers\Controller;
use Digipemad\Sia\Boarding\Http\Requests\Leaves\Submission\StoreRequest;
use Modules\Portal\Notifications\Leave\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Leave\Submission\CanceledNotification;

class SubmissionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$std = $request->user()->student;

		$leaves = $std->leaves()
			->withTrashed()
			->with('approvables.userable.position')
			->search($request->get('search'))
			->whenPeriod($request->get('start_at'), $request->get('end_at'))
			->latest()
			->paginate($request->get('limit', 10));

		$leaves_this_year_count = $std->leaves()->where('dates->[*]->d', 'like', '%' . date('Y') . '%')->whereApproved()->count();

		return view('academic::leave.submission.index', compact('employee', 'leaves', 'leaves_this_year_count'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Request $request)
	{
		$employee = $request->user()->student;
		$students = Student::with('user')->whereNull('deleted_at')->get();
		$categories = CompanyStudentLeaveCategory::with('children')->whereNull('parent_id')->get();

		return view('academic::leave.submission.create', compact('employee', 'categories', 'students'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreRequest $request)
	{
	//	$student = $request->user()->student;
		
		$student = Student::where('user_id', $request->student_id)->first();
		$leave = $student->leaves()->create($request->transform());

        $steps = config('modules.core.features.services.leaves_student.approvable_steps', []);

        foreach ($steps as $step) {
			if (($step['type'] ?? null) !== 'parent_position_level') {
				continue;
			}

			$expectedValues = $step['value'] ?? null;

			// Biarkan $expectedValues selalu berupa array agar konsisten
			$expectedPositionIds = is_array($expectedValues) ? $expectedValues : [$expectedValues];

			foreach ($expectedPositionIds as $expectedPositionId) {
				$position = null;
				switch ($expectedPositionId) {
					case 4:
						$semester = StudentSemester::with('classroom')->where('student_id', $student->id)->first();
						$supervisorId = optional($semester->classroom)->supervisor_id;

						if ($supervisorId) {
							$position = EmployeePosition::where([
								'empl_id'     => $supervisorId,
								'position_id' => $expectedPositionId,
							])->first();
						}
						break;
					case 7:
						
						$semester = StudentSemester::with('classroom')->where('student_id', $student->id)->first();
						$supervisorId = optional($semester->classroom)->supervisor_id;
						
						if ($supervisorId) {
							$position = EmployeePosition::where([
								'empl_id'     => $supervisorId,
								'position_id' => $expectedPositionId,
							])->first();
						}
						break;

					case 2:
						$boarding = BoardingStudents::where('student_id', $student->id)->first();

						if ($boarding) {
							$position = EmployeePosition::where([
								'empl_id'     => $boarding->empl_id,
								'position_id' => $expectedPositionId,
							])->first();
						}
						break;

					default:
						$position = EmployeePosition::where('position_id', $expectedPositionId)->first();
						break;
				}

				if ($position) {
					$leave->createApprovable($position);


					$leave->load('category');
					Auth::user()->log(
						'Wali, '.$student->user->name.' membuat izin kategori <strong>' . ($leave->category->name ?? '-') . '</strong>' .
						' <strong>[ID: ' . $leave->id . ']</strong>',
						BoardingStudentsLeave::class,
						$leave->id
					);

					break; 
				}
			}
		}

				// Handle notifications
				// if ($approvable = $leave->approvables()->orderBy('level')->first()) {
				// 	$approvable->userable->getUser()->notify(new SubmissionNotification($leave, null));
				// }

				return redirect()->route('academic::leave.manage.index')->with('success', isset($position) ? 'Pengajuan izin sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
			}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeLeave $leave, Request $request)
	{
		$user = $request->user();
		$employee = $user->employee;


		$leave = $leave->load('approvables.userable.position');

		return view('academic::leave.submission.show', compact('user', 'employee', 'leave'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(EmployeeLeave $leave)
	{
		$leave->delete();

		// Handle notifications
		if ($approvable = $leave->approvables()->whereNotIn('result', [ApprovableResultEnum::PENDING])->orderBy('level')->first()) {
			$approvable->userable->employee->user->notify(new CanceledNotification($leave));
		}

		return redirect()->route('academic::leave.submission.index')->with('success', 'Pengajuan telah dibatalkan dan kami telah mengirim notifikasi ke atasan!');
	}
}
