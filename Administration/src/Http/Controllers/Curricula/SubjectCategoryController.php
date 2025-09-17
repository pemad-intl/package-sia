<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Curricula;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Academic\Models\AcademicSubjectCategory;

use Digipemad\Sia\Administration\Http\Requests\Curricula\SubjectCategory\StoreRequest;
use Digipemad\Sia\Administration\Http\Requests\Curricula\SubjectCategory\UpdateRequest;

class SubjectCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicSubjectCategory::class);

        $trashed = $request->get('trash');

        $categories = AcademicSubjectCategory::withCount('subjects')
        ->where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $categories_count = AcademicSubjectCategory::count();

        return view('administration::curriculas.subject-categories.index', compact('categories', 'categories_count'));
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicSubjectCategory::class);

        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', AcademicSubjectCategory::class);

        $category = new AcademicSubjectCategory($request->only('name'));
        
        if($category->save()){
            Auth::user()->log(
                ' Kategori mapel bernama '.$category->name.' telah ditambahkan '.
                ' <strong>[ID: ' . $category->id . ']</strong>',
                AcademicSubjectCategory::class,
                $category->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Kategori mapel <strong>'.$category->name.'</strong> berhasil dibuat');
        } 

        return redirect($request->get('next', url()->previous()))->with('danger', 'Kategori mapel <strong>'.$category->name.'</strong> berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicSubjectCategory $subject_category)
    {
        $this->authorize('update', AcademicSubjectCategory::class);

        return abort(404);
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicSubjectCategory $subject_category, UpdateRequest $request)
    {
        $this->authorize('update', AcademicSubjectCategory::class);

        if ($subject_category->update(
            $request->only('name')
        )){
            Auth::user()->log(
                ' Kategori mapel bernama '.$subject_category->name.' telah diperbarui '.
                ' <strong>[ID: ' . $subject_category->id . ']</strong>',
                AcademicSubjectCategory::class,
                $subject_category->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Kategori mapel <strong>' . $subject_category->name . '</strong> berhasil dirubah');
        }

        return redirect($request->get('next', url()->previous()))->with('danger', 'Kategori mapel <strong>' . $subject_category->name . '</strong> gagal dirubah');
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicSubjectCategory $subject_category)
    {
        $this->authorize('show', AcademicSubjectCategory::class);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicSubjectCategory $subject_category)
    {
        $this->authorize('destroy', AcademicSubjectCategory::class);
        // $this->authorize('remove', $subject_category);

        // $tmp = $subject_category;
        if($subject_category->delete()){
            Auth::user()->log(
                ' Kategori mapel bernama '.$subject_category->name.' telah dihapus '.
                ' <strong>[ID: ' . $subject_category->id . ']</strong>',
                AcademicSubjectCategory::class,
                $subject_category->id
            );

            return redirect()->back()->with('success', 'Kategori mapel <strong>'. $subject_category->name.'</strong> berhasil dihapus');
        } 

        return redirect()->back()->with('danger', 'Kategori mapel <strong>'. $subject_category->name.'</strong> berhasil dihapus');
    }
}
