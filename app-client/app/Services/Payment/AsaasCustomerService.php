<?php

namespace App\Services\Payment;

use App\Repositories\AsaasCustomerRepository;
use App\Models\AsaasCustomer;
use App\Services\ErrorLog\ErrorLogService;
use App\Helpers\AsaasConfigHelper;

class AsaasCustomerService
{
    /**
     * @var AsaasCustomerRepository
     */
    protected AsaasCustomerRepository $asaasCustomerRepository;

    /**
     * @var ErrorLogService
     */
    protected ErrorLogService $errorLogService;

    /**
     * AsaasCustomerService constructor.
     *
     * @param AsaasCustomerRepository $asaasCustomerRepository
     * @param ErrorLogService $errorLogService
     */
    public function __construct(AsaasCustomerRepository $asaasCustomerRepository, ErrorLogService $errorLogService)
    {
        $this->asaasCustomerRepository = $asaasCustomerRepository;
        $this->errorLogService = $errorLogService;
    }

    /**
     * Create a new Asaas customer.
     *
     * @param array $data
     * @return AsaasCustomer
     */
    public function create(array $data): AsaasCustomer
    {
        return $this->asaasCustomerRepository->create($data);
    }

    /**
     * Check if a customer exists.
     *
     * @param array $data
     * @return bool
     */
    public function customerExists(array $data): bool
    {
        return $this->asaasCustomerRepository->customerExists($data);
    }

    /**
     * Find customer by company ID.
     *
     * @param int $companyId
     * @return AsaasCustomer|null
     */
    public function findByCompanyId(int $companyId): ?AsaasCustomer
    {
        return $this->asaasCustomerRepository->findByCompanyId($companyId);
    }

    /**
     * Find customer by customer ID.
     *
     * @param int $customerId
     * @return AsaasCustomer|null
     */
    public function findByCustomerId(int $customerId): ?AsaasCustomer
    {
        return $this->asaasCustomerRepository->findByCustomerId($customerId);
    }

    /**
     * Integrate a customer to Asaas.
     *
     * @param AsaasCustomer $asaasCustomer
     * @param string|null $customApiKey
     * @return bool|string
     */
    public function integrateCustomerToAsaas(AsaasCustomer $asaasCustomer, ?string $customApiKey = null)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $baseUrl = AsaasConfigHelper::getBaseUrl();
            $apiKey = $customApiKey ?? AsaasConfigHelper::getApiKey();

            if (empty($apiKey)) {
                throw new \Exception('Asaas API key não configurada');
            }

            $response = $client->request('POST', $baseUrl . '/v3/customers', [
                'body' => json_encode([
                    'name' => $asaasCustomer->name,
                    'cpfCnpj' => $asaasCustomer->cpf_cnpj,
                ]),
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => $apiKey,
                    'content-type' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);

                if (isset($responseData['id'])) {
                    $asaasCustomer->asaas_customer_id = $responseData['id'];
                    $asaasCustomer->save();
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'integrateCustomerToAsaas', 'asaasCustomer' => $asaasCustomer]);
            return $e->getMessage();
        }
    }
}
