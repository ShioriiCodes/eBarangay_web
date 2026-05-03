<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureResident
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'resident') {
            abort(Response::HTTP_FORBIDDEN, 'Resident access only.');
        }

        return $next($request);
    }
}
