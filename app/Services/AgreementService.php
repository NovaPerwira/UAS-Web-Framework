<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\AgreementTemplate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgreementService
{
    protected $templateEngine;

    public function __construct(TemplateEngineService $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * Generate agreement data from an invoice and a template.
     */
    public function generateFromInvoice(Invoice $invoice, AgreementTemplate $template): array
    {
        $invoice->load(['client', 'items']);

        // Format items as a simple text list
        $servicesList = $invoice->items->map(function ($item) {
            return "- {$item->description} (Rp " . number_format($item->total_price, 0, ',', '.') . ")";
        })->implode("\n");

        $data = [
            'client_name' => $invoice->client->name,
            'client_email' => $invoice->client->email,
            'company_name' => $invoice->client->company_name ?? $invoice->client->name,
            'price' => $invoice->grand_total,
            'formatted_price' => 'Rp ' . number_format((float) $invoice->grand_total, 0, ',', '.'),
            'service_description' => $servicesList,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'), // Default 30 days
        ];

        $renderedScope = $this->templateEngine->render($template->content, $data);

        return [
            'invoice_id' => $invoice->id,
            'client_name' => $data['client_name'],
            'client_email' => $data['client_email'],
            'company_name' => $data['company_name'],
            'price' => $data['price'],
            'service_description' => $servicesList,
            'scope_of_work' => $renderedScope,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'payment_terms' => "Lunas setelah invoice ditagihkan.", // Default
        ];
    }

    /**
     * Save base64 signature image to storage
     */
    public function saveSignature(string $base64Image, string $agreementNumber): string
    {
        // Remove data URI scheme (e.g. data:image/png;base64,)
        $imageParts = explode(";base64,", $base64Image);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1] ?? 'png';

        $imageBase64 = base64_decode($imageParts[1]);

        $filename = 'signatures/' . $agreementNumber . '_' . Str::random(10) . '.' . $imageType;

        Storage::disk('public')->put($filename, $imageBase64);

        return $filename;
    }
}
