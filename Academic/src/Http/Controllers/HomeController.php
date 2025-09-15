<?php

namespace Digipemad\Sia\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Academic\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    	$acsem = $this->acsem;

        if (empty($this->acsem->id)) {
            return redirect()->route('portal::dashboard-msdm.index')->with('msg-gagal', 'Semester harus diisi dahulu sebelum masuk ke akademik');
        }

    	$user = auth()->user();
    	$student = $user->student;

        $stsem = null;
        if(!empty($student)){
    	    $stsem = $student->semesters()->with('classroom.meets.subject', 'assessments', 'cases')->where('semester_id', $acsem->id)->first();
        }

        return view('academic::home', compact('acsem', 'user', 'student', 'stsem'));
    }
}
