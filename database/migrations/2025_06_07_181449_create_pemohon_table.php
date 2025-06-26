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
        Schema::create('pemohon', function (Blueprint $table) {
            $table->id();
            $table->datetime('tanggal');
            $table->string('nama');
            $table->string('kode_pemohon')->unique();
            $table->string('no_whatsapp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jenis_layanan');
            $table->string('keterangan');
            $table->enum('jenis_antrian', ['Online', 'Offline'])->default('Offline');
            $table->string('jenis_pengiriman')->nullable();
            $table->enum('status', ['0', '1'])->default('0'); // 0 = Belum Terlayani, 1 = Terlayani
            $table->string('dilayani_oleh')->nullable(); // Admin/operator yang melayani
            $table->timestamp('tanggal_dilayani')->nullable();
            $table->timestamps();

            // Indexes untuk performance
            $table->index(['tanggal', 'status']);
            $table->index(['kode_pemohon']);
            $table->index(['nama']);
            $table->index(['jenis_layanan']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemohon');
    }
};
