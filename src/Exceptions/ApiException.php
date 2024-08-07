<?php

namespace MissaelAnda\MercadoPago\Exceptions;

class ApiException extends \Exception
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
}
