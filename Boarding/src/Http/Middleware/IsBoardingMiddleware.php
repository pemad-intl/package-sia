<?php

namespace Digipemad\Sia\Boarding\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IsBoardingMiddleware
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
        
        if (Gate::allows('boarding::access')) {

            return $next($request);
        }

        return redirect()->route('portal::dashboard-msdm.index');
    }
}
