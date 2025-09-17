<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Curricula;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSubject;
use Digipemad\Sia\Academic\Models\AcademicSubjectCategory;
// use Digipemad\Sia\

use App\Models\References\GradeLevel;
use Digipemad\Sia\Administration\Http\Requests\Curricula\Subject\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Curricula\Subject\UpdateRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicSubject::class);

        $trashed = $request->get('trash');

        $acsems = AcademicSemester::openedByDesc()->get();

        $subjects = AcademicSubject::where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->where('semester_id', $request->get('academic', $acsems->first()->id))
        ->paginate($request->get('limit', 10));

        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id));

        if ($acsem) {
            $subjects_count = AcademicSubject::where('semester_id', $request->get('academic', $acsems->first()->id))
            ->count();

            return view('administration::curriculas.subjects.index', compact('acsems', 'acsem', 'subjects', 'subjects_count'));
        }

        return abort(404);
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicSubject::class);

        $acsems = AcademicSemester::openedByDesc()->get();
        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id));
 

        $categories = AcademicSubjectCategory::all();
        return view('administration::curriculas.subjects.create', compact('acsems', 'acsem', 'levels', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicSubject::class);
        $subject = new AcademicSubject($request->only('kd', 'name', 'semester_id', 'level_id', 'category_id', 'color_id', 'score_standard'));

        if($subject->save()){
            Auth::user()->log(
                ' Mapel bernama '.$subject->name.' telah ditambahkan '.
                ' <strong>[ID: ' . $subject->id . ']</strong>',
                AcademicSubjectCategory::class,
                $subject->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Mapel <strong>'.$subject->name.'</strong> berhasil dibuat');
        } 

        return redirect($request->get('next', url()->previous()))->with('danger', 'Mapel <strong>'.$subject->name.'</strong> gagal dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicSubject $subject, Request $request)
    {
        $this->authorize('show', AcademicSubject::class);
        $levels = GradeLevel::get();
        $categories = AcademicSubjectCategory::all();

        return view('administration::curriculas.subjects.edit', compact('subject', 'levels', 'categories'));
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicSubject $subject, UpdateRequest $request)
    {
        $this->authorize('update', AcademicSubject::class);
        if($subject->update($request->only('kd', 'name', 'level_id', 'category_id', 'color_id', 'score_standard'))){
            Auth::user()->log(
                ' Mapel bernama '.$subject->name.' telah diperbarui '.
                ' <strong>[ID: ' . $subject->id . ']</strong>',
                AcademicSubject::class,
                $subject->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Mapel <strong>'.$subject->name.'</strong> berhasil diperbarui');
        }

        return redirect($request->get('next', url()->previous()))->with('danger', 'Mapel <strong>'.$subject->name.'</strong> gagal diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicSubject $subject)
    {
        $this->authorize('show', AcademicSubject::class);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicSubject $subject)
    {
        $this->authorize('destroy', AcademicSubject::class);
        // $this->authorize('remove', $subject);

        if($subject->delete()){
            Auth::user()->log(
                ' Mapel bernama '.$subject->name.' telah dihapus '.
                ' <strong>[ID: ' . $subject->id . ']</strong>',
                AcademicSubject::class,
                $subject->id
            );

            return redirect()->back()->with('success', 'Mapel <strong>'.$subject->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Mapel <strong>'.$subject->name.'</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(AcademicSubject $subject)
    {
        $this->authorize('restore', AcademicSubject::class);
        // $this->authorize('delete', $subject);

        $subject->restore();

        return redirect()->back()->with('success', 'Mapel <strong>'.$subject->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(AcademicSubject $subject)
    {
        $this->authorize('kill', AcademicSubject::class);
        // $this->authorize('delete', $subject);

        $tmp = $subject;
        $subject->forceDelete();

        return redirect()->back()->with('success', 'Mapel <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
