<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Layanan;
use App\Models\SubLayanan;
use App\Models\LayananItem;
use App\Models\RegistrationOption;
use App\Models\ApplicantType;
use Illuminate\Support\Str;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // KTP/KK/KIA/IKD Layanan
        $ktp = Layanan::create([
            'title' => 'KTP / KK / KIA / IKD',
            'slug' => 'KTP_KK_KIA_IKD',
            'image' => 'KTP_KK_KIA.png',
            'description' => 'Pengurusan dokumen identitas kependudukan termasuk Kartu Tanda Penduduk, Kartu Keluarga, Kartu Identitas Anak, dan Izin Kependudukan.',
            'small' => 'Proses cepat dan akurat',
            'has_sub_layanan' => true
        ]);

        // Sub Layanan KTP
        $subKtp = SubLayanan::create([
            'layanan_id' => $ktp->id,
            'title' => 'KTP',
            'slug' => 'ktp',
            'image' => 'KTP_Animasi.png',
            'has_items' => true
        ]);

        // KTP Items
        LayananItem::create([
            'sub_layanan_id' => $subKtp->id,
            'title' => 'Pengambilan KTP',
            'slug' => 'pengambilan-ktp',
            'image' => 'Pengambilan_KTP.png'
        ]);

        LayananItem::create([
            'sub_layanan_id' => $subKtp->id,
            'title' => 'Cetak Ulang KTP',
            'slug' => 'cetak-ulang-ktp',
            'image' => 'CetakUlang_KTP.png'
        ]);

        // Sub Layanan KK
        $subKk = SubLayanan::create([
            'layanan_id' => $ktp->id,
            'title' => 'KK',
            'slug' => 'kk',
            'image' => 'KK_Animasi.png',
            'has_items' => true
        ]);

        // KK Items
        LayananItem::create([
            'sub_layanan_id' => $subKk->id,
            'title' => 'Pengajuan KK Barcode',
            'slug' => 'pengajuan-kk-barcode',
            'image' => 'Barcode_KK.jpg'
        ]);

        LayananItem::create([
            'sub_layanan_id' => $subKk->id,
            'title' => 'Perubahan Biodata KK',
            'slug' => 'perubahan-biodata-kk',
            'image' => 'Perubahan_KK.png'
        ]);

        // Akta Layanan
        $akta = Layanan::create([
            'title' => 'AKTA',
            'slug' => 'akta',
            'image' => 'Akta.png',
            'description' => 'Pembuatan dan pengurusan berbagai jenis akta seperti akta kelahiran, akta kematian, akta perkawinan, dan akta perceraian.',
            'small' => 'Dokumen legal yang terjamin',
            'has_sub_layanan' => true
        ]);

        // Sub Layanan Akta
        $subAktaKelahiran = SubLayanan::create([
            'layanan_id' => $akta->id,
            'title' => 'Akta Kelahiran',
            'slug' => 'akta-kelahiran',
            'image' => 'Akta_Kelahiran.png',
            'has_items' => false
        ]);

        $subAktaKematian = SubLayanan::create([
            'layanan_id' => $akta->id,
            'title' => 'Akta Kematian',
            'slug' => 'akta-kematian',
            'image' => 'akta_kematian.png',
            'has_items' => false
        ]);

        // Pindah Datang Layanan
        $pindahDatang = Layanan::create([
            'title' => 'PINDAH DATANG',
            'slug' => 'pindah-datang',
            'image' => 'datang_dan_pindah.png',
            'description' => 'Layanan administrasi untuk proses pindah keluar atau masuk ke wilayah Kelurahan Jemur Wonosari.',
            'small' => 'Mudah dan transparan',
            'has_sub_layanan' => true
        ]);

        // Sub Layanan Pindah Datang
        SubLayanan::create([
            'layanan_id' => $pindahDatang->id,
            'title' => 'Pindah Datang Dalam Kota',
            'slug' => 'pindah-datang-dalam-kota',
            'image' => 'PindahDatangDalamKota.png',
            'has_items' => false
        ]);

        SubLayanan::create([
            'layanan_id' => $pindahDatang->id,
            'title' => 'Pindah Masuk Antar Kota / Kabupaten',
            'slug' => 'pindah-masuk-antar-kota',
            'image' => 'PindahDatangAntarKota.png',
            'has_items' => false
        ]);

        // SKT/SKAW Layanan
        $sktSkaw = Layanan::create([
            'title' => 'SKT / SKAW',
            'slug' => 'skt-skaw',
            'image' => 'SKT_SKAW.png',
            'description' => 'Pengurusan Surat Keterangan Tanah dan Surat Keterangan Ahli Waris untuk keperluan administratif dan hukum.',
            'small' => 'Proses cepat dan terpercaya',
            'has_sub_layanan' => true
        ]);

        // Sub Layanan SKT/SKAW
        SubLayanan::create([
            'layanan_id' => $sktSkaw->id,
            'title' => 'Surat Keterangan Tanah',
            'slug' => 'skt',
            'image' => 'SKT.png',
            'has_items' => false
        ]);

        SubLayanan::create([
            'layanan_id' => $sktSkaw->id,
            'title' => 'Surat Keterangan Ahli Waris',
            'slug' => 'skaw',
            'image' => 'SKAW.png',
            'has_items' => false
        ]);

        // Layanan Kelurahan
        $layananKelurahan = Layanan::create([
            'title' => 'LAYANAN KELURAHAN',
            'slug' => 'layanan-kelurahan',
            'image' => 'LayananKelurahan.png',
            'description' => 'Berbagai layanan administratif lainnya yang disediakan oleh kelurahan, seperti surat pengantar atau keterangan.',
            'small' => 'Pelayanan prima untuk warga',
            'has_sub_layanan' => false
        ]);

        // Konsultasi Layanan
        Layanan::create([
            'title' => 'KONSULTASI',
            'slug' => 'konsultasi',
            'image' => 'Konsultasi.png',
            'description' => 'Layanan konsultasi terkait administrasi kependudukan dan pelayanan publik di tingkat kelurahan.',
            'small' => 'Solusi untuk setiap pertanyaan Anda',
            'has_sub_layanan' => false
        ]);

        // Create the three standard registration options
        RegistrationOption::create([
            'type' => 'kelurahan',
            'title' => 'Daftar Di Kelurahan',
            'image' => 'KantorKelurahan.png'
        ]);

        RegistrationOption::create([
            'type' => 'online',
            'title' => 'Daftar Online',
            'image' => 'DaftarOnline.png'
        ]);

        RegistrationOption::create([
            'type' => 'balai_rw',
            'title' => 'Daftar Di Balai RW',
            'image' => 'BalaiRW_Animasi.png'
        ]);

        // Create the two standard applicant types
        ApplicantType::create([
            'type' => 'baru',
            'title' => 'Pemohon Baru',
            'image' => 'PemohonBaru.png'
        ]);

        ApplicantType::create([
            'type' => 'lama',
            'title' => 'Pemohon Lama',
            'image' => 'PemohonLama.png'
        ]);
    }
}
