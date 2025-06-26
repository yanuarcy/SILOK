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
        Schema::create('antarmuka', function (Blueprint $table) {
            $table->id('id_antarmuka');
            $table->string('keterangan');
            $table->string('nama');
            $table->integer('durasi_video')->nullable();
            $table->text('sumber'); // URL YouTube embed
            $table->integer('volume')->default(50);
            $table->boolean('status')->default(0); // 0=tidak aktif, 1=aktif
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antarmuka');
    }
};
