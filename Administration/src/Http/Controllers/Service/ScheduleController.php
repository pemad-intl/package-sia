<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Repositories\EmployeeScheduleRepository;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\Portal\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Models\Employee;
use Modules\Core\Enums\PositionTypeEnum;

class ScheduleController extends Controller
{
    use EmployeeRepository, EmployeeScheduleRepository;

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

        return view('administration::services.schedules_teacher.index', compact('user', 'employees', 'employee_count'));
    }
}
