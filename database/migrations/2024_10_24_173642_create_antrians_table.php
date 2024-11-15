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
        Schema::create('antrians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal')->nullable();
            $table->string('nama', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('no_whatsapp', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('alamat', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('jenis_layanan', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('keterangan', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('no_antrian', 6)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('jenis_antrian', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('jenis_pengiriman', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->string('calling_by', 200)->charset('latin1')->collate('latin1_swedish_ci');
            $table->enum('status', ['0', '1'])->default('0');
            $table->dateTime('updated_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
