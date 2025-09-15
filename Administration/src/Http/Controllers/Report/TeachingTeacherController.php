<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyMoment;
use Modules\Core\Models\CompanyPosition;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeePosition;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Administration\Http\Requests\Summary\Attendance\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Summary\Attendance\UpdateRequest;
use Modules\HRMS\Models\EmployeeRecapSubmission;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Digipemad\Sia\Administration\Http\Requests\Service\Teacher\SubmissionUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class TeachingTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeRecapSubmission::class);
        $user     = $request->user();
        $employee = $user->employee->load('position.position.children');
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at   = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $departments = CompanyDepartment::whereIn(
            'id',
            CompanyPosition::whereType(PositionTypeEnum::TEACHER)
                ->pluck('dept_id')->unique()->toArray()
        )->visible()->with(['positions' => fn($poss) => $poss->whereType(PositionTypeEnum::TEACHER)])->get();

        $summaries = EmployeeRecapSubmission::whereType(DataRecapitulationTypeEnum::HONOR)->whereStrictPeriodIn($start_at, $end_at)->get();
        $employees = Employee::with('user', 'contract.position.position')
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->whereHas('position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->whereHas('position.position', fn($q) => $q->where('type', PositionTypeEnum::TEACHER->value))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        return view('administration::report.teacher.index', compact('start_at', 'end_at', 'departments', 'summaries', 'employees'));
    }
}
