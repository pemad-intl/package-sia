<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Scholar;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\Academic\Models\AcademicSuperior;
use Digipemad\Sia\Administration\Http\Requests\Scholar\Superior\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Scholar\Superior\UpdateRequest;

class SuperiorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicSuperior::class);

        // $this->authorize('access', User::class);

        $trashed = $request->get('trash');

        $acsems = AcademicSemester::openedByDesc()->get();

        $superiors = AcademicSuperior::with('classrooms')->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->where('semester_id', $request->get('academic', $acsems->first()->id))->orderByDesc('id')->paginate($request->get('limit', 10));

        $acsem = $acsems->firstWhere('id', $request->get('academic', $acsems->first()->id));

        if ($acsem) {
            $superiors_count = AcademicSuperior::where('semester_id', $request->get('academic', $acsem->id))->count();

            return view('administration::scholar.superiors.index', compact('acsems', 'acsem', 'superiors', 'superiors_count'));
        }

        return abort(404);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicSuperior::class);

        $superior = new AcademicSuperior($request->only(['name', 'semester_id']));

        if($superior->save()){
            Auth::user()->log(
                ' Unggulan bernama '.$superior->name.' telah ditambahkan '.
                ' <strong>[ID: ' . $superior->id . ']</strong>',
                AcademicSuperior::class,
                $superior->id
            );

            return redirect()->back()->with('success', 'Unggulan <strong>'.$superior->name.'</strong> berhasil ditambahkan');
        }

        return redirect()->back()->with('danger', 'Unggulan <strong>'.$superior->name.'</strong> gagal dibuat');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AcademicSuperior $superior, UpdateRequest $request)
    {
        $this->authorize('update', AcademicSuperior::class);

        if($superior->update($request->only('name'))){
             Auth::user()->log(
                ' Unggulan bernama '.$superior->name.' telah diperbarui '.
                ' <strong>[ID: ' . $superior->id . ']</strong>',
                AcademicSuperior::class,
                $superior->id
            );

            return redirect()->back()->with('success', 'Unggulan <strong>'.$superior->name.'</strong> berhasil diperbarui');
        }

        return redirect()->back()->with('danger', 'Unggulan <strong>'.$superior->name.'</strong> gagal diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicSuperior $superior)
    {
        $this->authorize('destroy', AcademicSuperior::class);

        // $this->authorize('remove', $superior);

        if($superior->delete()){
            Auth::user()->log(
                ' Unggulan bernama '.$superior->name.' telah dihapus '.
                ' <strong>[ID: ' . $superior->id . ']</strong>',
                AcademicSuperior::class,
                $superior->id
            );

            return redirect()->back()->with('success', 'Unggulan <strong>'.$superior->name.'</strong> telah dihapus');
        }

        return redirect()->back()->with('danger', 'Unggulan <strong>'.$superior->name.'</strong> gagal dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(AcademicSuperior $superior)
    {
        $this->authorize('restore', AcademicSuperior::class);

        // $this->authorize('delete', $superior);

        $superior->restore();

        return redirect()->back()->with('success', 'Unggulan <strong>'.$superior->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(AcademicSuperior $superior)
    {
        $this->authorize('kill', AcademicSuperior::class);

        // $this->authorize('delete', $superior);

        $tmp = $superior;
        $superior->forceDelete();

        return redirect()->back()->with('success', 'Unggulan <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
