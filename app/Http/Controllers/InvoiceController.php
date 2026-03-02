<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService,
        private PdfService $pdfService,
    ) {
    }

    public function index()
    {
        $invoices = Invoice::with(['agreement'])
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Direct invoice creation is disabled — use AgreementController::createInvoice
     * Redirect to agreements list with guidance.
     */
    public function create()
    {
        return redirect()
            ->route('agreements.index')
            ->with('info', 'To create an invoice, first open a Signed Agreement and use the "Create Invoice" button.');
    }

    /**
     * Direct invoice store is disabled — use AgreementController::storeInvoice
     */
    public function store(Request $request)
    {
        return redirect()
            ->route('agreements.index')
            ->with('info', 'To create an invoice, open a Signed Agreement and use the "Create Invoice" button.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['agreement', 'items', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        // Invoices generated from signed agreements are locked immediately.
        // Editing is not permitted after creation.
        return redirect()
            ->route('invoices.show', $invoice)
            ->with('error', 'Invoices cannot be edited after creation. They are bound to their parent agreement.');
    }

    public function update(Request $request, Invoice $invoice)
    {
        return redirect()
            ->route('invoices.show', $invoice)
            ->with('error', 'Invoice editing is not permitted.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Record a payment against an invoice.
     */
    public function addPayment(Request $request, Invoice $invoice)
    {
        if (in_array($invoice->status, [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED])) {
            return redirect()->back()->with('error', 'Cannot add payment to a paid or cancelled invoice.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->invoiceService->recordPayment($invoice, $validated);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Download Invoice PDF.
     */
    public function pdf(Invoice $invoice)
    {
        return $this->pdfService->generateInvoicePdf($invoice);
    }
}
