<?php

namespace App\Services\Admin;

use App\Services\User\UserService;
use App\Services\Company\CompanyService;
use App\Services\Unit\UnitService;
use App\Http\Requests\AdminStoreUserRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateUserService
{
    public function __construct(
        protected UserService $userService,
        protected CompanyService $companyService,
        protected UnitService $unitService
    ) {}

    /**
     * Create a new user with optional company and unit creation
     *
     * @param AdminStoreUserRequest $request
     * @return array
     * @throws Exception
     */
    public function execute(AdminStoreUserRequest $request): array
    {
        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'user_role_id' => $request->user_role_id,
                'active' => $request->has('active'),
            ];

            // Handle company creation or selection
            if ($request->boolean('create_new_company')) {
                $company = $this->companyService->create([
                    'name' => $request->company_name,
                    'document_number' => $request->company_document_number,
                    'document_type' => $request->company_document_type,
                    'active' => true,
                ]);
                $userData['company_id'] = $company->id;
            } else {
                $userData['company_id'] = $request->company_id;
            }

            // Handle unit creation or selection
            if ($request->boolean('create_new_unit')) {
                $unit = $this->unitService->createForCompany([
                    'name' => $request->unit_name,
                    'company_id' => $userData['company_id'],
                    'active' => true,
                ]);
                $userData['unit_id'] = $unit->id;
            } else {
                $userData['unit_id'] = $request->unit_id;
            }

            // Create the user
            $user = $this->userService->create($userData);

            DB::commit();

            return [
                'success' => true,
                'user' => $user,
                'message' => 'Usuário criado com sucesso!'
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
