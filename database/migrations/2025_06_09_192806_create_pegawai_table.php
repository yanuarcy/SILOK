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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // FK ke tabel users
            $table->string('jabatan'); // Jabatan di kelurahan
            $table->json('media_sosial')->nullable(); // JSON untuk menyimpan media sosial
            $table->boolean('is_active')->default(true);
            $table->integer('urutan_tampil')->default(1); // Untuk mengurutkan tampilan
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index
            $table->index(['is_active', 'urutan_tampil']);
            $table->index('jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
