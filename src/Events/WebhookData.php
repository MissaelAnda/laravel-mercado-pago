<?php

namespace MissaelAnda\MercadoPago\Events;

use Carbon\CarbonImmutable;
use MissaelAnda\MercadoPago\Resources\Resource;

class WebhookData extends Resource
{
    /**
     * Notification ID
     */
    public string $id;

    /**
     * Indicates if the URL entered is valid.
     */
    public string $liveMode;

    /**
     * Type of notification received (payments, mp-connect, subscription, claim, automatic-payments, etc)
     */
    public string $type;

    /**
     * Resource creation date
     */
    public CarbonImmutable $dateCreated;

    /**
     * Vendor UserID
     */
    public int $userId;

    /**
     * Indicates if it is a duplicate notification or not
     */
    public string $apiVersion;

    /**
     * Type of notification received, indicating whether it is the update of a resource or the creation of a new
     */
    public string $action;

    /**
     * - id ID of the payment, merchant_order or claim
     */
    public Data $webhookData;

    /**
     * (delivery) Number of times a notification was sent
     */
    public string $attempts;

    /**
     * (delivery) Resource Creation Date
     */
    public string $received;

    /**
     * (delivery) Type of notification received, indicating whether this is an update to a feature or the creation of a new one
     * (claims) Type of notification received, indicating notifications related to claims made by sales
     */
    public string $resource;

    /**
     * (delivery) Notification sent date
     */
    public string $sent;

    /**
     * (delivery) Type of notification received
     */
    public string $topic;

    public function setData(array $data): static
    {
        $this->webhookData = new Data($data);
        return $this;
    }
}

class Data extends Resource
{
    /**
     * The resource ID
     */
    public string $id;

    /**
     * - (only present on action card.updated) Customer ID with updated card
     */
    public string $customerId;

    /**
     * - (only present on action card.updated) Updated card number
     */
    public string $newCardId;

    /**
     * - (only present on action card.updated) Old card number
     */
    public string $oldCardId;
}
