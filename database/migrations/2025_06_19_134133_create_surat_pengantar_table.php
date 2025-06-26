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
        Schema::create('surat_pengantar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('user_id')->nullable();

            // Data Pemohon
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->text('alamat');
            $table->string('pekerjaan');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama');
            $table->enum('status_perkawinan', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']);
            $table->string('kewarganegaraan');
            $table->string('nomor_kk');

            // Keperluan
            $table->text('tujuan');
            $table->text('keperluan');
            $table->text('keterangan_lain')->nullable();

            // RT/RW
            $table->string('rt', 3);
            $table->string('rw', 3);

            // Approval RT
            $table->enum('status', [
                'pending_rt', 'approved_rt', 'rejected_rt',
                'pending_rw', 'approved_rw', 'rejected_rw'
            ])->default('pending_rt');
            $table->text('ttd_pemohon')->nullable();
            $table->text('ttd_rt')->nullable();
            $table->text('stempel_rt')->nullable();
            $table->timestamp('approved_rt_at')->nullable();
            $table->string('approved_rt_by')->nullable();
            $table->text('catatan_rt')->nullable();

            // Approval RW
            $table->text('ttd_rw')->nullable();
            $table->text('stempel_rw')->nullable();
            $table->timestamp('approved_rw_at')->nullable();
            $table->string('approved_rw_by')->nullable();
            $table->text('catatan_rw')->nullable();

            // File Management
            $table->string('file_pdf')->nullable();

            $table->timestamps();

            // $table->foreign('user_id')->references('id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('approved_rt_by')->nullable()->references('id')->constrained('users')->onDelete('set null');
            $table->foreign('approved_rt_by')->nullable()->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('approved_rw_by')->nullable()->references('id')->constrained('users')->onDelete('set null');
            $table->foreign('approved_rw_by')->nullable()->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Indexes
            $table->index(['status', 'rt', 'rw']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pengantar');
    }
};
