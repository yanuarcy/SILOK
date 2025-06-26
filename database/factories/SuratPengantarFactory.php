<?php
// File: database/factories/SuratPengantarFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuratPengantar>
 */
class SuratPengantarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_surat' => 'SP/' . date('Y/m/d') . '/' . Str::padLeft(fake()->numberBetween(1, 999), 3, '0'),
            'user_id' => User::factory(),
            'nama_lengkap' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'alamat' => fake()->address(),
            'pekerjaan' => fake()->jobTitle(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-17 years')->format('Y-m-d'),
            'agama' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'status_perkawinan' => fake()->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
            'kewarganegaraan' => 'WNI',
            'nomor_kk' => fake()->numerify('################'),
            'tujuan' => fake()->sentence(),
            'keperluan' => fake()->paragraph(),
            'keterangan_lain' => fake()->optional()->paragraph(),
            'rt' => fake()->numberBetween(1, 10),
            'rw' => fake()->numberBetween(1, 10),
            'status' => 'pending_rt',
            'ttd_pemohon' => 'surat_pengantar/signatures/' . fake()->uuid() . '.png',
            'ttd_pemilik' => null,
            'ttd_rt' => null,
            'stempel_rt' => null,
            'catatan_rt' => null,
            'approved_rt_at' => null,
            'approved_rt_by' => null,
            'ttd_rw' => null,
            'stempel_rw' => null,
            'catatan_rw' => null,
            'approved_rw_at' => null,
            'approved_rw_by' => null,
            'file_pdf' => null,
        ];
    }

    /**
     * State for approved by RT
     */
    public function approvedByRT(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved_rt',
            'ttd_rt' => 'spesimen/ttd_rt.png',
            'stempel_rt' => 'spesimen/stempel_rt.png',
            'catatan_rt' => 'Approved by RT',
            'approved_rt_at' => now(),
            'approved_rt_by' => User::factory()->ketuaRT(),
        ]);
    }

    /**
     * State for approved by RW (final approval)
     */
    public function approvedByRW(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved_rw',
            'ttd_rt' => 'spesimen/ttd_rt.png',
            'stempel_rt' => 'spesimen/stempel_rt.png',
            'catatan_rt' => 'Approved by RT',
            'approved_rt_at' => now()->subDay(),
            'approved_rt_by' => User::factory()->ketuaRT(),
            'ttd_rw' => 'spesimen/ttd_rw.png',
            'stempel_rw' => 'spesimen/stempel_rw.png',
            'catatan_rw' => 'Approved by RW',
            'approved_rw_at' => now(),
            'approved_rw_by' => User::factory()->ketuaRW(),
            'file_pdf' => 'surat_pengantar/pdf/' . fake()->uuid() . '.pdf',
        ]);
    }

    /**
     * State for rejected by RT
     */
    public function rejectedByRT(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected_rt',
            'catatan_rt' => 'Rejected by RT: ' . fake()->sentence(),
            'approved_rt_at' => now(),
            'approved_rt_by' => User::factory()->ketuaRT(),
        ]);
    }

    /**
     * State for rejected by RW
     */
    public function rejectedByRW(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected_rw',
            'ttd_rt' => 'spesimen/ttd_rt.png',
            'stempel_rt' => 'spesimen/stempel_rt.png',
            'catatan_rt' => 'Approved by RT',
            'approved_rt_at' => now()->subDay(),
            'approved_rt_by' => User::factory()->ketuaRT(),
            'catatan_rw' => 'Rejected by RW: ' . fake()->sentence(),
            'approved_rw_at' => now(),
            'approved_rw_by' => User::factory()->ketuaRW(),
        ]);
    }

    /**
     * State for specific RT/RW
     */
    public function forRTRW(string $rt, string $rw): static
    {
        return $this->state(fn (array $attributes) => [
            'rt' => str_pad($rt, 2, '0', STR_PAD_LEFT),
            'rw' => str_pad($rw, 2, '0', STR_PAD_LEFT),
        ]);
    }
}
