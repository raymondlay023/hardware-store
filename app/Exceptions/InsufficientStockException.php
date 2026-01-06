<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    protected $productName;
    protected $requestedQuantity;
    protected $availableStock;

    public function __construct(string $productName, int $requestedQuantity, int $availableStock)
    {
        $this->productName = $productName;
        $this->requestedQuantity = $requestedQuantity;
        $this->availableStock = $availableStock;

        parent::__construct(
            "Insufficient stock for {$productName}. Requested: {$requestedQuantity}, Available: {$availableStock}"
        );
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getRequestedQuantity(): int
    {
        return $this->requestedQuantity;
    }

    public function getAvailableStock(): int
    {
        return $this->availableStock;
    }
}
