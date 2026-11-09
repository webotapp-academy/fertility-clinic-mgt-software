<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfertilityWorkup extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }
}

