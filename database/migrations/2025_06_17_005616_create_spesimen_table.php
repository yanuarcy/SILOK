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
        Schema::create('spesimen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pejabat');
            $table->enum('jabatan', ['Ketua RT', 'Ketua RW']);
            $table->string('rt')->nullable();
            $table->string('rw');
            $table->string('file_ttd')->nullable(); // Path file tanda tangan
            $table->string('file_stempel')->nullable(); // Path file stempel
            $table->text('keterangan')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->boolean('is_active')->default(true);
            $table->integer('urutan_tampil')->nullable();
            $table->string('user_id'); // Relasi ke user
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['jabatan', 'rw', 'rt']);
            $table->index(['status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spesimen');
    }
};
