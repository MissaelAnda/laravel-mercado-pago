<?php

namespace MissaelAnda\MercadoPago\Clients;

use MissaelAnda\MercadoPago\Resources\Store;
use MissaelAnda\MercadoPago\Resources\Page;
use MissaelAnda\MercadoPago\Resources\PaginatedResult;
use MissaelAnda\MercadoPago\Utils;

class StoreClient extends Client
{
    const ALL = 'users/{user_id}/stores/search';
    const CREATE = 'users/{user_id}/stores';
    const GET = 'stores/{store_id}';
    const UPDATE_DELETE = 'users/{user_id}/stores/{id}';

    /**
     * @return PaginatedResult<Store>
     */
    public function all(?string $externalId = null, ?Page $page = null): PaginatedResult
    {
        $params = [
            'external_id' => $externalId,
        ];

        if ($page) {
            $params += $page->except('total');
        }

        $data = $this->get(
            static::replaceUriParams(self::ALL, $this->tenant->id()),
            Utils::filteredRequestArray($params),
        );

        return new PaginatedResult(Store::class, $data);
    }

    public function create(Store $store): Store
    {
        $data = $this->post(static::replaceUriParams(self::CREATE, $this->tenant->id()), $store->toArray());

        return $store->set($data);
    }

    public function find(int $storeId): ?Store
    {
        return $this->findResource(Store::class, static::replaceUriParams(self::GET, $storeId));
    }

    public function update(Store $store): Store
    {
        static::validate($store, 'id');

        $data = $this->put(static::replaceUriParams(self::UPDATE_DELETE, $this->tenant->id(), $store->id), $store->toArray());
        return $store->set($data);
    }

    public function destroy(Store|int $store): void
    {
        $store = static::validate($store, 'id');

        $this->delete(static::replaceUriParams(self::UPDATE_DELETE, $this->tenant->id(), $store));
    }
}
