<?php

namespace App\Http\Middleware;

use App\Enum\UserRoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
            auth()->check() && auth()->user()->user_roles->user_role_id === UserRoleEnum::ADMIN
        ) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
