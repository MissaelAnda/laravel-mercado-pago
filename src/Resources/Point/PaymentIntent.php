<?php

namespace MissaelAnda\MercadoPago\Resources\Point;

use MissaelAnda\MercadoPago\Resources\Resource;

/**
 * @method static setAdditional_info(AdditionalInfo $additional_info)
 * @method static setAmount(int $amount)
 * @method static setDescription(string $description)
 * @method static setDeviceId(string $deviceId)
 * @method static setId(string $id)
 * @method static setPayment(Payment $payment)
 * @method static setPaymentMode(?string $payment_mode)
 * @method static setState(?string $state)
 */
class PaymentIntent extends Resource
{
    public AdditionalInfo $additionalInfo;
    public int $amount;
    public string $description;
    public string $deviceId;
    public string $id;
    public ?string $paymentMode;
    public ?string $state;
    public Payment $payment;
}
