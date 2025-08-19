<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\User\UserService;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Unit\UnitService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Exceptions\User\UnauthorizedUserAccessException;
use App\Exceptions\User\SelfUpdateException;
use App\Exceptions\User\SelfDeactivationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param UserService $userService Service for handling user operations
     * @param ErrorLogService $errorLogService Service for handling error logging
     * @param UnitService $unitService Service for handling unit operations
     */
    public function __construct(
        protected UserService $userService,
        protected ErrorLogService $errorLogService,
        protected UnitService $unitService
    ) {}

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        try {
            $users = $this->userService->getUsersByCompany();

            return view('user.index', compact('users'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'index',
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);

            return view('user.index', ['users' => [], 'error' => __('user.error.load')]);
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $units = $this->unitService->getUnits();
        $userRoles = $this->userService->getAvailableUserRoles();

        return view('user.create', compact('units', 'userRoles'));
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user): View
    {
        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized access');
        }

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user): View
    {
        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized access');
        }

        $units = $this->unitService->getUnits();
        $userRoles = $this->userService->getAvailableUserRoles();

        return view('user.edit', compact('user', 'units', 'userRoles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->create($request->validated());

            return redirect()->route('users.index')
                ->with('success', __('user.success.created'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'store',
                'request_data' => $request->all(),
            ]);

            return back()->with('error', __('user.error.create'))->withInput();
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->update($user, $request->validated());

            return redirect()->route('users.index')
                ->with('success', __('user.success.updated'));
        } catch (UnauthorizedUserAccessException $e) {

            return back()->with('error', $e->getMessage());
        } catch (SelfUpdateException $e) {

            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update',
                'user_id' => $user->id,
                'request_data' => $request->all(),
            ]);

            return back()->with('error', __('user.error.update'));
        }
    }

    /**
     * Deactivate the specified user.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(User $user): RedirectResponse
    {
        try {
            $this->userService->deactivate($user);

            return redirect()->route('users.index')
                ->with('success', __('user.success.deactivated'));
        } catch (UnauthorizedUserAccessException $e) {

            return back()->with('error', $e->getMessage());
        } catch (SelfDeactivationException $e) {

            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {

            return back()->with('error', __('user.error.deactivate'));
        }
    }

    /**
     * Display a listing of the deactivated users.
     *
     * @return \Illuminate\View\View
     */
    public function deactivated(): View
    {
        try {
            $users = $this->userService->getDeactivatedUsersByCompany();

            return view('user.deactivated', compact('users'));
        } catch (\Exception $e) {

            return view('user.deactivated', ['users' => [], 'error' => __('user.error.load')]);
        }
    }

    /**
     * Activate the specified user.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(User $user): RedirectResponse
    {
        try {
            $this->userService->activate($user);

            return redirect()->route('users.deactivated')
                ->with('success', __('user.success.activated'));
        } catch (UnauthorizedUserAccessException $e) {

            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'activate',
                'user_id' => $user->id,
            ]);

            return back()->with('error', __('user.error.activate'));
        }
    }
}
