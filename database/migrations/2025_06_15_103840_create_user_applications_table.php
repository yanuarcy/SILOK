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
        Schema::create('user_applications', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('user_id');
            $table->enum('jenis_permohonan', ['PUNTADEWA', 'PSU', 'SKAW', 'SURAT PENGANTAR', 'VERIFIKASI DOMISILI']);
            $table->string('judul_permohonan');
            $table->text('deskripsi_permohonan')->nullable();
            $table->string('nama_pemohon');
            $table->string('nik');
            $table->string('rt');
            $table->string('rw');

            // Status workflow
            $table->enum('status', [
                'draft',
                'pending_rt',
                'approved_rt',
                'rejected_rt',
                'pending_rw',
                'approved_rw',
                'rejected_rw',
                'pending_kelurahan',
                'approved_kelurahan',
                'rejected_kelurahan',
                'completed'
            ])->default('pending_rt');

            // RT Approval
            $table->timestamp('approved_rt_at')->nullable();
            $table->string('approved_rt_by')->nullable();
            $table->text('catatan_rt')->nullable();

            // RW Approval
            $table->timestamp('approved_rw_at')->nullable();
            $table->string('approved_rw_by')->nullable();
            $table->text('catatan_rw')->nullable();

            // Kelurahan Approval (untuk beberapa jenis surat)
            $table->timestamp('approved_kelurahan_at')->nullable();
            $table->string('approved_kelurahan_by')->nullable();
            $table->text('catatan_kelurahan')->nullable();

            // Files
            $table->string('file_pdf')->nullable();
            $table->json('file_lampiran')->nullable(); // untuk menyimpan multiple files

            // Reference ke tabel asli
            $table->unsignedBigInteger('reference_id'); // ID dari tabel asli (puntadewa, psu, dll)
            $table->string('reference_table'); // nama tabel asli

            // Metadata
            $table->json('metadata')->nullable(); // untuk data tambahan spesifik per jenis
            $table->integer('download_count')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('approved_rt_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('approved_rw_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('approved_kelurahan_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Indexes
            $table->index(['user_id', 'jenis_permohonan']);
            $table->index(['status']);
            $table->index(['created_at']);
            $table->index(['rt', 'rw']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_applications');
    }
};
