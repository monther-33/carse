<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route middleware "can_any:perm.a,perm.b" — passes if the user has any of the permissions.
 */
class AuthorizeAny
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        abort_unless($request->user()?->canAny($permissions), 403);

        return $next($request);
    }
}
