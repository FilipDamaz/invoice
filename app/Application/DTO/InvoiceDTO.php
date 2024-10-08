<?php
// app/Application/DTO/InvoiceDTO.php
namespace App\Application\DTO;

class InvoiceDTO
{
    public function __construct(
        public string $number,
        public string $date,
        public string $due_date,
        public array $company,
        public array $billedCompany,
        public array $products,
        public float $total_price
    ) {}
}
