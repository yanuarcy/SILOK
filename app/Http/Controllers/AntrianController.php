<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Queue;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Layanan;
use App\Models\SubLayanan;
use App\Models\LayananItem;
use App\Models\RegistrationOption;
use App\Models\ApplicantType;
use App\Models\Loket;
use App\Models\APIWhatsappData;
use App\Models\Pemohon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;



class AntrianController extends Controller
{
    public function index()
    {
        // Get current user's loket information
        $userLoket = $this->getCurrentUserLoket();
        $type_menu = 'dashboard'; // Tambahkan ini

        return view('admin.dashboard.front-office', compact('userLoket', 'type_menu'));
    }

    // Method untuk mendapatkan loket petugas saat ini
    private function getCurrentUserLoket()
    {
        $user = auth()->user();

        // Assuming there's a relationship or field that connects user to loket
        // Sesuaikan dengan struktur database Anda
        $loket = Loket::where('user_id', $user->id)
                    //  ->orWhere('nama_front_office', $user->name)
                     ->first();

        return (string) $loket ? $loket->loket_number : '1'; // Default to Loket 1 if not found
    }

    public function getData(Request $request)
    {
        try {
            $jenisAntrian = $request->input('jenis_antrian', 'Offline');
            $tanggal = now()->toDateString();

            $query = Antrian::where('tanggal', $tanggal)
                        ->where('jenis_antrian', $jenisAntrian);

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Error in getData: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error processing request'
            ], 500);
        }
    }

    private function hasActiveCall($loket)
    {
        $today = now()->toDateString();

        return Antrian::where('tanggal', $today)
                     ->where('calling_by', 'LIKE', "Loket {$loket}%")
                     ->whereNotNull('calling_by')
                     ->where('calling_by', '!=', '')
                     ->exists();
    }

    public function call(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $loket = $request->loket;

            // Check if this loket already has an active call
            if ($this->hasActiveCall($loket)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki panggilan aktif. Silakan end call terlebih dahulu.'
                ]);
            }

            // Check if antrian is already being called by another loket
            if (!empty($antrian->calling_by)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Antrian sedang dipanggil oleh loket lain.'
                ]);
            }

            // Make the call
            $antrian->calling_by = 'Loket ' . $loket;
            $antrian->updated_date = now();
            $antrian->save();


            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil dipanggil'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function endCall(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $currentLoket = $this->getCurrentUserLoket();

            // Check if current loket is the one calling this antrian
            if (!preg_match("/Loket {$currentLoket}/", $antrian->calling_by)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Anda tidak memiliki akses untuk mengakhiri panggilan ini.'
                ]);
            }

            // End the call
            $antrian->calling_by = '';
            $antrian->save();

            return response()->json([
                'success' => true,
                'message' => 'Panggilan berhasil diakhiri'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function kirimPesan(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $currentLoket = $this->getCurrentUserLoket();

            // Check status
            if ($antrian->status == "0") {
                $antrian->status = '1';
                // $antrian->calling_by = 'Loket ' . $currentLoket . ' - Pesan Terkirim';
                $antrian->updated_date = now();
                $antrian->save();

                $pemohon = Pemohon::where('nama', $antrian->nama)
                    ->whereDate('tanggal', $antrian->tanggal)
                    ->where('jenis_layanan', $antrian->jenis_layanan)
                    ->where('status', '0')
                    ->first();

                if ($pemohon) {
                    $pemohon->markAsTerlayani(auth()->user()->name ?? 'System');
                }

                // Add to queue system for display
                // $this->addToQueueSystemAsync($antrian, $currentLoket);
                $this->addToQueueDirect($antrian, $currentLoket);

                // TODO Add WhatsApp sending logic here
                $this->sendWhatsAppNotification($antrian, 'complete');

                return response()->json([
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim dan antrian ditandai terlayani'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Status sudah diupdate sebelumnya'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Kirim pesan error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function panggil(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $currentLoket = $this->getCurrentUserLoket();

            // Check status
            if ($antrian->status == "0") {
                $antrian->status = '1';
                $antrian->calling_by = 'Loket ' . $currentLoket . ' - ' . (auth()->user()->name ?? 'System');
                $antrian->updated_date = now();
                $antrian->save();

                $pemohon = Pemohon::where('nama', $antrian->nama)
                    ->whereDate('tanggal', $antrian->tanggal)
                    ->where('jenis_layanan', $antrian->jenis_layanan)
                    ->where('status', '0')
                    ->first();

                if ($pemohon) {
                    $pemohon->markAsTerlayani(auth()->user()->name ?? 'System');
                }

                // Add to queue system for display
                // $this->addToQueueSystemAsync($antrian, $currentLoket);
                $this->addToQueueDirect($antrian, $currentLoket);

                $this->sendWhatsAppNotification($antrian, 'call');

                return response()->json([
                    'success' => true,
                    'message' => 'Antrian berhasil dipanggil'
                ]);
            } else {
                $antrian->calling_by = 'Loket ' . $currentLoket . ' - ' . (auth()->user()->name ?? 'System');
                $antrian->updated_date = now();
                $antrian->save();

                $this->addToQueueDirect($antrian, $currentLoket);

                return response()->json([
                    'success' => true,
                    'message' => 'Status sudah diupdate sebelumnya'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Panggil antrian error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function addToQueueSystemAsync($antrian, $loket)
    {
        // Run this in a separate process to avoid blocking the main response
        try {
            $data = [
                'antrian' => $antrian->no_antrian,
                'loket' => (string) $loket, // Ensure loket is string
                'nama' => $antrian->nama ?? '',
                'whatsapp' => $antrian->no_whatsapp ?? ''
            ];

            Log::info('Sending to queue system', [
                'data' => $data,
                'url' => url('/api/queue/add')
            ]);

            // Use shorter timeout and better error handling
            $response = Http::timeout(5)
                ->retry(2, 1000) // Retry 2 times with 1 second delay
                ->post(url('/api/queue/add'), $data);

            if ($response->successful()) {
                Log::info('Successfully added to queue system', [
                    'antrian' => $antrian->no_antrian,
                    'loket' => $loket,
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Failed to add to queue system', [
                    'antrian' => $antrian->no_antrian,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection timeout specifically
            Log::error('Connection timeout to queue system', [
                'error' => $e->getMessage(),
                'antrian' => $antrian->no_antrian
            ]);

            // Try direct database insert as fallback
            $this->addToQueueDirectly($antrian, $loket);

        } catch (\Exception $e) {
            Log::error('Add to queue system error', [
                'error' => $e->getMessage(),
                'antrian' => $antrian->no_antrian
            ]);

            // Try direct database insert as fallback
            $this->addToQueueDirectly($antrian, $loket);
        }
    }

    /**
     * Direct database insert as fallback
     */
    private function addToQueueDirect($antrian, $loket)
    {
        try {
            // Check if already exists to prevent duplicates
            $existingQueue = Queue::where('antrian', $antrian->no_antrian)
                ->where('loket', (string) $loket)
                ->where('status', 'pending')
                ->first();

            if ($existingQueue) {
                Log::info('Queue already exists, skipping', [
                    'antrian' => $antrian->no_antrian,
                    'existing_id' => $existingQueue->id
                ]);
                return $existingQueue;
            }

            $queue = Queue::create([
                'antrian' => $antrian->no_antrian,
                'loket' => (string) $loket,
                'nama' => $antrian->nama ?? '',
                'whatsapp' => $antrian->no_whatsapp ?? '',
                'status' => 'pending'
            ]);

            Log::info('Direct queue insert successful', [
                'queue_id' => $queue->id,
                'antrian' => $antrian->no_antrian,
                'loket' => $loket
            ]);

            return $queue;

        } catch (\Exception $e) {
            Log::error('Direct queue insert failed', [
                'error' => $e->getMessage(),
                'antrian' => $antrian->no_antrian
            ]);
            return null;
        }
    }

    // ========== METHOD BARU UNTUK REGISTRASI LAYANAN ==========

    public function submitRegistration(Request $request)
    {
        // Validasi input
        $rules = [
            'layanan_slug' => 'required|string',
            'registration_type' => 'required|string',
            'applicant_type' => 'required|string',
        ];

        // Validasi berbeda untuk pemohon baru dan lama
        if ($request->applicant_type === 'baru') {
            $rules['nama'] = 'required|string|max:255';
            $rules['whatsapp'] = 'required|string';
            $rules['alamat'] = 'required|string';
        } else {
            $rules['kode_pemohon'] = 'required|string|max:50';
        }

        // Validasi jenis pengiriman untuk registrasi online
        if ($request->registration_type === 'online') {
            $rules['jenis_pengiriman'] = 'required|string';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // Clean nomor WhatsApp sebelum disimpan (jika bukan dash)
            $whatsappNumber = $request->whatsapp;
            if ($request->whatsapp !== '-') {
                $whatsappNumber = $this->cleanPhoneNumber($request->whatsapp);
            }

            // Ambil data pemohon lama jika diperlukan
            $userData = [];
            if ($request->applicant_type === 'lama') {
                $existingUser = Antrian::where('id', $request->kode_pemohon)
                    ->orWhere('nama', 'LIKE', '%' . $request->kode_pemohon . '%')
                    ->first();

                if ($existingUser) {
                    $userData = [
                        'nama' => $existingUser->nama,
                        'no_whatsapp' => $existingUser->no_whatsapp,
                        'alamat' => $existingUser->alamat,
                    ];
                } else {
                    return back()->withErrors(['kode_pemohon' => 'Kode pemohon tidak ditemukan'])->withInput();
                }
            } else {
                $userData = [
                    'nama' => $request->nama,
                    'no_whatsapp' => $whatsappNumber,
                    'alamat' => $request->alamat,
                ];
            }

            // Ambil data layanan berdasarkan slug
            $layanan = Layanan::where('slug', $request->layanan_slug)->first();
            $subLayanan = null;
            if ($request->sub_layanan_slug && $request->sub_layanan_slug !== 'none') {
                $subLayanan = SubLayanan::where('slug', $request->sub_layanan_slug)->first();
            }

            // Mapping jenis layanan berdasarkan slug
            $jenisLayananMap = [
                'ktp-kk-kia-ikd' => 'KTP/KK/KIA/IKD',
                'akta' => 'AKTA',
                'pindah-datang' => 'PINDAH DATANG',
                'skt-skaw' => 'SKT/SKAW',
                'layanan-kelurahan' => 'LAYANAN KELURAHAN',
                'konsultasi' => 'KONSULTASI'
            ];

            // Mapping keterangan berdasarkan item_type
            $keteranganMap = [
                'pengambilan-ktp' => 'Pengambilan KTP',
                'cetak-ulang-ktp' => 'Cetak Ulang KTP',
                'pembuatan-ktp-baru' => 'Pembuatan KTP Baru',
                'perubahan-data-ktp' => 'Perubahan Data KTP',
                'pembuatan-kk-baru' => 'Pembuatan KK Baru',
                'perubahan-data-kk' => 'Perubahan Data KK',
                'akta-kelahiran' => 'Akta Kelahiran',
                'akta-kematian' => 'Akta Kematian',
                'akta-perkawinan' => 'Akta Perkawinan',
                'akta-perceraian' => 'Akta Perceraian',
                'surat-pindah' => 'Surat Pindah',
                'surat-datang' => 'Surat Datang',
                'skt' => 'Surat Keterangan Tanah',
                'skaw' => 'Surat Keterangan Ahli Waris',
                'konsultasi-umum' => 'Konsultasi Umum'
            ];

            // Mapping jenis antrian
            $jenisAntrianMap = [
                'kelurahan' => 'Offline',
                'balai-rw' => 'Offline',
                'online' => 'Online'
            ];

            // Mapping jenis pengiriman
            $jenisPengirimanMap = [
                'pickupAtKelurahan' => 'Ambil di Kelurahan',
                'pickupAtBalaiRW' => 'Ambil di Balai RW',
                'delivery' => 'Diantar ke Alamat'
            ];

            // Tentukan jenis layanan
            $jenisLayanan = $jenisLayananMap[$request->layanan_slug] ??
                        ($layanan ? $layanan->title : Str::title(str_replace('-', ' ', $request->layanan_slug)));

            // Generate nomor antrian berdasarkan jenis layanan dengan kode dinamis
            $noAntrian = $this->generateNomorAntrian($jenisLayanan);

            // Tentukan keterangan
            $keterangan = $keteranganMap[$request->item_type] ??
                        ($subLayanan ? $subLayanan->title : Str::title(str_replace('-', ' ', $request->item_type)));

            // Tentukan jenis antrian
            $jenisAntrian = $jenisAntrianMap[$request->registration_type] ?? 'Offline';

            // Tentukan jenis pengiriman
            $jenisPengiriman = 'Offline'; // default
            if ($request->registration_type === 'online' && $request->jenis_pengiriman) {
                $jenisPengiriman = $jenisPengirimanMap[$request->jenis_pengiriman] ?? $request->jenis_pengiriman;
            }

            // Generate kode pemohon
            $kodePemohon = $this->generateRandomCode();

            // Insert ke database menggunakan Model Antrian
            $antrian = Antrian::create([
                'tanggal' => now()->toDateString(),
                'nama' => $userData['nama'],
                'no_whatsapp' => $userData['no_whatsapp'],
                'alamat' => $userData['alamat'],
                'jenis_layanan' => $jenisLayanan,
                'keterangan' => $keterangan,
                'no_antrian' => $noAntrian,
                'jenis_antrian' => $jenisAntrian,
                'jenis_pengiriman' => $jenisPengiriman,
                'calling_by' => '',
                'status' => '0',
                'updated_date' => now()
            ]);

            $pemohon = Pemohon::create([
                'tanggal' => now(),
                'nama' => $userData['nama'],
                'kode_pemohon' => $kodePemohon,
                'no_whatsapp' => $userData['no_whatsapp'],
                'alamat' => $userData['alamat'],
                'jenis_layanan' => $jenisLayanan,
                'keterangan' => $keterangan,
                'jenis_antrian' => $jenisAntrian,
                'jenis_pengiriman' => $jenisPengiriman,
                'status' => '0', // Belum terlayani
                'dilayani_oleh' => null,
                'tanggal_dilayani' => null
            ]);

            DB::commit();

            // Send WhatsApp confirmation
            try {
                $this->sendWhatsAppNotification($antrian, 'registration');
            } catch (\Exception $e) {
                \Log::warning('WhatsApp notification failed: ' . $e->getMessage());
            }

            // Redirect dengan pesan sukses
            // return redirect()->route('adminduk.registration.success')
            //             ->with('success', 'Pendaftaran berhasil! Nomor antrian Anda: ' . $noAntrian)
            //             ->with('antrian_data', [
            //                 'nama' => $userData['nama'],
            //                 'no_antrian' => $noAntrian,
            //                 'kode_pemohon' => $kodePemohon,
            //                 'jenis_layanan' => $jenisLayanan,
            //                 'keterangan' => $keterangan,
            //                 'jenis_antrian' => $jenisAntrian,
            //                 'tanggal' => now()->format('d F Y'),
            //                 'jam' => now()->format('H:i')
            //             ]);

            session()->put('success', 'Pendaftaran berhasil! Nomor antrian Anda: ' . $noAntrian);
            session()->put('antrian_data', [
                'nama' => $userData['nama'],
                'no_antrian' => $noAntrian,
                'kode_pemohon' => $kodePemohon,
                'jenis_layanan' => $jenisLayanan,
                'keterangan' => $keterangan,
                'jenis_antrian' => $jenisAntrian,
                'tanggal' => now()->format('d F Y'),
                'jam' => now()->format('H:i')
            ]);

            // Save session explicitly
            session()->save();

            return redirect()->route('adminduk.registration.success');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error submit registration: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * ✅ Generate nomor antrian berdasarkan jenis layanan dengan kode dinamis
     */
    private function generateNomorAntrian($jenisLayanan)
    {
        try {
            // Dapatkan kode antrian dari database berdasarkan jenis layanan
            $layanan = Layanan::where('title', $jenisLayanan)->first();

            if (!$layanan || !$layanan->kode_layanan) {
                // Fallback: jika tidak ditemukan, gunakan kode default berdasarkan mapping
                $defaultMapping = [
                    'KTP/KK/KIA/IKD' => 'A',
                    'AKTA' => 'B',
                    'PINDAH DATANG' => 'C',
                    'SKT/SKAW' => 'D',
                    'LAYANAN KELURAHAN' => 'E',
                    'KONSULTASI' => 'F'
                ];

                $kodeAntrian = $defaultMapping[$jenisLayanan] ?? 'A';

                Log::warning("Kode layanan tidak ditemukan untuk: " . $jenisLayanan . ", menggunakan default: " . $kodeAntrian);
            } else {
                $kodeAntrian = $layanan->kode_layanan;
            }

            $today = now()->toDateString();

            // Cari nomor antrian terakhir untuk kode ini hari ini
            $lastAntrian = Antrian::where('tanggal', $today)
                ->where('no_antrian', 'LIKE', $kodeAntrian . '%')
                ->orderByRaw('CAST(SUBSTRING(no_antrian, 2) AS UNSIGNED) DESC')
                ->first();

            if ($lastAntrian && $lastAntrian->no_antrian) {
                // Ambil nomor terakhir dan tambah 1
                $lastNumber = (int) substr($lastAntrian->no_antrian, 1);
                $newNumber = $lastNumber + 1;
            } else {
                // Mulai dari 1 jika belum ada antrian untuk kode ini
                $newNumber = 1;
            }

            $generatedNumber = $kodeAntrian . $newNumber;

            Log::info("Generated antrian number: " . $generatedNumber . " for layanan: " . $jenisLayanan);

            return $generatedNumber;

        } catch (\Exception $e) {
            Log::error("Error generating nomor antrian: " . $e->getMessage());
            // Fallback ke sistem lama jika ada error
            return $this->generateNomorAntrianFallback();
        }
    }

    /**
     * ✅ Fallback method untuk generate nomor antrian (sistem lama)
     */
    private function generateNomorAntrianFallback()
    {
        $today = now()->toDateString();
        $lastAntrian = Antrian::where('tanggal', $today)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastAntrian) {
            $lastNumber = (int) substr($lastAntrian->no_antrian, 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'A' . $newNumber;
    }

    /**
     * ✅ Generate kode pemohon random
     */
    private function generateRandomCode($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomCode = '';
        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomCode;
    }

    /**
     * ✅ Dapatkan estimasi waktu tunggu
     */
    private function getEstimatedWaitTime($no_antrian, $jenis_layanan)
    {
        try {
            $today = now()->toDateString();
            $layanan = Layanan::where('title', $jenis_layanan)->first();

            if (!$layanan) {
                return "Tidak dapat diperkirakan";
            }

            $kodeAntrian = $layanan->kode_layanan;
            $currentNumber = (int) substr($no_antrian, 1);

            // Hitung antrian yang sudah selesai hari ini untuk layanan yang sama
            $selesaiCount = Antrian::where('tanggal', $today)
                ->where('no_antrian', 'LIKE', $kodeAntrian . '%')
                ->where('status', '1')
                ->count();

            // Estimasi 7 menit per antrian
            $estimatedMinutes = max(0, ($currentNumber - $selesaiCount - 1) * 7);

            if ($estimatedMinutes <= 0) {
                return "Segera dilayani";
            } else if ($estimatedMinutes <= 60) {
                return $estimatedMinutes . " menit";
            } else {
                $hours = floor($estimatedMinutes / 60);
                $minutes = $estimatedMinutes % 60;
                return $hours . " jam " . ($minutes > 0 ? $minutes . " menit" : "");
            }

        } catch (\Exception $e) {
            return "Tidak dapat diperkirakan";
        }
    }

    /**
     * ✅ Dapatkan informasi posisi antrian
     */
    private function getQueuePosition($no_antrian, $jenis_layanan)
    {
        try {
            $today = now()->toDateString();
            $layanan = Layanan::where('title', $jenis_layanan)->first();

            if (!$layanan) {
                return ['position' => 1, 'total' => 1, 'remaining' => 1];
            }

            $kodeAntrian = $layanan->kode_layanan;
            $currentNumber = (int) substr($no_antrian, 1);

            // Hitung total antrian hari ini untuk jenis layanan yang sama
            $totalQueue = Antrian::where('tanggal', $today)
                ->where('no_antrian', 'LIKE', $kodeAntrian . '%')
                ->count();

            // Hitung antrian yang sudah selesai sebelum nomor ini
            $selesaiSebelum = Antrian::where('tanggal', $today)
                ->where('no_antrian', 'LIKE', $kodeAntrian . '%')
                ->where('status', '1')
                ->whereRaw('CAST(SUBSTRING(no_antrian, 2) AS UNSIGNED) < ?', [$currentNumber])
                ->count();

            return [
                'position' => $currentNumber,
                'total' => $totalQueue,
                'remaining' => max(0, $currentNumber - $selesaiSebelum - 1)
            ];

        } catch (\Exception $e) {
            return ['position' => 1, 'total' => 1, 'remaining' => 1];
        }
    }

    /**
     * ✅ Enhanced WhatsApp notification system
     */
    private function sendWhatsAppNotification($antrian, $type = 'registration')
    {
        try {
            if (empty($antrian->no_whatsapp)) {
                Log::warning("WhatsApp number not found for antrian: {$antrian->no_antrian}");
                return false;
            }

            // Get active WhatsApp API data
            $apiData = $this->getActiveWhatsAppApiData();
            if (!$apiData) {
                Log::error("No active WhatsApp API found or quota exhausted");
                return false;
            }

            // Dapatkan estimasi waktu tunggu dan posisi
            $estimatedWait = $this->getEstimatedWaitTime($antrian->no_antrian, $antrian->jenis_layanan);
            $queueInfo = $this->getQueuePosition($antrian->no_antrian, $antrian->jenis_layanan);

            // Build pesan berdasarkan type
            switch ($type) {
                case 'registration':
                    $message = $this->buildRegistrationMessage($antrian, $estimatedWait, $queueInfo);
                    break;
                case 'call':
                    $message = $this->buildCallMessage($antrian);
                    break;
                case 'complete':
                    $message = $this->buildCompletionMessage($antrian);
                    break;
                default:
                    $message = $this->buildRegistrationMessage($antrian, $estimatedWait, $queueInfo);
            }

            // Send WhatsApp via Fonnte API
            $result = $this->sendWhatsAppViaFonnte($antrian->no_whatsapp, $message, $apiData->token);

            if ($result['success']) {
                // Kurangi quota jika pesan berhasil terkirim
                $this->reduceWhatsAppQuota($apiData->id);

                Log::info("WhatsApp {$type} message sent successfully", [
                    'no_antrian' => $antrian->no_antrian,
                    'whatsapp' => $antrian->no_whatsapp,
                    'api_id' => $apiData->id,
                    'remaining_quota' => $apiData->quota - 1,
                    'response' => $result['response']
                ]);
                return true;
            } else {
                Log::error("Failed to send WhatsApp {$type} message", [
                    'no_antrian' => $antrian->no_antrian,
                    'whatsapp' => $antrian->no_whatsapp,
                    'api_id' => $apiData->id,
                    'error' => $result['error']
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Error sending WhatsApp notification: " . $e->getMessage(), [
                'no_antrian' => $antrian->no_antrian ?? 'unknown',
                'type' => $type,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    private function sendWhatsAppViaFonnte($whatsapp, $message, $token)
    {
        try {
            // Clean phone number format
            $cleanWhatsapp = $this->cleanPhoneNumber($whatsapp);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30, // Set timeout 30 seconds
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $cleanWhatsapp,
                    'message' => $message,
                    'countryCode' => '62', // Indonesia country code
                    'delay' => '2'
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $token"
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);

            curl_close($curl);

            // Handle cURL errors
            if ($curlError) {
                return [
                    'success' => false,
                    'error' => "cURL Error: $curlError"
                ];
            }

            // Parse response
            $responseData = json_decode($response, true);

            // Check if message was sent successfully
            if ($httpCode == 200) {
                // Additional check for Fonnte API specific response
                if (isset($responseData['status']) && $responseData['status'] === true) {
                    return [
                        'success' => true,
                        'response' => $responseData,
                        'http_code' => $httpCode
                    ];
                } elseif (isset($responseData['status']) && $responseData['status'] === false) {
                    return [
                        'success' => false,
                        'error' => "Fonnte API Error: " . ($responseData['reason'] ?? 'Unknown error'),
                        'http_code' => $httpCode,
                        'response' => $responseData
                    ];
                } else {
                    // For backward compatibility, assume success if status is not explicitly set
                    return [
                        'success' => true,
                        'response' => $responseData,
                        'http_code' => $httpCode
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'error' => "HTTP $httpCode: " . ($responseData['message'] ?? $response),
                    'http_code' => $httpCode,
                    'response' => $responseData
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => "Exception: " . $e->getMessage()
            ];
        }
    }


    /**
     * ✅ Get active WhatsApp token and API data from database
     */
    private function getActiveWhatsAppApiData()
    {
        try {
            // Ambil API WhatsApp yang aktif dan memiliki quota > 0
            $activeApi = APIWhatsappData::where('status', 'active')
                ->where('quota', '>', 0)
                ->first();

            if (!$activeApi) {
                Log::warning("No active WhatsApp API found with available quota");
                return null;
            }

            return $activeApi;

        } catch (\Exception $e) {
            Log::error("Error getting active WhatsApp API data: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ Update method getActiveWhatsAppToken untuk kompatibilitas
     */
    private function getActiveWhatsAppToken()
    {
        $apiData = $this->getActiveWhatsAppApiData();
        return $apiData ? $apiData->token : null;
    }

    /**
     * ✅ Reduce quota after successful WhatsApp message
     */
    private function reduceWhatsAppQuota($apiId)
    {
        try {
            $api = APIWhatsappData::find($apiId);

            if ($api && $api->quota > 0) {
                $api->quota = $api->quota - 1;
                $api->save();

                Log::info("WhatsApp quota reduced for API ID: {$apiId}, remaining quota: {$api->quota}");

                // Jika quota habis, set status menjadi inactive
                if ($api->quota <= 0) {
                    $api->status = 'inactive';
                    $api->save();
                    Log::warning("WhatsApp API ID: {$apiId} has been deactivated due to zero quota");
                }

                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error("Error reducing WhatsApp quota: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Method untuk mengecek status WhatsApp API dan quota
     */
    public function checkWhatsAppApiStatus()
    {
        try {
            $activeApi = $this->getActiveWhatsAppApiData();

            if (!$activeApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada API WhatsApp aktif atau quota habis',
                    'data' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'API WhatsApp aktif ditemukan',
                'data' => [
                    'id' => $activeApi->id,
                    'name' => $activeApi->name,
                    'whatsapp_number' => $activeApi->whatsapp_number,
                    'status' => $activeApi->status,
                    'quota' => $activeApi->quota,
                    'subscription_date' => $activeApi->subscription_date_formatted
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking WhatsApp API status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Clean phone number format for WhatsApp
     */
    private function cleanPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Handle Indonesian phone number formats
        if (substr($cleaned, 0, 1) == '0') {
            // Convert 08xxx to 628xxx
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (substr($cleaned, 0, 2) != '62') {
            // Add 62 if not present
            $cleaned = '62' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * ✅ Get WhatsApp status and statistics
     */
    public function getWhatsAppStats()
    {
        try {
            $today = now()->toDateString();

            // Count messages sent today (from logs or separate table if you track it)
            $messagesSentToday = 0; // You can implement tracking if needed

            // Check token status
            $token = $this->getActiveWhatsAppToken();
            $tokenStatus = $token ? 'active' : 'inactive';

            // Test connection
            $connectionTest = false;
            if ($token) {
                // Simple test to check if API is responding
                $testResult = $this->sendWhatsAppViaFonnte('6281234567890', 'API Test', $token);
                $connectionTest = $testResult['success'] || $testResult['http_code'] == 200;
            }

            $stats = [
                'token_status' => $tokenStatus,
                'connection_status' => $connectionTest ? 'connected' : 'disconnected',
                'messages_sent_today' => $messagesSentToday,
                'total_antrian_with_whatsapp' => Antrian::where('tanggal', $today)
                    ->whereNotNull('no_whatsapp')
                    ->where('no_whatsapp', '!=', '')
                    ->count(),
                'total_antrian_without_whatsapp' => Antrian::where('tanggal', $today)
                    ->where(function($query) {
                        $query->whereNull('no_whatsapp')
                            ->orWhere('no_whatsapp', '');
                    })
                    ->count(),
                'last_checked' => now()->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting WhatsApp stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting WhatsApp statistics'
            ], 500);
        }
    }

    /**
     * ✅ Build registration message
     */
    private function buildRegistrationMessage($antrian, $estimatedWait, $queueInfo)
    {
        $message = "🎟️ *KONFIRMASI PENDAFTARAN ANTRIAN*\n\n";
        $message .= "Halo *{$antrian->nama}*! ✨\n\n";
        $message .= "Pendaftaran Anda telah berhasil diproses:\n\n";
        $message .= "📋 *DETAIL ANTRIAN*\n";
        $message .= "• Nomor Antrian: *{$antrian->no_antrian}*\n";
        $message .= "• Layanan: *{$antrian->jenis_layanan}*\n";
        $message .= "• Keterangan: *{$antrian->keterangan}*\n";
        $message .= "• Kode Pemohon: *{$antrian->kode_pemohon}*\n";
        $message .= "• Tanggal: *" . now()->format('d F Y, H:i') . "*\n\n";

        $message .= "📊 *INFO ANTRIAN*\n";
        $message .= "• Posisi: *{$queueInfo['position']}* dari *{$queueInfo['total']}*\n";
        $message .= "• Estimasi Tunggu: *{$estimatedWait}*\n\n";

        if ($antrian->jenis_antrian === "Online") {
            if (strpos($antrian->jenis_pengiriman, 'rumah') !== false || strpos($antrian->jenis_pengiriman, 'Alamat') !== false) {
                $message .= "🏠 *PENGIRIMAN KE ALAMAT*\n";
                $message .= "Dokumen akan dikirim setelah selesai diproses.\n\n";
            } else {
                $message .= "🏢 *AMBIL DI KELURAHAN*\n";
                $message .= "Anda akan dihubungi saat dokumen siap diambil.\n\n";
            }
        } else {
            $message .= "🏢 *LAYANAN OFFLINE*\n";
            $message .= "Harap datang dan tunggu panggilan sesuai nomor antrian.\n\n";
        }

        $message .= "⚠️ *PENTING:*\n";
        $message .= "• Simpan pesan ini sebagai bukti\n";
        $message .= "• Datang sesuai estimasi waktu\n";
        $message .= "• Hubungi kami jika ada kendala\n\n";
        $message .= "Terima kasih! 🙏";

        return $message;
    }

    /**
     * ✅ Build call message
     */
    private function buildCallMessage($antrian)
    {
        $message = "📢 *PANGGILAN ANTRIAN*\n\n";
        $message .= "Halo *{$antrian->nama}*!\n\n";
        $message .= "Nomor antrian *{$antrian->no_antrian}* sedang dipanggil.\n";
        $message .= "Layanan: *{$antrian->jenis_layanan}*\n";
        $message .= "Keterangan: *{$antrian->keterangan}*\n\n";
        $message .= "Silakan menuju ke *{$antrian->calling_by}*.\n\n";
        $message .= "Terima kasih! 🙏";

        return $message;
    }

    /**
     * ✅ Build completion message
     */
    private function buildCompletionMessage($antrian)
    {
        $message = "✅ *LAYANAN SELESAI*\n\n";
        $message .= "Halo *{$antrian->nama}*!\n\n";
        $message .= "Layanan untuk antrian *{$antrian->no_antrian}* telah selesai diproses.\n";
        $message .= "Layanan: *{$antrian->jenis_layanan}*\n";
        $message .= "Keterangan: *{$antrian->keterangan}*\n\n";

        if ($antrian->jenis_antrian === "Online") {
            if (strpos($antrian->jenis_pengiriman, 'rumah') !== false || strpos($antrian->jenis_pengiriman, 'Alamat') !== false) {
                $message .= "📦 Dokumen akan segera dikirim ke alamat Anda.\n\n";
            } else {
                $message .= "📋 Dokumen siap diambil di kelurahan.\n\n";
            }
        }

        $message .= "Terima kasih atas kepercayaan Anda! 🙏";

        return $message;
    }



    public function registrationSuccess()
    {
        // if (!session()->has('success')) {
        //     return redirect()->route('Adminduk');
        // }

        // return view('layanan.adminduk.success');
        // Check both flash and regular session
        $hasSuccess = session()->has('success') || session()->hasOldInput('success');
        $hasAntrianData = session()->has('antrian_data') || session()->hasOldInput('antrian_data');

        if (!$hasSuccess && !$hasAntrianData) {
            \Log::warning('No session data found for success page');
            return redirect()->route('Adminduk')
                            ->with('error', 'Session tidak ditemukan. Silakan daftar ulang.');
        }

        return view('layanan.adminduk.success');
    }

    // Method untuk validasi pemohon lama via AJAX
    public function validatePemohon(Request $request)
    {
        $kodePemohon = $request->kode_pemohon;

        $existingUser = Antrian::where('id', $kodePemohon)
            ->orWhere('nama', 'LIKE', '%' . $kodePemohon . '%')
            ->first();

        if ($existingUser) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $existingUser->nama,
                    'no_whatsapp' => $existingUser->no_whatsapp,
                    'alamat' => $existingUser->alamat
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode pemohon tidak ditemukan'
        ]);
    }

    // Method untuk cek status antrian
    public function checkAntrianStatus($noAntrian)
    {
        $antrian = Antrian::where('no_antrian', $noAntrian)
            ->where('tanggal', now()->toDateString())
            ->first();

        if ($antrian) {
            return response()->json([
                'success' => true,
                'data' => [
                    'no_antrian' => $antrian->no_antrian,
                    'nama' => $antrian->nama,
                    'jenis_layanan' => $antrian->jenis_layanan,
                    'keterangan' => $antrian->keterangan,
                    'status' => $antrian->status,
                    'calling_by' => $antrian->calling_by,
                    'jenis_antrian' => $antrian->jenis_antrian
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nomor antrian tidak ditemukan'
        ]);
    }

    // Method untuk mendapatkan statistik antrian hari ini
    public function getAntrianStats()
    {
        $today = now()->toDateString();

        // Ambil antrian yang sedang dipanggil (status = 1) - yang terakhir dipanggil
        $currentAntrian = Antrian::where('tanggal', $today)
            ->where('status', '1')
            ->orderBy('updated_date', 'desc')
            ->first();

        // Ambil antrian selanjutnya yang belum dipanggil (status = 0) - yang paling kecil nomornya
        $nextAntrian = Antrian::where('tanggal', $today)
            ->where('status', '0')
            ->orderByRaw('CAST(SUBSTRING(no_antrian, 2) AS UNSIGNED) ASC')
            ->first();

        // Hitung total antrian yang belum dipanggil
        $waitingCount = Antrian::where('tanggal', $today)
            ->where('status', '0')
            ->count();

        $stats = [
            'total_antrian' => Antrian::where('tanggal', $today)->count(),
            'antrian_sekarang' => $currentAntrian ? $currentAntrian->no_antrian : null,
            'antrian_selanjutnya' => $nextAntrian ? $nextAntrian->no_antrian : null,
            'antrian_menunggu' => $waitingCount,
            'offline_antrian' => Antrian::where('tanggal', $today)
                ->where('jenis_antrian', 'Offline')
                ->count(),
            'online_antrian' => Antrian::where('tanggal', $today)
                ->where('jenis_antrian', 'Online')
                ->count(),
            'current_antrian_detail' => $currentAntrian ? [
                'no_antrian' => $currentAntrian->no_antrian,
                'nama' => $currentAntrian->nama,
                'jenis_layanan' => $currentAntrian->jenis_layanan,
                'calling_by' => $currentAntrian->calling_by
            ] : null,
            'next_antrian_detail' => $nextAntrian ? [
                'no_antrian' => $nextAntrian->no_antrian,
                'nama' => $nextAntrian->nama,
                'jenis_layanan' => $nextAntrian->jenis_layanan
            ] : null
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    // Method untuk reset antrian harian (untuk admin)
    public function resetAntrian()
    {
        try {
            $today = now()->toDateString();

            Antrian::where('tanggal', $today)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Antrian hari ini berhasil direset'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error reset antrian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat reset antrian'
            ], 500);
        }
    }

    /**
     * ✅ Method untuk mendapatkan antrian berdasarkan kode layanan
     */
    public function getAntrianByKode($kode)
    {
        try {
            $today = now()->toDateString();

            $antrian = Antrian::where('tanggal', $today)
                ->where('no_antrian', 'LIKE', $kode . '%')
                ->orderByRaw('CAST(SUBSTRING(no_antrian, 2) AS UNSIGNED) ASC')
                ->get();

            $layanan = Layanan::where('kode_layanan', $kode)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'kode' => $kode,
                    'layanan' => $layanan ? $layanan->title : 'Unknown',
                    'total' => $antrian->count(),
                    'selesai' => $antrian->where('status', '1')->count(),
                    'menunggu' => $antrian->where('status', '0')->count(),
                    'antrian' => $antrian->map(function($item) {
                        return [
                            'id' => $item->id,
                            'no_antrian' => $item->no_antrian,
                            'nama' => $item->nama,
                            'keterangan' => $item->keterangan,
                            'status' => $item->status,
                            'calling_by' => $item->calling_by,
                            'created_at' => $item->created_at->format('H:i:s')
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting antrian by kode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data antrian'
            ], 500);
        }
    }

    /**
     * ✅ Method untuk monitoring real-time antrian
     */
    public function getRealtimeStats()
    {
        try {
            $today = now()->toDateString();

            // Get hourly statistics
            $hourlyStats = Antrian::where('tanggal', $today)
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->pluck('total', 'hour')
                ->toArray();

            // Get layanan distribution
            $layananDistribution = Antrian::where('tanggal', $today)
                ->selectRaw('jenis_layanan, COUNT(*) as total')
                ->groupBy('jenis_layanan')
                ->get()
                ->pluck('total', 'jenis_layanan')
                ->toArray();

            // Get average waiting time per layanan
            $avgWaitingTime = [];
            $layanan = Layanan::all();

            foreach ($layanan as $lay) {
                if ($lay->kode_layanan) {
                    $selesai = Antrian::where('tanggal', $today)
                        ->where('no_antrian', 'LIKE', $lay->kode_layanan . '%')
                        ->where('status', '1')
                        ->get();

                    if ($selesai->count() > 0) {
                        $totalMinutes = 0;
                        foreach ($selesai as $ant) {
                            $created = $ant->created_at;
                            $updated = $ant->updated_date;
                            $diff = $created->diffInMinutes($updated);
                            $totalMinutes += $diff;
                        }
                        $avgWaitingTime[$lay->title] = round($totalMinutes / $selesai->count(), 1);
                    } else {
                        $avgWaitingTime[$lay->title] = 0;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'hourly_stats' => $hourlyStats,
                    'layanan_distribution' => $layananDistribution,
                    'avg_waiting_time' => $avgWaitingTime,
                    'peak_hour' => array_keys($hourlyStats, max($hourlyStats))[0] ?? null,
                    'total_served' => Antrian::where('tanggal', $today)->where('status', '1')->count(),
                    'total_waiting' => Antrian::where('tanggal', $today)->where('status', '0')->count(),
                    'last_updated' => now()->format('H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting realtime stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik real-time'
            ], 500);
        }
    }

    /**
     * ✅ Method untuk export data antrian
     */
    public function exportAntrian(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', now()->toDateString());
            $format = $request->get('format', 'excel'); // excel, csv, pdf

            $antrian = Antrian::where('tanggal', $tanggal)
                ->orderBy('created_at')
                ->get();

            $data = $antrian->map(function($item) {
                return [
                    'No Antrian' => $item->no_antrian,
                    'Nama' => $item->nama,
                    'No WhatsApp' => $item->no_whatsapp,
                    'Jenis Layanan' => $item->jenis_layanan,
                    'Keterangan' => $item->keterangan,
                    'Jenis Antrian' => $item->jenis_antrian,
                    'Jenis Pengiriman' => $item->jenis_pengiriman,
                    'Kode Pemohon' => $item->kode_pemohon,
                    'Status' => $item->status == '1' ? 'Selesai' : 'Menunggu',
                    'Dipanggil Oleh' => $item->calling_by,
                    'Jam Daftar' => $item->created_at->format('H:i:s'),
                    'Jam Update' => $item->updated_date ? $item->updated_date->format('H:i:s') : '-'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => $data->count(),
                'tanggal' => $tanggal,
                'message' => 'Data antrian berhasil diekspor'
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting antrian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengekspor data'
            ], 500);
        }
    }
}
