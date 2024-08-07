<?php

namespace MissaelAnda\MercadoPago\Resources;

use Carbon\CarbonImmutable;

/**
 * @method static setId(int $id)
 * @method static setUserId(int $userId)
 * @method static setCategory(int $category)
 * @method static setStoreId(int $storeId)
 * @method static setFixedAmount(bool $fixedAmount)
 * @method static setName(string $name)
 * @method static setExternalId(string $externalId)
 * @method static setUuid(string $uuid)
 * @method static setExternalStoreId(string $externalStoreId)
 * @method static setUrl(string $url)
 * @method static setSite(string $site)
 * @method static setQrCode(string $qrCode)
 * @method static setStatus(string $status)
 * @method static setQr(QR $qr)
 * @method static setDateCreated(CarbonImmutable $dateCreated)
 * @method static setDateLastUpdated(CarbonImmutable $dateLastUpdated)
 */
class Pos extends Resource
{
    public int $id;
    public int $userId;
    public int $category;
    public int $storeId;
    public bool $fixedAmount;
    public string $name;
    public string $externalId;
    public string $uuid;
    public string $externalStoreId;
    public string $url;
    public string $site;
    public string $qrCode;
    public string $status;
    public QR $qr;
    public CarbonImmutable $dateCreated;
    public CarbonImmutable $dateLastUpdated;

    public function setDateCreated(string $value): static
    {
        $this->dateCreated = CarbonImmutable::parse($value);

        return $this;
    }

    public function setDateLastUpdated(string $value): static
    {
        $this->dateLastUpdated = CarbonImmutable::parse($value);

        return $this;
    }
}
