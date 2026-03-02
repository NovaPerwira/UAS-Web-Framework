<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Services\AgreementService;
use App\Services\InvoiceService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AgreementController extends Controller
{
    public function __construct(
        private AgreementService $agreementService,
        private InvoiceService $invoiceService,
        private PdfService $pdfService,
    ) {
    }

    // ─────────────────────────────────────────────────────────
    // AGREEMENT CRUD
    // ─────────────────────────────────────────────────────────

    public function index()
    {
        $agreements = Agreement::withCount('invoices')
            ->latest()
            ->paginate(10);

        return view('agreements.index', compact('agreements'));
    }

    public function create()
    {
        // Standalone creation — no invoice dependency
        return view('agreements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agreement_number' => 'nullable|string|unique:agreements,agreement_number',
            'agreement_date' => 'required|date',
            'provider_name' => 'required|string|max:255',
            'provider_address' => 'required|string',
            'provider_email' => 'required|email|max:255',
            'client_name' => 'required|string|max:255',
            'client_address' => 'required|string',
            'client_email' => 'required|email|max:255',
            'project_name' => 'required|string|max:255',
            'service_description' => 'required|string',
            'scope_of_work' => 'required|string',
            'total_value' => 'required|numeric|min:0',
            'payment_terms' => 'required|string',
            'start_date' => 'required|date',
            'estimated_completion_date' => 'required|date|after_or_equal:start_date',
        ]);

        $agreement = $this->agreementService->create($validated);

        return redirect()
            ->route('agreements.show', $agreement)
            ->with('success', 'Agreement created successfully.');
    }

    public function show(Agreement $agreement)
    {
        $agreement->load('invoices');
        return view('agreements.show', compact('agreement'));
    }

    public function edit(Agreement $agreement)
    {
        if (!$agreement->canEdit()) {
            return redirect()
                ->route('agreements.show', $agreement)
                ->with('error', 'This agreement cannot be edited — it has already been issued.');
        }

        return view('agreements.edit', compact('agreement'));
    }

    public function update(Request $request, Agreement $agreement)
    {
        $validated = $request->validate([
            'agreement_date' => 'required|date',
            'provider_name' => 'required|string|max:255',
            'provider_address' => 'required|string',
            'provider_email' => 'required|email|max:255',
            'client_name' => 'required|string|max:255',
            'client_address' => 'required|string',
            'client_email' => 'required|email|max:255',
            'project_name' => 'required|string|max:255',
            'service_description' => 'required|string',
            'scope_of_work' => 'required|string',
            'total_value' => 'required|numeric|min:0',
            'payment_terms' => 'required|string',
            'start_date' => 'required|date',
            'estimated_completion_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $this->agreementService->update($agreement, $validated);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route('agreements.show', $agreement)
            ->with('success', 'Agreement updated successfully.');
    }

    public function destroy(Agreement $agreement)
    {
        try {
            $this->agreementService->delete($agreement);
        } catch (ValidationException $e) {
            return redirect()
                ->route('agreements.show', $agreement)
                ->with('error', $e->errors()['agreement'][0] ?? 'Cannot delete this agreement.');
        }

        return redirect()
            ->route('agreements.index')
            ->with('success', 'Agreement deleted successfully.');
    }

    // ─────────────────────────────────────────────────────────
    // STATUS TRANSITIONS
    // ─────────────────────────────────────────────────────────

    public function transition(Request $request, Agreement $agreement)
    {
        $validated = $request->validate([
            'status' => 'required|in:issued,signed,cancelled',
        ]);

        try {
            $this->agreementService->transition($agreement, $validated['status']);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->with('error', $e->errors()['status'][0] ?? 'Invalid status transition.');
        }

        $verbs = [
            'issued' => 'issued',
            'signed' => 'signed',
            'cancelled' => 'cancelled',
        ];
        $verb = $verbs[$validated['status']] ?? 'updated';

        return redirect()
            ->route('agreements.show', $agreement)
            ->with('success', "Agreement successfully {$verb}. " . ($validated['status'] === 'issued' ? 'The document has been frozen.' : ''));
    }

    // ─────────────────────────────────────────────────────────
    // INVOICE CREATION UNDER AGREEMENT
    // ─────────────────────────────────────────────────────────

    public function createInvoice(Agreement $agreement)
    {
        if (!$agreement->canCreateInvoice()) {
            return redirect()
                ->route('agreements.show', $agreement)
                ->with('error', 'Invoices can only be created under a SIGNED agreement.');
        }

        return view('invoices.create', compact('agreement'));
    }

    public function storeInvoice(Request $request, Agreement $agreement)
    {
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_rate' => 'numeric|min:0|max:100',
            'discount_amount' => 'numeric|min:0',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate amount_due for guard check
        $items = $validated['items'];
        $subtotal = array_sum(array_map(fn($i) => $i['quantity'] * $i['unit_price'], $items));
        $taxAmount = $subtotal * (($validated['tax_rate'] ?? 0) / 100);
        $discount = $validated['discount_amount'] ?? 0;
        $validated['amount_due'] = $subtotal + $taxAmount - $discount;

        try {
            $invoice = $this->invoiceService->create($agreement, $validated);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully under agreement ' . $agreement->agreement_number . '.');
    }

    // ─────────────────────────────────────────────────────────
    // PDF
    // ─────────────────────────────────────────────────────────

    public function pdf(Agreement $agreement)
    {
        return $this->pdfService->generateAgreementPdf($agreement);
    }
}
