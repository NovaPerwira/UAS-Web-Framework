<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Invoice;
use App\Models\AgreementTemplate;
use App\Services\AgreementService;
use App\Services\PdfService;
use App\Http\Requests\StoreAgreementRequest;
use App\Http\Requests\UpdateAgreementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AgreementController extends Controller
{
    protected $agreementService;
    protected $pdfService;

    public function __construct(AgreementService $agreementService, PdfService $pdfService)
    {
        $this->agreementService = $agreementService;
        $this->pdfService = $pdfService;
    }

    public function index()
    {
        $agreements = Agreement::with(['invoice', 'creator'])->latest()->paginate(10);
        return view('agreements.index', compact('agreements'));
    }

    public function create(Request $request)
    {
        $invoices = Invoice::doesntHave('agreement')->get();
        $templates = AgreementTemplate::all();
        $selectedInvoice = $request->get('invoice_id');

        return view('agreements.create', compact('invoices', 'templates', 'selectedInvoice'));
    }

    public function store(StoreAgreementRequest $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        $template = AgreementTemplate::findOrFail($request->template_id);

        $data = $this->agreementService->generateFromInvoice($invoice, $template);

        $data['agreement_number'] = 'AGR-' . date('Y') . '-' . strtoupper(uniqid());
        $data['created_by'] = auth()->id() ?? 1; // Fallback to 1 for testing if needed
        $data['status'] = 'draft';

        $agreement = Agreement::create($data);

        return redirect()->route('agreements.show', $agreement)->with('success', 'Agreement generated successfully.');
    }

    public function show(Agreement $agreement)
    {
        return view('agreements.show', compact('agreement'));
    }

    public function edit(Agreement $agreement)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $agreement);
        return view('agreements.edit', compact('agreement'));
    }

    public function update(UpdateAgreementRequest $request, Agreement $agreement)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $agreement);

        $agreement->update($request->validated());

        return redirect()->route('agreements.show', $agreement)->with('success', 'Agreement updated successfully.');
    }

    public function destroy(Agreement $agreement)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $agreement);
        $agreement->delete();

        return redirect()->route('agreements.index')->with('success', 'Agreement deleted successfully.');
    }

    public function send(Agreement $agreement)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $agreement); // Only if draft

        $agreement->update(['status' => 'sent']);

        // Generate signed URL for client
        $signedUrl = URL::signedRoute('client.agreements.show', ['agreement' => $agreement->id]);

        // Here you would normally send an email with the $signedUrl

        return redirect()->back()->with('success', 'Agreement marked as sent. Client link: ' . $signedUrl);
    }

    public function downloadPdf(Agreement $agreement)
    {
        $pdf = $this->pdfService->generateAgreementPdf($agreement);
        return $pdf->download($agreement->agreement_number . '.pdf');
    }
}
