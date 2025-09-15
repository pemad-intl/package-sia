<?php

namespace Digipemad\Sia\Administration\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a analytical and statistical dashboard.
     */
    public function index()
    {
    	$user = auth()->user();

        return view('administration::dashboard.index', compact('user'));
    }
}
