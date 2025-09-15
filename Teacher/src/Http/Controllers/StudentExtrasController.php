<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

use Auth;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;
use Digipemad\Sia\Academic\Models\StudentAchievement;
use Digipemad\Sia\Academic\Models\StudentExtras;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\StudentSemesterReport;
use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\AcademicSubjectCompetence;

class StudentExtrasController extends Controller
{

    public function index(AcademicClassroom $classroom, $student, Request $request)	
    {
     //   $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

        // dd('ok');
		// $meet = $user->teacher->meets()
		// 					->with(['classroom.stsems' => function ($stsem) {
		// 						return $stsem->with('student', 'reports');
		// 					}])
		// 					->findOrFail($meet->id);
        $cls = $classroom->with(['stsems' => function ($stsem) {
								return $stsem->with('student', 'reports');
							}])
							->findOrFail($classroom->id);
		
		$teacher = $user->teacher;
        $extraStudent = StudentExtras::where('smt_id', $student)->whereNull('deleted_at')->get();
       
		return view('teacher::extrastudent.index', compact('cls', 'student', 'classroom', 'extraStudent'));
	}

	public function store(AcademicSubjectMeet $meet, $student, Request $request){
        $user = auth()->user();
		$request->validate([
            'name' => 'required|string|max:255'
		]);
        
        $updated = StudentExtras::create([
            'name' => $request->name,
			'smt_id' => $student
        ]);

        if ($updated) {
            Auth::user()->log(
				' Ekstrakulikuler untuk siswa bernama '.$updated->semester->student->user->name.' telah dibuat oleh '.$user->employee->user->name.' '.
				' <strong>[ID: ' . $updated->id . ']</strong>',
				StudentExtras::class,
				$updated->id
			);

            return redirect()->back()->with('success', 'Extrakulikuler berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Extrakulikuler gagal diperbarui.');		
	}

    public function update(StudentExtras $extra, Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255'
		]);

        $extra->update([
            'name' => $request->name,
        ]);
        

        if ($extra) {
            Auth::user()->log(
				' Ekstrakulikuler untuk siswa bernama '.$extra->semester->student->user->name.' telah diperbarui oleh '.$user->employee->user->name.' '.
				' <strong>[ID: ' . $extra->id . ']</strong>',
				StudentExtras::class,
				$extra->id
			);

            return redirect()->back()->with('success', 'Extrakulikuler berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Extrakulikuler gagal diperbarui.');
    }

   public function destroy(StudentExtras $extra)
    {
        $user = auth()->user();
        $deleted = $extra->delete();

        if ($deleted) {
            Auth::user()->log(
				' Ekstrakulikuler untuk siswa bernama '.$extra->semester->student->user->name.' telah dihapus oleh '.$user->employee->user->name.' '.
				' <strong>[ID: ' . $extra->id . ']</strong>',
				StudentExtras::class,
				$extra->id
			);

            return redirect()->back()->with('success', 'Extrakulikuler berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Extrakulikuler gagal dihapus.');
    }

}
