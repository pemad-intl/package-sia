<?php

namespace Digipemad\Sia\Boarding\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Boarding\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a analytical and statistical dashboard.
     */
    public function index()
    {
    	$user = auth()->user();

        return view('boarding::dashboard.index', compact('user'));
    }
}
