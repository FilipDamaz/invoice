<?php
// app/Domain/InvoiceModel/Services/InvoiceApprovalService.php
namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Entities\InvoiceModel;
use App\Domain\Invoice\Repositories\InvoiceRepositoryInterface; // This should be the interface
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

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
            throw new \InvalidArgumentException('InvoiceModel not found');
        }

        $approvalDto = $this->createApprovalDto($invoice);
        return $this->approvalFacade->approve($approvalDto);
    }

    public function rejectInvoice(UuidInterface $invoiceId): bool
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if (!$invoice) {
            throw new \InvalidArgumentException('InvoiceModel not found');
        }

        $approvalDto = $this->createApprovalDto($invoice);
        return $this->approvalFacade->reject($approvalDto);
    }

    private function createApprovalDto(InvoiceModel $invoice): ApprovalDto
    {
        $status = match ($invoice->status) {
            'approved' => StatusEnum::APPROVED,
            'rejected' => StatusEnum::REJECTED,
            default => StatusEnum::DRAFT, // Map default to DRAFT
        };
        $uuid = Uuid::fromString($invoice->getId());
        return new ApprovalDto(
            $uuid,
            $status,
            InvoiceModel::class
        );
    }
}
