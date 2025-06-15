<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Services\Customer\CustomerService;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class CustomerController
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{
    /**
     * @var CustomerService
     */
    protected CustomerService $customerService;

    /**
     * @var ErrorLogService
     */
    protected ErrorLogService $errorLogService;

    /**
     * CustomerController constructor.
     *
     * @param CustomerService $customerService
     * @param ErrorLogService $errorLogService
     */
    public function __construct(CustomerService $customerService, ErrorLogService $errorLogService)
    {
        $this->customerService = $customerService;
        $this->errorLogService = $errorLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $customers = $this->customerService->getCustomersByUnit();

            return view('customers.index', compact('customers'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'index']);

            return view('customers.index', ['customers' => [], 'error' => __('customers.error.load')]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCustomerRequest $request
     * @return JsonResponse
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        try {
            $this->customerService->createCustomer($request->validated());

            return redirect()->route('customers.index')->with('success', __('customers.success.created'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'store',
                'request_data' => $request->validated(),
            ]);
            return redirect()->back()->withInput()->with('error', __('customers.error.create'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return View
     */
    public function show(Customer $customer): View
    {
        try {
            return view('customers.show', compact('customer'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'show',
                'customer_id' => $customer->id,
            ]);
            return view('customers.show', ['error' => 'Failed to load customer details']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return View
     */
    public function edit(Customer $customer): View
    {
        try {
            return view('customers.edit', compact('customer'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'edit',
                'customer_id' => $customer->id,
            ]);
            return view('customers.index', ['error' => __('customers.error.load')]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCustomerRequest $request
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        try {
            $this->customerService->updateCustomer($customer, $request->validated());

            return redirect()->route('customers.index')->with('success', __('customers.success.updated'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update',
                'customer_id' => $customer->id,
                'request_data' => $request->validated(),
            ]);

            return redirect()->back()->with('error', __('customers.error.update'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        try {
            $this->customerService->deleteCustomer($customer);
            return redirect()->route('customers.index')->with('success', __('customers.success.deleted'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'destroy',
                'customer_id' => $customer->id,
            ]);
            return redirect()->back()->with('error', __('customers.error.delete'));
        }
    }
}
