<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function getProvinsi()
    {
        try {
            $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            $data = $response->json();

            // Tambahkan log untuk debugging
            \Log::info('Provinsi Data:', $data);

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error fetching provinces: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
    }

    public function getKota(Request $request)
    {
        try {
            $provinsiId = $request->provinsi_id;
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinsiId}.json");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
    }

    public function getKecamatan(Request $request)
    {
        try {
            $kotaId = $request->kota_id;
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$kotaId}.json");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch districts'], 500);
        }
    }

    public function getKelurahan(Request $request)
    {
        try {
            $kecamatanId = $request->kecamatan_id;
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$kecamatanId}.json");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch villages'], 500);
        }
    }
}
