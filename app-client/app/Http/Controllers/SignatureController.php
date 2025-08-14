<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\Plan;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Payment\AsaasPaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Class SignatureController
 * @package App\Http\Controllers
 */
class SignatureController extends Controller
{
    /**
     * @var ErrorLogService
     */
    protected ErrorLogService $errorLogService;

    /**
     * @var AsaasPaymentService
     */
    protected AsaasPaymentService $asaasService;

    /**
     * SignatureController constructor.
     *
     * @param ErrorLogService $errorLogService
     * @param AsaasPaymentService $asaasService
     */
    public function __construct(ErrorLogService $errorLogService, AsaasPaymentService $asaasService)
    {
        $this->errorLogService = $errorLogService;
        $this->asaasService = $asaasService;
    }

    /**
     * Display the signature details.
     *
     * @return View|RedirectResponse
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $signature = $user->company->signature;

            if (!$signature) {
                return redirect()->route('dashboard')->with('error', 'Nenhuma assinatura encontrada.');
            }

            return view('signature.index', compact('signature'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'index']);
            return redirect()->route('dashboard')->with('error', 'Erro ao carregar detalhes da assinatura.');
        }
    }

    /**
     * Display the payment page for signature.
     *
     * @param int $signatureId
     * @return View|RedirectResponse
     */
    public function payment(int $signatureId)
    {
        try {
            $user = Auth::user();
            $signature = Signature::where('id', $signatureId)
                ->where('company_id', $user->company_id)
                ->with('plan')
                ->first();

            if (!$signature) {
                return redirect()->route('signature.index')->with('error', 'Assinatura não encontrada.');
            }

            return view('signature.payment', compact('signature'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'payment', 'signature_id' => $signatureId]);
            return redirect()->route('signature.index')->with('error', 'Erro ao carregar página de pagamento.');
        }
    }
}
