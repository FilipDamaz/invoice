<?php

declare(strict_types=1);

namespace App\Modules\Approval\Application;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Approval\Api\Events\EntityRejected;
use Illuminate\Contracts\Events\Dispatcher;
use LogicException;

final readonly class ApprovalFacade implements ApprovalFacadeInterface
{
    public function __construct(
        private Dispatcher $dispatcher
    ) {
    }

    public function approve(ApprovalDto $dto): true
    {

        // Validate for approval
       $this->validate($dto, 'approve');
       $this->dispatcher->dispatch(new EntityApproved($dto));

      return true;
    }

    public function reject(ApprovalDto $dto): true
    {
        // Validate for rejection
        $this->validate($dto, 'reject');
        $this->dispatcher->dispatch(new EntityRejected($dto));

        return true;
    }

    private function validate(ApprovalDto $dto, string $action): void
    {
        // Check if the current status is DRAFT, if not, throw exceptions based on action
        if ($dto->status !== StatusEnum::DRAFT) {
            if ($action === 'approve') {
                if ($dto->status === StatusEnum::REJECTED) {
                    throw new LogicException('Document rejected; you can\'t approve it now');
                } else {
                    throw new LogicException('Approval status is already assigned');
                }
            } elseif ($action === 'reject') {
                if ($dto->status === StatusEnum::APPROVED) {
                    throw new LogicException('Document already approved; you can\'t reject it now');
                } else {
                    throw new LogicException('Rejection status is already assigned');
                }
            }
        }
    }
}
