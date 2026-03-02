<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    // ───── Status Constants ─────────────────────────────────
    const STATUS_DRAFT = 'draft';
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    // ───── Mass Assignment ───────────────────────────────────
    protected $fillable = [
        'agreement_id',
        'client_id',
        'project_id',
        'contract_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'status',
        'amount_due',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'payment_reference',
        'notes',
    ];

    // ───── Casts ─────────────────────────────────────────────
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'amount_due' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'agreement_id' => 'integer',
    ];

    // ───── Relationships ─────────────────────────────────────

    /**
     * Invoice belongs to its parent Agreement.
     * Agreement must exist before Invoice can be created.
     */
    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ───── Business Logic ────────────────────────────────────

    public function updateTotals(): void
    {
        $subtotal = (float) $this->items->sum('total_price');
        $taxAmount = $subtotal * ((float) $this->tax_rate / 100);
        $grandTotal = $subtotal + $taxAmount - (float) $this->discount_amount;

        $this->update([
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'tax_amount' => number_format($taxAmount, 2, '.', ''),
            'grand_total' => number_format($grandTotal, 2, '.', ''),
            'amount_due' => number_format($grandTotal, 2, '.', ''),
        ]);
    }

    /**
     * Amount still owed after applying recorded payments.
     */
    public function balanceDue(): float
    {
        return (float) $this->grand_total - (float) $this->payments->sum('amount');
    }
}
