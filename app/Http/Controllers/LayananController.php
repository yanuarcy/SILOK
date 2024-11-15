<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Layanan;
use App\Models\SubLayanan;
use App\Models\LayananItem;
use App\Models\RegistrationOption;
use App\Models\ApplicantType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class LayananController extends Controller
{
    // private $layananData = [
    //     'KTP_KK_KIA_IKD' => [
    //         'title' => 'KTP / KK / KIA / IKD',
    //         'image' => 'KTP_KK_KIA.png',
    //         'description' => 'Pengurusan dokumen identitas kependudukan termasuk Kartu Tanda Penduduk, Kartu Keluarga, Kartu Identitas Anak, dan Izin Kependudukan.',
    //         'small' => 'Proses cepat dan akurat',
    //         'subLayanan' => [
    //             'KTP' => [
    //                 'title' => 'KTP',
    //                 'image' => 'KTP_Animasi.png',
    //                 'items' => [
    //                     'Pengambilan KTP' => [
    //                         'title' => 'PENGAMBILAN KTP',
    //                         'image' => 'Pengambilan_KTP.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ]
    //                         ]
    //                     ],
    //                     'Cetak Ulang KTP' => [
    //                         'title' => 'CETAK ULANG KTP',
    //                         'image' => 'CetakUlang_KTP.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ]
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'KK' => [
    //                 'title' => 'KK',
    //                 'image' => 'KK_Animasi.png',
    //                 'items' => [
    //                     'Pengajuan KK Barcode' => [
    //                         'title' => 'PENGAJUAN KK BARCODE',
    //                         'image' => 'Barcode_KK.jpg',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ]
    //                         ]
    //                     ],
    //                     'Perubahan Biodata KK' => [
    //                         'title' => 'PERUBAHAN BIODATA KK',
    //                         'image' => 'Perubahan_KK.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ]
    //                         ]
    //                     ],
    //                     'Pemutahiran Gelar' => [
    //                         'title' => 'PEMUTAHIRAN GELAR',
    //                         'image' => 'Pemutakhiran_Gelar.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                                 ]
    //                         ]
    //                     ],
    //                     'Pecah KK' => [
    //                         'title' => 'PECAH KK',
    //                         'image' => 'Pecah_KK.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                                 ]
    //                         ]
    //                     ],
    //                     'Cetak Ulang KK' => [
    //                         'title' => 'CETAK ULANG KK',
    //                         'image' => 'CetakUlang_KK.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                                 ]
    //                         ]
    //                     ],
    //                     'Pengambilan KK' => [
    //                         'title' => 'PENGAMBILAN KK',
    //                         'image' => 'Pengambilan_KK.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                                 ]
    //                         ]
    //                     ],
    //                     'Buka Blokir Hapus Data Ganda' => [
    //                         'title' => 'BUKA BLOKIR HAPUS DATA GANDA',
    //                         'image' => 'BukaBlokir.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'kantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                                 ]
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'KIA' => [
    //                 'title' => 'KIA',
    //                 'image' => 'KIA_Animasi.png',
    //                 'items' => [
    //                     'Pengambilan KIA' => [
    //                         'title' => 'PENGAMBILAN KIA',
    //                         'image' => 'Pengambilan_KIA.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                                 ]
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'IKD' => [
    //                 'title' => 'IKD',
    //                 'image' => 'IKD_Animasi.png',
    //                 'items' => [
    //                     'Pengajuan IKD' => [
    //                         'title' => 'PENGAJUAN IKD',
    //                         'image' => 'IKD_Animasi.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => [
    //                                 'title' => 'Daftar Di Kelurahan',
    //                                 'image' => 'KantorKelurahan.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ],
    //                             'Online' => [
    //                                 'title' => 'Daftar Online',
    //                                 'image' => 'DaftarOnline.png',
    //                                 'applicantTypes' => [
    //                                     'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                                     'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                                 ]
    //                             ]
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ],
    //     'Akta' => [
    //         'title' => 'AKTA',
    //         'image' => 'Akta.png',
    //         'description' => 'Pembuatan dan pengurusan berbagai jenis akta seperti akta kelahiran, akta kematian, akta perkawinan, dan akta perceraian.',
    //         'small' => 'Dokumen legal yang terjamin',
    //         'subLayanan' => [
    //             'Kelahiran' => [
    //                 'title' => 'Akta Kelahiran',
    //                 'image' => 'Akta_Kelahiran.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'Kematian' => [
    //                 'title' => 'Akta Kematian',
    //                 'image' => 'akta_kematian.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ],
    //     'Pindah Datang' => [
    //         'title' => 'PINDAH DATANG',
    //         'image' => 'datang_dan_pindah.png',
    //         'description' => 'Layanan administrasi untuk proses pindah keluar atau masuk ke wilayah Kelurahan Jemur Wonosari.',
    //         'small' => 'Mudah dan transparan',
    //         'subLayanan' => [
    //             'Pindah Datang Dalam Kota' => [
    //                 'title' => 'Pindah Datang Dalam Kota',
    //                 'image' => 'PindahDatangDalamKota.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'Pindah Masuk Antar Kota Kabupaten' => [
    //                 'title' => 'Pindah Masuk Antar Kota / Kabupaten',
    //                 'image' => 'PindahDatangAntarKota.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'Pindah Keluar Antar Kota Kabupaten' => [
    //                 'title' => 'Pindah Keluar Antar Kota / Kabupaten',
    //                 'image' => 'PindahKeluarAntarKota.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'Penduduk Non Permanen' => [
    //                 'title' => 'Penduduk Non Permanen',
    //                 'image' => 'PendudukNonPermanen2.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'SKPTI Orang Terlantar' => [
    //                 'title' => 'SKPTI Orang Terlantar',
    //                 'image' => 'SKPTI_OrangTerlantar2.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ],
    //     'SKT_SKAW' => [
    //         'title' => 'SKT / SKAW',
    //         'image' => 'SKT_SKAW.png',
    //         'description' => 'Pengurusan Surat Keterangan Tanah dan Surat Keterangan Ahli Waris untuk keperluan administratif dan hukum.',
    //         'small' => 'Proses cepat dan terpercaya',
    //         'subLayanan' => [
    //             'SKT' => [
    //                 'title' => 'Surat Keterangan Tanah',
    //                 'image' => 'SKT.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'SKAW' => [
    //                 'title' => 'Surat Keterangan Ahli Waris',
    //                 'image' => 'SKAW.png',
    //                 'registrationOptions' => [
    //                     'Daftar Di Kelurahan' => [
    //                         'title' => 'Daftar Di Kelurahan',
    //                         'image' => 'KantorKelurahan.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ],
    //                     'Online' => [
    //                         'title' => 'Daftar Online',
    //                         'image' => 'DaftarOnline.png',
    //                         'applicantTypes' => [
    //                             'Baru' => ['title' => 'Pemohon Baru', 'image' => 'PemohonBaru.png'],
    //                             'Lama' => ['title' => 'Pemohon Lama', 'image' => 'PemohonLama.png']
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ],
    //     'LayananKelurahan' => [
    //         'title' => 'LAYANAN KELURAHAN',
    //         'image' => 'LayananKelurahan.png',
    //         'description' => 'Berbagai layanan administratif lainnya yang disediakan oleh kelurahan, seperti surat pengantar atau keterangan.',
    //         'small' => 'Pelayanan prima untuk warga',
    //         'subLayanan' => [
    //             'SuratPengantar' => [
    //                 'title' => 'Surat Pengantar',
    //                 'image' => 'surat_pengantar.png',
    //                 'items' => [
    //                     'Pengajuan Surat Pengantar' => [
    //                         'title' => 'PENGAJUAN SURAT PENGANTAR',
    //                         'image' => 'pengajuan_surat_pengantar.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => ['title' => 'Daftar Di Kelurahan', 'image' => 'daftar_kelurahan.png'],
    //                             'Online' => ['title' => 'Daftar Online', 'image' => 'daftar_online.png']
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'SuratKeterangan' => [
    //                 'title' => 'Surat Keterangan',
    //                 'image' => 'surat_keterangan.png',
    //                 'items' => [
    //                     'Pengajuan Surat Keterangan' => [
    //                         'title' => 'PENGAJUAN SURAT KETERANGAN',
    //                         'image' => 'pengajuan_surat_keterangan.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => ['title' => 'Daftar Di Kelurahan', 'image' => 'daftar_kelurahan.png'],
    //                             'Online' => ['title' => 'Daftar Online', 'image' => 'daftar_online.png']
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ],
    //     'Konsultasi' => [
    //         'title' => 'KONSULTASI',
    //         'image' => 'Konsultasi.png',
    //         'description' => 'Layanan konsultasi terkait administrasi kependudukan dan pelayanan publik di tingkat kelurahan.',
    //         'small' => 'Solusi untuk setiap pertanyaan Anda',
    //         'subLayanan' => [
    //             'KonsultasiUmum' => [
    //                 'title' => 'Konsultasi Umum',
    //                 'image' => 'konsultasi_umum.png',
    //                 'items' => [
    //                     'Pengajuan Konsultasi' => [
    //                         'title' => 'PENGAJUAN KONSULTASI',
    //                         'image' => 'pengajuan_konsultasi.png',
    //                         'registrationOptions' => [
    //                             'Di Kelurahan' => ['title' => 'Daftar Di Kelurahan', 'image' => 'daftar_kelurahan.png'],
    //                             'Online' => ['title' => 'Daftar Online', 'image' => 'daftar_online.png']
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ]
    // ];

    // public function index()
    // {
    //     return view('layanan.adminduk.index', ['layananList' => $this->layananData]);
    // }

    // public function show($layananType)
    // {
    //     if (!array_key_exists($layananType, $this->layananData)) {
    //         abort(404);
    //     }

    //     $layanan = $this->layananData[$layananType];
    //     return view('layanan.adminduk.show', ['layanan' => $layanan, 'layananType' => $layananType]);
    // }

    // public function showSubLayanan($layananType, $subLayananType)
    // {
    //     if (!array_key_exists($layananType, $this->layananData) ||
    //         !array_key_exists($subLayananType, $this->layananData[$layananType]['subLayanan'])) {
    //         abort(404);
    //     }

    //     $subLayanan = $this->layananData[$layananType]['subLayanan'][$subLayananType];
    //     $hasItems = $layananType === 'KTP_KK_KIA_IKD';

    //     return view('layanan.adminduk.show_sub_layanan', [
    //         'layananType' => $layananType,
    //         'subLayanan' => $subLayanan,
    //         'subLayananType' => $subLayananType,
    //         'hasItems' => $hasItems
    //     ]);
    // }

    // public function showRegistrationOptions($layananType, $subLayananType, $itemType = null)
    // {
    //     if (!array_key_exists($layananType, $this->layananData) ||
    //         !array_key_exists($subLayananType, $this->layananData[$layananType]['subLayanan'])) {
    //         abort(404);
    //     }

    //     $subLayanan = $this->layananData[$layananType]['subLayanan'][$subLayananType];

    //     if ($layananType === 'KTP_KK_KIA_IKD') {
    //         if (!$itemType || !array_key_exists($itemType, $subLayanan['items']) ||
    //             !array_key_exists('registrationOptions', $subLayanan['items'][$itemType])) {
    //             abort(404);
    //         }
    //         $options = $subLayanan['items'][$itemType]['registrationOptions'];
    //         $title = $subLayanan['items'][$itemType]['title'];
    //     } else {
    //         if (!array_key_exists('registrationOptions', $subLayanan) ||
    //             !array_key_exists('Daftar Di Kelurahan', $subLayanan['registrationOptions'])) {
    //             abort(404);
    //         }
    //         $options = $subLayanan['registrationOptions']['Daftar Di Kelurahan']['applicantTypes'];
    //         $title = $subLayanan['title'];
    //     }

    //     return view('layanan.adminduk.show_registration_options', [
    //         'layananType' => $layananType,
    //         'subLayananType' => $subLayananType,
    //         'itemType' => $itemType,
    //         'options' => $options,
    //         'title' => $title
    //     ]);
    // }

    // public function showApplicantTypes($layananType, $subLayananType, $itemType = null, $registrationType)
    // {
    //     if (!array_key_exists($layananType, $this->layananData) ||
    //         !array_key_exists($subLayananType, $this->layananData[$layananType]['subLayanan'])) {
    //         abort(404);
    //     }

    //     $subLayanan = $this->layananData[$layananType]['subLayanan'][$subLayananType];

    //     if ($layananType === 'KTP_KK_KIA_IKD') {
    //         if (!$itemType || !array_key_exists($itemType, $subLayanan['items']) ||
    //             !array_key_exists('registrationOptions', $subLayanan['items'][$itemType]) ||
    //             !array_key_exists($registrationType, $subLayanan['items'][$itemType]['registrationOptions'])) {
    //             abort(404);
    //         }
    //         $registrationOption = $subLayanan['items'][$itemType]['registrationOptions'][$registrationType];
    //     } else {
    //         if (!array_key_exists('registrationOptions', $subLayanan) ||
    //             !array_key_exists('Daftar Di Kelurahan', $subLayanan['registrationOptions']) ||
    //             !array_key_exists('applicantTypes', $subLayanan['registrationOptions']['Daftar Di Kelurahan']) ||
    //             !array_key_exists($registrationType, $subLayanan['registrationOptions']['Daftar Di Kelurahan']['applicantTypes'])) {
    //             abort(404);
    //         }
    //         $registrationOption = [
    //             'title' => $subLayanan['registrationOptions']['Daftar Di Kelurahan']['title'],
    //             'applicantTypes' => [
    //                 $registrationType => $subLayanan['registrationOptions']['Daftar Di Kelurahan']['applicantTypes'][$registrationType]
    //             ]
    //         ];
    //     }

    //     return view('layanan.adminduk.show_applicant_types', [
    //         'layananType' => $layananType,
    //         'subLayananType' => $subLayananType,
    //         'itemType' => $itemType,
    //         'registrationType' => $registrationType,
    //         'registrationOption' => $registrationOption
    //     ]);
    // }

    // public function showRegistrationForm($layananType, $subLayananType, $itemType = null, $registrationType = null, $applicantType = null)
    // {
    //     if (!array_key_exists($layananType, $this->layananData) ||
    //         !array_key_exists($subLayananType, $this->layananData[$layananType]['subLayanan'])) {
    //         abort(404);
    //     }

    //     $subLayanan = $this->layananData[$layananType]['subLayanan'][$subLayananType];

    //     if ($layananType === 'KTP_KK_KIA_IKD') {
    //         // Kode untuk KTP_KK_KIA_IKD tetap sama
    //         if (!array_key_exists($itemType, $subLayanan['items']) ||
    //             !array_key_exists($registrationType, $subLayanan['items'][$itemType]['registrationOptions'])) {
    //             abort(404);
    //         }
    //         $registrationOption = $subLayanan['items'][$itemType]['registrationOptions'][$registrationType];
    //         $isNewApplicant = ($applicantType === 'Baru');
    //     } else {
    //         // Untuk layanan non-KTP_KK_KIA_IKD
    //         if (!array_key_exists('registrationOptions', $subLayanan) ||
    //             !array_key_exists($itemType, $subLayanan['registrationOptions']) ||
    //             !array_key_exists('applicantTypes', $subLayanan['registrationOptions'][$itemType]) ||
    //             !array_key_exists($registrationType, $subLayanan['registrationOptions'][$itemType]['applicantTypes'])) {
    //             abort(404);
    //         }
    //         $registrationOption = $subLayanan['registrationOptions'][$itemType];
    //         $isNewApplicant = ($applicantType === 'Baru');
    //     }

    //     $isOnlineRegistration = ($itemType === 'Online' || $registrationType === 'Online');
    //     $deliveryOptions = $isOnlineRegistration ? [
    //         'Datang ke kelurahan' => 'Datang ke kelurahan',
    //         'Dikirim ke rumah' => 'Dikirim ke rumah'
    //     ] : [];

    //     $registrationTitle = $registrationOption['title'];
    //     return view('layanan.adminduk.registration_form', [
    //         'layananType' => $layananType,
    //         'subLayananType' => $subLayananType,
    //         'itemType' => $itemType,
    //         'registrationType' => $registrationType,
    //         'applicantType' => $applicantType,
    //         'isNewApplicant' => $isNewApplicant,
    //         'registrationTitle' => $registrationTitle,
    //         'isOnlineRegistration' => $isOnlineRegistration,
    //         'deliveryOptions' => $deliveryOptions,
    //     ]);
    // }

    public function index()
    {
        $layananList = Layanan::all();
        return view('layanan.adminduk.index', compact('layananList'));
    }

    public function show(Layanan $layanan) // Now using model binding
    {
        if (!$layanan->has_sub_layanan) {
            return view('layanan.adminduk.show_registration_options', [
                'layananType' => $layanan->slug,
                'subLayananType' => null,
                'itemType' => null,
                'options' => RegistrationOption::all(),
                'title' => $layanan->title
            ]);
        }

        return view('layanan.adminduk.show', [
            'layanan' => [
                'subLayanans' => $layanan->subLayanans->map(function($subLayanan) {
                    return [
                        'slug' => $subLayanan->slug,  // pastikan ada field slug
                        'title' => $subLayanan->title,
                        'image' => $subLayanan->image
                    ];
                })->toArray()
            ],
            'layananType' => $layanan->slug
        ]);
    }

    public function showSubLayanan(Layanan $layanan, SubLayanan $subLayanan)
    {
        // Validasi bahwa subLayanan benar-benar milik layanan ini
        if ($subLayanan->layanan_id !== $layanan->id) {
            abort(404);
        }

        // Data untuk breadcrumb dan navigasi
        $commonData = [
            'layanan' => $layanan,
            'subLayanan' => $subLayanan,
            'layananType' => $layanan->slug,
            'subLayananType' => $subLayanan->slug,
            'itemType' => null,
            'title' => $subLayanan->title
        ];

        if (!$subLayanan->has_items) {
            return view('layanan.adminduk.show_registration_options', array_merge($commonData, [
                'options' => RegistrationOption::all(),
                'hasItems' => false
            ]));
        }

        // Load relasi items jika diperlukan
        $subLayanan->load('items');

        return view('layanan.adminduk.show_sub_layanan', array_merge($commonData, [
            'hasItems' => true,
            'items' => $subLayanan->items // Pastikan items sudah di-load
        ]));
    }

    public function showRegistrationOptions(Layanan $layanan, SubLayanan $subLayanan = null, $itemType = null)
    {
        $options = RegistrationOption::all();

        return view('layanan.adminduk.show_registration_options', [
            'layananType' => $layanan->slug,
            'subLayananType' => $subLayanan ? $subLayanan->slug : 'none',
            'itemType' => $itemType ?? 'none',
            'options' => $options,
            'title' => $subLayanan ? $subLayanan->title : $layanan->title,
            'layanan' => $layanan,
            'subLayanan' => $subLayanan
        ]);
    }

    public function showApplicantTypes(Layanan $layanan, $subLayanan, $itemType, $registrationType)
    {
        // Cari registration option berdasarkan type
        $registrationOption = RegistrationOption::where('type', $registrationType)->firstOrFail();

        // Ambil semua applicant types
        $applicantTypes = ApplicantType::all();

        // Handle subLayanan
        $subLayananModel = null;
        if ($subLayanan !== 'none') {
            $subLayananModel = SubLayanan::where('slug', $subLayanan)->first();
        }

        return view('layanan.adminduk.show_applicant_types', [
            'layananType' => $layanan->slug,
            'subLayananType' => $subLayanan !== 'none' ? $subLayanan : null,
            'itemType' => $itemType === 'none' ? null : $itemType,
            'registrationType' => $registrationType,
            'registrationOption' => $registrationOption,
            'applicantTypes' => $applicantTypes,
            'layanan' => $layanan,
            'subLayanan' => $subLayananModel
        ]);
    }


    public function showRegistrationForm(
        Layanan $layanan,
        $subLayanan,
        $itemType,
        $registrationType = null,
        $applicantType = null
    ) {
        // Handle subLayanan
        $subLayananModel = null;
        if ($subLayanan !== 'none') {
            $subLayananModel = SubLayanan::where('slug', $subLayanan)->first();
        }

        // Set judul registrasi
        $registrationTitle = $subLayananModel ? $subLayananModel->title : $layanan->title;

        // Logic untuk tipe pemohon dan jenis registrasi
        $isNewApplicant = $applicantType === 'baru'; // sesuaikan dengan slug yang digunakan
        $isOnlineRegistration = $registrationType === 'online'; // untuk registrasi online

        // Opsi pengiriman hanya untuk registrasi online
        $deliveryOptions = [
            'pickupAtKelurahan' => 'Ambil Di Kelurahan',
            'pickupAtBalaiRW' => 'Ambil Di Balai RW',
            'delivery' => 'Diantar ke Alamat'
        ];

        return view('layanan.adminduk.registration_form', [
            'layananType' => $layanan->slug,
            'subLayananType' => $subLayanan !== 'none' ? $subLayanan : null,
            'itemType' => $itemType === 'none' ? null : $itemType,
            'registrationType' => $registrationType,
            'applicantType' => $applicantType,
            'registrationTitle' => $registrationTitle,
            'isNewApplicant' => $isNewApplicant,
            'isOnlineRegistration' => $isOnlineRegistration,
            'deliveryOptions' => $deliveryOptions,
            'layanan' => $layanan,
            'subLayanan' => $subLayananModel
        ]);
    }

    // CRUD methods for admin
    public function create()
    {
        return view('admin.masterdata.Layanan.create', ['type_menu' => 'master-data']);
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Request data:', $request->all());  // Tambahkan logging untuk debugging

            $existingLayanan = Layanan::where('title', $request->title)->first();
            if ($existingLayanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Title layanan sudah terdaftar!'
                ], 422);
            }

            // Validasi dasar terlebih dahulu
            $mainValidation = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|string',
                'small' => 'required|string|max:255',
                'has_sub_layanan' => 'boolean',
            ]);

            // Validasi sub layanan jika checkbox dicentang
            if ($request->has('has_sub_layanan') && $request->boolean('has_sub_layanan')) {
                if (!$request->has('sub_layanan') || !is_array($request->sub_layanan)) {
                    throw ValidationException::withMessages([
                        'sub_layanan' => ['Sub layanan is required when Has Sub Layanan is checked']
                    ]);
                }

                foreach ($request->sub_layanan as $index => $subLayanan) {
                    $subValidation = $request->validate([
                        "sub_layanan.$index.title" => 'required|string|max:255',
                        "sub_layanan.$index.image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        "sub_layanan.$index.has_items" => 'boolean',
                    ]);

                    // Validasi items jika has_items dicentang
                    if (isset($subLayanan['has_items']) && $subLayanan['has_items']) {
                        if (!isset($subLayanan['items']) || !is_array($subLayanan['items'])) {
                            throw ValidationException::withMessages([
                                "sub_layanan.$index.items" => ['Items are required when Has Items is checked']
                            ]);
                        }

                        foreach ($subLayanan['items'] as $itemIndex => $item) {
                            $request->validate([
                                "sub_layanan.$index.items.$itemIndex.title" => 'required|string|max:255',
                                "sub_layanan.$index.items.$itemIndex.image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                            ]);
                        }
                    }
                }
            }

            \DB::beginTransaction();

            try {
                // Upload and save main layanan image
                $imageName = $request->image->getClientOriginalName();
                $path = public_path('img/layanan');

                // Jika file dengan nama yang sama sudah ada, tambahkan number
                $counter = 1;
                $newImageName = $imageName;
                while (File::exists($path . '/' . $newImageName)) {
                    $filename = pathinfo($imageName, PATHINFO_FILENAME);
                    $extension = pathinfo($imageName, PATHINFO_EXTENSION);
                    $newImageName = $filename . '_' . $counter . '.' . $extension;
                    $counter++;
                }

                $request->image->move($path, $newImageName);

                // Create layanan
                $layanan = Layanan::create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'image' => $newImageName,
                    'description' => $request->description,
                    'small' => $request->small,
                    'has_sub_layanan' => $request->boolean('has_sub_layanan')
                ]);

                // Handle sub layanan if exists
                if ($request->boolean('has_sub_layanan') && $request->has('sub_layanan')) {
                    foreach ($request->sub_layanan as $subLayananData) {
                        // Upload sub layanan image
                        $subImageName = $subLayananData['image']->getClientOriginalName();
                        $counter = 1;
                        $newSubImageName = $subImageName;
                        while (File::exists($path . '/' . $newSubImageName)) {
                            $filename = pathinfo($subImageName, PATHINFO_FILENAME);
                            $extension = pathinfo($subImageName, PATHINFO_EXTENSION);
                            $newSubImageName = $filename . '_' . $counter . '.' . $extension;
                            $counter++;
                        }

                        $subLayananData['image']->move($path, $newSubImageName);

                        // Create sub layanan
                        $subLayanan = SubLayanan::create([
                            'layanan_id' => $layanan->id,
                            'title' => $subLayananData['title'],
                            'slug' => Str::slug($subLayananData['title']),
                            'image' => $newSubImageName,
                            'has_items' => isset($subLayananData['has_items']) && $subLayananData['has_items'] == '1'
                        ]);

                        // Handle items if exists
                        if (isset($subLayananData['has_items']) &&
                            $subLayananData['has_items'] == '1' &&
                            isset($subLayananData['items'])) {

                            foreach ($subLayananData['items'] as $itemData) {
                                $itemImageName = $itemData['image']->getClientOriginalName();
                                $counter = 1;
                                $newItemImageName = $itemImageName;
                                while (File::exists($path . '/' . $newItemImageName)) {
                                    $filename = pathinfo($itemImageName, PATHINFO_FILENAME);
                                    $extension = pathinfo($itemImageName, PATHINFO_EXTENSION);
                                    $newItemImageName = $filename . '_' . $counter . '.' . $extension;
                                    $counter++;
                                }

                                $itemData['image']->move($path, $newItemImageName);

                                LayananItem::create([
                                    'sub_layanan_id' => $subLayanan->id,
                                    'title' => $itemData['title'],
                                    'slug' => Str::slug($itemData['title']),
                                    'image' => $newItemImageName
                                ]);
                            }
                        }
                    }
                }

                \DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Layanan berhasil ditambahkan!'
                ]);

                // return redirect()->route('masterdata.layanan')
                //     ->with('success', 'Layanan created successfully.');

            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Error in transaction: ' . $e->getMessage());
                throw $e;
            }

        } catch (ValidationException $e) {
            \Log::error('Validation error: ', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Unexpected error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getData(Request $request)
    {
        $query = Layanan::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image', function($layanan) {
                return '<img src="'.asset('/img/layanan/'.$layanan->image).'" height="50">';
            })
            ->editColumn('has_sub_layanan', function($layanan) {
                return $layanan->has_sub_layanan ? 'Yes' : 'No';
            })
            ->editColumn('created_at', function($layanan) {
                return $layanan->created_at->format('d F Y');
            })
            ->addColumn('actions', function($layanan) {
                return '
                    <div class="d-flex justify-content-center gap-2">
                        <a href="'.route('layanan.edit', $layanan->id).'" class="btn btn-warning btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'.route('layanan.destroy', $layanan->id).'" method="POST" class="delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-name="'.$layanan->title.'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['image', 'actions'])
            ->make(true);
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('admin.masterdata.Layanan.edit', [
            'type_menu' => 'master-data',
            'layanan' => $layanan
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $layanan = Layanan::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|string',
                'small' => 'required|string|max:255',
                'has_sub_layanan' => 'sometimes|boolean',
                'new_sub_layanan.*.title' => 'required_if:has_sub_layanan,1|string|max:255',
                'new_sub_layanan.*.image' => 'required_if:has_sub_layanan,1|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'new_sub_layanan.*.has_items' => 'sometimes|nullable|boolean'
            ]);

            // Update layanan
            if ($request->hasFile('image')) {
                if ($layanan->image && file_exists(public_path('img/layanan/'.$layanan->image))) {
                    unlink(public_path('img/layanan/'.$layanan->image));
                }

                $imageName = $request->file('image')->getClientOriginalName();
                $request->image->move(public_path('img/layanan'), $imageName);
                $layanan->image = $imageName;
            }

            $layanan->title = $request->title;
            $layanan->description = $request->description;
            $layanan->small = $request->small;
            $layanan->has_sub_layanan = $request->boolean('has_sub_layanan');
            $layanan->save();

            // Only handle sub layanan if has_sub_layanan is true
            if ($request->boolean('has_sub_layanan')) {
                if ($request->has('new_sub_layanan') && is_array($request->new_sub_layanan)) {
                    foreach ($request->new_sub_layanan as $subLayananData) {
                        if (isset($subLayananData['title']) && isset($subLayananData['image']) && $subLayananData['image'] instanceof UploadedFile) {
                            $imageName = $subLayananData['image']->getClientOriginalName();
                            $subLayananData['image']->move(public_path('img/layanan'), $imageName);

                            SubLayanan::create([
                                'layanan_id' => $layanan->id,
                                'title' => $subLayananData['title'],
                                'slug' => Str::slug($subLayananData['title']),
                                'image' => $imageName,
                                'has_items' => isset($subLayananData['has_items']) ? boolval($subLayananData['has_items']) : false
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Layanan updated successfully',
                'data' => $layanan->load('subLayanans')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating Layanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            \DB::beginTransaction();

            $layanan = Layanan::findOrFail($id);

            // Delete associated sub layanan images
            foreach ($layanan->subLayanans as $subLayanan) {
                // Delete associated item images
                foreach ($subLayanan->items as $item) {
                    if (file_exists(public_path('img/layanan/'.$item->image))) {
                        unlink(public_path('img/layanan/'.$item->image));
                    }
                    $item->delete();
                }

                if (file_exists(public_path('img/layanan/'.$subLayanan->image))) {
                    unlink(public_path('img/layanan/'.$subLayanan->image));
                }
                $subLayanan->delete();
            }

            // Delete main layanan image
            if (file_exists(public_path('img/layanan/'.$layanan->image))) {
                unlink(public_path('img/layanan/'.$layanan->image));
            }

            // Delete the layanan
            $layanan->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error deleting layanan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
