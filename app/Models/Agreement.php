<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $fillable = [
        'invoice_id',
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
        'total_price',
        'payment_terms',
        'start_date',
        'estimated_completion_date',
        'status',
    ];

    protected $casts = [
        'agreement_date' => 'date',
        'total_price' => 'decimal:2',
        'start_date' => 'date',
        'estimated_completion_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
