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
        Schema::create('data_kependudukan', function (Blueprint $table) {
            $table->id();
            $table->integer('total_penduduk')->default(0);
            $table->integer('total_kk')->default(0);
            $table->integer('total_rw')->default(0);
            $table->integer('total_rt')->default(0);

            // Demografis berdasarkan usia
            $table->integer('usia_0_17')->default(0);
            $table->integer('usia_18_35')->default(0);
            $table->integer('usia_36_55')->default(0);
            $table->integer('usia_56_plus')->default(0);

            // Demografis berdasarkan jenis kelamin
            $table->integer('laki_laki')->default(0);
            $table->integer('perempuan')->default(0);

            // Demografis berdasarkan pendidikan
            $table->integer('sd_sederajat')->default(0);
            $table->integer('smp_sederajat')->default(0);
            $table->integer('sma_sederajat')->default(0);
            $table->integer('diploma_s1_plus')->default(0);

            // Metadata
            $table->string('periode_data')->default(date('Y-m')); // Format: 2025-06
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kependudukan');
    }
};
