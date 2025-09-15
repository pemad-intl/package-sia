<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Service;

use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Digipemad\Sia\Administration\Http\Requests\Service\Teacher\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Modules\Core\Models\CompanyContract;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Employee::class);

        $teacher = [];

        if ($request->user()->employee->position->position_id == '12' || $request->user()->employee->position->position_id == '7') {
            $teacher = Employee::isTeacherJakarta();
        } else {
            $teacher = Employee::isTeacher();
        }

        $teachers = $teacher->with('user.meta', 'contract.positions.position')
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        $teacher_count = $teachers->count();

        return view('administration::services.teachers.index', compact('teachers', 'teacher_count'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Employee $teacher)
    {
        $this->authorize('update', $teacher);

        $teacher = $teacher->load('user.meta', 'contracts', 'positions');

        return view('administration::services.teachers.show', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Employee $teacher, UpdateRequest $request)
    {
        $teacher->setManyMeta($request->transformed()->toArray());
        if ($teacher->getMeta('code')) {
            return redirect()->next()->with('success', 'Informasi guru <strong>' . $teacher->user->name . ' (' . $teacher->user->username . ')</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $teacher)
    {
        $this->authorize('destroy', $teacher);
        $tmp = $teacher;
        if ($teacher->delete()) {
            return redirect()->next()->with('success', 'Guru <strong>' . $tmp->user->name . ' (' . $tmp->user->username . ')</strong> berhasil dihapus');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Employee $teacher)
    {
        $this->authorize('restore', $teacher);
        if ($teacher->restore()) {
            return redirect()->next()->with('success', 'Guru <strong>' . $teacher->user->name . ' (' . $teacher->user->username . ')</strong> berhasil dipulihkan');
        }
        return redirect()->fail();
    }

    public function importExcel(Request $request)
    {
        $file = $request->file('uploadTeacher');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();

        $lastRow = $worksheet->getHighestRow();
        $lastColumn = $worksheet->getHighestColumn();
        $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

        $data = [];
        $processedCount = 0;

        for ($row = 4; $row <= $lastRow; $row++) {
            $c = $worksheet->getCell('C' . $row)->getValue();
            $d = $worksheet->getCell('D' . $row)->getValue();
            $e = $worksheet->getCell('E' . $row)->getValue();

            if (!empty($c) && !empty($d) && !empty($e)) {
                $data[] = [
                    'code' => $c,
                    'default_workhour' => $d,
                    'empl_id' => $e,
                ];


                $employee = Employee::firstOrNew(['id' => $e]);

                $employee->setManyMeta([
                    'code' => $c,
                    'default_workhour' => $d
                ]);

                if ($employee) {
                    $processedCount++;
                }
            }
        }
        $totalValidData = count($data);

        if ($processedCount === $totalValidData) {
            return redirect()->back()->with('success', 'Semua data berhasil diproses!');
        }
    }
}
