<?php

namespace App\Services;

use App\Models\Agreement;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Generate Agreement PDF.
     *
     * After ISSUED, uses the frozen rendered_content snapshot.
     * Before ISSUED (draft), renders live from the blade template for preview.
     */
    public function generateAgreementPdf(Agreement $agreement)
    {
        if ($agreement->rendered_content && $agreement->status !== Agreement::DRAFT) {
            // Use the immutable legal snapshot (rendered_content is raw HTML)
            $pdf = Pdf::loadHTML($agreement->rendered_content);
        } else {
            // Live preview (draft only)
            $pdf = Pdf::loadView('agreements.pdf', compact('agreement'));
        }

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download($agreement->agreement_number . '-agreement.pdf');
    }

    /**
     * Generate Invoice PDF.
     *
     * Separate layout from agreements — shows payment details only.
     */
    public function generateInvoicePdf(Invoice $invoice)
    {
        $invoice->loadMissing(['agreement', 'items', 'payments']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $pdf->setPaper('a4', 'portrait');

        $filename = ($invoice->invoice_number ?? 'invoice-' . $invoice->id) . '.pdf';

        return $pdf->download($filename);
    }
}
