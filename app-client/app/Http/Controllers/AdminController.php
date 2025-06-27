<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreUserRequest;
use App\Models\Company;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserRole;
use App\Services\Admin\CreateUserService;
use App\Services\Company\CompanyService;
use App\Services\Unit\UnitService;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

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
    public function __construct(
        protected UserService $userService,
        protected CompanyService $companyService,
        protected UnitService $unitService,
        protected CreateUserService $createUserService
    ) {}

    /**
     * Display the admin dashboard index page.
     *
     * Retrieves all users and displays them on the admin dashboard.
     *
     * @return \Illuminate\View\View The admin dashboard view with users data
     */
    public function index(): View
    {
        $users = $this->userService->getUsers();

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
        $users = $this->userService->getUsers();

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
        $companies = $this->companyService->getCompanies();
        $companies->load('Units');
        $units = Unit::where('active', true)->get();
        $userRoles = UserRole::where('active', true)->get();

        return view('admin.users.create', compact('companies', 'userRoles', 'units'));
    }

    /**
     * Store a new user.
     *
     * Processes the form submission to create a new user and redirects to the users list.
     *
     * @param \App\Http\Requests\AdminStoreUserRequest $request The validated HTTP request containing user data
     * @return \Illuminate\Http\RedirectResponse Redirects to admin users page after creation
     */
    public function storeUser(AdminStoreUserRequest $request): RedirectResponse
    {
        try {
            $result = $this->createUserService->execute($request);

            return redirect()
                ->route('admin.users.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erro ao criar usuário: ' . $e->getMessage()])
                ->withInput();
        }
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
        $user = $this->userService->findById($id);

        if (!$user) {
            abort(404);
        }

        return view('admin.users.edit', ['user' => $user]);
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
        $user = $this->userService->findById($id);

        if (!$user) {
            abort(404);
        }

        $user = $this->userService->update($user, $request->all());

        return redirect()
            ->route('admin.users')
            ->with('success', 'Usuário atualizado com sucesso!');
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
        $user = $this->userService->findById($id);

        if (!$user) {
            abort(404);
        }

        return view('admin.users.deactivate', ['user' => $user]);
    }

    /**
     * Show user details
     *
     * @param int $id The ID of the user to show
     * @return \Illuminate\View\View The user details view
     */
    public function showUser(int $id): View
    {
        $user = $this->userService->findById($id);

        if (!$user) {
            abort(404);
        }

        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Get units by company ID
     *
     * @param int $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnitsByCompany(int $companyId)
    {
        $units = Unit::where('company_id', $companyId)
            ->where('active', true)
            ->get(['id', 'name']);

        return response()->json($units);
    }
}
