<?php
// File: tests/TestCase.php - Fixed Version with Proper Data Types

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\SuratPengantar;
use App\Models\Spesimen;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup default storage for testing
        Storage::fake('public');

        // Disable event broadcasting for tests
        Event::fake();

        // Create some test files for spesimen
        Storage::disk('public')->put('spesimen/ttd_test.png', 'fake-ttd-content');
        Storage::disk('public')->put('spesimen/stempel_test.png', 'fake-stempel-content');

        // TEMP FIX: Check if tables exist before cleanup
        try {
            if (\Schema::hasTable('surat_pengantar')) {
                $this->cleanupTestData();
            }
        } catch (\Exception $e) {
            // Ignore cleanup errors during setup if tables don't exist
        }
    }

    protected function tearDown(): void
    {
        // Clean up any created files
        Storage::disk('public')->deleteDirectory('surat_pengantar');
        Storage::disk('public')->deleteDirectory('spesimen');

        parent::tearDown();
    }

    /**
     * Get valid data for surat pengantar creation - FIXED for database schema
     */
    protected function getValidSuratPengantarData(): array
    {
        return [
            'nama_lengkap' => 'John Doe Test',
            'alamat' => 'Jl. Test No. 123, RT 01/RW 01',
            'pekerjaan' => 'Software Engineer',
            'gender' => 'L',
            'jenis_kelamin' => 'L', // Add explicit jenis_kelamin
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'kewarganegaraan' => 'WNI',
            'nomor_kk' => '1234567890123456',
            'tujuan' => 'Pengajuan KTP',
            'keperluan' => 'Untuk pembuatan dokumen identitas',
            'keterangan_lain' => 'Tidak ada keterangan tambahan',
            'rt' => '01', // Ensure string
            'rw' => '01', // Ensure string
            'ttd_pemohon' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg=='
            // REMOVED: ttd_pemilik - this column doesn't exist based on error
        ];
    }

    /**
     * Get valid update data for surat pengantar
     */
    protected function getValidUpdateData(): array
    {
        return array_merge($this->getValidSuratPengantarData(), [
            'nama_lengkap' => 'Updated Test Name'
        ]);
    }

    /**
     * Create test user with specific role - FIXED VERSION
     */
    protected function createTestUser(string $role = 'user', array $attributes = []): User
    {
        // FIXED: Generate more unique identifiers to avoid duplicates
        $uniqueId = 'TEST_' . Str::upper(Str::random(8));
        $microtime = microtime(true);
        $timestamp = str_replace('.', '', $microtime) . rand(1000, 9999);

        $defaultAttributes = [
            'id' => $uniqueId, // Specify ID untuk string primary key
            'name' => 'Test User ' . $timestamp,
            'email' => 'test_' . $timestamp . '@example.com', // More unique email
            'username' => 'test_user_' . $timestamp, // Add username field
            'telp' => '08' . rand(1000000000, 9999999999), // Add telp field
            'role' => $role,
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            // FIXED: Add other required fields with proper constraints
            'kode_pos' => '12345', // Max 5 characters as per error
            'nik' => '1234567890123456', // 16 digits
            'gender' => 'L',
            'address' => 'Test Address',
            'kelurahan' => 'Test Kelurahan',
            'kecamatan' => 'Test Kecamatan',
            'kota' => 'Test Kota',
            'provinsi' => 'Test Provinsi',
            'tempat_lahir' => 'Test Tempat',
            'tanggal_lahir' => '1990-01-01',
            'status_perkawinan' => 'Belum Kawin',
            'pekerjaan' => 'Test Job',
            'agama' => 'Islam'
        ];

        // Merge with passed attributes first
        $finalAttributes = array_merge($defaultAttributes, $attributes);

        // CRITICAL FIX: Set RT/RW based on role AFTER merging attributes
        // This ensures role-based defaults don't override passed attributes
        if ($role === 'Ketua RT') {
            // Only set defaults if not already provided in attributes
            if (!isset($finalAttributes['rt'])) {
                $finalAttributes['rt'] = '01';
            }
            if (!isset($finalAttributes['rw'])) {
                $finalAttributes['rw'] = '01';
            }
        } elseif ($role === 'Ketua RW') {
            // Only set default if not already provided in attributes
            if (!isset($finalAttributes['rw'])) {
                $finalAttributes['rw'] = '01';
            }
        }

        // Force RT/RW to be strings if they exist
        if (isset($finalAttributes['rt'])) {
            $finalAttributes['rt'] = (string) $finalAttributes['rt'];
        }
        if (isset($finalAttributes['rw'])) {
            $finalAttributes['rw'] = (string) $finalAttributes['rw'];
        }

        // FIXED: Ensure kode_pos is max 5 characters
        if (isset($finalAttributes['kode_pos']) && strlen($finalAttributes['kode_pos']) > 5) {
            $finalAttributes['kode_pos'] = substr($finalAttributes['kode_pos'], 0, 5);
        }

        // Make email even more unique if there's still a conflict
        $emailAttempts = 0;
        while ($emailAttempts < 3) {
            try {
                $user = User::create($finalAttributes);
                return $user;
            } catch (\Illuminate\Database\QueryException $e) {
                if (strpos($e->getMessage(), 'email_unique') !== false) {
                    $emailAttempts++;
                    $finalAttributes['email'] = 'test_' . $timestamp . '_' . $emailAttempts . '@example.com';
                    $finalAttributes['username'] = 'test_user_' . $timestamp . '_' . $emailAttempts;
                } else {
                    throw $e;
                }
            }
        }

        throw new \Exception('Unable to create unique user after 3 attempts');
    }

    /**
     * Create test spesimen - FIXED: Provide all required fields
     */
    protected function createTestSpesimen(string $jabatan, array $attributes = []): Spesimen
    {
        // FIXED: Create user first or use existing one to satisfy foreign key
        $creator = $attributes['user_id'] ?? $this->createTestUser('admin', [
            'id' => 'CREATOR_' . time() . '_' . rand(1000, 9999),
            'email' => 'creator_' . time() . '_' . rand(1000, 9999) . '@example.com'
        ])->id;

        $defaultAttributes = [
            'jabatan' => $jabatan,
            'nama_pejabat' => 'Test Pejabat ' . time(),
            'status' => 'Aktif',
            'is_active' => true,
            'file_ttd' => 'spesimen/ttd_test.png',
            'file_stempel' => 'spesimen/stempel_test.png',
            'user_id' => $creator,         // REQUIRED field
            'created_by' => $creator,      // REQUIRED field
            'rw' => '01'                   // REQUIRED field
        ];

        // Add rt based on jabatan
        if ($jabatan === 'Ketua RT') {
            $defaultAttributes['rt'] = '01';
        } else {
            $defaultAttributes['rt'] = null; // RT can be nullable for non-RT positions
        }

        $finalAttributes = array_merge($defaultAttributes, $attributes);

        // Ensure rt and rw are strings if not null
        if (isset($finalAttributes['rt']) && $finalAttributes['rt'] !== null) {
            $finalAttributes['rt'] = (string) $finalAttributes['rt'];
        }
        if (isset($finalAttributes['rw'])) {
            $finalAttributes['rw'] = (string) $finalAttributes['rw'];
        }

        return Spesimen::create($finalAttributes);
    }

    /**
     * Create test surat pengantar with proper field mapping - FIXED VERSION
     */
    protected function createTestSuratPengantar(array $attributes = []): SuratPengantar
    {
        $defaultData = $this->getValidSuratPengantarData();

        $defaultAttributes = array_merge($defaultData, [
            'nomor_surat' => 'TEST/' . date('Y/m/d') . '/' . Str::padLeft(rand(1, 999), 3, '0'),
            'nik' => $defaultData['nomor_kk'], // Map nomor_kk to nik
            'jenis_kelamin' => $defaultData['gender'], // Map gender to jenis_kelamin
            'status' => 'pending_rt',
            'rt' => '01', // Default RT as string
            'rw' => '01'  // Default RW as string
        ]);

        // Ensure user_id is provided either from attributes or create new user
        if (!isset($attributes['user_id'])) {
            $defaultAttributes['user_id'] = $this->createTestUser()->id;
        }

        $finalAttributes = array_merge($defaultAttributes, $attributes);

        // CRITICAL FIX: Force RT/RW to be strings to match database schema
        if (isset($finalAttributes['rt'])) {
            $finalAttributes['rt'] = (string) $finalAttributes['rt'];
        }
        if (isset($finalAttributes['rw'])) {
            $finalAttributes['rw'] = (string) $finalAttributes['rw'];
        }

        return SuratPengantar::create($finalAttributes);
    }

    /**
     * Clean up test data - improved version
     */
    protected function cleanupTestData(): void
    {
        // Delete test records with proper string ID handling
        SuratPengantar::where('nama_lengkap', 'like', '%Test%')
                      ->orWhere('nomor_surat', 'like', 'TEST/%')
                      ->delete();

        User::where('email', 'like', '%test_%@example.com')
            ->orWhere('id', 'like', 'TEST_%')
            ->delete();

        Spesimen::where('nama_pejabat', 'like', '%Test Pejabat%')->delete();
    }

    /**
     * Helper method to debug test data - NEW
     */
    protected function debugTestData($user, $surat): void
    {
        echo "\n=== DEBUG TEST DATA ===\n";
        echo "User ID: " . $user->id . " (type: " . gettype($user->id) . ")\n";
        echo "User Role: " . $user->role . "\n";
        echo "User RT: " . $user->rt . " (type: " . gettype($user->rt) . ")\n";
        echo "User RW: " . $user->rw . " (type: " . gettype($user->rw) . ")\n";
        echo "Surat ID: " . $surat->id . "\n";
        echo "Surat RT: " . $surat->rt . " (type: " . gettype($surat->rt) . ")\n";
        echo "Surat RW: " . $surat->rw . " (type: " . gettype($surat->rw) . ")\n";
        echo "Surat Status: " . $surat->status . "\n";
        echo "=========================\n";
    }
}
