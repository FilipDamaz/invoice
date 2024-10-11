<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\CompanyModel as InvoiceCompany; // Use the correct namespace for the Entity
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface;
use App\Domain\Invoice\Services\InvoiceService;
use App\Application\DTO\InvoiceDTO;
use App\Domain\Invoice\Entities\InvoiceModel; // Make sure this namespace is correct
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    private InvoiceService $invoiceService;
    private InvoiceRepositoryInterface $invoiceRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $this->invoiceService = new InvoiceService($this->invoiceRepositoryMock);
    }

    public function testGetAllInvoicesReturnsArrayOfInvoices(): void
    {
        $invoices = collect([
            (object)[
                'id' => '1',
                'number' => 'INV-0001',
            ],
            (object)[
                'id' => '2',
                'number' => 'INV-0002',
            ]
        ]);

        $this->invoiceRepositoryMock
            ->method('findAll')
            ->willReturn($invoices);

        $result = $this->invoiceService->getAllInvoices();

        $this->assertCount(2, $result);
        $this->assertEquals('INV-0001', $result[0]['number']);
        $this->assertEquals('INV-0002', $result[1]['number']);
    }

    public function testGetInvoiceReturnsInvoiceDTO(): void
    {
        $company = $this->createMock(InvoiceCompany::class);
        $billedCompany = $this->createMock(InvoiceCompany::class);

        // Mocking CompanyModel methods...
        $company->method('getId')->willReturn('company-id-1');
        $company->method('getName')->willReturn('CompanyModel A');
        $company->method('getStreet')->willReturn('123 Main St');
        $company->method('getCity')->willReturn('City A');
        $company->method('getZip')->willReturn('12345');
        $company->method('getPhone')->willReturn('123-456-7890');
        $company->method('getEmail')->willReturn('contact@companya.com');

        $billedCompany->method('getId')->willReturn('company-id-2');
        $billedCompany->method('getName')->willReturn('Billed CompanyModel A');
        $billedCompany->method('getStreet')->willReturn('456 Elm St');
        $billedCompany->method('getCity')->willReturn('City B');
        $billedCompany->method('getZip')->willReturn('54321');
        $billedCompany->method('getPhone')->willReturn('098-765-4321');
        $billedCompany->method('getEmail')->willReturn('contact@billedcompanya.com');

        $invoice = $this->createMock(InvoiceModel::class);

        // Set up the mock to return specific values when its methods are called
        $invoice->method('getId')->willReturn('0b658810-a9cf-4ac2-a376-7315e9deb09c');
        $invoice->method('getNumber')->willReturn('INV-0001');
        $invoice->method('getDate')->willReturn('2024-10-01');
        $invoice->method('getDueDate')->willReturn('2024-10-31');

        // Create a collection of products for the invoice
        $products = collect([
            (object)[
                'name' => 'ProductModel 1',
                'pivot' => (object)[
                    'quantity' => 2,
                ],
                'price' => 10.0,
            ],
            (object)[
                'name' => 'ProductModel 2',
                'pivot' => (object)[
                    'quantity' => 1,
                ],
                'price' => 20.0,
            ]
        ]);

        // Set up the products method to return the collection
        $invoice->method('getProducts')->willReturn($products);
        $invoice->method('getCompany')->willReturn($company);
        $invoice->method('getBilledCompany')->willReturn($billedCompany);

        // Mock the findWithDetails method to return the InvoiceModel mock
        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findWithDetails')
            ->with('0b658810-a9cf-4ac2-a376-7315e9deb09c')
            ->willReturn($invoice);

        // Call the getInvoice method on the InvoiceService
        $result = $this->invoiceService->getInvoice('0b658810-a9cf-4ac2-a376-7315e9deb09c');

        // Assert that the result is an instance of InvoiceDTO
        $this->assertInstanceOf(InvoiceDTO::class, $result);

        // Assert that the properties of the result match the expected values
        $this->assertEquals('INV-0001', $result->number);
        $this->assertEquals('2024-10-01', $result->date);
        $this->assertEquals('2024-10-31', $result->dueDate);
        $this->assertEquals(40.0, $result->total); // Ensure this is a float
    }


    public function testGetInvoiceReturnsNullWhenInvoiceNotFound(): void
    {
        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findWithDetails')
            ->with('non-existent-id')
            ->willReturn(null);

        $result = $this->invoiceService->getInvoice('non-existent-id');

        $this->assertNull($result);
    }
}
