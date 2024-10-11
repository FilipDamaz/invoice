<?php

namespace App\Domain\Invoice\ValueObjects;

class Product
{
    private string $name;
    private int $quantity;
    private float $unitPrice;


    public function __construct(string $name, int $quantity, float $unitPrice)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    public function total(): float
    {
        return $this->quantity * $this->unitPrice;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'total' => $this->total(),
        ];
    }
}
