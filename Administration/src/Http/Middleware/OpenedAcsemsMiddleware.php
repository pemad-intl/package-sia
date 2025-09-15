<?php

namespace Digipemad\Sia\Administration\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Digipemad\Sia\Academic\Models\AcademicSemester;

class OpenedAcsemsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(AcademicSemester::where('open', 1)->count()) {
            return $next($request);
        }

        return redirect()->route('administration::empty-acsems');
    }
}
