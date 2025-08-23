<?php

namespace App\Http\Controllers;

use App\Models\AutomatedMessage;
use App\Models\User;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\AutomatedMessage\AutomatedMessageService;
use App\Services\Http\HttpResponseService;
use App\Services\Unit\UnitService;
use App\Http\Requests\StoreAutomatedMessageRequest;
use App\Http\Requests\UpdateAutomatedMessageRequest;
use App\Exceptions\AutomatedMessage\AutomatedMessageException;
use App\Exceptions\AutomatedMessage\AutomatedMessageNotFoundException;
use App\Exceptions\AutomatedMessage\AutomatedMessageValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Controller responsible for managing automated messages in the application.
 * Handles CRUD operations for automated messages.
 */
class AutomatedMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ErrorLogService $errorLogService Service for logging errors
     * @param AutomatedMessageService $automatedMessageService Service for managing automated messages
     * @param HttpResponseService $httpResponse Service for handling HTTP responses
     * @param UnitService $unitService Service for managing units
     */
    public function __construct(
        protected ErrorLogService $errorLogService,
        protected AutomatedMessageService $automatedMessageService,
        protected HttpResponseService $httpResponse,
        protected UnitService $unitService
    ) {}

    /**
     * Display a listing of automated messages.
     *
     * @param Request $request The incoming request
     * @return View The view containing the list of automated messages
     */
    public function index(Request $request): View
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $units = collect();
            $selectedUnit = null;
            $showUnitSelector = false;

            // Se o usuário é proprietário, buscar todas as unidades ativas da empresa
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                // Se há mais de uma unidade, mostrar o seletor
                if ($units->count() > 1) {
                    $showUnitSelector = true;

                    // Obter a unidade selecionada (da query string ou padrão da unidade do usuário)
                    $selectedUnitId = $request->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;

                    // Buscar mensagens da unidade selecionada
                    $messages = $this->automatedMessageService->getMessagesByUnit($selectedUnit->id);
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                    $messages = $this->automatedMessageService->getMessagesByUnit($selectedUnit->id);
                }
            } else {
                // Para outros tipos de usuário, usar a unidade padrão
                $selectedUnit = $user->unit;
                $messages = $this->automatedMessageService->getMessagesByUnit($selectedUnit->id);
            }

            return view('automated-messages.index', compact('messages', 'units', 'selectedUnit', 'showUnitSelector'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'user_id' => Auth::id(),
                'method' => 'AutomatedMessageController::index'
            ]);

            return view('automated-messages.index', [
                'messages' => collect(),
                'units' => collect(),
                'selectedUnit' => null,
                'showUnitSelector' => false,
                'error' => __('automated-messages.messages.load_error')
            ]);
        }
    }

    /**
     * Show the form for creating a new automated message.
     *
     * @param Request $request The incoming request
     * @return View|RedirectResponse The view for creating a new automated message or redirect on error
     */
    public function create(Request $request): View|RedirectResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $units = collect();
            $selectedUnit = null;
            $showUnitSelector = false;

            // Se o usuário é proprietário, buscar todas as unidades ativas da empresa
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                // Se há mais de uma unidade, mostrar o seletor
                if ($units->count() > 1) {
                    $showUnitSelector = true;

                    // Obter a unidade selecionada (da query string ou padrão da unidade do usuário)
                    $selectedUnitId = $request->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                }
            } else {
                // Para outros tipos de usuário, usar a unidade padrão
                $selectedUnit = $user->unit;
            }

            $messageTypes = $this->automatedMessageService->getMessageTypes();

            return view('automated-messages.create', compact('units', 'selectedUnit', 'showUnitSelector', 'messageTypes'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'user_id' => Auth::id(),
                'method' => 'AutomatedMessageController::create'
            ]);

            return redirect()->route('automated-messages.index')
                ->with('error', __('automated-messages.messages.load_error'));
        }
    }

    /**
     * Store a newly created automated message.
     *
     * @param StoreAutomatedMessageRequest $request The validated request
     * @return RedirectResponse Redirect to the index page
     */
    public function store(StoreAutomatedMessageRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            /** @var User $user */
            $user = Auth::user();

            // Validar se a unidade pertence à empresa do usuário
            if (!$user->isOwner() && $validated['unit_id'] != $user->unit_id) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['unit_id' => __('automated-messages.messages.unit_not_belongs_to_company')]);
            }

            // Para usuários que não são owner, usar o company_id do usuário
            if (!$user->isOwner()) {
                $validated['company_id'] = $user->company_id;
            } else {
                // Para owners, obter o company_id da unidade
                $unit = \App\Models\Unit::find($validated['unit_id']);
                if ($unit) {
                    $validated['company_id'] = $unit->company_id;
                }
            }

            $message = $this->automatedMessageService->createMessage($validated);

            return redirect()->route('automated-messages.index')
                ->with('success', __('automated-messages.messages.created'));
        } catch (AutomatedMessageValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (AutomatedMessageException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'method' => 'AutomatedMessageController::store'
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('automated-messages.messages.create_error', ['message' => $e->getMessage()])]);
        }
    }

    /**
     * Show the form for editing the specified automated message.
     *
     * @param AutomatedMessage $automatedMessage The automated message to edit
     * @return View|RedirectResponse The view for editing the automated message or redirect on error
     */
    public function edit(AutomatedMessage $automatedMessage): View|RedirectResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            if (!$user->isOwner() && $automatedMessage->unit_id != $user->unit_id) {
                abort(403);
            }

            $units = collect();
            $showUnitSelector = false;

            // Se o usuário é proprietário, buscar todas as unidades ativas da empresa
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                // Se há mais de uma unidade, mostrar o seletor
                if ($units->count() > 1) {
                    $showUnitSelector = true;
                }
            }

            $messageTypes = $this->automatedMessageService->getMessageTypes();

            return view('automated-messages.edit', compact('automatedMessage', 'units', 'showUnitSelector', 'messageTypes'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'message_id' => $automatedMessage->id,
                'user_id' => Auth::id(),
                'method' => 'AutomatedMessageController::edit'
            ]);

            return redirect()->route('automated-messages.index')
                ->with('error', __('automated-messages.messages.load_error'));
        }
    }

    /**
     * Update the specified automated message.
     *
     * @param UpdateAutomatedMessageRequest $request The validated request
     * @param AutomatedMessage $automatedMessage The automated message to update
     * @return RedirectResponse Redirect to the index page
     */
    public function update(UpdateAutomatedMessageRequest $request, AutomatedMessage $automatedMessage): RedirectResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            if (!$user->isOwner() && $automatedMessage->unit_id != $user->unit_id) {
                abort(403);
            }

            $validated = $request->validated();

            // Validar se a unidade pertence à empresa do usuário
            if (!$user->isOwner() && $validated['unit_id'] != $user->unit_id) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['unit_id' => __('automated-messages.messages.unit_not_belongs_to_company')]);
            }

            // Para usuários que não são owner, usar o company_id do usuário
            if (!$user->isOwner()) {
                $validated['company_id'] = $user->company_id;
            } else {
                // Para owners, obter o company_id da unidade
                $unit = \App\Models\Unit::find($validated['unit_id']);
                if ($unit) {
                    $validated['company_id'] = $unit->company_id;
                }
            }

            $message = $this->automatedMessageService->updateMessage($automatedMessage, $validated);

            return redirect()->route('automated-messages.index')
                ->with('success', __('automated-messages.messages.updated'));
        } catch (AutomatedMessageValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (AutomatedMessageException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'message_id' => $automatedMessage->id,
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'method' => 'AutomatedMessageController::update'
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('automated-messages.messages.update_error', ['message' => $e->getMessage()])]);
        }
    }

    /**
     * Remove the specified automated message.
     *
     * @param AutomatedMessage $automatedMessage The automated message to delete
     * @return RedirectResponse Redirect to the index page
     */
    public function destroy(AutomatedMessage $automatedMessage): RedirectResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            if (!$user->isOwner() && $automatedMessage->unit_id != $user->unit_id) {
                abort(403);
            }

            $this->automatedMessageService->deleteMessage($automatedMessage);

            return redirect()->route('automated-messages.index')
                ->with('success', __('automated-messages.messages.deleted'));
        } catch (AutomatedMessageException $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'message_id' => $automatedMessage->id,
                'user_id' => Auth::id(),
                'method' => 'AutomatedMessageController::destroy'
            ]);

            return redirect()->back()
                ->withErrors(['error' => __('automated-messages.messages.delete_error', ['message' => $e->getMessage()])]);
        }
    }


}
