<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegistrationOption;
use App\Models\ApplicantType;
use App\Models\Layanan;
use App\Models\SubLayanan;
use App\Models\LayananItem;

class RegistrationAndApplicantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For KTP Items
        $this->createRegistrationOptionsForKTP();

        // For KK Items
        $this->createRegistrationOptionsForKK();

        // For Akta
        $this->createRegistrationOptionsForAkta();

        // For Pindah Datang
        $this->createRegistrationOptionsForPindahDatang();

        // For SKT/SKAW
        $this->createRegistrationOptionsForSKTSKAW();
    }

    private function createRegistrationOptionsForKTP()
    {
        // For Pengambilan KTP
        $pengambilanKtp = LayananItem::where('slug', 'pengambilan-ktp')->first();
        $regOptionPengambilan = RegistrationOption::create([
            'registrationable_type' => 'App\Models\LayananItem',
            'registrationable_id' => $pengambilanKtp->id,
            'type' => 'pengambilan_ktp',
            'title' => 'Pengambilan KTP Elektronik',
            'image' => 'pengambilan_ktp_option.png'
        ]);

        // Applicant Types for Pengambilan KTP
        ApplicantType::create([
            'registration_option_id' => $regOptionPengambilan->id,
            'type' => 'sendiri',
            'title' => 'Mengambil Sendiri',
            'image' => 'self_pickup.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionPengambilan->id,
            'type' => 'kuasa',
            'title' => 'Diwakilkan',
            'image' => 'representative_pickup.png'
        ]);

        // For Cetak Ulang KTP
        $cetakUlangKtp = LayananItem::where('slug', 'cetak-ulang-ktp')->first();
        $regOptionCetakUlang = RegistrationOption::create([
            'registrationable_type' => 'App\Models\LayananItem',
            'registrationable_id' => $cetakUlangKtp->id,
            'type' => 'cetak_ulang_ktp',
            'title' => 'Cetak Ulang KTP Elektronik',
            'image' => 'cetak_ulang_ktp_option.png'
        ]);

        // Applicant Types for Cetak Ulang KTP
        ApplicantType::create([
            'registration_option_id' => $regOptionCetakUlang->id,
            'type' => 'hilang',
            'title' => 'KTP Hilang',
            'image' => 'lost_ktp.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionCetakUlang->id,
            'type' => 'rusak',
            'title' => 'KTP Rusak',
            'image' => 'damaged_ktp.png'
        ]);
    }

    private function createRegistrationOptionsForKK()
    {
        // For Pengajuan KK Barcode
        $kkBarcode = LayananItem::where('slug', 'pengajuan-kk-barcode')->first();
        $regOptionKKBarcode = RegistrationOption::create([
            'registrationable_type' => 'App\Models\LayananItem',
            'registrationable_id' => $kkBarcode->id,
            'type' => 'kk_barcode',
            'title' => 'Pengajuan KK Barcode',
            'image' => 'kk_barcode_option.png'
        ]);

        // Applicant Types for KK Barcode
        ApplicantType::create([
            'registration_option_id' => $regOptionKKBarcode->id,
            'type' => 'baru',
            'title' => 'KK Baru',
            'image' => 'new_kk.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionKKBarcode->id,
            'type' => 'pembaruan',
            'title' => 'Pembaruan KK',
            'image' => 'update_kk.png'
        ]);

        // For Perubahan Biodata KK
        $biodataKK = LayananItem::where('slug', 'perubahan-biodata-kk')->first();
        $regOptionBiodataKK = RegistrationOption::create([
            'registrationable_type' => 'App\Models\LayananItem',
            'registrationable_id' => $biodataKK->id,
            'type' => 'perubahan_biodata',
            'title' => 'Perubahan Biodata KK',
            'image' => 'perubahan_biodata_option.png'
        ]);

        // Applicant Types for Perubahan Biodata
        ApplicantType::create([
            'registration_option_id' => $regOptionBiodataKK->id,
            'type' => 'penambahan',
            'title' => 'Penambahan Anggota',
            'image' => 'add_member.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionBiodataKK->id,
            'type' => 'pengurangan',
            'title' => 'Pengurangan Anggota',
            'image' => 'remove_member.png'
        ]);
    }

    private function createRegistrationOptionsForAkta()
    {
        // For Akta Kelahiran
        $aktaKelahiran = SubLayanan::where('slug', 'akta-kelahiran')->first();
        $regOptionKelahiran = RegistrationOption::create([
            'registrationable_type' => 'App\Models\SubLayanan',
            'registrationable_id' => $aktaKelahiran->id,
            'type' => 'akta_kelahiran',
            'title' => 'Pengurusan Akta Kelahiran',
            'image' => 'akta_kelahiran_option.png'
        ]);

        // Applicant Types for Akta Kelahiran
        ApplicantType::create([
            'registration_option_id' => $regOptionKelahiran->id,
            'type' => 'baru',
            'title' => 'Akta Kelahiran Baru',
            'image' => 'new_birth_cert.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionKelahiran->id,
            'type' => 'pembaruan',
            'title' => 'Pembaruan Akta Kelahiran',
            'image' => 'update_birth_cert.png'
        ]);

        // For Akta Kematian
        $aktaKematian = SubLayanan::where('slug', 'akta-kematian')->first();
        $regOptionKematian = RegistrationOption::create([
            'registrationable_type' => 'App\Models\SubLayanan',
            'registrationable_id' => $aktaKematian->id,
            'type' => 'akta_kematian',
            'title' => 'Pengurusan Akta Kematian',
            'image' => 'akta_kematian_option.png'
        ]);

        // Applicant Types for Akta Kematian
        ApplicantType::create([
            'registration_option_id' => $regOptionKematian->id,
            'type' => 'keluarga',
            'title' => 'Keluarga Almarhum',
            'image' => 'family_deceased.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionKematian->id,
            'type' => 'perwakilan',
            'title' => 'Perwakilan',
            'image' => 'representative.png'
        ]);
    }

    private function createRegistrationOptionsForPindahDatang()
    {
        // For Pindah Datang Dalam Kota
        $pindahDalamKota = SubLayanan::where('slug', 'pindah-datang-dalam-kota')->first();
        $regOptionDalamKota = RegistrationOption::create([
            'registrationable_type' => 'App\Models\SubLayanan',
            'registrationable_id' => $pindahDalamKota->id,
            'type' => 'pindah_dalam_kota',
            'title' => 'Pindah Dalam Kota',
            'image' => 'pindah_dalam_kota_option.png'
        ]);

        // Applicant Types for Pindah Dalam Kota
        ApplicantType::create([
            'registration_option_id' => $regOptionDalamKota->id,
            'type' => 'keluarga',
            'title' => 'Satu Keluarga',
            'image' => 'family_move.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionDalamKota->id,
            'type' => 'perorangan',
            'title' => 'Perorangan',
            'image' => 'individual_move.png'
        ]);

        // For Pindah Masuk Antar Kota
        $pindahAntarKota = SubLayanan::where('slug', 'pindah-masuk-antar-kota')->first();
        $regOptionAntarKota = RegistrationOption::create([
            'registrationable_type' => 'App\Models\SubLayanan',
            'registrationable_id' => $pindahAntarKota->id,
            'type' => 'pindah_antar_kota',
            'title' => 'Pindah Antar Kota',
            'image' => 'pindah_antar_kota_option.png'
        ]);

        // Applicant Types for Pindah Antar Kota
        ApplicantType::create([
            'registration_option_id' => $regOptionAntarKota->id,
            'type' => 'keluarga',
            'title' => 'Satu Keluarga',
            'image' => 'family_move_city.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionAntarKota->id,
            'type' => 'perorangan',
            'title' => 'Perorangan',
            'image' => 'individual_move_city.png'
        ]);
    }

    private function createRegistrationOptionsForSKTSKAW()
    {
        // For SKT
        $skt = SubLayanan::where('slug', 'skt')->first();
        $regOptionSKT = RegistrationOption::create([
            'registrationable_type' => 'App\Models\SubLayanan',
            'registrationable_id' => $skt->id,
            'type' => 'skt',
            'title' => 'Surat Keterangan Tanah',
            'image' => 'skt_option.png'
        ]);

        // Applicant Types for SKT
        ApplicantType::create([
            'registration_option_id' => $regOptionSKT->id,
            'type' => 'pemilik',
            'title' => 'Pemilik Tanah',
            'image' => 'land_owner.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionSKT->id,
            'type' => 'kuasa',
            'title' => 'Penerima Kuasa',
            'image' => 'authorized_person.png'
        ]);

        // For SKAW
        $skaw = SubLayanan::where('slug', 'skaw')->first();
        $regOptionSKAW = RegistrationOption::create([
            'registrationable_type' => 'App\Models\SubLayanan',
            'registrationable_id' => $skaw->id,
            'type' => 'skaw',
            'title' => 'Surat Keterangan Ahli Waris',
            'image' => 'skaw_option.png'
        ]);

        // Applicant Types for SKAW
        ApplicantType::create([
            'registration_option_id' => $regOptionSKAW->id,
            'type' => 'ahli_waris',
            'title' => 'Ahli Waris',
            'image' => 'heir.png'
        ]);

        ApplicantType::create([
            'registration_option_id' => $regOptionSKAW->id,
            'type' => 'perwakilan',
            'title' => 'Perwakilan Ahli Waris',
            'image' => 'heir_representative.png'
        ]);
    }
}
