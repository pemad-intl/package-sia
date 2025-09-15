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
use Digipemad\Sia\Academic\Models\StudentSemesterReport;
use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\AcademicSubjectCompetence;

class AchievementStudentController extends Controller
{

    public function index(AcademicClassroom $classroom, $student, Request $request)	
    {
     //   $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$cls = $classroom->with(['stsems' => function ($stsem) {
								return $stsem->with('student', 'reports');
							}])
							->findOrFail($classroom->id);
		
		$teacher = $user->teacher;

        $achievementStudent = StudentAchievement::whereNull('deleted_at')->get();
        

		return view('teacher::acievestudent.index', compact('cls', 'student', 'achievementStudent'));
	}

    public function store(AcademicClassroom $classroom, $student, Request $request){
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $insert = StudentAchievement::create([
            'name' => $request->name,
            'date' => $request->date,
            'smt_id' => $student,
            // 'student_id' => $student,
            // 'classroom' => $classroom->id
        ]);

        if ($insert) {
            Auth::user()->log(
				' Prestasi untuk siswa bernama '.$insert->semester->student->user->name .
                ' telah ditambahkan oleh '.$user->employee->user->name.
				' <strong>[ID: ' . $insert->id . ']</strong>',
				StudentAchievement::class,
				$insert->id
			);

            return redirect()->back()->with('success', 'Prestasi berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Prestasi gagal ditambahkan.');
    }

    public function update(StudentAchievement $achievement, Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);


        $updated = $achievement->update([
            'name' => $request->name,
            'date' => $request->date
        ]);

        if ($updated) {
            
            Auth::user()->log(
				' Prestasi untuk siswa bernama '.$achievement->semester->student->user->name .
                ' telah diperbarui oleh '.$user->employee->user->name.
				' <strong>[ID: ' . $achievement->id . ']</strong>',
				StudentAchievement::class,
				$achievement->id
			);

            return redirect()->back()->with('success', 'Prestasi berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Prestasi gagal diperbarui.');
    }

   public function destroy(StudentAchievement $achievement)
    {
        $user = auth()->user();
        $deleted = $achievement->delete();

        if ($deleted) {
            Auth::user()->log(
				' Prestasi untuk siswa bernama '.$achievement->semester->student->user->name .
                ' telah dihapus oleh '.$user->employee->user->name.
				' <strong>[ID: ' . $achievement->id . ']</strong>',
				StudentAchievement::class,
				$achievement->id
			);

            return redirect()->back()->with('success', 'Prestasi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Prestasi gagal dihapus.');
    }

}
