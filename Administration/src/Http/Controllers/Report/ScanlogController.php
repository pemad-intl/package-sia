<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeTeacherScanLog;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Requests\Service\Attendance\Scanlog\StoreRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ScanlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeTeacherScanLog::class);

        $start_at = $request->get('start_at', date('Y-m-01')) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $departments = CompanyDepartment::visible()->with('positions')->get();

        foreach (WorkLocationEnum::cases() as $location) {
            $locations[$location->value] = $location->name;
        }

        $datascanlogs = EmployeeTeacherScanLog::with('employee.user', 'employee.contract.position.position')
            ->where('created_at', '>=', Carbon::parse($start_at))
            ->where('created_at', '<=', Carbon::parse($end_at))
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->latest()
            ->get();

        $totalGroup = count($datascanlogs);
        $perPage    = $request->get('limit', 10);
        $page       = Paginator::resolveCurrentPage('page');


        $scanlogs = new LengthAwarePaginator($datascanlogs->forPage($page, $perPage), $totalGroup, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        return view('administration::report.scanlogs.index', compact('start_at', 'end_at', 'departments', 'scanlogs', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));

        $input = $request->transformed()->toArray();
        $input['empl_id'] = $employee->id;

        $scan = new EmployeeTeacherScanLog($input);

        if ($scan->save()) {
            return redirect()->back()->with('success', 'Rekap presensi <strong>' . $employee->user->name . '</strong> pada tanggal ' . $request->input('datetime') . ' berhasil dibuat.');
        }
        return redirect()->fail();
    }
}
