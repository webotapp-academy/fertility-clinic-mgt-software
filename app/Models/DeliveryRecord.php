<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRecord extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function antenatalRecord()
    {
        return $this->belongsTo(AntenatalRecord::class, 'antenatal_record_id');
    }

    public function babies()
    {
        return $this->hasMany(BabyRecord::class, 'delivery_record_id');
    }
}

