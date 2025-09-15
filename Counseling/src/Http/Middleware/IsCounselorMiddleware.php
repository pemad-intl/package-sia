<?php

namespace Digipemad\Sia\Counseling\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IsCounselorMiddleware
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
        if (!Gate::forUser($request->user())->allows('counseling::access')) {
            return redirect()->route('portal::dashboard.index');
        }

        return Gate::authorize('counseling::access')
            ? $next($request)
            : abort(403);
    }
}
