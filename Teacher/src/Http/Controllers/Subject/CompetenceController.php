<?php

namespace Digipemad\Sia\Teacher\Http\Controllers\Subject;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\AcademicSubjectCompetence;
use Digipemad\Sia\Academic\Models\AcademicSemester;

use Digipemad\Sia\Teacher\Http\Requests\Subject\Competence\StoreRequest;
use Digipemad\Sia\Teacher\Http\Requests\Subject\Competence\UpdateRequest;

class CompetenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AcademicSubject $subject, Request $request)
    {
   //     $this->authorize('index', AcademicSemester::class);

        $acsem = $this->acsem;
        $user = auth()->user()->load('teacher.meets');
        $teacher = $user->teacher;

        $subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($subject->id);

        return view('teacher::subjects.competences.index', compact('acsem', 'user', 'teacher', 'subject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademicSubject $subject, StoreRequest $request)
    {
     //   $this->authorize('store', AcademicSemester::class);

        $acsem = $this->acsem;
        $user = auth()->user()->load('teacher.meets');

        $subject = AcademicSubject::with('competences')->inTeacherAndSemester($user->teacher, $acsem)->find($subject->id);

        $data = $request->only('kd', 'name', 'indicators');
        $data['employee_id'] = $user->teacher->employee_id;

        $comp = new AcademicSubjectCompetence($data);

        if($sbj = $subject->competences()->save($comp)){
            Auth::user()->log(
				' Kompetensi '.$comp->name.' dibuat oleh '.$user->name.' pada mata pelajaran '.$subject->name.' telah ditambahkan '.
				' <strong>[ID: ' . $sbj->id . ']</strong>',
				AcademicSubjectCompetence::class,
				$sbj->id
			);

            return redirect($request->get('next', url()->previous()))->with('success', 'Kompetensi mapel <strong>'.$subject->name.'</strong> berhasil ditambahkan');
        }

        return redirect($request->get('next', url()->previous()))->with('danger', 'Kompetensi mapel <strong>'.$subject->name.'</strong> gagal ditambahkan');
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicSubject $subject, AcademicSubjectCompetence $competence, UpdateRequest $request)
    {
      //  $this->authorize('update', AcademicSemester::class);
        $user = auth()->user()->load('teacher.meets');

        if($subject->update($request->only('kd', 'name', 'level_id', 'category_id'))){
            Auth::user()->log(
				' Kompetensi '.$competence->name.' dibuat oleh '.$user->name.' pada mata pelajaran '.$subject->name.' telah diperbarui '.
				' <strong>[ID: ' . $subject->id . ']</strong>',
				AcademicSubjectCompetence::class,
				$subject->id
			);

            return redirect($request->get('next', url()->previous()))->with('success', 'Mapel <strong>'.$subject->name.'</strong> berhasil diperbarui');
        }

        return redirect($request->get('next', url()->previous()))->with('success', 'Mapel <strong>'.$subject->name.'</strong> gagal diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicSubject $subject, AcademicSubjectCompetence $competence)
    {
     //   $this->authorize('destroy', AcademicSemester::class);
        $tmp = $competence;
        $user = auth()->user()->load('teacher.meets');
        
        if($competence->delete()){
            Auth::user()->log(
				' Kompetensi '.$competence->name.' dibuat oleh '.$user->name.' pada mata pelajaran '.$subject->name.' telah dihapus '.
				' <strong>[ID: ' . $subject->id . ']</strong>',
				AcademicSubjectCompetence::class,
				$subject->id
			);

            return redirect()->back()->with('success', 'KD <strong>'.$tmp->kd.'</strong> mapel <strong>'.$subject->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'KD <strong>'.$tmp->kd.'</strong> mapel <strong>'.$subject->name.'</strong> berhasil dihapus');
    }
}
