<?php

namespace App\Services;

use App\Models\Agreement;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Generate PDF for a given agreement.
     *
     * @param Agreement $agreement
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateAgreementPdf(Agreement $agreement)
    {
        $agreement->load('invoice.client');

        $pdf = Pdf::loadView('pdf.agreement', compact('agreement'));

        // return $pdf->download($agreement->agreement_number . '.pdf');
        // We just return the PDF instance so the controller can decide to download or stream.
        return $pdf;
    }
}
