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
            return view('customers.index', ['customers' => [], 'error' => 'Failed to load customers']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCustomerRequest $request
     * @return JsonResponse
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $customer = $this->customerService->createCustomer($request->validated());

            return response()->json([
                'success' => true,
                'customer' => $customer
            ], 201);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'store',
                'request_data' => $request->validated()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer'
            ], 500);
        }
    }

    /**
     * Search for customers by name or phone.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            $customers = $this->customerService->searchCustomers($query);
            return response()->json($customers, 200);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'search',
                'query' => $query
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to search customers'
            ], 500);
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
                'customer_id' => $customer->id
            ]);
            return view('customers.show', ['error' => 'Failed to load customer details']);
        }
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
                'customer_id' => $customer->id
            ]);
            return view('customers.edit', ['error' => 'Failed to load customer for editing']);
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
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update',
                'customer_id' => $customer->id,
                'request_data' => $request->validated()
            ]);
            return redirect()->back()->with('error', 'Failed to update customer');
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
            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'destroy',
                'customer_id' => $customer->id
            ]);
            return redirect()->back()->with('error', 'Failed to delete customer');
        }
    }
}
