<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->user()?->force_password_change
            && ! in_array($request->route()?->getName(), [
                'password.first-login.edit',
                'password.first-login.update',
                'logout',
            ], true)
        ) {
            return to_route('password.first-login.edit');
        }

        return $next($request);
    }
}
