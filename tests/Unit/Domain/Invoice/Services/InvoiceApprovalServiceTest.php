<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\InvoiceModel;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface;
use App\Domain\Invoice\Services\InvoiceApprovalService;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Domain\Enums\StatusEnum;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class InvoiceApprovalServiceTest extends TestCase
{
    private InvoiceApprovalService $invoiceApprovalService;
    private ApprovalFacadeInterface $approvalFacadeMock;
    private InvoiceRepositoryInterface $invoiceRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();


        $this->approvalFacadeMock = $this->createMock(ApprovalFacadeInterface::class);
        $this->invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);

        $this->invoiceApprovalService = new InvoiceApprovalService(
            $this->approvalFacadeMock,
            $this->invoiceRepositoryMock
        );
    }

    public function testApproveInvoice(): void
    {

        $invoiceId = Uuid::uuid4();
        $invoiceMock = $this->createMock(InvoiceModel::class);

        $invoiceMock->method('getId')->willReturn($invoiceId->toString());

        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn($invoiceMock);

        $this->approvalFacadeMock
            ->expects($this->once())
            ->method('approve')
            ->with($this->isInstanceOf(ApprovalDto::class))
            ->willReturn(true);

        $result = $this->invoiceApprovalService->approveInvoice($invoiceId);
        $this->assertTrue($result);
    }

    public function testRejectInvoice(): void
    {
        $invoiceId = Uuid::uuid4();
        $invoiceMock = $this->createMock(InvoiceModel::class);

        $invoiceMock->method('getId')->willReturn($invoiceId->toString());

        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn($invoiceMock);

        $this->approvalFacadeMock
            ->expects($this->once())
            ->method('reject')
            ->with($this->isInstanceOf(ApprovalDto::class))
            ->willReturn(true);

        $result = $this->invoiceApprovalService->rejectInvoice($invoiceId);
        $this->assertTrue($result);
    }

    public function testApproveInvoiceThrowsExceptionWhenInvoiceNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $invoiceId = Uuid::uuid4();

        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn(null);

        $this->invoiceApprovalService->approveInvoice($invoiceId);
    }

    public function testRejectInvoiceThrowsExceptionWhenInvoiceNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $invoiceId = Uuid::uuid4();

        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($invoiceId)
            ->willReturn(null);

        $this->invoiceApprovalService->rejectInvoice($invoiceId);
    }
}
