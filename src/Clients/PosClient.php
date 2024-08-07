<?php

namespace MissaelAnda\MercadoPago\Clients;

use MissaelAnda\MercadoPago\Resources\Pos;
use MissaelAnda\MercadoPago\Resources\Page;
use MissaelAnda\MercadoPago\Resources\PaginatedResult;
use MissaelAnda\MercadoPago\Resources\Store;
use MissaelAnda\MercadoPago\Utils;

class PosClient extends Client
{
    const API = 'pos/{id}';

    /**
     * @return PaginatedResult<Pos>
     */
    public function all(
        Store|int|null $store = null,
        ?string $externalStoreId = null,
        ?string $externalId = null,
        ?string $category = null,
        ?Page $page = null,
    ): PaginatedResult {
        $params = Utils::toRequestArray(compact(
            'externalStoreId',
            'externalId',
            'category',
        ) + ['store_id' => $store instanceof Store ? $store->id : $store]);

        if ($page) {
            $params += $page->except('total');
        }

        $data = $this->get(static::replaceUriParams(self::API, ''), $params);

        return new PaginatedResult(Pos::class, $data);
    }

    public function find(int $id): ?Pos
    {
        return $this->findResource(Pos::class, static::replaceUriParams(self::API, $id));
    }

    public function create(Pos $pos): Pos
    {
        $data = $this->post(static::replaceUriParams(self::API, ''), $pos->toArray());

        return $pos->set($data);
    }

    public function update(Pos $pos): Pos
    {
        static::validate($pos, 'id');

        $data = $this->put(static::replaceUriParams(self::API, $pos->id), $pos->toArray());
        return $pos->set($data);
    }

    public function destroy(Pos|int $pos): void
    {
        $pos = static::validate($pos, 'id');

        $this->delete(static::replaceUriParams(self::API, $pos));
    }
}
