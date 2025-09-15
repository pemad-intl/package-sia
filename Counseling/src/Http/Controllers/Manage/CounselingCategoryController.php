<?php

namespace Digipemad\Sia\Counseling\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\AcademicCounselingCategory;
use Digipemad\Sia\Counseling\Http\Requests\Manage\CounselingCategory\StoreRequest;
use Digipemad\Sia\Counseling\Http\Requests\Manage\CounselingCategory\UpdateRequest;

class CounselingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('access', User::class);

        $trashed = $request->get('trash');

        $categories = AcademicCounselingCategory::where('grade_id', userGrades())->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $categories_count = AcademicCounselingCategory::where('grade_id', userGrades())->count();

        return view('counseling::manage.counselings.categories.index', compact('categories', 'categories_count'));
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicCounselingCategory::class);

        return view('counseling::manage.counselings.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicCounselingCategory::class);

        $category = new AcademicCounselingCategory(array_merge(
        $request->only('name'),
        [
            'grade_id' => userGrades() 
        ]));

        $category->save();

        return redirect($request->get('next', url()->previous()))->with('success', 'Kategori konseling <strong>'.$category->name.'</strong> berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicCounselingCategory $category, Request $request)
    {
        $this->authorize('update', AcademicCounselingCategory::class);

        return view('counseling::manage.counselings.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicCounselingCategory $category, UpdateRequest $request)
    {
        $this->authorize('update', AcademicCounselingCategory::class);

        $category->update(array_merge(
            $request->only('name'),
            [
                'grade_id' => userGrades() 
            ]));

        return redirect($request->get('next', url()->previous()))->with('success', 'Kategori konseling <strong>'.$category->name.'</strong> berhasil diperbarui');
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicCounselingCategory $category)
    {
        $this->authorize('show', AcademicCounselingCategory::class);

        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicCounselingCategory $category)
    {
        $this->authorize('destroy', AcademicCounselingCategory::class);

        // $this->authorize('remove', $category);

        $tmp = $category;
        $category->delete();

        return redirect()->back()->with('success', 'Kategori konseling <strong>'.$tmp->name.'</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(AcademicCounselingCategory $category)
    {
        $this->authorize('restore', AcademicCounselingCategory::class);

        // $this->authorize('delete', $category);

        $category->restore();

        return redirect()->back()->with('success', 'Kategori konseling <strong>'.$category->name.'</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(AcademicCounselingCategory $category)
    {
        $this->authorize('kill', AcademicCounselingCategory::class);

        // $this->authorize('delete', $category);

        $tmp = $category;
        $category->forceDelete();

        return redirect()->back()->with('success', 'Kategori konseling <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }
}
