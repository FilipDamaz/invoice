<?php
// app/Domain/InvoiceModel/Services/InvoiceService.php
namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\InvoiceModel;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface;
use App\Application\DTO\InvoiceDTO;
use App\Domain\Invoice\ValueObjects\Company;
use App\Domain\Invoice\ValueObjects\Product;
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

    private function generateInvoiceDTO(InvoiceModel $invoice): InvoiceDTO
    {

        $company = new Company(
            $invoice->getCompany()->getName(),
            $invoice->getCompany()->getStreet(),
            $invoice->getCompany()->getCity(),
            $invoice->getCompany()->getZip(),
            $invoice->getCompany()->getPhone()
        );

        $billedCompany = new Company(
            $invoice->getBilledCompany()->getName(),
            $invoice->getBilledCompany()->getStreet(),
            $invoice->getBilledCompany()->getCity(),
            $invoice->getBilledCompany()->getZip(),
            $invoice->getBilledCompany()->getPhone(),
            $invoice->getBilledCompany()->getEmail()
        );

        $products = $invoice->getProducts()->map(function ($product) {
            return new Product(
                $product->name,
                $product->pivot->quantity,
                $product->price,
            );
        });

        $total = $this->calculateTotal($products);

        // Map ProductModel objects to arrays using jsonSerialize
        $productsArray = $products->map(function ($product) {
            return $product->jsonSerialize();
        });

        return new InvoiceDTO(
            $invoice->getId(),
            $invoice->getNumber(),
            $invoice->getDate(),
            $invoice->getDueDate(),
            $company,
            $billedCompany,
            $productsArray->toArray(),
            $total
        );
    }

    private function calculateTotal($products): float
    {
        return $products->sum(fn(Product $product) => $product->total());
    }
}
