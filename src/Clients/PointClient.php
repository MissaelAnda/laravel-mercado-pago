<?php

namespace MissaelAnda\MercadoPago\Clients;

use MissaelAnda\MercadoPago\Exceptions\ApiException;
use MissaelAnda\MercadoPago\Resources\Page;
use MissaelAnda\MercadoPago\Resources\PaginatedResult;
use MissaelAnda\MercadoPago\Resources\Point;
use MissaelAnda\MercadoPago\Resources\Point\PaymentIntent;
use MissaelAnda\MercadoPago\Resources\Pos;
use MissaelAnda\MercadoPago\Resources\Store;

class PointClient extends Client
{
    const MAX_OFFSET = 50;
    const API = 'point/integration-api/devices/{device_id}';
    const CREATE_INTENT = 'point/integration-api/devices/{device_id}/payment-intents';
    const GET_INTENT = 'point/integration-api/payment-intents/{payment_intent_id}';

    /**
     * @return PaginatedResult<Point>
     */
    public function all(
        Store|int|null $store = null,
        Pos|int|null $pos = null,
        ?Page $page = null,
    ): PaginatedResult {
        $params = [
            'store_id' => $store instanceof Store ? $store->id : $store,
            'pos_id' => $pos instanceof Pos ? $pos->id : $pos,
        ];

        if ($page) {
            $params += $page->except('total');
        }

        $data = $this->get(static::replaceUriParams(self::API, ''), $params);
        return new PaginatedResult(Point::class, $data, 'devices');
    }

    /**
     * Searches for the specific point device by id iterating through all the tenant's point devices
     */
    public function find(string $point, Store|int|null $store = null, Pos|int|null $pos = null): ?Point
    {
        $currentIdx = 0;

        do {
            $page = new Page([
                'limit' => self::MAX_OFFSET,
                'offset' => $currentOffset = self::MAX_OFFSET * $currentIdx,
            ]);
            $response = $this->all($store, $pos, $page);

            foreach ($response->results as $p) {
                if ($p->id == $point) {
                    return $p;
                }
            }

            $currentIdx++;
        } while ($response->paging->total > $currentOffset + self::MAX_OFFSET);

        return null;
    }

    public function update(Point $point): Point
    {
        static::validate($point, 'id');
        static::validate($point, 'operatingMode');

        $data = $this->patch(static::replaceUriParams(self::API, $point->id), $point->only('operating_mode'));
        return $point->set($data);
    }

    public function createPaymentIntent(Point|string $pointId, PaymentIntent $intent): PaymentIntent
    {
        $pointId = static::validate($pointId, 'id');

        $data = $this->post(
            static::replaceUriParams(self::CREATE_INTENT, $pointId),
            $intent->only('additional_info', 'amount'),
        );

        return $intent->set($data);
    }

    public function findPaymentIntent(string $id): ?PaymentIntent
    {
        return $this->findResource(PaymentIntent::class, static::replaceUriParams(self::GET_INTENT, $id));
    }
}
