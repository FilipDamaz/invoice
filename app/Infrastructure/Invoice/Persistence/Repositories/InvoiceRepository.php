<?php
// app/Infrastructure/Persistence/Repositories/InvoiceRepository.php
namespace App\Infrastructure\Invoice\Persistence\Repositories;

use App\Domain\Invoice\Entities\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\UuidInterface;
use Illuminate\Database\Eloquent\Collection;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function findWithDetails(string $id): ?Invoice
    {
        return Invoice::with(['company', 'company', 'products'])->find($id);
    }

    public function findAll(): Collection
    {
        return Invoice::all();
    }

    public function save(Invoice $invoice): void
    {
        \DB::table('invoices')->where('id', $invoice->getId())->update(['status' => $invoice->getStatus()]);
    }

    public function findById(UuidInterface $id): ?Invoice
    {
        return $this->invoiceModel->find($id);
    }
}
