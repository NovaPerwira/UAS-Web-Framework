<?php

namespace App\Services;

use App\Models\Agreement;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    /**
     * Create an Invoice under a signed Agreement.
     *
     * Guards:
     * - Agreement must be SIGNED
     * - New invoice amount must not exceed agreement's remaining value
     *
     * @throws ValidationException
     */
    public function create(Agreement $agreement, array $data): Invoice
    {
        // Guard 1: Agreement must be signed
        if (!$agreement->canCreateInvoice()) {
            throw ValidationException::withMessages([
                'agreement_id' => 'Invoices can only be created under a SIGNED agreement.',
            ]);
        }

        // Guard 2: Prevent overbilling
        $newAmount = (float) ($data['amount_due'] ?? 0);
        $existingTotal = $agreement->totalInvoiced();

        if (($existingTotal + $newAmount) > (float) $agreement->total_value) {
            throw ValidationException::withMessages([
                'amount_due' => sprintf(
                    'Invoice amount (Rp %s) exceeds the agreement remaining value (Rp %s).',
                    number_format($newAmount, 0, ',', '.'),
                    number_format($agreement->remainingValue(), 0, ',', '.')
                ),
            ]);
        }

        return DB::transaction(function () use ($agreement, $data) {
            $invoice = Invoice::create([
                'agreement_id' => $agreement->id,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'status' => Invoice::STATUS_UNPAID,
                'tax_rate' => $data['tax_rate'] ?? 0,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'payment_reference' => $data['payment_reference'] ?? null,
            ]);

            // Create line items
            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            // Calculate and save totals (also sets amount_due)
            $invoice->updateTotals();

            // Generate invoice number immediately
            $this->assignInvoiceNumber($invoice);

            return $invoice->fresh(['agreement', 'items', 'payments']);
        });
    }

    /**
     * Record a payment against an invoice.
     * Marks invoice as PAID if fully settled.
     */
    public function recordPayment(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->payments()->create($data);

            $totalPaid = $invoice->payments()->sum('amount');
            if ($totalPaid >= (float) $invoice->grand_total) {
                $invoice->update(['status' => Invoice::STATUS_PAID]);
            }

            return $invoice->fresh(['payments']);
        });
    }

    /**
     * Mark invoice as unpaid (sent) and generate its number.
     */
    public function send(Invoice $invoice): Invoice
    {
        if ($invoice->status !== Invoice::STATUS_DRAFT) {
            throw ValidationException::withMessages([
                'status' => 'Only draft invoices can be sent.',
            ]);
        }

        return DB::transaction(function () use ($invoice) {
            $invoice->update(['status' => Invoice::STATUS_UNPAID]);
            $this->assignInvoiceNumber($invoice);
            return $invoice->fresh();
        });
    }

    /**
     * Generate and assign the invoice number: INV/COMPANY/YYYY/XXXX
     */
    private function assignInvoiceNumber(Invoice $invoice): void
    {
        if ($invoice->invoice_number) {
            return; // Already assigned
        }

        $year = now()->format('Y');
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereNotNull('invoice_number')
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastInvoice && preg_match('/(\d+)$/', $lastInvoice->invoice_number, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        $invoice->update([
            'invoice_number' => 'INV/' . $year . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT),
        ]);
    }
}
