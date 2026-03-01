<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Http\Requests\SignAgreementRequest;
use App\Services\AgreementService;
use App\Services\PdfService;
use Illuminate\Http\Request;

class ClientAgreementController extends Controller
{
    protected $agreementService;
    protected $pdfService;

    public function __construct(AgreementService $agreementService, PdfService $pdfService)
    {
        $this->agreementService = $agreementService;
        $this->pdfService = $pdfService;
    }

    public function show(Request $request, Agreement $agreement)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature link.');
        }

        return view('agreements.client.show', compact('agreement'));
    }

    public function sign(SignAgreementRequest $request, Agreement $agreement)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature link.');
        }

        if ($agreement->status === 'signed') {
            return redirect()->back()->with('error', 'This agreement is already signed.');
        }

        // Save signature image
        $path = $this->agreementService->saveSignature($request->signature, $agreement->agreement_number);

        $agreement->update([
            'status' => 'signed',
            'signed_at' => now(),
            'signature_path' => $path,
        ]);

        return redirect()->route('client.agreements.success', ['agreement' => $agreement->id])->with('success', 'Agreement successfully signed.');
    }

    public function success(Agreement $agreement)
    {
        return view('agreements.client.success', compact('agreement'));
    }

    public function downloadPdf(Request $request, Agreement $agreement)
    {
        // Allowed if signed or with valid signature
        if ($agreement->status !== 'signed' && !$request->hasValidSignature()) {
            abort(401, 'Unauthorized access.');
        }

        $pdf = $this->pdfService->generateAgreementPdf($agreement);
        return $pdf->download($agreement->agreement_number . '.pdf');
    }
}
