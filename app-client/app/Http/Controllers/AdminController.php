<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Admin Controller
 *
 * Handles administrative functionality and dashboard operations.
 * Provides user management capabilities for administrators.
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Display the admin dashboard index page.
     *
     * Retrieves all users and displays them on the admin dashboard.
     *
     * @return \Illuminate\View\View The admin dashboard view with users data
     */
    public function index(): View
    {
        $users = User::all();

        return view('admin.index', ['users' => $users]);
    }

    /**
     * Display the users management page.
     *
     * Retrieves all users and displays them in a dedicated users management interface.
     *
     * @return \Illuminate\View\View The users management view with users data
     */
    public function users(): View
    {
        $users = User::all();

        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Display the user creation form.
     *
     * Shows the form for creating a new user in the admin panel.
     *
     * @return \Illuminate\View\View The user creation form view
     */
    public function createUser(): View
    {
        return view('admin.users.create');
    }

    /**
     * Display the user editing form.
     *
     * Shows the form for editing an existing user's information.
     *
     * @param int $id The ID of the user to edit
     * @return \Illuminate\View\View The user editing form view with user ID
     */
    public function editUser(int $id): View
    {
        return view('admin.users.edit', ['id' => $id]);
    }

    /**
     * Update an existing user's information.
     *
     * Processes the form submission to update user data and redirects to the users list.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing user data
     * @param int $id The ID of the user to update
     * @return \Illuminate\Http\RedirectResponse Redirects to admin users page after update
     */
    public function updateUser(Request $request, int $id): RedirectResponse
    {
        $user = User::find($id);
        $user->update($request->all());
        return redirect()->route('admin.users');
    }

    /**
     * Display the user deactivation confirmation page.
     *
     * Shows the confirmation page for deactivating a user account.
     *
     * @param int $id The ID of the user to deactivate
     * @return \Illuminate\View\View The user deactivation confirmation view with user ID
     */
    public function deactivateUser(int $id): View
    {
        return view('admin.users.deactivate', ['id' => $id]);
    }
}
