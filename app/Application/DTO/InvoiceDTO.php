<?php
// app/Application/DTO/InvoiceDTO.php
namespace App\Application\DTO;

use App\Domain\Invoice\ValueObjects;

class InvoiceDTO
{
    public function __construct(
    public string $number,
    public string $date,
    public string $dueDate,
    public Company $company,
    public Company $billedCompany,
    public array $products, // array of Product ValueObjects
    public float $total
    ) {}
}
