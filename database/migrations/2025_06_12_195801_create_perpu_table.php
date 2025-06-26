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
        Schema::create('perpu', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_peraturan');
            $table->string('tahun');
            $table->string('judul');
            $table->text('tentang');
            $table->text('deskripsi')->nullable();
            $table->string('file_pdf');
            $table->bigInteger('ukuran_file')->nullable(); // dalam bytes
            $table->date('tanggal_penetapan');
            $table->date('tanggal_upload');
            $table->enum('jenis_peraturan', [
                'Peraturan Walikota',
                'Peraturan Daerah',
                'Keputusan Walikota',
                'Instruksi Walikota',
                'Surat Edaran Walikota',
                'Peraturan Menteri',
                'Undang-Undang',
                'Peraturan Pemerintah',
                'Lainnya'
            ])->default('Peraturan Walikota');
            $table->enum('status', ['Draft', 'Published', 'Archived'])->default('Published');
            $table->integer('urutan_tampil')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('tags')->nullable(); // untuk kategori/tag
            $table->bigInteger('download_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['jenis_peraturan', 'tahun']);
            $table->index(['status', 'is_active']);
            $table->index('tanggal_penetapan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpu');
    }
};
