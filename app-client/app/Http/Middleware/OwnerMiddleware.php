<?php

namespace App\Http\Middleware;

use App\Enum\UserRoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::check() && Auth::user()->user_role_id === UserRoleEnum::OWNER
        ) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
