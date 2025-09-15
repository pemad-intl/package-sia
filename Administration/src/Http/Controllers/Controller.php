<?php

namespace Digipemad\Sia\Administration\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as AppController;

use Digipemad\Sia\Academic\Models\AcademicSemester;


class Controller extends AppController
{

    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     if (
        //         !session()->has('selected_grade') &&
        //         !$request->routeIs('administration::education.index') &&
        //         !$request->routeIs('administration::education.store')
        //     ) {
        //         return redirect()->route('administration::education.index');
        //     }

        //     return $next($request);
        // });

     	$this->acsems = AcademicSemester::openedByDesc()->get();
     	$this->acsem = $this->acsems->first();

     	\View::share('ACSEM', $this->acsem);
    }
}
