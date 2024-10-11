<?php
// app/Infrastructure/Persistence/Repositories/InvoiceRepository.php
namespace App\Infrastructure\Invoice\Persistence\Repositories;

use App\Domain\Invoice\Entities\InvoiceModel;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;


class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function findWithDetails(string $id): ?InvoiceModel
    {
        return InvoiceModel::with(['company', 'company', 'products'])->find($id);
    }

    public function findAll(): Collection
    {
        return InvoiceModel::all();
    }

    public function save(InvoiceModel $invoice): void
    {
        \DB::table('invoices')->where('id', $invoice->getId())->update(['status' => $invoice->getStatus()]);
    }

    public function findById(UuidInterface $id): ?InvoiceModel
    {
        return InvoiceModel::find($id->toString());
    }
}
