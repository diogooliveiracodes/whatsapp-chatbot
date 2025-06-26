<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Create a new controller instance.
     *
     * Initializes the controller and sets the authenticated user.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
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
        if ($this->user->isAdmin()) {
            return redirect()->route('admin.index');
        }

        return view('dashboard.index');
    }
}
