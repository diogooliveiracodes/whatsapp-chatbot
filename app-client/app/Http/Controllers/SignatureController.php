<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\Plan;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Payment\AsaasPaymentService;
use App\Services\Signature\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Enum\AsaasCustomerTypeEnum;
use App\Services\Payment\AsaasCustomerService;

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
    protected AsaasPaymentService $asaasPaymentService;

    /**
     * @var SignatureService
     */
    protected SignatureService $signatureService;

    /**
     * @var AsaasCustomerService
     */
    protected AsaasCustomerService $asaasCustomerService;

    /**
     * SignatureController constructor.
     *
     * @param ErrorLogService $errorLogService
     * @param AsaasPaymentService $asaasPaymentService
     * @param SignatureService $signatureService
     * @param AsaasCustomerService $asaasCustomerService
     */
    public function __construct(ErrorLogService $errorLogService, AsaasPaymentService $asaasPaymentService, SignatureService $signatureService, AsaasCustomerService $asaasCustomerService)
    {
        $this->errorLogService = $errorLogService;
        $this->asaasPaymentService = $asaasPaymentService;
        $this->signatureService = $signatureService;
        $this->asaasCustomerService = $asaasCustomerService;
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

            // Carregar pagamentos com paginação e ordenação (mais novo primeiro)
            $payments = $signature->payments()
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            return view('signature.index', compact('signature', 'payments'));
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

    public function generatePayment(Signature $signature)
    {
        try {
            $company = $signature->company;

            $customerExists = $this->asaasCustomerService->customerExists([
                'type' => AsaasCustomerTypeEnum::COMPANY->value,
                'company_id' => $signature->company_id,
            ]);

            if (!$customerExists) {
                $asaasCustomer = $this->asaasCustomerService->create([
                    'type' => AsaasCustomerTypeEnum::COMPANY->value,
                    'company_id' => $signature->company_id,
                    'name' => $company->name,
                    'cpf_cnpj' => $company->document_number,
                ]);

                $integrationResult = $this->asaasCustomerService->integrateCustomerToAsaas($asaasCustomer);

                if ($integrationResult !== true) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Erro ao integrar cliente com Asaas: ' . $integrationResult
                    ], 500);
                }
            }

            $asaasCustomer = $this->asaasCustomerService->findByCompanyId($signature->company_id);

            $response = $this->signatureService->generateSignaturePayment($signature, $asaasCustomer);

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'generatePayment', 'signature_id' => $signature->id]);

            return response()->json(['success' => false, 'error' => 'Erro ao gerar pagamento: '], 500);
        }
    }

    public function getPixCode(Signature $signature, Request $request)
    {
        try {
            $request->validate([
                'payment_id' => 'required|string'
            ]);

            $response = $this->asaasPaymentService->getPixCode($request->payment_id);

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'getPixCode', 'signature_id' => $signature->id, 'payment_id' => $request->payment_id ?? null]);

            return response()->json(['success' => false, 'error' => 'Erro ao obter código PIX: '], 500);
        }
    }

    /**
     * Check payment status
     *
     * @param Signature $signature
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPaymentStatus(Signature $signature, Request $request)
    {
        try {
            $request->validate([
                'payment_id' => 'required|string'
            ]);

            $result = $this->signatureService->checkPaymentStatus($request->payment_id);

            return response()->json($result);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'checkPaymentStatus', 'signature_id' => $signature->id, 'payment_id' => $request->payment_id ?? null]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar status do pagamento'
            ], 500);
        }
    }
}
