<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Service;

use Illuminate\Http\Request;
use Modules\Account\Notifications\AccountNotification;
use Modules\HRMS\Models\EmployeeScheduleSubmissionTeacher;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Models\Employee;
use Modules\Account\Models\User;
use Modules\Core\Enums\PositionLevelEnum;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\HRMS\Enums\ObShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Repositories\EmployeeSubmissionCollectiveScheduleRepository;
use Modules\HRMS\Repositories\EmployeeCollectiveTeacherImportExcel;
use Modules\Portal\Http\Requests\Schedule\SubmissionUpdateRequest;
use Modules\Portal\Notifications\ScheduleTeacher\AllocateSubmission\Submission\AllocativeSubmissionNotification;
use Modules\Portal\Notifications\ScheduleTeacher\Submission\AllocateSubmission\ApprovedNotification;
use Modules\Portal\Notifications\ScheduleTeacher\Submission\AllocateSubmission\RejectedNotification;
use Modules\Portal\Notifications\ScheduleTeacher\Submission\AllocateSubmission\RevisedNotification;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Digipemad\Sia\Administration\Excel\RowSchedule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class ManageSubmissionScheduleController extends Controller
{
    use EmployeeCollectiveTeacherImportExcel;
    private $superiors = [];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $start_at = $request->get('start_at', date('Y-m-01', strtotime("-1 months"))) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $submissions = EmployeeScheduleSubmissionTeacher::search($request->get('search'))
            ->whenPending($request->get('pending'))
            ->whenCreateInPeriod($start_at, $end_at)
            ->latest()
            ->distinct('empl_id')
            ->paginate($request->get('limit', 10));

        $submission_count = $submissions->whereNull('approved_at')->count();

        $steps = array_filter(
            config('modules.core.features.services.schedules.approvable_steps', []),
            fn($step) => empty($step['hide_from_input'])
        );



        return view('administration::services.schedules_teacher.manage.submission.index', compact('submissions', 'submission_count', 'start_at', 'end_at'));
    }

    public function show(EmployeeScheduleSubmissionTeacher $submission, Request $request)
    {
        $submissionId = $submission->id;
        $employee = $request->user()->employee;
        $month    = isset($request->month) ? Carbon::parse($request->month) : Carbon::now()->startOfMonth();
        $start_at = $month->copy()->startOfMonth()->addDays(20)->format('Y-m-d');
        $end_at   = $month->copy()->endOfMonth()->addDays(20)->format('Y-m-d');

        $calendarData = EmployeeScheduleSubmissionTeacher::whereMonth('start_at', $month)
            ->with(['employee.user', 'employee.position.position'])
            ->where('empl_id', $submission->empl_id)
            ->get()
            ->groupBy('empl_id');

        $result = [];


        foreach ($calendarData as $keyShowData => $valShowData) {
            $result[$keyShowData] = [];
            foreach ($valShowData as $keyLevel2 => $valLevel2) {
                if ($valLevel2->employee->position->position_id == $submission->employee->position->position_id) {

                    switch ($submission->employee->position->position_id) {
                        case 14:
                            $type = PositionTypeEnum::TEACHER->value;
                            $label = PositionTypeEnum::TEACHER->key();
                            $workshifts = TeacherShiftEnum::cases();
                            break;

                        case 15:
                            $type = PositionTypeEnum::TEACHERjAKARTA->value;
                            $label = PositionTypeEnum::TEACHERjAKARTA->key();
                            $workshifts = TeacherShiftEnum::cases();
                            break;


                        default:
                            $workshifts = [];
                            break;
                    }

                    $result[$keyShowData][$submission->employee->position->position_id] = [];

                    foreach ($valLevel2->dates as $keyLevel3 => $valLevel3) {
                        foreach ($valLevel3 as $keyLevel4 => $valLevel4) {
                            if ($valLevel4[0] != null) {
                                $result[$keyShowData][$submission->employee->position->position_id][$keyLevel3][] = $keyLevel4 + 1;
                            }
                        }
                    }
                }
            }
        }

        // $databaseResult = '';
        // if (count($result) > 0) {
        //     $databaseResult = json_encode($result);
        // }

        $databaseResult = json_encode($result);
        //$employee = $user->employee;
        $scheduleSubmission = $submission->load('approvables.userable.position');

        $results = ApprovableResultEnum::cases();

        if (isset($submission->dates->first()['cashable'])) {
            unset($results[array_search(ApprovableResultEnum::REVISION, $results)]);
        }

        return view('administration::services.schedules_teacher.manage.submission.show', compact('month', 'workshifts', 'databaseResult', 'calendarData', 'type', 'scheduleSubmission', 'employee', 'results', 'submissionId'));
    }

    public function update() {}

    public function SubmissionUpdate(SubmissionUpdateRequest $request, CompanyApprovable $approvable)
    {
        $approvable->update($request->transformed()->toArray());

        if ($approvable->cancelable) {
            $approvable->modelable->approvables()->update($request->transformed()->only('result'));

            if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
                $approvable->modelable->update([
                    'dates' => $approvable->modelable->dates->filter(fn($d) => empty($d['c']))
                ]);
                $this->sendCancelableNotifications($approvable);
            }

            if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
                $approvable->modelable->update([
                    'dates' => $approvable->modelable->dates->map(function ($date) {
                        $date['c'] = false;
                        return array_filter($date);
                    })
                ]);
                $this->sendRejectionNotifications($approvable, 1);
            }
        } else {
            if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
                $approveAt = EmployeeScheduleSubmissionTeacher::find($request->id);
                $approveAt->update(['approved_at' => now()]);

                $existingSch = EmployeeScheduleTeacher::where(['empl_id' => $approveAt->empl_id, 'start_at' => $approveAt->start_at, 'end_at' => $approveAt->end_at])->first();

                if ($existingSch) {
                    $existingSch->update([
                        'dates' => $approveAt->dates,
                        'workdays_count' => $approveAt->workdays_count
                    ]);
                } else {
                    $schedule = new EmployeeScheduleTeacher([
                        'empl_id' => $approveAt->empl_id,
                        'start_at' => $approveAt->start_at,
                        'end_at' => $approveAt->end_at,
                        'dates' => $approveAt->dates,
                        'workdays_count' => $approveAt->workdays_count
                    ]);

                    $schedule->save();
                }
                $this->sendApprovalNotifications($approvable);
            }

            if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
                $this->sendRejectionNotifications($approvable);
            }

            if ($request->input('result') == ApprovableResultEnum::REVISION->value) {
                $this->sendRevisionNotifications($approvable);
            }
        }
        // $schedule = EmployeeScheduleSubmission::where('id', $id);

        // if ($this->storeEmployeeSchedule($schedule->first()->toArray()) == true) {
        return redirect($request->input('next'))->with('success', 'Submission saved successfully!');
        // }

        // return redirect()->fail();
    }

    public function importExcel(Request $request)
    {

        $file = $request->file('scheduleFile');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();

        $lastRow = $worksheet->getHighestRow();
        $lastColumn = $worksheet->getHighestColumn();
        $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

        $dateShifts = [];
        $getDate = [];
        for ($col = 1; $col <= $lastColumnIndex; $col += 5) {
            $tanggalCell = $worksheet->getCellByColumnAndRow($col, 2);
            $tanggalValue = $tanggalCell->getValue();

            if ($tanggalValue) {
                $timestamp = strtotime($tanggalValue);
                $tanggal = date('Y-m-d', $timestamp);

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
                    $nextMonday = date('Y-m-d', strtotime('next monday')); // Senin minggu depan
                    $nextSunday = date('Y-m-d', strtotime('next monday +6 days')); // Minggu minggu depan

                    if ($tanggal >= $nextMonday && $tanggal <= $nextSunday) {
                        $monthYear = substr($tanggal, 0, 7);
                        if (!in_array($monthYear, $getDate)) {
                            $getDate[] = $monthYear;
                        }
                    } else {
                        return redirect()->back()->with('danger', 'Data gagal diproses, pastikan tanggal sesuai dengan minggu depan!');
                    }
                }

                $shifts = [];
                for ($shiftCol = $col; $shiftCol < $col + 5; $shiftCol++) {
                    $shiftCell = $worksheet->getCellByColumnAndRow($shiftCol, 4);
                    $shiftName = $shiftCell->getValue();

                    $shiftData = [];

                    for ($dataRow = 5; $dataRow <= $lastRow; $dataRow++) {
                        $dataValue = $worksheet->getCellByColumnAndRow($shiftCol, $dataRow)->getValue();
                        if (!empty($dataValue)) {
                            $shiftData[] = $dataValue;
                        }
                    }

                    if ($shiftName) {
                        $shifts[$shiftName] = !empty($shiftData) ? $shiftData : [];
                    }
                }

                if (!empty($shifts)) {
                    $dateShifts[$tanggal] = $shifts;
                }
            }
        }

        $dateShiftsz = [];
        foreach ($getDate as $monthYear) {
            $startOfMonth = Carbon::parse($monthYear . '-01')->startOfMonth();
            $endOfMonth = Carbon::parse($monthYear . '-01')->endOfMonth();
            $datesInMonth = [];
            $datePeriod = CarbonPeriod::create($startOfMonth, $endOfMonth);
            foreach ($datePeriod as $date) {
                $formattedDate = $date->format('Y-m-d');
                $datesInMonth[$formattedDate] = [];
            }

            $dateShiftsz[$monthYear] = $datesInMonth;
        }

        $dataShiftUser = [];
        $compareShift = [];

        foreach ($dateShifts as $key => $val) {
            if (!Carbon::hasFormat($key, 'Y-m-d')) {
                continue;
            }

            $monthKey = Carbon::parse($key)->format('Y-m');

            if (!isset($dateShiftsz[$monthKey])) {
                $startOfMonth = Carbon::parse($monthKey . '-01')->startOfMonth();
                $endOfMonth = Carbon::parse($monthKey . '-01')->endOfMonth();

                $datesInMonth = [];
                $datePeriod = CarbonPeriod::create($startOfMonth, $endOfMonth);

                foreach ($datePeriod as $date) {
                    $formattedDate = $date->format('Y-m-d');
                    $datesInMonth[$formattedDate] = [];
                }

                $dateShiftsz[$monthKey] = $datesInMonth;
            }

            foreach ($val as $key2 => $val2) {
                foreach ($val2 as $values) {
                    $metaCode = DB::table('empl_meta')
                        ->where('key', 'code')
                        ->where('value', $values)
                        ->first();

                    if ($metaCode) {
                        foreach ($dateShiftsz[$monthKey] as $date => $data) {
                            if (!isset($dataShiftUser[$metaCode->empl_id][$monthKey][$date])) {
                                $dataShiftUser[$metaCode->empl_id][$monthKey][$date] = array_fill(1, 5, [null, null]);
                            }

                            $shiftEnum = TeacherShiftEnum::tryFrom($key2);
                            if ($shiftEnum && isset($dataShiftUser[$metaCode->empl_id][$monthKey][$key][$key2])) {
                                $dataShiftUser[$metaCode->empl_id][$monthKey][$key][$key2] = [
                                    $shiftEnum->defaultTime()['in'][0],
                                    $shiftEnum->defaultTime()['out'][0]
                                ];
                            } else {
                                $dataShiftUser[$metaCode->empl_id][$monthKey][$date][$key2] = [null, null];
                            }
                        }
                    }
                }
            }
        }

        $userShift = [];
        $totalMonthly = [];
        foreach ($dataShiftUser as $user => $monthLy) {
            $userShift[$user] = [];
            $totalMonthly[$user] = [];
            foreach ($monthLy as $dateKey => $dateValue) {
                $userShift[$user][$dateKey] = [];
                $totalMonthly[$user][$dateKey] = 0;
                foreach ($dateValue as $shiftKey => $shiftValue) {
                    $userShift[$user][$dateKey][$shiftKey] = 0;
                    $validShifts = 0;
                    foreach ($shiftValue as $shift) {
                        if ($shift !== [null, null]) {
                            $validShifts++;
                            $totalMonthly[$user][$dateKey]++;
                        }
                    }

                    if ($userShift[$user][$dateKey][$shiftKey]) {
                        $userShift[$user][$dateKey][$shiftKey] = 0;
                    }


                    $userShift[$user][$dateKey][$shiftKey] += $validShifts;
                }
            }
        }

        $arrData = [
            'data' => $dataShiftUser,
            'countShift' => $userShift,
            'totalUserShiftMonthly' => $totalMonthly,
            'updateShifts' => $dateShifts
        ];

        $employee = $request->user()->employee;
        if ($this->storeEmployeeSchedule($arrData, $employee)) {
            return redirect()->back()->with('success', 'Data berhasil diproses!');
        }
        return redirect()->fail();
    }

    public function sendApprovalNotifications(CompanyApprovable $approvable)
    {
        $superior = $approvable->modelable->approvables->sortBy('level')->first();
        $workerPositions = $approvable->modelable->employee->user;
        $creatorPositions = User::find($approvable->modelable->created_by);

        $workerPositions->notify(new ApprovedNotification($approvable->modelable));
        $creatorPositions->notify(new ApprovedNotification($approvable->modelable));

        $this->sendAccountNotification($workerPositions, $approvable, 'approved', $superior->userable->employee->user);
        $this->sendAccountNotification($creatorPositions, $approvable, 'approved', $superior->userable->employee->user);
    }

    protected function sendRejectionNotifications(CompanyApprovable $approvable, $cancelable = false)
    {
        //  $cancelable
        //          ? $approvable->modelable->employee->employee->user->notify(new CancelationRejectedNotification($approvable->modelable))
        //          : $approvable->modelable->employee->employee->user->notify(new RejectedNotification($approvable->modelable, $approvable));
        $superior = $approvable->modelable->approvables->sortBy('level')->first();
        $creatorPositions = User::find($approvable->modelable->created_by);
        $creatorPositions->notify(new RejectedNotification($approvable->modelable));

        $this->sendAccountNotification($creatorPositions, $approvable, 'rejection', $superior->userable->employee->user);
    }

    /**
     * Send rejection notifications.
     */
    protected function sendCancelableNotifications(CompanyApprovable $approvable)
    {
        // $approvable->modelable->quota->employee->user->notify(new CancelationApprovedNotification($approvable->modelable));
        // $this->sendAccountNotification($approvable->modelable->quota->employee->user, $approvable, 'cancelable');
    }

    /**
     * Send rejection notifications.
     */
    protected function sendRevisionNotifications(CompanyApprovable $approvable)
    {
        $superior = $approvable->modelable->approvables->sortBy('level')->first();
        $creatorPositions = User::find($approvable->modelable->created_by);
        $creatorPositions->notify(new RevisedNotification($approvable->modelable));

        $this->sendAccountNotification($creatorPositions, $approvable, 'revision', $superior->userable->employee->user);
    }

    protected function sendAccountNotification($user, CompanyApprovable $approvable, $type, $submitterName = null)
    {
        $message = match ($type) {
            'approved' => "Pengajuan jadwal telah disetujui oleh direktur, silakan cek pada link berikut " . route('portal::schedule.submission.show', ['submission' => $approvable->modelable->id]),
            'rejection' => "Maaf, pengajuan jadwal ditolak, silakan cek pada link berikut " . route('portal::schedule.submission.show', ['submission' => $approvable->modelable->id]),
            'revision' => "Maaf, pengajuan jadwal memerlukan revisi, silakan cek pada link berikut " . route('portal::vacation.submission.show', ['vacation' => $approvable->modelable->id]),
            default => ''
        };

        $user->notify(new AccountNotification($message, $user));
    }
}
