<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\Plan;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
     * SignatureController constructor.
     *
     * @param ErrorLogService $errorLogService
     */
    public function __construct(ErrorLogService $errorLogService)
    {
        $this->errorLogService = $errorLogService;
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
}
