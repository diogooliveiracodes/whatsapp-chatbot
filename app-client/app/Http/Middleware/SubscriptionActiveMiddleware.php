<?php

namespace App\Http\Middleware;

use App\Enum\SignatureStatusEnum;
use App\Services\Admin\DeactivateCompanyService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class SubscriptionActiveMiddleware
{
    public function __construct(
        protected DeactivateCompanyService $deactivateCompanyService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $signature = $user->company->signature;

        if (!$signature || $signature->status === SignatureStatusEnum::EXPIRED) {
            $this->deactivateCompanyService->execute($user->company->id);
            Auth::logout();

            return redirect()->route('login')->with('error', __('middleware.subscription.subscription_expired'));
        }

        if ($signature->expires_at && Carbon::now()->isAfter($signature->expires_at)) {
            $signature->update(['status' => SignatureStatusEnum::EXPIRED]);
            $this->deactivateCompanyService->execute($user->company->id);
            Auth::logout();

            return redirect()->route('login')->with('error', __('middleware.subscription.subscription_expired'));
        }

        return $next($request);
    }
}
