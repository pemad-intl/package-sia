<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Facility;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Digipemad\Sia\Administration\Models\SchoolBuildingRoomAsset;
use Digipemad\Sia\Administration\Models\SchoolBuildingRoomAssetCategory;
use Digipemad\Sia\Administration\Models\SchoolBuildingRoom;

class AssetController extends Controller
{
    /**
     * index.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBuildingRoomAssetCategory::class);

        $trashed = $request->get('trash', 0);

    	$assets = SchoolBuildingRoomAsset::with('room')->where('name', 'like', '%'.$request->get('search').'%')->paginate($request->get('limit', 10));

        $rooms = SchoolBuildingRoom::all();
        $ctgs = SchoolBuildingRoomAssetCategory::all();

        return view('administration::facility.assets.index', compact('assets','rooms','ctgs'));
    }

    public function store(Request $request)
    {

        $asset = new SchoolBuildingRoomAsset([
            'room_id' => $request->input('room_id'),
            'name' => $request->input('name'),
            'ctg_id' => $request->input('ctg_id'),
            'count' => $request->input('count'),
            'condition' => $request->input('condition')
        ]);

        $asset->save();

        return redirect()->back()->with('success', 'Asset <strong>'.$asset->name.'</strong> berhasil disimpan</strong>');
    }

    public function storeCategory(Request $request)
    {

        $ctg = new SchoolBuildingRoomAssetCategory([
            'name' => $request->input('name')
        ]);

        $asset->save();

        return redirect()->back()->with('success', 'Asset <strong>'.$asset->name.'</strong> berhasil disimpan</strong>');
    }

    public function show(SchoolBuildingRoomAsset $asset)
    {

        if($asset->trashed()) abort(404);


        return view('administration::facility.assets.show', compact('asset'));
    }

    public function destroy(SchoolBuildingRoomAsset $asset)
    {

        $tmp = $asset;
        $asset->delete();

        return redirect()->back()->with('success', 'Asset <strong>'.$tmp->name.'</strong> berhasil dihapus');
    }

    public function restore(SchoolBuildingRoomAsset $asset)
    {

        $asset->restore();

        return redirect()->back()->with('success', 'Asset <strong>'.$asset->name.'</strong> berhasil dipulihkan');
    }

    public function kill(SchoolBuildingRoomAsset $asset)
    {

        $tmp = $asset;
        $asset->forceDelete();

        return redirect()->back()->with('success', 'Asset <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
