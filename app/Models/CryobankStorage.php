<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryobankStorage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function cycle()
    {
        return $this->belongsTo(IvfCycle::class, 'cycle_id');
    }
}

