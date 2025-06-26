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
        Schema::create('data_skm', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); // FK ke tabel users
            $table->string('nama');
            $table->text('alamat');
            $table->enum('tingkat_kepuasan', ['Sangat Puas', 'Puas', 'Tidak Puas']);
            $table->text('kritik_saran');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_skm');
    }
};
