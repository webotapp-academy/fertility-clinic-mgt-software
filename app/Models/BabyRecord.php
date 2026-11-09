<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabyRecord extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function deliveryRecord()
    {
        return $this->belongsTo(DeliveryRecord::class, 'delivery_record_id');
    }

    public function vaccinations()
    {
        return $this->hasMany(VaccinationSchedule::class, 'baby_record_id')->orderBy('due_date');
    }
}

