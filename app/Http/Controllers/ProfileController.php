<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', ['type_menu' => 'profile']);
    }

    public function update(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            // Required fields
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),

            // Optional fields with validation rules
            'nik' => 'nullable|string|size:16',
            'telp' => 'nullable|string|max:20',
            'gender' => 'nullable|in:L,P',
            'address' => 'nullable|string',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:5',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status_perkawinan' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'nik.size' => 'NIK harus 16 digit',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 10MB'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $user = Auth::user();

            // Handle image upload if present
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/profileUser'), $imageName);
                $user->image = 'img/profileUser/' . $imageName;
            }

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->nik = $request->nik;
            $user->telp = $request->telp;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->rt = $request->rt;
            $user->rw = $request->rw;
            $user->kelurahan = $request->kelurahan_text;
            $user->kecamatan = $request->kecamatan_text;
            $user->kota = $request->kota_text;
            $user->provinsi = $request->provinsi_text;
            $user->kode_pos = $request->kode_pos;
            $user->tempat_lahir = $request->tempat_lahir;
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->status_perkawinan = $request->status_perkawinan;
            $user->pekerjaan = $request->pekerjaan;
            $user->agama = $request->agama;
            $user->description = strip_tags($request->description, '<br>');

            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui profile'
            ], 500);
        }
    }
}
