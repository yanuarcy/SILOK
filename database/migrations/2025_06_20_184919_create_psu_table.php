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
        Schema::create('psu', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Data Pemohon
            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->text('alamat');
            $table->string('pekerjaan');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('kewarganegaraan')->default('Indonesia');
            $table->string('nomor_kk', 20);

            // Lokasi RT/RW
            $table->string('rt', 3);
            $table->string('rw', 3);

            // Data PSU Specific
            $table->enum('ditujukan_kepada', ['warga_rt', 'warga_rw', 'rt', 'rw', 'kelurahan']);

            // NEW: Target information untuk PSU Internal
            $table->string('target_type')->nullable(); // 'individual', 'semua_rt', 'semua_rw'
            $table->string('target_rt')->nullable(); // RT target untuk PSU Internal
            $table->string('target_rw')->nullable(); // RW target untuk PSU Internal
            $table->string('target_warga_id')->nullable(); // ID warga target (jika individual)
            $table->string('target_warga_name')->nullable(); // Nama warga target

            $table->string('nama_ketua_rt')->nullable();
            $table->string('nama_ketua_rw')->nullable();
            $table->string('bulan');
            $table->enum('sifat', ['Penting', 'Biasa', 'Segera', 'Rahasia']);
            $table->text('hal'); // Isi surat / keperluan
            $table->text('isi_surat'); // Isi detail surat
            $table->enum('tujuan_internal', ['rt', 'rw', 'kelurahan', 'kecamatan'])->nullable();
            $table->text('tujuan_eksternal')->nullable(); // Alamat tujuan jika eksternal

            // Status dan Approval - UPDATED with new statuses
            $table->enum('status', [
                'auto_approved', // For internal PSU
                'pending_rt',
                'approved_rt',
                'rejected_rt',
                'pending_rw',
                'approved_rw',
                'rejected_rw',
                'pending_kelurahan',
                'approved_kelurahan', // Approved by Front Office
                'rejected_kelurahan',
                'processing_lurah', // NEW: Sedang diproses Lurah
                'processed_lurah', // NEW: Selesai diproses Lurah
                'processing_back_office', // NEW: Sedang diproses Back Office
                'completed' // NEW: Final - sudah selesai semua
            ])->default('pending_rt');

            // TTD dan Stempel
            $table->string('ttd_pemohon')->nullable(); // TTD digital pemohon
            $table->string('ttd_rt')->nullable(); // From spesimen
            $table->string('stempel_rt')->nullable(); // From spesimen
            $table->string('ttd_rw')->nullable(); // From spesimen
            $table->string('stempel_rw')->nullable(); // From spesimen
            $table->string('ttd_kelurahan')->nullable(); // From spesimen
            $table->string('stempel_kelurahan')->nullable(); // From spesimen

            // Approval Data RT
            $table->timestamp('approved_rt_at')->nullable();
            $table->string('approved_rt_by')->nullable();
            $table->foreign('approved_rt_by')->nullable()->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('catatan_rt')->nullable();

            // Approval Data RW
            $table->timestamp('approved_rw_at')->nullable();
            $table->string('approved_rw_by')->nullable();
            $table->foreign('approved_rw_by')->nullable()->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('catatan_rw')->nullable();

            // Approval Data Kelurahan
            $table->timestamp('approved_kelurahan_at')->nullable();
            $table->string('approved_kelurahan_by')->nullable();
            $table->foreign('approved_kelurahan_by')->nullable()->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('catatan_kelurahan')->nullable();

            // NEW: Workflow tracking untuk Kelurahan
            $table->timestamp('received_kelurahan_at')->nullable(); // Kapan diterima Front Office
            $table->string('received_kelurahan_by')->nullable(); // Siapa Front Office yang terima
            $table->foreign('received_kelurahan_by')->nullable()->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('surat_tanda_terima')->nullable(); // File surat tanda terima
            $table->string('surat_disposisi')->nullable(); // File lembar disposisi

            // NEW: Lurah processing
            $table->timestamp('processed_lurah_at')->nullable();
            $table->string('processed_lurah_by')->nullable();
            $table->foreign('processed_lurah_by')->nullable()->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('catatan_lurah')->nullable();

            // NEW: Back Office final processing
            $table->timestamp('processed_back_office_at')->nullable();
            $table->string('processed_back_office_by')->nullable();
            $table->foreign('processed_back_office_by')->nullable()->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('file_esurat')->nullable(); // File E-Surat final
            $table->string('nomor_nota_dinas')->nullable(); // Nomor nota dinas

            // File Management
            $table->string('file_pdf')->nullable();
            $table->json('file_lampiran')->nullable();
            $table->integer('download_count')->default(0);

            // Additional Fields
            $table->string('level_akhir')->nullable(); // rt, rw, kelurahan, auto_approved
            $table->json('metadata')->nullable(); // For additional data storage

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['rt', 'rw']);
            $table->index(['status', 'created_at']);
            $table->index('nomor_surat');
            $table->index(['target_type', 'target_rt', 'target_rw']); // NEW: Index for target queries
            $table->index('received_kelurahan_at'); // NEW: Index for kelurahan workflow
            $table->index('processed_lurah_at'); // NEW: Index for lurah workflow
            $table->index('processed_back_office_at'); // NEW: Index for back office workflow
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psu');
    }
};
