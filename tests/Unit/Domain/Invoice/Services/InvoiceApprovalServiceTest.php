<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface; // Use the interface
use App\Domain\Invoice\Services\InvoiceApprovalService;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Domain\Enums\StatusEnum;
use PHPUnit\Framework\TestCase;

class InvoiceApprovalServiceTest extends TestCase
{
    private InvoiceApprovalService $invoiceApprovalService;
    private ApprovalFacadeInterface $approvalFacadeMock;
    private InvoiceRepositoryInterface $invoiceRepositoryMock; // Ensure this is the interface

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the ApprovalFacadeInterface
        $this->approvalFacadeMock = $this->createMock(ApprovalFacadeInterface::class);

        // Mock the Invoice repository interface
        $this->invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class); // Correctly mock the interface

        // Initialize InvoiceApprovalService with mocks
        $this->invoiceApprovalService = new InvoiceApprovalService(
            $this->approvalFacadeMock,
            $this->invoiceRepositoryMock
        );
    }

    public function testApproveInvoice(): void
    {
        $invoiceId = 'some-uuid'; // Replace with a valid UUID
        $invoice = new Invoice(); // Initialize with necessary properties
        $invoice->setId($invoiceId); // Ensure the ID is set

        // Mock the behavior of the invoice repository
        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn($invoice);

        // Mock the behavior of the approval facade
        $this->approvalFacadeMock
            ->expects($this->once())
            ->method('approve')
            ->with($this->isInstanceOf(ApprovalDto::class))
            ->willReturn(true);

        // Call the method under test
        $result = $this->invoiceApprovalService->approveInvoice($invoiceId);
        $this->assertTrue($result);
    }

    public function testRejectInvoice(): void
    {
        $invoiceId = 'some-uuid'; // Replace with a valid UUID
        $invoice = new Invoice(); // Initialize with necessary properties
        $invoice->setId($invoiceId); // Ensure the ID is set

        // Mock the behavior of the invoice repository
        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn($invoice);

        // Mock the behavior of the approval facade
        $this->approvalFacadeMock
            ->expects($this->once())
            ->method('reject')
            ->with($this->isInstanceOf(ApprovalDto::class))
            ->willReturn(true);

        // Call the method under test
        $result = $this->invoiceApprovalService->rejectInvoice($invoiceId);
        $this->assertTrue($result);
    }

    public function testApproveInvoiceThrowsExceptionWhenInvoiceNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invoice not found');

        $invoiceId = 'some-uuid';
        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn(null); // Simulate not found

        $this->invoiceApprovalService->approveInvoice($invoiceId);
    }

    public function testRejectInvoiceThrowsExceptionWhenInvoiceNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invoice not found');

        $invoiceId = 'some-uuid';
        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn(null); // Simulate not found

        $this->invoiceApprovalService->rejectInvoice($invoiceId);
    }
}
