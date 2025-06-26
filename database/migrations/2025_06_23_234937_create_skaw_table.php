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
        // 1. Tabel utama SKAW
        Schema::create('skaw', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Data Pemohon (auto fill dari user profile)
            $table->string('nama_lengkap');
            $table->string('nik');
            $table->text('alamat');
            $table->string('pekerjaan');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('kewarganegaraan')->default('Indonesia');
            $table->string('nomor_kk');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('email');
            $table->string('no_telepon')->nullable();

            // Data khusus SKAW Pemohon
            $table->string('nomor_akta_perkawinan')->nullable();
            $table->date('tanggal_terbit_akta_perkawinan');
            $table->integer('jumlah_anak')->default(0);

            // Data Pewaris
            $table->string('pewaris_nik');
            $table->string('pewaris_tempat_lahir');
            $table->date('pewaris_tanggal_lahir');
            $table->string('pewaris_nama_lengkap');
            $table->string('pewaris_gelar')->nullable();
            $table->text('pewaris_tempat_tinggal_terakhir');
            $table->date('pewaris_tanggal_kematian');
            $table->string('pewaris_tempat_kematian');
            $table->string('pewaris_nomor_akta_kematian');
            $table->date('pewaris_tanggal_terbit_akta_kematian');

            // Data Saksi
            $table->json('data_saksi')->nullable();

            // Status & Workflow
            $table->enum('status', [
                'draft', 'submitted', 'front_office_approved',
                'skaw_generated', 'jadwal_sidang_created', 'sidang_selesai',
                'evidence_uploaded', 'lurah_approved', 'camat_approved',
                'skaw_final', 'completed'
            ])->default('draft');

            // Front Office Process
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('front_office_approved_at')->nullable();
            $table->string('front_office_approved_by')->nullable();
            $table->foreign('front_office_approved_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('front_office_notes')->nullable();
            $table->string('nomor_register_kelurahan')->nullable();

            // Surat Tanda Terima & SKAW Generate
            $table->string('file_tanda_terima')->nullable();
            $table->string('file_skaw_draft')->nullable();
            $table->timestamp('skaw_generated_at')->nullable();

            // Jadwal Sidang
            $table->date('tanggal_sidang')->nullable();
            $table->time('jam_sidang')->nullable();
            $table->string('tempat_sidang')->nullable();
            $table->string('file_daftar_sidang')->nullable();
            $table->timestamp('jadwal_sidang_created_at')->nullable();
            $table->string('jadwal_sidang_created_by')->nullable();
            $table->foreign('jadwal_sidang_created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Evidence Sidang
            $table->json('evidence_photos')->nullable();
            $table->string('file_evidence_pdf')->nullable();
            $table->timestamp('evidence_uploaded_at')->nullable();
            $table->string('evidence_uploaded_by')->nullable();
            $table->foreign('evidence_uploaded_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // SKAW TTD dan Upload Final
            $table->string('file_skaw_ttd_scan')->nullable();
            $table->timestamp('skaw_ttd_uploaded_at')->nullable();
            $table->string('skaw_ttd_uploaded_by')->nullable();
            $table->foreign('skaw_ttd_uploaded_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Approval Lurah
            $table->timestamp('lurah_approved_at')->nullable();
            $table->string('lurah_approved_by')->nullable();
            $table->foreign('lurah_approved_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('lurah_notes')->nullable();

            // Approval Camat
            $table->timestamp('camat_approved_at')->nullable();
            $table->string('camat_approved_by')->nullable();
            $table->foreign('camat_approved_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('camat_notes')->nullable();

            // SKAW Final
            $table->string('file_skaw_final')->nullable();
            $table->timestamp('skaw_final_uploaded_at')->nullable();
            $table->string('skaw_final_uploaded_by')->nullable();
            $table->foreign('skaw_final_uploaded_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('completed_at')->nullable();

            // Metadata & Additional Info
            $table->json('metadata')->nullable();
            $table->integer('download_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['tanggal_sidang']);
            $table->index(['rt', 'rw']);
        });

        // 2. Tabel Data Anak (relasi one-to-many dengan SKAW)
        Schema::create('skaw_anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skaw_id')->constrained('skaw')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->integer('urutan')->comment('Anak ke-1, ke-2, dst');
            $table->timestamps();

            $table->index(['skaw_id', 'urutan']);
        });

        // 3. Tabel File Persyaratan SKAW
        Schema::create('skaw_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skaw_id')->constrained('skaw')->onDelete('cascade');
            $table->string('file_type'); // ktp_keluarga, akta_kematian, etc
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamp('uploaded_at');
            $table->timestamps();

            $table->index(['skaw_id', 'file_type']);
        });

        // 4. Tabel Activity Log untuk tracking
        Schema::create('skaw_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skaw_id')->constrained('skaw')->onDelete('cascade');
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('action');
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['skaw_id', 'created_at']);
            $table->index(['user_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skaw');
    }
};
