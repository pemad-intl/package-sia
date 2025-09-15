<?php

namespace Digipemad\Sia\Academic\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as AppController;

use Digipemad\Sia\Academic\Models\AcademicSemester;

class Controller extends AppController
{
    /**
     * Controller instance.
     */
    public function __construct()
    {
     	$this->acsems = AcademicSemester::openedByDesc()->get();

     	$this->acsem = $this->acsems->first();

     	\View::share('ACSEM', $this->acsem);
    }
}
