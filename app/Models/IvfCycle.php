<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IvfCycle extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function previousCycle()
    {
        return $this->belongsTo(IvfCycle::class, 'previous_cycle_id');
    }

    public function reattempts()
    {
        return $this->hasMany(IvfCycle::class, 'previous_cycle_id');
    }

    public function scans()
    {
        return $this->hasMany(FollicularScan::class, 'cycle_id')->orderBy('cycle_day');
    }

    public function labRecord()
    {
        return $this->hasOne(OpuAndLabRecord::class, 'cycle_id');
    }

    public function transfers()
    {
        return $this->hasMany(EmbryoTransfer::class, 'cycle_id');
    }

    public function outcome()
    {
        return $this->hasOne(CycleOutcome::class, 'cycle_id');
    }
}

