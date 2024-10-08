<?php
// app/Domain/Invoice/Services/InvoiceService.php
namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface;
use App\Application\DTO\InvoiceDTO;
use Exception;

class InvoiceService
{
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getAllInvoices(): array
    {
        return $this->invoiceRepository->findAll()->map(function ($invoice) {
            return [
                'id' => $invoice->id, // Directly accessing Eloquent properties
                'number' => $invoice->number,
            ];
        })->toArray();
    }


    public function getInvoice(string $id): ?InvoiceDTO
    {
        $invoice = $this->invoiceRepository->findWithDetails($id);
        if (!$invoice) {
            return null;
        }
        $InvoiceDTO = $this->generateInvoiceDTO($invoice);
        return $InvoiceDTO;
    }

    private function generateInvoiceDTO(Invoice $invoice): InvoiceDTO
    {
        return new InvoiceDTO(
            $invoice->number,
            $invoice->date->format('Y-m-d'),
            $invoice->due_date->format('Y-m-d'),
            [
                'name' => $invoice->company->name,
                'street' => $invoice->company->street,
                'city' => $invoice->company->city,
                'zip' => $invoice->company->zip,
                'phone' => $invoice->company->phone,
            ],
            [
                'name' => $invoice->billedCompany->name,
                'street' => $invoice->billedCompany->street,
                'city' => $invoice->billedCompany->city,
                'zip' => $invoice->billedCompany->zip,
                'phone' => $invoice->billedCompany->phone,
                'email' => $invoice->billedCompany->email,
            ],
            $this->getProductsArray($invoice->products), // Call the new method to handle products
            $this->calculateTotal($invoice->products) // Assuming you have a method to calculate total
        );
    }

    private function getProductsArray($products): array
    {
        return $products->map(function ($product) {
            return [
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
                'unit_price' => $product->price,
                'total' => $product->pivot->quantity * $product->price,
            ];
        })->toArray(); // Convert the collection to an array
    }
    private function calculateTotal($products): float
    {
        return $products->sum(function ($product) {
            return $product->pivot->quantity * $product->price; // Calculate total for each product
        });
    }

    public function approveInvoice(string $id): void
    {
        $invoice = $this->invoiceRepository->find($id);
        if (!$invoice) {
            throw new Exception("Invoice not found");
        }

        $invoice->approve();
        $this->invoiceRepository->save($invoice);
    }

    public function rejectInvoice(string $id): void
    {
        $invoice = $this->invoiceRepository->find($id);
        if (!$invoice) {
            throw new Exception("Invoice not found");
        }

        $invoice->reject();
        $this->invoiceRepository->save($invoice);
    }
}
