<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreUserRequest;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserRole;
use App\Services\Admin\CreateUserService;
use App\Services\Admin\DeactivateCompanyService;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Company\CompanyService;
use App\Services\Unit\UnitService;
use App\Services\User\UserService;
use App\Services\Plan\PlanService;
use App\Services\CompanySettings\CompanySettingsService;
use App\Services\Dashboard\AdminDashboardMetricsService;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use App\Enum\DocumentTypeEnum;
use App\Helpers\CnpjHelper;
use App\Helpers\CpfHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\UpdateCompanySettingsRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        protected DeactivateCompanyService $deactivateCompanyService,
        protected ErrorLogService $errorLogService,
        protected PlanService $planService,
        protected CompanySettingsService $companySettingsService,
        protected AdminDashboardMetricsService $adminMetricsService
    ) {}

    /**
     * Display the admin dashboard index page.
     *
     * Retrieves all users and displays them on the admin dashboard.
     * This is the main landing page for administrators.
     *
     * @return View The admin dashboard view with users data
     */
    public function index(Request $request): View
    {
        $period = $request->get('period', '1_month');
        $metrics = $this->adminMetricsService->getMetricsForAdmin($period);

        return view('admin.index', compact('metrics'));
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
        $plans = $this->planService->getPlans();

        return view('admin.users.create', compact('companies', 'userRoles', 'units', 'plans'));
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
                ->with('success', __('admin.user_created_success'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'store',
                'request_data' => $request->validated(),
            ]);

            return redirect()
                ->back()
                ->withErrors(['error' => __('admin.user_created_error', ['message' => $e->getMessage()])])
                ->withInput();
        }
    }

    /**
     * Processes the deactivation of a company and all its associated data.
     * Redirects to the admin dashboard with a success message.
     *
     * @param int $company The company ID from the route parameter
     * @return RedirectResponse Redirects to admin dashboard after successful deactivation
     */
    public function deactivateCompany(int $company): RedirectResponse
    {
        try {
            $this->deactivateCompanyService->execute($company);

            return redirect()->route('admin.companies.index')->with('success', __('admin.company_deactivated_success'));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => __('admin.company_deactivated_error', ['message' => $e->getMessage()])]);
        }
    }

    /**
     * Display the companies management page.
     *
     * Retrieves all companies and displays them in a dedicated companies management interface.
     *
     * @return \Illuminate\View\View The companies management view with companies data
     */
    public function indexCompanies(): View
    {
        $companies = $this->companyService->getCompanies();

        return view('admin.companies.index', ['companies' => $companies]);
    }

    /**
     * Edit a company.
     *
     * Shows the form for editing a company in the admin panel.
     * Loads companies, units, and user roles for the form dropdowns.
     *
     * @return View The company edit form view with companies, units, and user roles
     */
    public function editCompany(int $id): View
    {
        $company = $this->companyService->findById($id);
        $company->load('companySettings');
        $plans = $this->planService->getPlans();

        return view('admin.companies.edit', ['company' => $company, 'plans' => $plans]);
    }

    public function updateCompany(Request $request, Company $company): RedirectResponse
    {
        try {
            if (!$this->validateDocumentNumber($request->document_number, $request->document_type)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('admin.company_updated_error', ['message' => __('admin.invalid_document')]));
            }

            $this->companyService->update($company, $request->all());

            return redirect()->route('admin.companies.index')->with('success', __('admin.company_updated_success'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update',
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', __('admin.company_updated_error', ['message' => $e->getMessage()]));
        }
    }

    public function updateCompanySettings(UpdateCompanySettingsRequest $request, Company $company): RedirectResponse
    {
        try {
            $data = $request->validated();

            // Mapear o campo settings_active para active
            if (isset($data['settings_active'])) {
                $data['active'] = $data['settings_active'];
                unset($data['settings_active']);
            }

            // Usar o service para atualizar as configurações
            $this->companySettingsService->updateByCompanyId($company->id, $data);

            return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update_settings',
                'company_id' => $company->id,
                'request_data' => $request->validated(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Validate the document number.
     *
     * @param string $documentNumber
     * @param int $documentType
     * @return bool
     */
    public function validateDocumentNumber(string $documentNumber, int $documentType): bool
    {
        if ($documentType == DocumentTypeEnum::CNPJ->value) {
            return CnpjHelper::isValid($documentNumber);
        }

        return CpfHelper::isValid($documentNumber);
    }

    public function logs(): View
    {
        $logs = $this->errorLogService->getLogs();

        return view('admin.logs', ['logs' => $logs]);
    }

    /**
     * Display the admin profile edit page.
     *
     * @return View The admin profile edit view
     */
    public function editProfile(): View
    {
        return view('admin.profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the admin profile information.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update($request->only('name', 'email'));

        return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the admin password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')->with('status', 'password-updated');
    }

    /**
     * Delete the admin profile.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroyProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
