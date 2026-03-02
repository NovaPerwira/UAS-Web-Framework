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
     * @return \Barryvdh\DomPDF\PDF|\Illuminate\Http\Response
     */
    public function generateAgreementPdf(Agreement $agreement)
    {
        $pdf = Pdf::loadView('agreements.pdf', compact('agreement'));

        // Return the downloaded PDF instance
        return $pdf->download($agreement->agreement_number . '.pdf');
    }
}
