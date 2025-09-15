<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Summary;

use Auth;
use Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Models\EmployeeRecapSubmission;
use Digipemad\Sia\Administration\Http\Requests\Summary\Coord\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Summary\Coord\UpdateRequest;
use Modules\HRMS\Models\EmployeeAppreciation;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Models\EmployeePosition;

class CoordinatorController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('administration::summary.coords.index', [
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'employees'   => Employee::with(['user', 'contract.position.position', 'recapSubmissions' => fn($recap) => tap(
                $recap->whereType(DataRecapitulationTypeEnum::COORD)->whereStrictPeriodIn($start_at, $end_at),
                fn($filtered) => $filtered->pluck('type')
            )])
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->whereHas('position.position', fn($q) => $q->where('type', PositionTypeEnum::TEACHER->value))
                ->whereHas('contract')
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10))
        ]);
    }

    public function show(Employee $employee, Request $request)
    {
        $start_at = $request->start_at;
        $end_at = $request->end_at;
        $status = '';
        if (isset($request->status)) {
            $status = $request->status;
        }

        $userNow = $request->user()->employee->position;

        $results = ApprovableResultEnum::cases();
        $attendance = EmployeeRecapSubmission::where([
            'empl_id' => $employee->id,
            'start_at' => $start_at,
            'end_at' => $end_at,
            'type' => DataRecapitulationTypeEnum::COORD
        ])->first();


        $showRecap = $employee->recapSubmissions
            ->filter(
                fn($item) =>
                $item->type === DataRecapitulationTypeEnum::COORD &&
                    $item->whereStrictPeriodIn($start_at, $end_at) // Sesuaikan dengan method di model
            );

        return view('administration::summary.coords.show', compact('showRecap', 'start_at', 'end_at', 'employee', 'userNow', 'attendance', 'results', 'status'));
    }

    /**
     * Create resource
     */
    public function create(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $employee = Employee::findOrFail($request->get('employee'));

        return view('administration::summary.coords.create', [
            'employee'   => $employee,
            'start_at'   => $start_at,
            'end_at'     => $end_at,
            'types'      => [DataRecapitulationTypeEnum::COORD],
            'employees'  => Employee::with('user', 'contract.position.position')->get(),
            'recap'      => EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::COORD)->find($request->get('edit')),
        ]);
    }

    /**
     * Store resource
     */
    public function store(StoreRequest $request)
    {
        $resulter = $request->transform();
        $employee = Employee::find($resulter['employee']);


        $recap = $employee->recapSubmissions()->create([
            'empl_id' => $resulter['employee'],
            'type' => DataRecapitulationTypeEnum::COORD,
            'start_at' => date('Y-m-d', strtotime($resulter['start_at'])),
            'end_at' => date('Y-m-d', strtotime($resulter['end_at'])),
            'result' => $resulter['result']
        ]);

        foreach (config('modules.core.features.services.recapcoordinator.approvable_steps', []) as $model) {
            if ($model['type'] == 'employee_position_by_kd') {
                if ($approver = EmployeePosition::active()->whereHas('position', fn($position) => $position->whereIn('kd', $model['value']))->first()) {
                    $recap->createApprovable($approver);
                }
            }
        }

        //  Auth::user()->log('membuat rekap ' . count($request->input('fields', [])) . ' POM karyawan atas nama ' . $employee->user->name, EmployeeDataRecapitulation::class, $recap->id);

        return redirect()->next()->with('success', 'Rekap total <strong>' . $resulter['counting'] . '</strong> murit dari karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi dibuat!');
    }

    public function submissionApprovals(EmployeeRecapSubmission $recap, Request $request)
    {
        $attendance = EmployeeRecapSubmission::with('approvables')
            ->where('id', $recap->id);

        $approvables = $attendance->get()->flatMap->approvables;
        $submissionCoor = $attendance->first();

        if ($approvables->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        foreach ($approvables as $approvable) {
            if (ApprovableResultEnum::APPROVE->value == (int) $request->result) {
                $approvable->update([
                    'result' => ApprovableResultEnum::APPROVE->value,
                    'reason' => $request->input('reason') ?? 'Diterima oleh sistem.',
                ]);

                $submission = EmployeeRecapSubmission::find($approvable->modelable_id);
                $submission->update(['validated_at' => now()]);

                if ((int) $request->result == ApprovableResultEnum::APPROVE->value && $submission) {
                    EmployeeDataRecapitulation::create([
                        'empl_id' => $approvable->userable_id,
                        'type' => $submissionCoor->type,
                        'start_at' => $submissionCoor->start_at,
                        'end_at' => $submissionCoor->end_at,
                        'result' => $submissionCoor->result,
                    ]);
                }
            } else if (ApprovableResultEnum::REJECT->value == (int) $request->result) {
                $approvable->update([
                    'result' => ApprovableResultEnum::REJECT->value,
                    'reason' => $request->input('reason') ?? 'Ditolak oleh sistem.',
                ]);
            } else if (ApprovableResultEnum::REVISION->value == (int) $request->result) {
                $approvable->update([
                    'result' => ApprovableResultEnum::REVISION->value,
                    'reason' => $request->input('reason') ?? 'Ditolak oleh sistem.',
                ]);
            } else {
                $approvable->update([
                    'result' => $request->input('result'),
                    'reason' => $request->input('reason'),
                ]);
            }
        }

        return back()->with('success', 'Approval berhasil diperbarui.');
    }

    public function update(Employee $employee, UpdateRequest $request)
    {
        $start_at = $request->start_at;
        $end_at = $request->end_at;

        $attendance = EmployeeRecapSubmission::where([
            'empl_id' => $employee->id,
            'start_at' => $start_at,
            'end_at' => $end_at,
            'type' => DataRecapitulationTypeEnum::COORD
        ])->first();

        if ($attendance) {
            $attendance->update([
                'result' => $request->transform()['result']
            ]);

            return back()->with('success', 'Rekap koordinator berhasil diperbarui!');
        }

        return back()->with('error', 'Rekap koordinator gagal diperbarui');
    }

    public function destroy(Employee $employee, Request $request)
    {
        $start_at = $request->start_at;
        $end_at = $request->end_at;

        $attendance = EmployeeRecapSubmission::where([
            'empl_id' => $employee->id,
            'start_at' => $start_at,
            'end_at' => $end_at,
            'type' => DataRecapitulationTypeEnum::COORD
        ])->first();

        if ($attendance) {
            $attendance->delete(); // Hapus data
            return back()->with('success', 'Data berhasil dihapus!');
        } else {
            return back()->with('error', 'Data tidak ditemukan');
        }
    }

    public function summary(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $data = EmployeeAppreciation::with([
            'employee' => fn($w) => $w->with('user', 'contract', 'position.position')
        ])->whereBetween('approved_at', [$start_at, $end_at])->get()->groupBy('period');

        $poms = $data->map(function ($period, $index) use ($start_at, $end_at) {
            return
                $period->map(function ($empl) use ($start_at, $end_at) {
                    return [
                        'name'   => $empl->employee->user->name,
                        'dept'   => $empl->employee->position->position->department->name,
                        'position' => $empl->employee->position->position->name,
                        'period' => $empl->period,
                        'title'  => $empl->name,
                        'type'   => $empl->type->label(),
                        'rate'   => ($rate = $empl->type->getRate()),
                        'q'      => ($param = $empl->employee->position->position->dept_id != 4
                            ? collect(config('modules.finance.features.pom.groups.support'))
                            : ($empl->employee->position->position->level->value == 5 ? collect(config('modules.finance.features.pom.groups.coordinator'))
                                : ($empl->employee->position->position->level->value == 6 ? collect(config('modules.finance.features.pom.groups.projectmanager'))
                                    : (in_array($empl->employee->position->position->id, [12, 13]) ? collect(config('modules.finance.features.pom.groups.editor'))
                                        : collect(config('modules.finance.features.pom.groups.translator'))
                                    ))
                            )),
                        'count'  => ($count = EmployeeAppreciation::with(['employee' => fn($w) => $w->with('user', 'position.position')])
                            ->wherePeriod($empl->period)
                            ->whereBetween('approved_at', [$start_at, $end_at])
                            ->whereHas('employee.position.position', fn($h) => $h->where('dept_id', $empl->employee->position->position->dept_id) && $h->{$param->pluck('query')->flatten()->first()}(...$param->pluck('clause')->flatten(1)))
                            ->count()),
                        'amount' => $rate * 1 / $count
                    ];
                });
        });

        foreach ($poms as $key => $value) {
            $json[$key] = [
                'columns' => [
                    'number' => 'No',
                    'name' => 'Nama Karyawan',
                    'department' => 'Departemen',
                    'position' => 'Posisi',
                    'period' => 'Periode',
                    'type' => 'Kategori',
                    'description' => 'Judul',
                    'multiplier' => 'Pengali',
                    'rate' => 'Tarif',
                    'subtotal' => 'Subtotal',
                ],
                'data' => $value->map(function ($item, $i) use ($key, $value) {
                    return [
                        'number' => $i + 1,
                        'name' => $item['name'],
                        'department' => $item['dept'],
                        'position' => $item['position'],
                        'period' => $item['period'],
                        'type' => $item['type'],
                        'description' => $item['title'],
                        'multiplier' => 1 / $item['count'],
                        'rate' => (int)$item['rate'],
                        'subtotal' => $item['amount'],
                    ];
                }),
            ];
        }

        return response()->json([
            'title' => ($title = 'Rekap personnel of the month periode ' . date('Y-m-d', strtotime($start_at)) . ' - ' . date('Y-m-d', strtotime($end_at))),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'sheets' => $json
        ]);
    }
}
