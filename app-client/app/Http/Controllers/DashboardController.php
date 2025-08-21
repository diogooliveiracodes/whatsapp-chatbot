<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\Dashboard\OwnerDashboardMetricsService;

/**
 * Controller for handling dashboard functionality.
 *
 * This controller manages user dashboard access and redirects users
 * based on their role (admin or regular user).
 */
class DashboardController extends Controller
{
    /**
     * The authenticated user instance.
     *
     * @var User
     */
    private User $user;
    private OwnerDashboardMetricsService $ownerMetricsService;

    /**
     * Create a new controller instance.
     *
     * Initializes the controller and sets the authenticated user.
     *
     * @return void
     */
    public function __construct(OwnerDashboardMetricsService $ownerMetricsService)
    {
        $this->user = Auth::user();
        $this->ownerMetricsService = $ownerMetricsService;
    }

    /**
     * Display the dashboard index page.
     *
     * Redirects admin users to the admin dashboard, otherwise
     * displays the regular user dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user instanceof User && $user->isAdmin()) {
            return redirect()->route('admin.index');
        }

        $metrics = null;
        if ($user instanceof User && $user->isOwner()) {
            $metrics = $this->ownerMetricsService->getMetricsForOwner($user);
        }

        return view('dashboard.index', compact('metrics'));
    }
}
