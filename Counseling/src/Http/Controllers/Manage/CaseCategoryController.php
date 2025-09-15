<?php

namespace Digipemad\Sia\Counseling\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\AcademicCaseCategory;
use Digipemad\Sia\Counseling\Http\Requests\Manage\CaseCategory\StoreRequest;
use Digipemad\Sia\Counseling\Http\Requests\Manage\CaseCategory\UpdateRequest;

class CaseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('access', User::class);
        $this->authorize('access', AcademicCaseCategory::class);

        $trashed = $request->get('trash');

        $categories = AcademicCaseCategory::where('grade_id', userGrades())
        ->withCount('descriptions')->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $categories_count = AcademicCaseCategory::where('grade_id', userGrades())->count();

        return view('counseling::manage.cases.categories.index', compact('categories', 'categories_count'));
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicCaseCategory::class);

        return view('counseling::manage.cases.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicCaseCategory::class);

        $category = new AcademicCaseCategory(
            array_merge(
            $request->only('name'),
            [
                'grade_id' => userGrades() 
            ])
        );
        $category->save();

        return redirect($request->get('next', url()->previous()))->with('success', 'Kategori kasus <strong>'.$category->name.'</strong> berhasil dibuat!');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicCaseCategory $category, Request $request)
    {
        $this->authorize('update', AcademicCaseCategory::class);

        return view('counseling::manage.cases.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicCaseCategory $category, UpdateRequest $request)
    {
        $this->authorize('update', AcademicCaseCategory::class);

        $category->update(array_merge(
            $request->only('name'),
            [
                'grade_id' => userGrades() 
            ])
        );

        return redirect($request->get('next', url()->previous()))->with('success', 'Kategori kasus <strong>'.$category->name.'</strong> berhasil diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicCaseCategory $category)
    {
        $this->authorize('show', AcademicCaseCategory::class);

        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicCaseCategory $category)
    {
        $this->authorize('destroy', AcademicCaseCategory::class);

        // $this->authorize('remove', $category);

        $tmp = $category;
        $category->delete();

        return redirect()->back()->with('success', 'Kategori kasus <strong>'.$tmp->name.'</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(AcademicCaseCategory $category)
    {
        $this->authorize('restore', AcademicCaseCategory::class);

        // $this->authorize('delete', $category);

        $category->restore();

        return redirect()->back()->with('success', 'Kategori kasus <strong>'.$category->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(AcademicCaseCategory $category)
    {
        $this->authorize('kill', AcademicCaseCategory::class);

        // $this->authorize('delete', $category);

        $tmp = $category;
        $category->forceDelete();

        return redirect()->back()->with('success', 'Kategori kasus <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
