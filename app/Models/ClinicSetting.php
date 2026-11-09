<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicSetting extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function getMasterSettings()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'clinic_name' => 'AURA Reproductive Medicine & Advanced Conception Centre',
                'art_reg_no' => 'ART-IND-2026-0894',
                'director_name' => 'Dr. Elena Vance, MD (Reprod. Endo)',
                'incubator_temp' => '37.0°C (6% CO2, 5% O2)',
                'ln2_alarm' => 'Liquid Level < 15 cm',
                'contact_phone' => '+91 98200 00000',
                'contact_email' => 'contact@aura-ivf.com',
                'clinic_address' => 'Suite 401, Healthcare Boulevard, Metro City, India',
            ]
        );
    }
}

