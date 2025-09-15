<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Database;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Auth;
use Digipemad\Sia\Administration\Models\SchoolCurricula;
use Digipemad\Sia\Administration\Http\Requests\Database\Curricula\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Database\Curricula\UpdateRequest;

class CurriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolCurricula::class);

        $trashed = $request->get('trash');

        $curriculas = SchoolCurricula::where('grade_id', userGrades())
        ->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->orderByDesc('id')->paginate($request->get('limit', 10));

        $curriculas_count = SchoolCurricula::count();

        return view('administration::database.curriculas.index', compact('curriculas', 'curriculas_count'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', SchoolCurricula::class);

        $curricula = new SchoolCurricula(array_merge($request->only('kd', 'name', 'year'),
        [
            'grade_id' => userGrades()
        ]));

        if($curricula->save()){
            Auth::user()->log(
                ' Kurikulum '.$curricula->name.' telah dibuat '.
                ' <strong>[ID: ' . $curricula->id . ']</strong>',
                SchoolCurricula::class,
                $curricula->id
            );

            return redirect()->back()->with('success', 'Kurikulum <strong>'.$curricula->name.'</strong> berhasil dibuat');
        }

        return redirect()->back()->with('danger', 'Kurikulum <strong>'.$curricula->name.'</strong> gagal dibuat');
    }

    /**
     * Show the specified resource.
     */
    public function show(SchoolCurricula $curricula, Request $request)
    {
        $this->authorize('show', SchoolCurricula::class);
        // $this->authorize('view', $curricula);

        if($curricula->trashed() || $curricula->id == auth()->id()) abort(404);

        $trashed = $request->get('trash');

        $semesters = $curricula->semesters()->withCount('classrooms')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->get();

        return view('administration::database.curriculas.show', compact('curricula', 'semesters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, SchoolCurricula $curricula)
    {
        $this->authorize('update', SchoolCurricula::class);
        if($curricula->trashed()) abort(404);

        if($curricula->update(array_merge($request->only('kd', 'name', 'year'), [
            'grade_id' => userGrades()
        ]))){
            Auth::user()->log(
                ' Kurikulum '.$curricula->name.' telah diperbarui '.
                ' <strong>[ID: ' . $curricula->id . ']</strong>',
                SchoolCurricula::class,
                $curricula->id
            );

            return redirect()->back()->with('success', 'Kurikulum <strong>'.$curricula->name.'</strong> berhasil diperbarui');
        }

        return redirect()->back()->with('danger', 'Kurikulum <strong>'.$curricula->name.'</strong> gagal diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolCurricula $curricula)
    {
        $this->authorize('destroy', SchoolCurricula::class);
        // $this->authorize('remove', $curricula);

        if($curricula->semesters()->exists())
            return redirect()->back()->with('danger', 'Kurikulum <strong>'.$tmp->name.'</strong> tidak dapat dihapus!');

        $tmp = $curricula;
        if($curricula->delete()){
            Auth::user()->log(
                ' Kurikulum '.$curricula->name.' telah dihapus '.
                ' <strong>[ID: ' . $curricula->id . ']</strong>',
                SchoolCurricula::class,
                $curricula->id
            );

            return redirect()->back()->with('success', 'Kurikulum <strong>'.$tmp->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Kurikulum <strong>'.$tmp->name.'</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(SchoolCurricula $curricula)
    {
        $this->authorize('restore', SchoolCurricula::class);
        // $this->authorize('delete', $curricula);

        $curricula->restore();

        return redirect()->back()->with('success', 'Kurikulum <strong>'.$curricula->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(SchoolCurricula $curricula)
    {
        $this->authorize('kill', SchoolCurricula::class);
        // $this->authorize('delete', $curricula);

        $tmp = $curricula;
        $curricula->forceDelete();

        return redirect()->back()->with('success', 'Kurikulum <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
