<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\HRMS\Enums\ObShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Repositories\EmployeeCollectiveScheduleTeacherRepository;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\Portal\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Attendance\CollectiveSubmission\StoreRequest;

class CollectiveController extends Controller
{
    use EmployeeRepository, EmployeeCollectiveScheduleTeacherRepository;

    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $this->authorize('access', EmployeeScheduleTeacher::class);

        $user     = $request->user();
        $employee = $user->employee->load('position.position.children');
        $month    = Carbon::parse($request->get('month', now()));
        $start_at = $month->copy()->startOfMonth()->addDays(20)->format('Y-m-d');
        $end_at   = $month->copy()->endOfMonth()->addDays(20)->format('Y-m-d');

        switch ($request->get('type')) {
            case 'teacher':
                $type = PositionTypeEnum::TEACHER->value;
                $label = PositionTypeEnum::TEACHER->key();
                $workshifts = TeacherShiftEnum::cases();
                break;

            case 'security':
                $type = PositionTypeEnum::SECURITY->value;
                $label = PositionTypeEnum::SECURITY->key();
                $workshifts = ObShiftEnum::cases();
                break;

            case 'nonstaf':
                $type = PositionTypeEnum::NONSTAF->value;
                $label = PositionTypeEnum::NONSTAF->key();
                $workshifts = ObShiftEnum::cases();
                break;

            case 'driver':
                $type = PositionTypeEnum::DRIVER->value;
                $label = PositionTypeEnum::DRIVER->key();
                $workshifts = ObShiftEnum::cases();
                break;

            default:
                $type = PositionTypeEnum::NONSTAF->value;
                $label = PositionTypeEnum::NONSTAF->key();
                $workshifts = ObShiftEnum::cases();
                break;
        }


        $employees = Employee::with([
            'user.meta',
            'position.position',
            'schedules' => fn($schedule) => $schedule->where('start_at', $start_at)->where('end_at', $end_at),
        ])
            ->whereHas('position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->when($type, fn($t) => $t->whereHas('position.position', fn($q) => $q->where('type', $type)))
            ->search($request->get('search'))->whenTrashed($request->get('trash'))->get();

        $empPersonil = [];
        foreach ($employees as $employee) {
            $empPersonil[$employee->id] = $employee->id;
            $employee->color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        $employee_count = $employees->count();

        $calendarData = EmployeeScheduleTeacher::whereMonth('start_at', $month) // Ambil berdasarkan bulan
            ->with(['employee.user', 'employee.position.position'])
            ->get()
            ->groupBy('empl_id');



        // Array yang akan menampung hasil
        $result = [];

        // Looping data kalender
        foreach ($calendarData as $keyShowData => $valShowData) {
            if (isset($empPersonil[$keyShowData])) {
                $result[$keyShowData] = [];
                foreach ($valShowData as $keyLevel2 => $valLevel2) {
                    $result[$keyShowData][$type] = [];
                    foreach ($valLevel2->dates as $keyLevel3 => $valLevel3) {
                        foreach ($valLevel3 as $keyLevel4 => $valLevel4) {
                            if ($valLevel4[0] != null) {
                                $result[$keyShowData][$type][$keyLevel3][] = $keyLevel4 + 1;
                            }
                        }
                    }
                }
            }
        }

        $databaseResult = '';
        if (count($result) > 0) {
            $databaseResult = json_encode($result);
        }

        return view('administration::services.schedules_teacher.manage.collective.create', compact('user', 'employees', 'employee_count', 'month', 'workshifts', 'calendarData', 'type', 'databaseResult', 'label'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = $request->user()->employee;
        if ($this->storeEmployeeSchedule($request->transformed()->toArray(), $employee)) {
            return redirect()->back()->with('success', 'Data berhasil diproses!');
        }
        return redirect()->fail();
    }
}
