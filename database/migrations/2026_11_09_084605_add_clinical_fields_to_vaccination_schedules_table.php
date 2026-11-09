<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vaccination_schedules', function (Blueprint $table) {
            $table->string('site_and_route')->nullable()->after('administered_by');
            $table->string('manufacturer')->nullable()->after('site_and_route');
            $table->date('vaccine_expiry_date')->nullable()->after('manufacturer');
            $table->string('baby_weight_at_vac')->nullable()->after('vaccine_expiry_date');
            $table->string('temperature_f')->nullable()->after('baby_weight_at_vac');
            $table->text('clinical_remarks')->nullable()->after('temperature_f');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccination_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'site_and_route',
                'manufacturer',
                'vaccine_expiry_date',
                'baby_weight_at_vac',
                'temperature_f',
                'clinical_remarks',
            ]);
        });
    }
};

