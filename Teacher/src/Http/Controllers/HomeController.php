<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     	$acsem = $this->acsem;
        
        if (empty($this->acsem->id)) {
            return redirect()->route('portal::dashboard.index')->with('msg-gagal', 'Semester harus diisi dahulu sebelum masuk ke menu guru');
        }

     	$user = auth()->user()->load(['teacher.meets' => function ($meet) {
            $meet->whereAcsemIn($this->acsem->id)->withCount('plans');
        }]);

     	$teacher = $user->teacher;

        $closest_plans = $user->teacher?->plans()->whereAcsemIn($this->acsem->id)->getClosestPlans();

        $unpresenced_plans = $user->teacher?->plans()->whereAcsemIn($this->acsem->id)->getUnpresencedPlans();

        return view('teacher::home', compact('user', 'teacher', 'acsem', 'closest_plans', 'unpresenced_plans'));
    }
}
