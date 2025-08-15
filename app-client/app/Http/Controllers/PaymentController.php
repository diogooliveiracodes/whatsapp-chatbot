<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\ErrorLog\ErrorLogService;
use App\Enum\PaymentStatusEnum;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * @var ErrorLogService
     */
    protected ErrorLogService $errorLogService;

    /**
     * PaymentController constructor.
     *
     * @param ErrorLogService $errorLogService
     */
    public function __construct(ErrorLogService $errorLogService)
    {
        $this->errorLogService = $errorLogService;
    }

    /**
     * Display the payment history.
     *
     * @return View
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $company = $user->company;

            // Buscar todos os pagamentos da empresa com paginação
            $payments = Payment::where('company_id', $company->id)
                ->with(['plan', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('payments.index', compact('payments'))
                ->with('paymentStatusEnum', PaymentStatusEnum::class);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'payment_history_index']);
            return redirect()->route('dashboard')->with('error', 'Erro ao carregar histórico de pagamentos.');
        }
    }
}
