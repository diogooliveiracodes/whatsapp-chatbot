<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreUserRequest;
use App\Models\Unit;
use App\Models\UserRole;
use App\Services\Admin\CreateUserService;
use App\Services\Admin\DeactivateCompanyService;
use App\Services\Company\CompanyService;
use App\Services\Unit\UnitService;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin Controller
 *
 * Handles administrative functionality and dashboard operations.
 * Provides comprehensive user and company management capabilities for administrators.
 * Includes user creation, editing, deactivation, and company management features.
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param UserService $userService Service for user operations
     * @param CompanyService $companyService Service for company operations
     * @param UnitService $unitService Service for unit operations
     * @param CreateUserService $createUserService Service for creating users
     * @param DeactivateCompanyService $deactivateCompanyService Service for deactivating companies
     */
    public function __construct(
        protected UserService $userService,
        protected CompanyService $companyService,
        protected UnitService $unitService,
        protected CreateUserService $createUserService,
        protected DeactivateCompanyService $deactivateCompanyService
    ) {}

    /**
     * Display the admin dashboard index page.
     *
     * Retrieves all users and displays them on the admin dashboard.
     * This is the main landing page for administrators.
     *
     * @return View The admin dashboard view with users data
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
     * Loads companies, units, and user roles for the form dropdowns.
     *
     * @return View The user creation form view with companies, units, and user roles
     */
    public function createUser(): View
    {
        $companies = $this->companyService->getActiveCompanies();
        $companies->load('Units');
        $units = Unit::where('active', true)->get();
        $userRoles = UserRole::where('active', true)->get();

        return view('admin.users.create', compact('companies', 'userRoles', 'units'));
    }

    /**
     * Store a new user.
     *
     * Processes the form submission to create a new user and redirects to the users list.
     * Handles validation errors and displays appropriate success/error messages.
     *
     * @param AdminStoreUserRequest $request The validated HTTP request containing user data
     * @return RedirectResponse Redirects to admin users page after successful creation or back with errors
     * @throws \Exception When user creation fails
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
     * Processes the deactivation of a company and all its associated data.
     * Redirects to the admin dashboard with a success message.
     *
     * @param Request $request The HTTP request containing the company ID
     * @return RedirectResponse Redirects to admin dashboard after successful deactivation
     */
    public function deactivateCompany(Request $request): RedirectResponse
    {
        $companyId = $request->input('company_id');

        $this->deactivateCompanyService->execute($companyId);

        return redirect()->route('admin.users.index')->with('success', 'Empresa desativada com sucesso!');
    }

    public function indexCompanies(): View
    {
        $companies = $this->companyService->getCompanies();

        return view('admin.companies.index', ['companies' => $companies]);
    }
}
