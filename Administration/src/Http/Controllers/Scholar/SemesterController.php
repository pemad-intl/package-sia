<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar;

use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use App\Models\References\GradeLevel;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Excel\Exports\StudentSemesterExport;
use Digipemad\Sia\Academic\Excel\Imports\StudentSemesterImport;
use Digipemad\Sia\Administration\Http\Requests\Scholar\Semester\PromoteRequest;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicSemester::class);
        // $this->authorize('access', User::class);

        $acsems = AcademicSemester::orderByDesc('id')->get();

        $acsem = $acsems->firstWhere('open', 1);

        $stsems = StudentSemester::where('semester_id', $request->get('acsem', $acsem->id))->whereHas('student', function ($student) use ($request) {
            return $student->search($request->get('search', ''));
        })->paginate($request->get('limit', 10));

        $stsems_count = StudentSemester::where('semester_id', $request->get('acsem', $acsem->id))->count();

        return view('administration::scholar.semesters.index', compact('stsems', 'stsems_count' , 'acsems', 'acsem'));
    }

    /**
     * Show the registrations form.
     */
    public function registrations(Request $request)
    {
        $this->authorize('access', AcademicSemester::class);

        $aclassRoom = AcademicClassroom::whereIn('level_id', $grade)->whereNull('deleted_at')->get();
        $acsems = AcademicSemester::with(['classrooms'])->orderByDesc('id')->get();

        $acsem = ($request->has('acsem'))
                        ? $acsems->firstWhere('id', $request->get('acsem'))
                        : $acsems->firstWhere('open', 1);

        $students = Student::whereDoesntHave('semesters')->get();

        return view('administration::scholar.semesters.registration', compact('students', 'acsems', 'acsem', 'aclassRoom'));
    }

    /**
     * Show the promotions form.
     */
    public function promotions(Request $request)
    {
        $this->authorize('access', AcademicSemester::class);

        $aclassRoom = AcademicClassroom::whereNull('deleted_at')->get();
        $acsems = AcademicSemester::with(['classrooms'])->openedByDesc()->orderByDesc('id')->get();

        $acsem = ($request->has('acsem'))
                        ? $acsems->firstWhere('id', $request->get('acsem'))
                        : $acsems->firstWhere('open', 1);

        $stsems = StudentSemester::where('semester_id', $request->get('acsem', $acsem->id))->whereHas('student', function ($student) use ($request) {
            return $student->search($request->get('search', ''));
        })->get();

        return view('administration::scholar.semesters.promotions', compact('stsems', 'acsems', 'acsem', 'aclassRoom'));
    }

    /**
     * Promote.
     */
    public function promote(PromoteRequest $request)
    {
        $this->authorize('access', AcademicSemester::class);

        if (empty($request->input('students'))) {
            return redirect($request->get('next', url()->current()))
                ->with('danger', 'Pertemuan gagal dibuat, murid tidak ada');
        }

        $acsem = AcademicSemester::findOrFail($request->input('semester_id'));

        $students = [];
        foreach ($request->input('students') as $student) {
            $students[] = [
                'student_id' => $student,
                'classroom_id' => $request->input('classroom_id')
            ];
        }

        $studentSemesters = $acsem->stsems()->createMany($students);

        $studentSemesters->load('student.user');
        foreach ($studentSemesters as $stsem) {
            $studentName = $stsem->student->user->name ?? '-';

            Auth::user()->log(
                'Siswa/siswi bernama <strong>' . $studentName . '</strong> telah diregistrasikan pada ruangan ' .
                '<strong>' . ($request->input('classroom_id') ?? '-') . '</strong>' .
                ' <strong>[ID: ' . $stsem->id . ']</strong>',
                StudentSemester::class,
                $stsem->id
            );
        }

        return redirect($request->get('next', url()->previous()))->with('success', 'Total <strong>'.count($request->input('students', [])).'</strong> siswa telah berhasil diregistrasikan ke Tahun Ajaran <strong>'.$acsem->full_name.'</strong>!');
    }

    /**
     * Exporting data.
     */
    public function export()
    {
        $this->authorize('access', AcademicSemester::class);

        return Excel::download(new StudentSemesterExport, 'template-student-semesters.xlsx');
    }

    /**
     * Importing data.
     */
    public function import(Request $request)
    {
        $this->authorize('access', AcademicSemester::class);

        $import = new StudentSemesterImport();
        try {
            $import->import($request->file('file'));

            return redirect()->back()->with('success', 'Impor semester siswa sukses');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failure = $e->failures()[0];

            return redirect()->back()->withErrors(['file' => 'Error di ROW:'.$failure->row().', '.$failure->errors()[0]]);
        }
    }
}
