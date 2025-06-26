<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PanduanController extends Controller
{
    /**
     * Display panduan layanan overview (untuk section di informasi umum)
     */
    public function index(): View
    {
        $data = [
            'title' => 'Panduan Layanan - SILOK',
            'meta_description' => 'Panduan lengkap layanan administrasi kependudukan Kelurahan Jemur Wonosari',
            'kategori_layanan' => $this->getKategoriLayanan(),
            'layanan_populer' => $this->getLayananPopuler(),
            'total_layanan' => count($this->getAllLayanan()),
        ];

        return view('layanan.InformasiUmum.persyaratan-pelayanan', $data);
    }

    /**
     * Display detail layanan by slug
     */
    public function detail(string $slug): View
    {
        $layanan = $this->getLayananBySlug($slug);

        if (!$layanan) {
            abort(404, 'Layanan tidak ditemukan');
        }

        $data = [
            'title' => $layanan['nama'] . ' - Panduan Layanan',
            'meta_description' => $layanan['deskripsi'],
            'layanan' => $layanan,
            'layanan_terkait' => $this->getLayananTerkait($layanan['kategori'], $slug),
        ];

        return view('layanan.InformasiUmum.detail-persyaratan-pelayanan', $data);
    }

    /**
     * Download formulir layanan
     */
    public function downloadFormulir(string $slug)
    {
        $layanan = $this->getLayananBySlug($slug);

        if (!$layanan || !$layanan['has_formulir']) {
            abort(404, 'Formulir tidak tersedia');
        }

        // Path ke file formulir di storage/app/public/formulir/
        $filePath = storage_path('app/public/formulir/' . $slug . '.pdf');

        if (!file_exists($filePath)) {
            abort(404, 'File formulir tidak ditemukan');
        }

        $fileName = 'Formulir_' . str_replace(' ', '_', $layanan['nama']) . '.pdf';

        return response()->download($filePath, $fileName);
    }

    /**
     * API: Search layanan
     */
    public function search(Request $request): JsonResponse
    {
        $query = strtolower($request->get('q', ''));
        $kategori = $request->get('kategori', '');

        $layanan = $this->getAllLayanan();
        $results = [];

        foreach ($layanan as $item) {
            $match = false;

            // Search by nama
            if (empty($query) || str_contains(strtolower($item['nama']), $query)) {
                $match = true;
            }

            // Filter by kategori
            if (!empty($kategori) && $item['kategori'] !== $kategori) {
                $match = false;
            }

            if ($match) {
                $results[] = [
                    'nama' => $item['nama'],
                    'slug' => $item['slug'],
                    'kategori' => $item['kategori'],
                    'deskripsi' => substr($item['deskripsi'], 0, 100) . '...',
                    'estimasi' => $item['estimasi'],
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => array_slice($results, 0, 10) // Limit 10 results
        ]);
    }

    /**
     * API: Get all layanan for list
     */
    public function getLayananList(): JsonResponse
    {
        $layanan = $this->getAllLayanan();

        $results = array_map(function($item) {
            return [
                'nama_layanan' => $item['nama'],
                'slug' => $item['slug'],
                'deskripsi' => $item['deskripsi']
            ];
        }, $layanan);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Data kategori layanan
     */
    private function getKategoriLayanan(): array
    {
        return [
            [
                'nama' => 'Pencatatan Sipil',
                'slug' => 'pencatatan-sipil',
                'deskripsi' => 'Akta kelahiran, akta kematian, dan pencatatan peristiwa penting lainnya',
                'icon' => 'bi-file-earmark-person',
                'color' => '#007bff',
                'total_layanan' => 4
            ],
            [
                'nama' => 'Pendaftaran Penduduk',
                'slug' => 'pendaftaran-penduduk',
                'deskripsi' => 'KTP, KK, KIA dan layanan pendaftaran penduduk lainnya',
                'icon' => 'bi-person-vcard',
                'color' => '#28a745',
                'total_layanan' => 7
            ],
            [
                'nama' => 'Surat Keterangan',
                'slug' => 'surat-keterangan',
                'deskripsi' => 'SKAW, SKT, dan berbagai surat keterangan lainnya',
                'icon' => 'bi-file-text',
                'color' => '#ffc107',
                'total_layanan' => 2
            ]
        ];
    }

    /**
     * Data layanan populer
     */
    private function getLayananPopuler(): array
    {
        return [
            [
                'nama' => 'KTP (Kartu Tanda Penduduk)',
                'slug' => 'ktp-kartu-tanda-penduduk',
                'kategori' => 'Pendaftaran Penduduk',
                'estimasi' => '14 hari kerja',
                'biaya' => 'Gratis',
            ],
            [
                'nama' => 'KK (Kartu Keluarga)',
                'slug' => 'kk-kartu-keluarga',
                'kategori' => 'Pendaftaran Penduduk',
                'estimasi' => '7-14 hari kerja',
                'biaya' => 'Gratis',
            ],
            [
                'nama' => 'Akta Kelahiran',
                'slug' => 'akta-kelahiran',
                'kategori' => 'Pencatatan Sipil',
                'estimasi' => '7-14 hari kerja',
                'biaya' => 'Gratis',
            ],
            [
                'nama' => 'SKAW (Surat Keterangan Ahli Waris)',
                'slug' => 'skaw-surat-keterangan-ahli-waris',
                'kategori' => 'Surat Keterangan',
                'estimasi' => '1-3 hari kerja',
                'biaya' => 'Gratis',
            ]
        ];
    }

    /**
     * Data semua layanan
     */
    private function getAllLayanan(): array
    {
        return [
            // Pencatatan Sipil
            [
                'nama' => 'Akta Kelahiran',
                'slug' => 'akta-kelahiran',
                'kategori' => 'Pencatatan Sipil',
                'deskripsi' => 'Pengurusan akta kelahiran baru dan duplikat akta kelahiran',
                'estimasi' => '7-14 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Surat keterangan lahir dari bidan/dokter/rumah sakit',
                    'Fotokopi KTP kedua orang tua',
                    'Fotokopi Kartu Keluarga',
                    'Fotokopi Akta Nikah orang tua',
                    'Surat pengantar RT/RW'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses pembuatan akta',
                    'Pengambilan dokumen jadi'
                ]
            ],
            [
                'nama' => 'Akta Kematian',
                'slug' => 'akta-kematian',
                'kategori' => 'Pencatatan Sipil',
                'deskripsi' => 'Pengurusan akta kematian untuk keperluan administrasi',
                'estimasi' => '3-7 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Surat keterangan kematian dari dokter/rumah sakit',
                    'Fotokopi KTP almarhum',
                    'Fotokopi KK almarhum',
                    'Fotokopi KTP pelapor',
                    'Surat pengantar RT/RW'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses pembuatan akta',
                    'Pengambilan dokumen jadi'
                ]
            ],

            // Pendaftaran Penduduk
            [
                'nama' => 'KTP (Kartu Tanda Penduduk)',
                'slug' => 'ktp-kartu-tanda-penduduk',
                'kategori' => 'Pendaftaran Penduduk',
                'deskripsi' => 'Pengurusan KTP baru, perpanjangan, dan perubahan data',
                'estimasi' => '14 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Fotokopi Kartu Keluarga',
                    'Fotokopi Akta Kelahiran',
                    'Pas foto 3x4 (2 lembar)',
                    'Surat pengantar RT/RW',
                    'Formulir permohonan'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses pembuatan KTP',
                    'Pengambilan KTP jadi'
                ]
            ],
            [
                'nama' => 'KK (Kartu Keluarga)',
                'slug' => 'kk-kartu-keluarga',
                'kategori' => 'Pendaftaran Penduduk',
                'deskripsi' => 'Pengurusan KK baru, perubahan data, dan cetak ulang',
                'estimasi' => '7-14 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Fotokopi KTP kepala keluarga',
                    'Fotokopi KTP seluruh anggota keluarga',
                    'Fotokopi Akta Nikah/Cerai',
                    'Fotokopi Akta Kelahiran anak',
                    'Surat pengantar RT/RW'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses pembuatan KK',
                    'Pengambilan KK jadi'
                ]
            ],
            [
                'nama' => 'KIA (Kartu Identitas Anak)',
                'slug' => 'kia-kartu-identitas-anak',
                'kategori' => 'Pendaftaran Penduduk',
                'deskripsi' => 'Pengurusan kartu identitas untuk anak usia 0-17 tahun',
                'estimasi' => '7 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Fotokopi Kartu Keluarga',
                    'Fotokopi KTP orang tua',
                    'Fotokopi Akta Kelahiran anak',
                    'Pas foto anak 2x3 (2 lembar)',
                    'Surat pengantar RT/RW'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses pembuatan KIA',
                    'Pengambilan KIA jadi'
                ]
            ],
            [
                'nama' => 'Cetak Ulang KK',
                'slug' => 'cetak-ulang-kk',
                'kategori' => 'Pendaftaran Penduduk',
                'deskripsi' => 'Cetak ulang Kartu Keluarga yang rusak atau hilang',
                'estimasi' => '3 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Surat kehilangan dari kepolisian (jika hilang)',
                    'Fotokopi KTP kepala keluarga',
                    'KK lama (jika masih ada)',
                    'Surat pengantar RT/RW',
                    'Formulir permohonan'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses cetak ulang KK',
                    'Pengambilan KK jadi'
                ]
            ],
            [
                'nama' => 'Cetak Ulang KTP',
                'slug' => 'cetak-ulang-ktp',
                'kategori' => 'Pendaftaran Penduduk',
                'deskripsi' => 'Cetak ulang KTP yang rusak atau hilang',
                'estimasi' => '14 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Surat kehilangan dari kepolisian (jika hilang)',
                    'Fotokopi Kartu Keluarga',
                    'KTP lama (jika masih ada)',
                    'Pas foto 3x4 (2 lembar)',
                    'Surat pengantar RT/RW'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses cetak ulang KTP',
                    'Pengambilan KTP jadi'
                ]
            ],
            [
                'nama' => 'Pindah Datang Dalam Kota',
                'slug' => 'pindah-datang-dalam-kota',
                'kategori' => 'Pendaftaran Penduduk',
                'deskripsi' => 'Layanan perpindahan penduduk dalam satu kota',
                'estimasi' => '5 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Surat pindah dari kelurahan asal',
                    'Fotokopi KTP',
                    'Fotokopi Kartu Keluarga',
                    'Surat kontrak/sewa rumah',
                    'Surat pengantar RT/RW tujuan'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses perpindahan data',
                    'Pengambilan dokumen jadi'
                ]
            ],

            // Surat Keterangan
            [
                'nama' => 'SKAW (Surat Keterangan Ahli Waris)',
                'slug' => 'skaw-surat-keterangan-ahli-waris',
                'kategori' => 'Surat Keterangan',
                'deskripsi' => 'Surat keterangan untuk keperluan waris dan warisan',
                'estimasi' => '1-3 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Fotokopi KTP ahli waris',
                    'Fotokopi KK ahli waris',
                    'Surat kematian asli',
                    'Fotokopi KTP almarhum',
                    'Surat pengantar RT/RW'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses pembuatan surat',
                    'Pengambilan surat jadi'
                ]
            ],
            [
                'nama' => 'SKT (Surat Keterangan Tanah)',
                'slug' => 'skt-surat-keterangan-tanah',
                'kategori' => 'Surat Keterangan',
                'deskripsi' => 'Surat keterangan kepemilikan atau status tanah',
                'estimasi' => '3-5 hari kerja',
                'biaya' => 'Gratis',
                'lokasi' => 'Kantor Kelurahan Jemur Wonosari',
                'jam_layanan' => 'Senin-Kamis: 08:00-15:00, Jumat: 08:00-11:30',
                'has_formulir' => true,
                'persyaratan' => [
                    'Fotokopi KTP pemohon',
                    'Fotokopi Kartu Keluarga',
                    'Fotokopi sertifikat tanah',
                    'Fotokopi PBB tahun terakhir',
                    'Surat pengantar RT/RW'
                ],
                'alur_perizinan' => [
                    'Persiapan dokumen persyaratan',
                    'Pengajuan permohonan di loket',
                    'Verifikasi dokumen oleh petugas',
                    'Proses pembuatan surat',
                    'Pengambilan surat jadi'
                ]
            ]
        ];
    }

    /**
     * Get layanan by slug
     */
    private function getLayananBySlug(string $slug): ?array
    {
        $layanan = $this->getAllLayanan();

        foreach ($layanan as $item) {
            if ($item['slug'] === $slug) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Get layanan terkait
     */
    private function getLayananTerkait(string $kategori, string $currentSlug): array
    {
        $layanan = $this->getAllLayanan();
        $terkait = [];

        foreach ($layanan as $item) {
            if ($item['kategori'] === $kategori && $item['slug'] !== $currentSlug) {
                $terkait[] = [
                    'nama' => $item['nama'],
                    'slug' => $item['slug'],
                    'estimasi' => $item['estimasi'],
                    'biaya' => $item['biaya']
                ];

                if (count($terkait) >= 4) break;
            }
        }

        return $terkait;
    }
}
