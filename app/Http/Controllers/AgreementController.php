<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Invoice;
use App\Services\PdfService;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    public function index()
    {
        $agreements = Agreement::with('invoice.client')->latest()->paginate(10);
        return view('agreements.index', compact('agreements'));
    }

    public function create(Request $request)
    {
        if (!$request->has('invoice_id')) {
            $invoices = Invoice::doesntHave('agreement')->with(['client', 'project'])->latest()->get();
            return view('agreements.select_invoice', compact('invoices'));
        }

        $invoice = Invoice::with(['client', 'project'])->findOrFail($request->invoice_id);

        if ($invoice->agreement) {
            return redirect()->route('agreements.show', $invoice->agreement)
                ->with('info', 'Agreement already exists for this invoice.');
        }

        return view('agreements.create', compact('invoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'agreement_number' => 'required|string|unique:agreements',
            'agreement_date' => 'required|date',
            'provider_name' => 'required|string',
            'provider_address' => 'required|string',
            'provider_email' => 'required|email',
            'client_name' => 'required|string',
            'client_address' => 'required|string',
            'client_email' => 'required|email',
            'project_name' => 'required|string',
            'service_description' => 'required|string',
            'scope_of_work' => 'required|string',
            'total_price' => 'required|numeric',
            'payment_terms' => 'required|string',
            'start_date' => 'required|date',
            'estimated_completion_date' => 'required|date',
        ]);

        $agreement = Agreement::create($validated);

        return redirect()->route('agreements.show', $agreement)
            ->with('success', 'Agreement created successfully.');
    }

    public function show(Agreement $agreement)
    {
        return view('agreements.show', compact('agreement'));
    }

    public function edit(Agreement $agreement)
    {
        return view('agreements.edit', compact('agreement'));
    }

    public function update(Request $request, Agreement $agreement)
    {
        $validated = $request->validate([
            'agreement_number' => 'required|string|unique:agreements,agreement_number,' . $agreement->id,
            'agreement_date' => 'required|date',
            'provider_name' => 'required|string',
            'provider_address' => 'required|string',
            'provider_email' => 'required|email',
            'client_name' => 'required|string',
            'client_address' => 'required|string',
            'client_email' => 'required|email',
            'project_name' => 'required|string',
            'service_description' => 'required|string',
            'scope_of_work' => 'required|string',
            'total_price' => 'required|numeric',
            'payment_terms' => 'required|string',
            'start_date' => 'required|date',
            'estimated_completion_date' => 'required|date',
            'status' => 'required|in:draft,issued,signed,cancelled',
        ]);

        $agreement->update($validated);

        return redirect()->route('agreements.show', $agreement)
            ->with('success', 'Agreement updated successfully.');
    }

    public function destroy(Agreement $agreement)
    {
        $agreement->delete();

        return redirect()->route('agreements.index')
            ->with('success', 'Agreement deleted successfully.');
    }

    public function pdf(Agreement $agreement, PdfService $pdfService)
    {
        return $pdfService->generateAgreementPdf($agreement);
    }
}
