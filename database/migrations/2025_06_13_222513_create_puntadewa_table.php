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
        Schema::create('puntadewa', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_id'); // FK ke tabel users
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Data Pemohon
            $table->string('nama_pemohon');
            $table->string('nik');
            $table->text('alamat_asal');
            $table->text('alasan_tinggal');
            $table->string('rt');
            $table->string('rw');

            // Data Bekerja
            $table->string('nama_perusahaan')->nullable();
            $table->text('alamat_perusahaan')->nullable();

            // Data Sekolah
            $table->string('nama_sekolah')->nullable();
            $table->text('alamat_sekolah')->nullable();

            // Data Kesehatan
            $table->string('nama_rumah_sakit')->nullable();
            $table->text('alamat_rumah_sakit')->nullable();

            // Alasan Lainnya
            $table->text('alasan_lainnya')->nullable();

            // Data Penjamin
            $table->string('nama_penjamin');
            $table->string('nik_penjamin');
            $table->text('alamat_penjamin');
            $table->string('no_telp_penjamin');

            // Upload Files
            $table->string('file_kk_asal');

            // Digital Signature - Pemohon
            $table->text('ttd_pemohon');

            // Digital Signature - Pemilik Kost/Kontrakan
            $table->text('ttd_pemilik_kost')->nullable();

            // Digital Signature & Stempel - RT
            $table->text('ttd_rt')->nullable();
            $table->text('stempel_rt')->nullable();
            $table->timestamp('approved_rt_at')->nullable();
            $table->string('approved_rt_by')->nullable();
            $table->foreign('approved_rt_by')->references('id')->on('users')->onDelete('cascade');
            $table->text('catatan_rt')->nullable();

            // Digital Signature & Stempel - RW
            $table->text('ttd_rw')->nullable();
            $table->text('stempel_rw')->nullable();
            $table->timestamp('approved_rw_at')->nullable();
            $table->string('approved_rw_by')->nullable();
            $table->foreign('approved_rw_by')->references('id')->on('users')->onDelete('cascade');
            $table->text('catatan_rw')->nullable();

            // Location Data
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('alamat_lokasi')->nullable();

            // Status workflow: pending_rt, approved_rt, pending_rw, approved_rw, rejected_rt, rejected_rw
            $table->enum('status', [
                'pending_rt',
                'approved_rt',
                'pending_rw',
                'approved_rw',
                'rejected_rt',
                'rejected_rw'
            ])->default('pending_rt');

            $table->string('file_pdf')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntadewa');
    }
};
