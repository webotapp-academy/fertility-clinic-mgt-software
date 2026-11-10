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
        Schema::table('couples', function (Blueprint $table) {
            $table->string('consent_file_path')->nullable()->after('consent_status');
            $table->timestamp('consent_signed_at')->nullable()->after('consent_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('couples', function (Blueprint $table) {
            $table->dropColumn(['consent_file_path', 'consent_signed_at']);
        });
    }
};
