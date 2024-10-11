<?php

namespace App\Application\DTO;



use App\Domain\Invoice\ValueObjects\Company;

class InvoiceDTO
{
    public function __construct(
    public string $id,
    public string $number,
    public string $date,
    public string $dueDate,
    public Company $company,
    public Company $billedCompany,
    public array $products, // array of ProductModel ValueObjects
    public float $total
    ) {}
}
