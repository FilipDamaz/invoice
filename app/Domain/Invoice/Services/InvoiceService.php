<?php
// app/Domain/Invoice/Services/InvoiceService.php
namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface;
use App\Application\DTO\InvoiceDTO;
use App\Domain\Invoice\ValueObjects\Company;
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

        $company = new Company(
            $invoice->company->name,
            $invoice->company->street,
            $invoice->company->city,
            $invoice->company->zip,
            $invoice->company->phone
        );

        $billedCompany = new Company(
            $invoice->billedCompany->name,
            $invoice->billedCompany->street,
            $invoice->billedCompany->city,
            $invoice->billedCompany->zip,
            $invoice->billedCompany->phone,
            $invoice->billedCompany->email
        );

        $products = $invoice->products->map(function ($product) {
            return new Product(
                $product->name,
                $product->pivot->quantity,
                $product->price,
                $product->pivot->quantity * $product->price
            );
        });

        $total = $this->calculateTotal($products);
        return new InvoiceDTO(
            $invoice->number,
            $invoice->date->format('Y-m-d'),
            $invoice->due_date->format('Y-m-d'),
            $company,
            $billedCompany,
            $products->toArray(),
            $total
        );
    }

    private function calculateTotal($products): float
    {
        return $products->sum(fn(Product $product) => $product->total);
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
}
