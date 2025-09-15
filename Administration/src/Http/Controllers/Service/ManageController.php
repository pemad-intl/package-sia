<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Service;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\ObShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Models\EmployeeScheduleSubmissionTeacher;
use Modules\HRMS\Repositories\EmployeeScheduleTeacherRepository;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Schedule\StoreRequest;
use Modules\Portal\Http\Requests\Schedule\UpdateRequest;
use Modules\Portal\Notifications\ScheduleTeacher\Submission\SubmissionNotification;
use Modules\Portal\Notifications\ScheduleTeacher\Manage\ApprovedNotification;
use Modules\Portal\Notifications\ScheduleTeacher\Manage\RejectedNotification;
use Modules\HRMS\Models\EmployeeTeacherScanLog;

class ManageController extends Controller
{
    use EmployeeRepository, EmployeeScheduleTeacherRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeScheduleTeacher::class);

        $user     = $request->user();
        $employee = $user->employee->load('position.position.children');
        $month    = Carbon::parse($request->get('month', now()));

        $employees = Employee::with([
            'user.meta',
            'contract.position.position',
            'schedulesTeachers' => fn($schedule) => $schedule->whenMonth($month),
        ])
            ->whereHas('position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->whereHas('position.position', fn($p) => $p->whereNotIn('type', [PositionTypeEnum::BACKOFFICE]))
            ->search($request->get('search'))->whenTrashed($request->get('trash'))->paginate($request->get('limit', 10));

        $employee_count = $employees->count();

        return view('administration::services.schedules_teacher.manage.index', compact('user', 'employees', 'employee_count'));
    }

    /**
     * create a resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeScheduleTeacher::class);

        $employee = Employee::findOrFail($request->get('employee'));
        $month    = Carbon::parse($request->get('month', now()));
        $start_at = $month->copy()->startOfMonth()->format('Y-m-d 01:00:01');
        $end_at   = $month->copy()->endOfMonth()->format('Y-m-d 23:59:59');
        $moments  = CompanyMoment::holiday()->whereBetween('date', [Carbon::parse($start_at)->format('Y-m-d'), Carbon::parse($end_at)->format('Y-m-d')])->get();
        $periods  = CarbonPeriod::create(Carbon::parse($start_at)->format('Y-m-d'), '1 day', Carbon::parse($end_at)->format('Y-m-d'));

        switch ($employee->position->position->type) {
            case PositionTypeEnum::BACKOFFICE:
                $workshifts = WorkShiftEnum::cases();
                break;

            case PositionTypeEnum::TEACHER:
                $workshifts = TeacherShiftEnum::cases();
                break;

            case PositionTypeEnum::NONSTAF:
            case PositionTypeEnum::SECURITY:
                $workshifts = ObShiftEnum::cases();
                break;

            default:
                $workshifts = [];
                break;
        }

        // Iterate over the period
        $dates = [];
        foreach ($periods as $key => $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return view('administration::services.schedules_teacher.manage.create', compact('employee', 'dates', 'workshifts', 'moments', 'start_at', 'end_at'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($schedule = $this->storeEmployeeSchedule($request->transformed()->toArray())) {

            // foreach (config('modules.core.features.services.schedules.approvable_steps', []) as $model) {
            //     if ($model['type'] == 'parent_position_level') {
            //         if ($position = $schedule->employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {

            //             $schedule->createApprovable($position);
            //         }
            //     }
            // }

            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeScheduleTeacher $schedule, Request $request)
    {
        $this->authorize('update', $schedule);
        $month     = Carbon::parse($request->get('month', now()));
        $start_at  = $month->copy()->startOfMonth()->format('Y-m-d 01:00:01');
        $end_at    = $month->copy()->endOfMonth()->format('Y-m-d 23:59:59');
        $periods   = CarbonPeriod::create(Carbon::parse($start_at)->format('Y-m-d'), '1 day', Carbon::parse($end_at)->format('Y-m-d'));
        $moments   = CompanyMoment::holiday()->whereBetween('date', [Carbon::parse($start_at)->format('Y-m-d'), Carbon::parse($end_at)->format('Y-m-d')])->get();

        switch ($schedule->employee->position->position->type) {
            case PositionTypeEnum::TEACHER:
                $workshifts = TeacherShiftEnum::cases();
                break;

            case PositionTypeEnum::TEACHERjAKARTA:
                $workshifts = TeacherShiftEnum::cases();
                break;

            default:
                $workshifts = [];
                break;
        }

        $dates = [];
        foreach ($periods as $key => $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return view('administration::services.schedules_teacher.manage.show', compact('schedule', 'workshifts', 'dates', 'moments', 'start_at', 'end_at'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeScheduleTeacher $schedule, UpdateRequest $request)
    {
        if (in_array($request->user()->employee->position->position_id, [6, 7])) {
            if ($schedule = $this->updateEmployeeSchedule($schedule, $request->transformed()->toArray())) {
                return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> diajukan untuk disetujui.');
            }
        } else {
            $submission = EmployeeScheduleSubmissionTeacher::firstOrNew([
                'empl_id' => $schedule->empl_id,
            ]);

            if ($submission) {
                $submission->approved_at = null;
                $submission->save();
                if ($schedule = $this->updateEmployeeSubmissionSchedule($submission, $request->transformed()->toArray())) {
                    foreach (config('modules.core.features.services.schedules.approvable_steps', []) as $model) {
                        if ($model['type'] == 'parent_position_level') {
                            if ($position = $schedule->employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
                                $schedule->createApprovable($position);
                            }
                        }
                    }
                }

                return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> diajukan sedang diajukan.');
            }
        }

        return redirect()->fail();
    }

    public function otomatic(EmployeeScheduleTeacher $schedule)
    {
        $this->authorize('update', $schedule);

        $empl_id = $schedule->empl_id;
        $schedules = $schedule->dates;
        $withNull = false;

        foreach ($schedules as $date => $shifts) {
            foreach ($shifts as $i => $shift) {
                $workshift = TeacherShiftEnum::tryFrom($i + 1);
                if (!empty($shift[0]) && $workshift) {
                    $schedule = $shift[0];
                    $input = new EmployeeTeacherScanLog([
                        'empl_id'    => $empl_id,
                        'latlong'    => [-6.200000, 106.816666],
                        'location'   => 1,
                        'ip'         => getClientIp(),
                        'user_agent' => 'Mozilla/5.0',
                        'created_at' => $schedule,
                    ]);

                    $input->save();
                }
            }
        }

        return back()->with('success', 'Simulasi kehadiran berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeScheduleTeacher $schedule)
    {
        $this->authorize('destroy', $schedule);

        if ($schedule = $this->destroyEmployeeSchedule($schedule)) {
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Update the specified resource in storage.
     */
    public function approval(CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable->update($request->transformed()->toArray());

        // Handle notifications
        if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
            $approvable->modelable->employee->user->notify(new ApprovedNotification($approvable->modelable, $approvable));
            if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn($a) => $a->level > $approvable->level)->first()) {
                $superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable, $approvable->userable));
            }
        }

        if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
            $approvable->modelable->employee->user->notify(new RejectedNotification($approvable->modelable, $approvable));
        }

        return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}
