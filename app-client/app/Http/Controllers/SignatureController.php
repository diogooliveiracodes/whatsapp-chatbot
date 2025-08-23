<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\Plan;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Payment\AsaasPaymentService;
use App\Services\Payment\PaymentService;
use App\Services\Signature\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Enum\AsaasCustomerTypeEnum;
use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentServiceEnum;
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
     * @var PaymentService
     */
    protected PaymentService $paymentService;

    /**
     * SignatureController constructor.
     *
     * @param ErrorLogService $errorLogService
     * @param AsaasPaymentService $asaasPaymentService
     * @param SignatureService $signatureService
     * @param AsaasCustomerService $asaasCustomerService
     * @param PaymentService $paymentService
     */
    public function __construct(ErrorLogService $errorLogService, AsaasPaymentService $asaasPaymentService, SignatureService $signatureService, AsaasCustomerService $asaasCustomerService, PaymentService $paymentService)
    {
        $this->errorLogService = $errorLogService;
        $this->asaasPaymentService = $asaasPaymentService;
        $this->signatureService = $signatureService;
        $this->asaasCustomerService = $asaasCustomerService;
        $this->paymentService = $paymentService;
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
                ->with(['plan', 'payments' => function($query) {
                    $query->where('status', PaymentStatusEnum::PENDING->value)
                          ->where('payment_method', \App\Enum\PaymentMethodEnum::PIX->value)
                          ->where('service', \App\Enum\PaymentServiceEnum::SIGNATURE->value)
                          ->where('expires_at', '>', now())
                          ->orderBy('created_at', 'desc');
                }])
                ->first();

            if (!$signature) {
                return redirect()->route('signature.index')->with('error', 'Assinatura não encontrada.');
            }

            return view('signature.payment', compact('signature'))
                ->with('paymentStatusEnum', PaymentStatusEnum::class);
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

                        // Se a resposta foi bem-sucedida e contém o código PIX, salvar no banco
            $pixCode = null;

            // Verificar diferentes campos possíveis da resposta da API do Asaas
            // Priorizar o payload que é o código PIX copia e cola
            if (isset($response['payload']) && !empty($response['payload'])) {
                $pixCode = $response['payload'];
            } elseif (isset($response['encodedImage']) && !empty($response['encodedImage'])) {
                $pixCode = $response['encodedImage'];
            } elseif (isset($response['pixCode']) && !empty($response['pixCode'])) {
                $pixCode = $response['pixCode'];
            } elseif (isset($response['qrCode']) && !empty($response['qrCode'])) {
                $pixCode = $response['qrCode'];
            } elseif (isset($response['copyPaste']) && !empty($response['copyPaste'])) {
                $pixCode = $response['copyPaste'];
            } elseif (isset($response['pixCopyPaste']) && !empty($response['pixCopyPaste'])) {
                $pixCode = $response['pixCopyPaste'];
            }

            if ($pixCode) {
                $this->savePixCodeToPayment($request->payment_id, $pixCode);
            }

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'getPixCode', 'signature_id' => $signature->id, 'payment_id' => $request->payment_id ?? null]);

            return response()->json(['success' => false, 'error' => 'Erro ao obter código PIX: '], 500);
        }
    }

        /**
     * Save PIX code to payment record
     *
     * @param string $asaasPaymentId
     * @param string $pixCode
     * @return void
     */
        private function savePixCodeToPayment(string $asaasPaymentId, string $pixCode): void
    {
        try {
            $payment = $this->paymentService->findByAsaasPaymentId($asaasPaymentId);

            if ($payment) {
                $payment->update(['pix_copy_paste' => $pixCode]);
            }
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'savePixCodeToPayment',
                'asaas_payment_id' => $asaasPaymentId
            ]);
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
