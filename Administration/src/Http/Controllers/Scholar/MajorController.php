<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Digipemad\Sia\Administration\Http\Requests\Scholar\Major\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Scholar\Major\UpdateRequest;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicMajor;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicMajor::class);

        $trashed = $request->get('trash');

        $acsems = AcademicSemester::openedByDesc()->get();

        $majors = AcademicMajor::with('classrooms')->where('name', 'like', '%'.$request->get('search').'%')
        ->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->where('semester_id', $request->get('academic', $acsems->first()->id))->orderByDesc('id')->paginate($request->get('limit', 10));

        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id));

        if ($acsem) {
            $majors_count = AcademicMajor::where('semester_id', $request->get('academic', $acsem->id))->count();

            return view('administration::scholar.majors.index', compact('acsems', 'acsem', 'majors', 'majors_count'));
        }

        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicMajor::class);

        $major = new AcademicMajor($request->only('name', 'semester_id'));

        if($major->save()){
            Auth::user()->log(
                ' Jurusan bernama '.$major->name.' telah ditambahkan '.
                ' <strong>[ID: ' . $major->id . ']</strong>',
                AcademicMajor::class,
                $major->id
            );

            return redirect()->back()->with('success', 'Jurusan <strong>'.$major->name.'</strong> berhasil dibuat');
        } 

        return redirect()->back()->with('danger', 'Jurusan <strong>'.$major->name.'</strong> berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AcademicMajor $major, UpdateRequest $request)
    {
        $this->authorize('update', AcademicMajor::class);

        if($major->update(
            $request->only('name'),
        )){
            Auth::user()->log(
                ' Jurusan bernama '.$major->name.' telah diperbarui '.
                ' <strong>[ID: ' . $major->id . ']</strong>',
                AcademicMajor::class,
                $major->id
            );

            return redirect()->back()->with('success', 'Jurusan <strong>'.$major->name.'</strong> berhasil perbarui');
        }

        return redirect()->back()->with('danger', 'Jurusan <strong>'.$major->name.'</strong> gagal perbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicMajor $major)
    {
        $this->authorize('destroy', AcademicMajor::class);

        // $this->authorize('remove', $major);

        if($major->delete()){
            Auth::user()->log(
                ' Jurusan bernama '.$major->name.' telah dihapus '.
                ' <strong>[ID: ' . $major->id . ']</strong>',
                AcademicMajor::class,
                $major->id
            );

            return redirect()->back()->with('success', 'Jurusan <strong>'.$major->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Jurusan <strong>'.$major->name.'</strong> gagal dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(AcademicMajor $major)
    {
        $this->authorize('restore', AcademicMajor::class);

        // $this->authorize('delete', $major);

        $major->restore();

        return redirect()->back()->with('success', 'Jurusan <strong>'.$major->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(AcademicMajor $major)
    {
        $this->authorize('kill', AcademicMajor::class);

        // $this->authorize('delete', $major);

        $tmp = $major;
        $major->forceDelete();

        return redirect()->back()->with('success', 'Jurusan <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
