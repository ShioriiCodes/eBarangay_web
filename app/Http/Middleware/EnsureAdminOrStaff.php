<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminOrStaff
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->user()?->role, ['admin', 'staff'], true)) {
            abort(Response::HTTP_FORBIDDEN, 'Admin or staff access required.');
        }

        return $next($request);
    }
}
