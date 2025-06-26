<?php
// File: database/factories/UserFactory.php - Fixed Version

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => 'USR_' . Str::upper(Str::random(8)), // Generate string ID
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'username' => fake()->unique()->userName(), // Add username field
            'telp' => '08' . fake()->numberBetween(1000000000, 9999999999), // Add telp field
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'user',
            'nik' => fake()->optional()->numerify('################'), // Add nik field
            'gender' => fake()->optional()->randomElement(['L', 'P']), // Add gender field
            'address' => fake()->optional()->address(), // Add address field
            'rt' => null,
            'rw' => null,
            'kelurahan' => fake()->optional()->city(),
            'kecamatan' => fake()->optional()->city(),
            'kota' => fake()->optional()->city(),
            'provinsi' => fake()->optional()->state(),
            'kode_pos' => fake()->optional()->postcode(),
            'tempat_lahir' => fake()->optional()->city(),
            'tanggal_lahir' => fake()->optional()->dateTimeBetween('-60 years', '-17 years')?->format('Y-m-d'),
            'status_perkawinan' => fake()->optional()->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
            'pekerjaan' => fake()->optional()->jobTitle(),
            'image' => null,
            'agama' => fake()->optional()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'description' => fake()->optional()->paragraph(),
            'remember_token' => Str::random(10),
            'remember_token_created_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create user with Ketua RT role
     */
    public function ketuaRT(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Ketua RT',
            'rt' => '01',
            'rw' => '01',
        ]);
    }

    /**
     * Create user with Ketua RW role
     */
    public function ketuaRW(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Ketua RW',
            'rw' => '01',
        ]);
    }

    /**
     * Create user with admin role
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Create user with Front Office role
     */
    public function frontOffice(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Front Office',
        ]);
    }

    /**
     * Create user with Operator role
     */
    public function operator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Operator',
        ]);
    }
}
