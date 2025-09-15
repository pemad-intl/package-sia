<?php

namespace Digipemad\Sia\Counseling\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\AcademicCaseCategory;
use Digipemad\Sia\Academic\Models\AcademicCaseCategoryDescription;
use Digipemad\Sia\Counseling\Http\Requests\Manage\CaseDescription\StoreRequest;
use Digipemad\Sia\Counseling\Http\Requests\Manage\CaseDescription\UpdateRequest;

class CaseDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('access', User::class);

        $ctg = $request->get('ctg', false);

        $descriptions = AcademicCaseCategoryDescription::with('category')
                            ->when($ctg, function ($q) use ($ctg) {
                                return $q->where('ctg_id', $ctg);
                            })
                            ->whereHas('category', function($category){
                                return $category->where('grade_id', userGrades());
                            })
                            ->where('name', 'like', '%'.$request->get('search').'%')
                            ->paginate($request->get('limit', 10));

        $descriptions_count = AcademicCaseCategoryDescription::with('category')
        ->whereHas('category', function($category){
            return $category->where('grade_id', userGrades());
        })
        ->count();

        $categories = AcademicCaseCategory::where('grade_id', userGrades())->whereNull('deleted_at')->get();

        return view('counseling::manage.cases.descriptions.index', compact('categories', 'descriptions', 'descriptions_count'));
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicCaseCategoryDescription::class);

        return view('counseling::manage.cases.descriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicCaseCategoryDescription::class);

        $description = new AcademicCaseCategoryDescription($request->only('ctg_id', 'name', 'point'));
        $description->save();

        return redirect($request->get('next', url()->previous()))->with('success', 'Deskripsi kasus <strong>'.$description->name.'</strong> berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicCaseCategoryDescription $description, Request $request)
    {
        $this->authorize('update', AcademicCaseCategoryDescription::class);

        $categories = AcademicCaseCategory::where('grade_id', userGrades())->whereNull('deleted_at')->get();

        return view('counseling::manage.cases.descriptions.edit', compact('description', 'categories'));
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicCaseCategoryDescription $description, UpdateRequest $request)
    {
        $this->authorize('update', AcademicCaseCategoryDescription::class);

        $description->update($request->only('ctg_id', 'name', 'point'));

        return redirect($request->get('next', url()->previous()))->with('success', 'Deskripsi kasus <strong>'.$description->name.'</strong> berhasil diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicCaseCategoryDescription $description)
    {
        $this->authorize('show', AcademicCaseCategoryDescription::class);

        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicCaseCategoryDescription $description)
    {
        $this->authorize('destroy', AcademicCaseCategoryDescription::class);

        // $this->authorize('remove', $description);

        $tmp = $description;
        $description->delete();

        return redirect()->back()->with('success', 'Deskripsi kasus <strong>'.$tmp->name.'</strong> berhasil dihapus');
    }
}
