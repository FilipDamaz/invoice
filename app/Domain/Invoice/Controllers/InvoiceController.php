<?php
// app/Domain/Invoice/Controllers/InvoiceController.php
namespace App\Domain\Invoice\Controllers;

use App\Domain\Invoice\Services\InvoiceService;
use App\Infrastructure\Controller;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
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
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json($invoiceDTO);
    }

        public function approve($id): JsonResponse
    {
        try {
            $this->invoiceService->approveInvoice($id);
            return response()->json(['message' => 'Invoice approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function reject($id): JsonResponse
    {
        try {
            $this->invoiceService->rejectInvoice($id);
            return response()->json(['message' => 'Invoice rejected successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
