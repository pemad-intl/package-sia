<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Database;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\Academic;
use Digipemad\Sia\Administration\Http\Requests\Database\Academic\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Database\Academic\UpdateRequest;

class AcademicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Academic::class);

        $trashed = $request->get('trash');

        $academics = Academic::with(['semesters' => function ($semester) {
            $semester->where('open', 1);
        }])->where('grade_id', userGrades())        
        ->withCount('semesters')->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->orderByDesc('id')->paginate($request->get('limit', 10));

        $academics_count = Academic::count();

        return view('administration::database.academics.index', compact('academics', 'academics_count'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', Academic::class);
        $academic = new Academic($request->only('name', 'year'));

        if($academic->save()){
            Auth::user()->log(
                ' Tahun akademik '.$academic->name.' telah dibuat '.
                ' <strong>[ID: ' . $academic->id . ']</strong>',
                Academic::class,
                $academic->id
            );

            return redirect()->back()->with('success', 'Tahun akademik <strong>'.$academic->name.'</strong> berhasil dibuat');
        }


        return redirect()->back()->with('danger', 'Tahun akademik <strong>'.$academic->name.'</strong> berhasil dibuat');
    }

    /**
     * Show the specified resource.
     */
    public function show(Academic $academic, Request $request)
    {
        $this->authorize('access', Academic::class);

        if($academic->trashed() || $academic->id == auth()->id()) abort(404);

        $trashed = $request->get('trash');

        $semesters = $academic->semesters()->withCount('classrooms')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->get();

        return view('administration::database.academics.show', compact('academic', 'semesters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Academic $academic)
    {
        $this->authorize('store', Academic::class);
        if($academic->trashed()) abort(404);

        if($academic->update($request->only('name', 'year'))){
            Auth::user()->log(
                ' Tahun akademik '.$academic->name.' telah diperbarui '.
                ' <strong>[ID: ' . $academic->id . ']</strong>',
                Academic::class,
                $academic->id
            );

            return redirect()->back()->with('success', 'Tahun akademik <strong>'.$academic->name.'</strong> berhasil diperbarui');
        }

        return redirect()->back()->with('danger', 'Tahun akademik <strong>'.$academic->name.'</strong> berhasil dibuat');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Academic $academic)
    {
        $this->authorize('destroy', Academic::class);
        // $this->authorize('remove', $academic);

        if($academic->semesters()->exists())
            return redirect()->back()->with('danger', 'Tahun akademik <strong>'.$tmp->name.'</strong> tidak dapat dihapus!');

        $tmp = $academic;
        $academic->delete();

        Auth::user()->log(
            ' Tahun akademik '.$academic->name.' telah dihapus '.
            ' <strong>[ID: ' . $academic->id . ']</strong>',
            Academic::class,
            $academic->id
        );

        return redirect()->back()->with('success', 'Tahun akademik <strong>'.$tmp->name.'</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Academic $academic)
    {
        $this->authorize('restore', Academic::class);
        // $this->authorize('delete', $academic);

        $academic->restore();

        return redirect()->back()->with('success', 'Tahun akademik <strong>'.$academic->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(Academic $academic)
    {
        $this->authorize('kill', Academic::class);
        // $this->authorize('delete', $academic);

        $tmp = $academic;
        $academic->forceDelete();

        return redirect()->back()->with('success', 'Tahun akademik <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
