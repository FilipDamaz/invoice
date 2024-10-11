<?php

namespace App\Domain\Invoice\Controllers;

use App\Domain\Invoice\Services\InvoiceService;
use App\Domain\Invoice\Services\InvoiceApprovalService;
use App\Infrastructure\Controller;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;

class InvoiceController extends Controller
{
    private InvoiceService $invoiceService;
    private InvoiceApprovalService $invoiceApprovalService;

    public function __construct(InvoiceService $invoiceService, InvoiceApprovalService $invoiceApprovalService)
    {
        $this->invoiceService = $invoiceService;
        $this->invoiceApprovalService = $invoiceApprovalService;
    }

    public function index(): JsonResponse
    {
        $invoices = $this->invoiceService->getAllInvoices();
        return response()->json($invoices);
    }

    public function show($id): JsonResponse
    {

        $invoiceDTO = $this->invoiceService->getInvoice($id);
        if (!$invoiceDTO) {
            return response()->json(['error' => 'InvoiceModel not found'], 404);
        }

        return response()->json($invoiceDTO);
    }

    public function approve($id)
    {
        try {
            $uuid = Uuid::fromString($id);
            $this->invoiceApprovalService->approveInvoice($uuid);
            return response()->json(['message' => 'InvoiceModel approved successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function reject($id)
    {
        try {
            $uuid = Uuid::fromString($id);
            $this->invoiceApprovalService->rejectInvoice($uuid);
            return response()->json(['message' => 'InvoiceModel rejected successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
