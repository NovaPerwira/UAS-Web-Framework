<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{
    protected $fillable = ['name', 'skill'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
