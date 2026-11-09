<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function wife()
    {
        return $this->belongsTo(Patient::class, 'wife_id');
    }

    public function husband()
    {
        return $this->belongsTo(Patient::class, 'husband_id');
    }

    public function workup()
    {
        return $this->hasOne(InfertilityWorkup::class);
    }

    public function cycles()
    {
        return $this->hasMany(IvfCycle::class)->orderBy('cycle_number');
    }

    public function antenatalRecords()
    {
        return $this->hasMany(AntenatalRecord::class);
    }

    public function cryoStorages()
    {
        return $this->hasMany(CryobankStorage::class);
    }
}

