<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Facility;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

use Digipemad\Sia\Administration\Models\SchoolBuildingRoomAssetCategory;

class AssetCategoriesController extends Controller
{
    /**
     * index.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBuildingRoomAssetCategory::class);

        $trashed = $request->get('trash', 0);

        $ctgs = SchoolBuildingRoomAssetCategory::all();

        return view('administration::facility.assets.index', compact('ctgs'));
    }


    public function store(Request $request)
    {
        $this->authorize('store', SchoolBuildingRoomAssetCategory::class);

        $ctg = new SchoolBuildingRoomAssetCategory([
            'name' => $request->input('name')
        ]);
        

        $ctg->save(['timestamps' => false]);

        return redirect()->back()->with('success', 'Kategori <strong>'.$ctg->name.'</strong> berhasil disimpan</strong>');
    }

}
