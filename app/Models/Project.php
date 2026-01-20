<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
            'client_id',
            'freelancer_id', 
            'project_name',
            'budget',
            'status'
        ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }
}
