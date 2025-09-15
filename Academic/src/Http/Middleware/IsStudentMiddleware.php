<?php

namespace Digipemad\Sia\Academic\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IsStudentMiddleware
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

        if (!$request->user()->student) {
            return redirect()->route('portal::dashboard-msdm.index'); 
        }

        return $next($request);
        
        // Gate::authorize('academic::access')
        //     ? $next($request)
        //     : abort(403);
    }
}
