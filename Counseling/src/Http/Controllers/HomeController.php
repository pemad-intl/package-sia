<?php

namespace Digipemad\Sia\Counseling\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use Digipemad\Sia\Academic\Models\StudentSemesterCase;
use Digipemad\Sia\Academic\Models\StudentSemesterCounseling;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    	$acsem = $this->acsem;

        if(empty($this->acsem->id)){
            return redirect()->route('portal::dashboard.index')->with('msg-gagal', 'Semester harus diisi dahulu sebelum masuk ke konseling');
        }

        $employee = auth()->user();
     	//$employee = $user->;

   //     dd($user);
        $last_cases = StudentSemesterCase::whereHas('semester', function ($semester) {
                            return $semester->where('semester_id', $this->acsem->id)->whereHas('student');
                        })->latest()->take(5)->get();

        $last_counselings = StudentSemesterCounseling::whereHas('semester', function ($semester) {
                            return $semester->where('semester_id', $this->acsem->id)->whereHas('student');
                        })->latest()->take(5)->get();

        return view('counseling::home', compact('employee', 'acsem', 'last_cases', 'last_counselings'));
    }
}
