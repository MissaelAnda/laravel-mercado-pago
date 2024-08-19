<?php

namespace MissaelAnda\MercadoPago\Exceptions;

class ApiException extends \Exception implements \JsonSerializable
{
    /**
     * The error type
     */
    public ?string $error = null;

    public function __construct(protected array $data, int $code)
    {
        $this->error = $data['error'] ?? null;
        parent::__construct($data['message'] ?? '', $code);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
