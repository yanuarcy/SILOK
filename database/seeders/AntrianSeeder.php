<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AntrianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('antrians')->insert([
            'tanggal' => now()->toDateString(),
            'nama' => 'John Doe',
            'no_whatsapp' => '081234567890',
            'alamat' => 'Jl. Contoh No. 123, Surabaya',
            'jenis_layanan' => 'KTP/KK/KIA/IKD',
            'keterangan' => 'Pengambilan KTP',
            'no_antrian' => 'A1',
            'jenis_antrian' => 'Offline', // bisa 'Offline' atau 'Online'
            'jenis_pengiriman' => 'Offline', // sesuaikan dengan kebutuhan
            'calling_by' => '', // kosong untuk status awal
            'status' => '0', // 0 untuk belum dipanggil, 1 untuk sudah dipanggil
            'updated_date' => now()
        ]);
    }
}
