<?php
// File: database/factories/SpesimenFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spesimen>
 */
class SpesimenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jabatan' => fake()->randomElement(['Ketua RT', 'Ketua RW']),
            'nama_pejabat' => fake()->name(),
            'rt' => null,
            'rw' => fake()->numberBetween(1, 10),
            'status' => 'Aktif',
            'is_active' => true,
            'file_ttd' => 'spesimen/ttd_' . fake()->uuid() . '.png',
            'file_stempel' => 'spesimen/stempel_' . fake()->uuid() . '.png',
        ];
    }

    /**
     * State for Ketua RT spesimen
     */
    public function ketuaRT(string $rt = '01', string $rw = '01'): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Ketua RT',
            'rt' => str_pad($rt, 2, '0', STR_PAD_LEFT),
            'rw' => str_pad($rw, 2, '0', STR_PAD_LEFT),
            'nama_pejabat' => 'Ketua RT ' . $rt . ' RW ' . $rw,
        ]);
    }

    /**
     * State for Ketua RW spesimen
     */
    public function ketuaRW(string $rw = '01'): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Ketua RW',
            'rt' => null,
            'rw' => str_pad($rw, 2, '0', STR_PAD_LEFT),
            'nama_pejabat' => 'Ketua RW ' . $rw,
        ]);
    }

    /**
     * State for inactive spesimen
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Tidak Aktif',
            'is_active' => false,
        ]);
    }
}
