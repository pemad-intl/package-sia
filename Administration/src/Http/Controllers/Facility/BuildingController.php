<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Facility;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use App\Models\Regencies;
use App\Models\District;

class BuildingController extends Controller
{
    /**
     * index.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBuilding::class);

        $user = auth()->user();

    	$trashed = $request->get('trash', 0);

    	$buildings = SchoolBuilding::where('grade_id', userGrades())->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $district = District::whereIn('regency_id', [3401, 3402, 3403, 3404, 3471])->get();

        return view('administration::facility.buildings.index', compact('user','buildings', 'district'));
    }

    public function store(Request $request)
    {
        $this->authorize('store', SchoolBuilding::class);

        $building = new SchoolBuilding([
            'kd' => $request->input('kd'),
            'grade_id' => userGrades(),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'rt' => $request->input('rt'),
            'rw' => $request->input('rw'),
            'village' => $request->input('village'),
            'district_id' => $request->input('district_id'),
            'postal' => $request->input('postal')
        ]);

        if($building->save()){
            Auth::user()->log(
                ' Gedung bernama '.$building->name.' telah ditambahkan '.
                ' <strong>[ID: ' . $building->id . ']</strong>',
                SchoolBuilding::class,
                $building->id
            );

            return redirect()->back()->with('success', 'gedung <strong>'.$building->name.'</strong> berhasil disimpan</strong>');
        }

        return redirect()->back()->with('danger', 'gedung <strong>'.$building->name.'</strong> gagal disimpan</strong>');
    }

    public function update(SchoolBuilding $building, Request $request)
    {
        $this->authorize('update', SchoolBuilding::class);

        if($building->trashed()) abort(404);

        if($building->update([
            'kd' => $request->input('kd'),
            'grade_id' => userGrades(),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'rt' => $request->input('rt'),
            'rw' => $request->input('rw'),
            'village' => $request->input('village'),
            'district_id' => $request->input('district_id'),
            'postal' => $request->input('district_id')
        ])){
            Auth::user()->log(
                ' Gedung bernama '.$building->name.' telah diperbarui '.
                ' <strong>[ID: ' . $building->id . ']</strong>',
                SchoolBuilding::class,
                $building->id
            );
       
            return redirect()
                ->route('administration::facility.buildings.index')
                ->with('success', 'Gedung <strong>' . $building->name . '</strong> berhasil diperbarui');
        }

        return redirect()
                ->route('administration::facility.buildings.index')
                ->with('danger', 'Gedung <strong>' . $building->name . '</strong> gagal diperbarui');
    }

    public function show(SchoolBuilding $building)
    {
        $this->authorize('show', SchoolBuilding::class);

        if($building->trashed()) abort(404);
        $districtAll = District::whereIn('regency_id', [3401, 3402, 3403, 3404, 3471])->get();

        return view('administration::facility.buildings.show', compact('building', 'districtAll'));
    }

    public function destroy(SchoolBuilding $building)
    {
        $this->authorize('destroy', SchoolBuilding::class);

        if($building->delete()){
            Auth::user()->log(
                ' Gedung bernama '.$building->name.' telah dihapus '.
                ' <strong>[ID: ' . $building->id . ']</strong>',
                SchoolBuilding::class,
                $building->id
            );

            return redirect()->back()->with('success', 'Gedung <strong>'.$building->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Gedung <strong>'.$building->name.'</strong> gagal dihapus');
    }

    public function restore(SchoolBuilding $building)
    {
        $this->authorize('restore', SchoolBuilding::class);

        $building->restore();

        return redirect()->back()->with('success', 'Gedung <strong>'.$building->name.'</strong> berhasil dipulihkan');
    }

    public function kill(SchoolBuilding $building)
    {
        $this->authorize('kill', SchoolBuilding::class);

        $tmp = $building;
        $building->forceDelete();

        return redirect()->back()->with('success', 'Gedung <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
