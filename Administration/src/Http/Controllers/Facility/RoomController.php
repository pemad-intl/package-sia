<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Facility;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Auth;
use Digipemad\Sia\Administration\Models\SchoolBuildingRoom;
use Digipemad\Sia\Administration\Models\SchoolBuilding;

class RoomController extends Controller
{
    /**
     * index.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBuildingRoom::class);

        $user = auth()->user();

    	$trashed = $request->get('trash', 0);

    	$rooms = SchoolBuildingRoom::with('building')
        ->where('grade_id', userGrades())
        ->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $buildings = SchoolBuilding::where('grade_id', userGrades())->whereNull('deleted_at')->get();

        return view('administration::facility.rooms.index', compact('user','rooms','buildings'));
    }

    public function store(Request $request)
    {
        $this->authorize('store', SchoolBuildingRoom::class);

        $room = new SchoolBuildingRoom([
            'building_id' => $request->input('building_id'),
            'kd' => $request->input('kd'),
            'name' => $request->input('name'),
            'capacity' => $request->input('capacity'),
            'grade_id' => userGrades()
        ]);

        if($room->save()){
            Auth::user()->log(
                ' Ruangan bernama '.$room->name.' telah dihapus '.
                ' <strong>[ID: ' . $room->id . ']</strong>',
                SchoolBuildingRoom::class,
                $room->id
            );

            return redirect()->back()->with('success', 'Ruang <strong>'.$room->name.'</strong> berhasil disimpan</strong>');
        } 

        return redirect()->back()->with('danger', 'Ruang <strong>'.$room->name.'</strong> gagal disimpan</strong>');
    }

    public function update(SchoolBuildingRoom $room, Request $request){
        $this->authorize('update', SchoolBuildingRoom::class);

        if ($room->trashed()) abort(404);

        if($room->update([
            'kd' => $request->input('kd'),
            'name' => $request->input('name'),
            'capacity' => $request->input('capacity'),
            'grade_id' => userGrades()
        ])){
            Auth::user()->log(
                ' Ruangan bernama '.$room->name.' telah diperbarui '.
                ' <strong>[ID: ' . $room->id . ']</strong>',
                SchoolBuildingRoom::class,
                $room->id
            );

            return redirect()
                ->route('administration::facility.rooms.index')
                ->with('success', 'Ruangan <strong>' . $room->name . '</strong> berhasil diperbarui');
        }

        return redirect()
                ->route('administration::facility.rooms.index')
                ->with('danger', 'Ruangan <strong>' . $room->name . '</strong> gagal diperbarui');
    }

    public function show(SchoolBuildingRoom $room)
    {
        $this->authorize('show', SchoolBuildingRoom::class);

        if($room->trashed()) abort(404);


        return view('administration::facility.rooms.show', compact('room'));
    }

    public function destroy(SchoolBuildingRoom $room)
    {
        $this->authorize('destroy', SchoolBuildingRoom::class);
        if($room->delete()){
            Auth::user()->log(
                ' Ruangan bernama '.$room->name.' telah dihapus '.
                ' <strong>[ID: ' . $room->id . ']</strong>',
                SchoolBuildingRoom::class,
                $room->id
            );

            return redirect()->back()->with('success', 'Ruang <strong>'.$room->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Ruang <strong>'.$room->name.'</strong> gagal dihapus');
    }

    public function restore(SchoolBuildingRoom $room)
    {
        $this->authorize('restore', SchoolBuildingRoom::class);

        $room->restore();

        return redirect()->back()->with('success', 'Ruang <strong>'.$room->name.'</strong> berhasil dipulihkan');
    }

    public function kill(SchoolBuildingRoom $room)
    {
        $this->authorize('kill', SchoolBuildingRoom::class);

        $tmp = $room;
        $room->forceDelete();

        return redirect()->back()->with('success', 'Ruang <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }

}
