<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationSchedule extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function baby()
    {
        return $this->belongsTo(BabyRecord::class, 'baby_record_id');
    }
}

