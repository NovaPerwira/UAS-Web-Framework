<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'client_id',
        'project_id',
        'contract_number',
        'title',
        'start_date',
        'end_date',
        'contract_value',
        'scope_of_work',
        'timeline',
        'payment_terms',
        'revisions',
        'ownership_rights',
        'warranty',
        'general_terms',
        'status',
        'content',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'contract_value' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (empty($contract->contract_number)) {
                $latest = static::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $contract->contract_number = 'CNT-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
