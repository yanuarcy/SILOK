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
        Schema::create('bank_data', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->text('deskripsi');
            $table->enum('jenis_bank_data', ['Kelurahan', 'RW', 'RT']);
            $table->string('nomor_rw')->nullable(); // untuk RW
            $table->string('nomor_rt')->nullable(); // untuk RT
            $table->date('tanggal_kegiatan');
            $table->string('lokasi')->nullable();
            $table->json('files_foto')->nullable(); // menyimpan array path foto
            $table->json('files_video')->nullable(); // menyimpan array path video
            $table->enum('status', ['Published', 'Draft', 'Archived'])->default('Published');
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->json('tags')->nullable();
            $table->integer('urutan_tampil')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['jenis_bank_data', 'status', 'is_active']);
            $table->index(['nomor_rw', 'nomor_rt']);
            $table->index('tanggal_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_data');
    }
};
