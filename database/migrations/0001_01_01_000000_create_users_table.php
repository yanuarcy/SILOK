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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username');
            $table->string('telp', 20);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->string('nik', 16)->nullable(); // Nomor NIK 16 digit
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->text('address')->nullable(); // Alamat lengkap
            $table->string('rt', 3)->nullable(); // RT
            $table->string('rw', 3)->nullable(); // RW
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('status_perkawinan', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'])->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('image')->nullable(); // Untuk foto profil
            $table->string('agama')->nullable();
            $table->text('description')->nullable(); // Bio/deskripsi profil pengguna
            $table->rememberToken();
            $table->timestamp('remember_token_created_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 20)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->timestamp('login_at')->nullable();
            $table->integer('last_activity')->index();
            $table->timestamps();

            $table->index(['user_id']); // Tambahkan index

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
