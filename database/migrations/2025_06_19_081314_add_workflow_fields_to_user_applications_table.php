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
        Schema::table('user_applications', function (Blueprint $table) {
            // Add new fields for enhanced workflow
            $table->string('ditujukan_kepada')->nullable()->after('metadata')
                  ->comment('Target level for PSU: rt, rw, kelurahan');

            $table->string('level_akhir')->nullable()->after('ditujukan_kepada')
                  ->comment('Final approval level needed: rt, rw, kelurahan');

            // Add index for better performance
            $table->index(['jenis_permohonan', 'ditujukan_kepada']);
            $table->index(['jenis_permohonan', 'level_akhir']);
            $table->index(['status', 'jenis_permohonan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_applications', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['jenis_permohonan', 'ditujukan_kepada']);
            $table->dropIndex(['jenis_permohonan', 'level_akhir']);
            $table->dropIndex(['status', 'jenis_permohonan']);

            // Drop columns
            $table->dropColumn(['ditujukan_kepada', 'level_akhir']);
        });
    }
};
