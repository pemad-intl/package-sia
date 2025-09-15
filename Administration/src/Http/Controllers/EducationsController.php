<?php

namespace Digipemad\Sia\Administration\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

class EducationsController extends Controller
{
    /**
     * Display a analytical and statistical dashboard.
     */
    public function index()
    {
    	$user = auth()->user();


        return view('administration::education', compact('user'));
    }

    public function store(Request $request){
        $user = auth()->user();

        if ($request->has('grade_id')) {
            $gradeId = $request->input('grade_id');

            if (in_array($gradeId, [4, 5])) {
                session()->put('selected_grade', $gradeId);
            }
        }

        $selectedGrade = session('selected_grade');

        return redirect()->route('administration::dashboard');
    }
}
