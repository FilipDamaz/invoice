<?php
// app/Domain/Invoice/Services/InvoiceApprovalService.php
namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface; // This should be the interface
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\UuidInterface;

class InvoiceApprovalService
{
    public function __construct(
        private ApprovalFacadeInterface $approvalFacade,
        private InvoiceRepositoryInterface $invoiceRepository // Ensure this is the interface
    ) {}

    public function approveInvoice(UuidInterface $invoiceId): bool
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if (!$invoice) {
            throw new \InvalidArgumentException('Invoice not found');
        }

        $approvalDto = $this->createApprovalDto($invoice);
        return $this->approvalFacade->approve($approvalDto);
    }

    public function rejectInvoice(UuidInterface $invoiceId): bool
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if (!$invoice) {
            throw new \InvalidArgumentException('Invoice not found');
        }

        $approvalDto = $this->createApprovalDto($invoice);
        return $this->approvalFacade->reject($approvalDto);
    }

    private function createApprovalDto(Invoice $invoice): ApprovalDto
    {
        return new ApprovalDto(
            $invoice->getId(),
            StatusEnum::DRAFT, // Assuming DRAFT as initial status
            Invoice::class
        );
    }
}
