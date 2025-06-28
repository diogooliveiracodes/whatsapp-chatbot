<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompanyActiveMiddleware
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->company) {
            return redirect()->route('login')->with('error', __('middleware.company.no_company'));
        }

        if (!$user->company->active) {
            return redirect()->route('login')->with('error', __('middleware.company.company_inactive'));
        }

        return $next($request);
    }
}
