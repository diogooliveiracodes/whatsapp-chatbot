<?php

namespace App\Http\Controllers;

use App\Enum\CustomerInactivePeriodEnum;
use App\Repositories\CustomerInactiveRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerInactiveController extends Controller
{
    public function __construct(
        private CustomerInactiveRepository $customerInactiveRepository
    ) {}

    public function index(Request $request)
    {
        $days = $request->get('days', CustomerInactivePeriodEnum::THIRTY_DAYS->value);
        $unitId = Auth::user()->unit_id;

        $customers = $this->customerInactiveRepository->getInactiveCustomers($days, $unitId);
        $periodOptions = CustomerInactivePeriodEnum::getOptions();

        // Pré-carregar os dados de último agendamento para otimizar performance
        $customers->load(['schedules' => function ($query) {
            $query->where('active', true)
                  ->orderBy('schedule_date', 'desc')
                  ->limit(1);
        }]);

        return view('customers.inactive', compact('customers', 'periodOptions', 'days'));
    }
}
