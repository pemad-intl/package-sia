<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Database;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\Academic;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Administration\Http\Requests\Database\AcademicSemester\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Database\AcademicSemester\UpdateRequest;

class AcademicSemesterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Academic $academic, StoreRequest $request)
    {
        $this->authorize('store', AcademicSemester::class);

        $semester = new AcademicSemester($request->only('name', 'open'));

        $academic->semesters()->save($semester);
        $semester->createAllMetas();

        return redirect()->back()->with('success', 'Semester <strong>'.$semester->name.'</strong> tahun akademik <strong>'.$academic->name.'</strong> berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Academic $academic, AcademicSemester $semester, UpdateRequest $request)
    {
        $this->authorize('update', AcademicSemester::class);

        if($semester->trashed()) abort(404);

        $semester->update([
            'open' => $request->input('open')
        ]);

        return redirect()->back()->with('success', 'Semester <strong>'.$semester->name.'</strong> tahun akademik <strong>'.$academic->name.'</strong> berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Academic $academic, AcademicSemester $semester)
    {
        $this->authorize('destroy', AcademicSemester::class);
        // $this->authorize('remove', $academic);

        if($semester->classrooms()->exists())
            return redirect()->back()->with('danger', 'Semester <strong>'.$semester->name.'</strong> tahun akademik <strong>'.$academic->name.'</strong> tidak dapat dihapus!');

        $tmp = $semester;
        $semester->delete();

        return redirect()->back()->with('success', 'Semester <strong>'.$tmp->name.'</strong> tahun akademik <strong>'.$academic->name.'</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Academic $academic, AcademicSemester $semester)
    {
        $this->authorize('restore', AcademicSemester::class);
        // $this->authorize('delete', $academic);

        $semester->restore();

        return redirect()->back()->with('success', 'Semester <strong>'.$semester->name.'</strong> tahun akademik <strong>'.$academic->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(Academic $academic, AcademicSemester $semester)
    {
        $this->authorize('kill', AcademicSemester::class);
        // $this->authorize('delete', $academic);

        $tmp = $semester;
        $semester->forceDelete();

        return redirect()->back()->with('success', 'Semester <strong>'.$tmp->name.'</strong> tahun akademik <strong>'.$academic->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
