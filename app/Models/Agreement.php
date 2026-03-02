<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    // ───── Status Constants ─────────────────────────────────
    const DRAFT = 'draft';
    const ISSUED = 'issued';
    const SIGNED = 'signed';
    const CANCELLED = 'cancelled';

    /**
     * Valid status transitions.
     * Key = current status, Value = allowed next statuses
     */
    const ALLOWED_TRANSITIONS = [
        self::DRAFT => [self::ISSUED, self::CANCELLED],
        self::ISSUED => [self::SIGNED, self::CANCELLED],
        self::SIGNED => [], // Terminal — no further transitions allowed
        self::CANCELLED => [], // Terminal
    ];

    // ───── Mass Assignment ───────────────────────────────────
    protected $fillable = [
        'agreement_number',
        'agreement_date',
        'provider_name',
        'provider_address',
        'provider_email',
        'client_name',
        'client_address',
        'client_email',
        'project_name',
        'service_description',
        'scope_of_work',
        'rendered_content',
        'total_value',
        'payment_terms',
        'start_date',
        'estimated_completion_date',
        'status',
    ];

    // ───── Casts ─────────────────────────────────────────────
    protected $casts = [
        'agreement_date' => 'date',
        'start_date' => 'date',
        'estimated_completion_date' => 'date',
        'total_value' => 'decimal:2',
    ];

    // ───── Relationships ─────────────────────────────────────

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // ───── Business Logic Helpers ────────────────────────────

    /**
     * Agreement can only be edited while in DRAFT status.
     * Once ISSUED, the content is frozen as a legal snapshot.
     */
    public function canEdit(): bool
    {
        return $this->status === self::DRAFT;
    }

    /**
     * Invoices can only be created under a SIGNED agreement.
     */
    public function canCreateInvoice(): bool
    {
        return $this->status === self::SIGNED;
    }

    /**
     * Returns allowed next statuses from current status.
     */
    public function allowedNextStatuses(): array
    {
        return self::ALLOWED_TRANSITIONS[$this->status] ?? [];
    }

    /**
     * Returns true if the given transition is valid.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedNextStatuses(), true);
    }

    /**
     * Total amount already invoiced under this agreement.
     */
    public function totalInvoiced(): float
    {
        return (float) $this->invoices()->sum('amount_due');
    }

    /**
     * Remaining billable amount under this agreement.
     */
    public function remainingValue(): float
    {
        return (float) $this->total_value - $this->totalInvoiced();
    }
}
