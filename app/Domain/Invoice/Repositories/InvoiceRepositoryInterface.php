<?php
// app/Domain/InvoiceModel/Repositories/InvoiceRepositoryInterface.php

namespace App\Domain\Invoice\Repositories;

use App\Domain\Invoice\Entities\InvoiceModel;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface InvoiceRepositoryInterface
{
    public function findById(UuidInterface $id): ?InvoiceModel;
    public function findAll(): Collection;
    public function findWithDetails(string $id): ?InvoiceModel;
}
