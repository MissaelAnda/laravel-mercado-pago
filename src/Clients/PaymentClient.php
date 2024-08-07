<?php

namespace MissaelAnda\MercadoPago\Clients;

use Illuminate\Support\Carbon;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient as MpPaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Net\MPSearchRequest;
use MercadoPago\Resources\Payment;
use MissaelAnda\MercadoPago\Contracts\MercadoPagoTenant;
use MissaelAnda\MercadoPago\Exceptions\ApiException;
use MissaelAnda\MercadoPago\Resources\Page;
use MissaelAnda\MercadoPago\Resources\PaginatedResult;
use MissaelAnda\MercadoPago\Utils;

/**
 * @method Payment create(array $request)
 * @method Payment get(int $id)
 * @method Payment cancel(int $id)
 * @method Payment capture(int $id, ?float $amount)
 */
class PaymentClient extends Client
{
    protected MpPaymentClient $client;

    public function __construct(protected ?MercadoPagoTenant $tenant)
    {
        parent::__construct($tenant);
        $this->client = new MpPaymentClient();
    }

    /**
     * @param  'date_approved'|'date_created'|'date_last_updated'|'id'|'money_release_date'|null  $sort
     * @param  'date_created'|'date_last_updated'|'date_approved'|'money_release_date'|null  $range
     * @param  'desc'|'asc'|null  $order
     * @return PaginatedResult<Payment>
     */
    public function all(
        ?string $externalReference = null,
        ?string $sort = null,
        ?string $order = null,
        ?string $range = null,
        ?Carbon $from = null,
        ?Carbon $to = null,
        ?int $storeId = null,
        ?string $collectorId = null,
        ?string $payerId = null,
        ?Page $page = null
    ): PaginatedResult {
        $result = $this->__call('search', [new MPSearchRequest(
            $page?->limit,
            $page?->offset,
            Utils::filteredRequestArray([
                'sort' => $sort,
                'criteria' => $order,
                'external_reference' => $externalReference,
                'range' => $range,
                'begin_date' => $from?->toIso8601String(),
                'end_date' => $to?->toIso8601String(),
                'store_id' => $storeId,
                'collector.id' => $collectorId,
                'payer.id' => $payerId,
            ])
        )]);

        $paginated = new PaginatedResult(Payment::class);
        $paginated->setPaging(new Page([
            'total' => $result->paging->total,
            'limit' => $result->paging->limit,
            'offset' => $result->paging->offset,
        ]));
        $paginated->setResults($result->results);

        return $paginated;
    }

    public function get(int $id): ?Payment
    {
        try {
            return $this->__call('get', [$id]);
        } catch (ApiException $e) {
            if ($e->getCode() === 404) {
                return null;
            }

            throw $e;
        }
    }

    public function find(int $id): ?Payment
    {
        return $this->get($id);
    }

    public function __call($method, $params): mixed
    {
        if (!method_exists($this->client, $method)) {
            throw new \BadMethodCallException("The method $method does not exists.");
        }

        $params[] = new RequestOptions($this->accessToken);

        try {
            return $this->client->{$method}(...$params);
        } catch (MPApiException $e) {
            throw new ApiException($e->getApiResponse()->getContent(), $e->getStatusCode());
        }
    }
}
