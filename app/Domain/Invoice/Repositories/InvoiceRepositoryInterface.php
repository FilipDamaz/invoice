<?php
// app/Domain/Invoice/Repositories/InvoiceRepositoryInterface.php
namespace App\Domain\Invoice\Repositories;

use App\Domain\Invoice\Entities\Invoice;
use Illuminate\Database\Eloquent\Collection;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): void;
    public function findAll(): Collection;
}
