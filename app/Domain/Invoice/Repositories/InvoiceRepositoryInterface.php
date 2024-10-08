<?php
// app/Domain/Invoice/Repositories/InvoiceRepositoryInterface.php

namespace App\Domain\Invoice\Repositories;

use App\Domain\Invoice\Entities\Invoice;
use Ramsey\Uuid\UuidInterface;

interface InvoiceRepositoryInterface
{
    public function findById(UuidInterface $id): ?Invoice; // Use the correct type hint
    // other method declarations...
}
