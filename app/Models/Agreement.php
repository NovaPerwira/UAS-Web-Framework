<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $fillable = [
        'invoice_id',
        'agreement_number',
        'client_name',
        'client_email',
        'company_name',
        'service_description',
        'scope_of_work',
        'price',
        'payment_terms',
        'start_date',
        'end_date',
        'status',
        'signed_at',
        'signature_path',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
