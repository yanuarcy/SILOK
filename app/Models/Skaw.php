<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Traits\Loggable;
use Carbon\Carbon;

class Skaw extends Model
{
    use HasFactory, Loggable;

    protected $table = 'skaw';

    protected $fillable = [
        'nomor_surat',
        'user_id',

        // Data Pemohon
        'nama_lengkap',
        'nik',
        'alamat',
        'pekerjaan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_perkawinan',
        'kewarganegaraan',
        'nomor_kk',
        'rt',
        'rw',
        'email',
        'no_telepon',

        // Data khusus SKAW Pemohon
        'nomor_akta_perkawinan',
        'tanggal_terbit_akta_perkawinan',
        'jumlah_anak',

        // Data Pewaris
        'pewaris_nik',
        'pewaris_tempat_lahir',
        'pewaris_tanggal_lahir',
        'pewaris_nama_lengkap',
        'pewaris_gelar',
        'pewaris_tempat_tinggal_terakhir',
        'pewaris_tanggal_kematian',
        'pewaris_tempat_kematian',
        'pewaris_nomor_akta_kematian',
        'pewaris_tanggal_terbit_akta_kematian',

        // Data Saksi
        // 'saksi_nama_lengkap',
        // 'saksi_gelar',
        // 'saksi_alamat',

        'data_saksi',

        // Status & Workflow
        'status',

        // Front Office Process
        'submitted_at',
        'front_office_approved_at',
        'front_office_approved_by',
        'front_office_notes',
        'nomor_register_kelurahan',

        // Surat Tanda Terima & SKAW Generate
        'file_tanda_terima',
        'file_skaw_draft',
        'skaw_generated_at',

        // Jadwal Sidang
        'tanggal_sidang',
        'jam_sidang',
        'tempat_sidang',
        'file_daftar_sidang',
        'jadwal_sidang_created_at',
        'jadwal_sidang_created_by',

        // Evidence Sidang
        'evidence_photos',
        'file_evidence_pdf',
        'evidence_uploaded_at',
        'evidence_uploaded_by',

        // SKAW TTD dan Upload Final
        'file_skaw_ttd_scan',
        'skaw_ttd_uploaded_at',
        'skaw_ttd_uploaded_by',

        // Approval Lurah
        'lurah_approved_at',
        'lurah_approved_by',
        'lurah_notes',

        // Approval Camat
        'camat_approved_at',
        'camat_approved_by',
        'camat_notes',

        // SKAW Final
        'file_skaw_final',
        'skaw_final_uploaded_at',
        'skaw_final_uploaded_by',
        'completed_at',

        // Metadata
        'metadata',
        'download_count',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_terbit_akta_perkawinan' => 'date',
        'pewaris_tanggal_lahir' => 'date',
        'pewaris_tanggal_kematian' => 'date',
        'pewaris_tanggal_terbit_akta_kematian' => 'date',
        'tanggal_sidang' => 'date',
        'jam_sidang' => 'datetime:H:i',
        'evidence_photos' => 'array',
        'metadata' => 'array',
        'jumlah_anak' => 'integer',
        'download_count' => 'integer',
        'data_saksi' => 'array',

        // Timestamps
        'submitted_at' => 'datetime',
        'front_office_approved_at' => 'datetime',
        'skaw_generated_at' => 'datetime',
        'jadwal_sidang_created_at' => 'datetime',
        'evidence_uploaded_at' => 'datetime',
        'skaw_ttd_uploaded_at' => 'datetime',
        'lurah_approved_at' => 'datetime',
        'camat_approved_at' => 'datetime',
        'skaw_final_uploaded_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getSaksi1Attribute()
    {
        return $this->data_saksi['saksi1'] ?? null;
    }

    public function getSaksi2Attribute()
    {
        return $this->data_saksi['saksi2'] ?? null;
    }

    /**
     * Generate nomor surat SKAW
     */
    public static function generateNomorSurat($rt, $rw)
    {
        $tahun = date('Y');
        $bulan = sprintf('%02d', date('n'));

        // Format RT dan RW dengan leading zero
        $rtFormatted = sprintf('%02d', $rt);
        $rwFormatted = sprintf('%02d', $rw);

        // Hitung nomor urut berdasarkan tahun
        $lastNumber = self::whereYear('created_at', $tahun)->count();
        $noUrut = sprintf('%03d', $lastNumber + 1);

        // Format: 001/SKAW/06/30.436.9.02.01/2025
        return "{$noUrut}/SKAW/{$bulan}/30.436.9.{$rwFormatted}.{$rtFormatted}/{$tahun}";
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anakList(): HasMany
    {
        return $this->hasMany(SkawAnak::class)->orderBy('urutan');
    }

    public function files(): HasMany
    {
        return $this->hasMany(SkawFile::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(SkawActivityLog::class)->orderBy('created_at', 'desc');
    }

    // Relationship approvers
    public function frontOfficeApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'front_office_approved_by');
    }

    public function jadwalSidangCreator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jadwal_sidang_created_by');
    }

    public function evidenceUploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evidence_uploaded_by');
    }

    public function skawTtdUploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'skaw_ttd_uploaded_by');
    }

    public function lurahApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lurah_approved_by');
    }

    public function camatApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'camat_approved_by');
    }

    public function skawFinalUploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'skaw_final_uploaded_by');
    }

    /**
     * Status Badge
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            'draft' => 'badge-secondary',
            'submitted' => 'badge-primary',
            'front_office_approved' => 'badge-info',
            'skaw_generated' => 'badge-warning',
            'jadwal_sidang_created' => 'badge-info',
            'sidang_selesai' => 'badge-success',
            'evidence_uploaded' => 'badge-warning',
            'lurah_approved' => 'badge-success',
            'camat_approved' => 'badge-success',
            'skaw_final' => 'badge-success',
            'completed' => 'badge-success',
        ];

        $statusLabels = [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'front_office_approved' => 'Front Office Approved',
            'skaw_generated' => 'SKAW Draft Dibuat',
            'jadwal_sidang_created' => 'Jadwal Sidang Dibuat',
            'sidang_selesai' => 'Sidang Selesai',
            'evidence_uploaded' => 'Evidence Uploaded',
            'lurah_approved' => 'Lurah Approved',
            'camat_approved' => 'Camat Approved',
            'skaw_final' => 'SKAW Final',
            'completed' => 'Selesai',
        ];

        $class = $statusClasses[$this->status] ?? 'badge-secondary';
        $label = $statusLabels[$this->status] ?? ucfirst($this->status);

        return '<span class="badge ' . $class . '">' . $label . '</span>';
    }

    /**
     * Status Text
     */
    public function getStatusTextAttribute()
    {
        $statusMap = [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'front_office_approved' => 'Disetujui Front Office',
            'skaw_generated' => 'SKAW Draft Dibuat',
            'jadwal_sidang_created' => 'Jadwal Sidang Dibuat',
            'sidang_selesai' => 'Sidang Selesai',
            'evidence_uploaded' => 'Evidence Diupload',
            'lurah_approved' => 'Disetujui Lurah',
            'camat_approved' => 'Disetujui Camat',
            'skaw_final' => 'SKAW Final Tersedia',
            'completed' => 'Selesai',
        ];

        return $statusMap[$this->status] ?? 'Status Tidak Diketahui';
    }

    /**
     * Workflow Progress
     */
    public function getWorkflowProgressAttribute()
    {
        $steps = [
            'submitted' => $this->status !== 'draft',
            'front_office_approved' => in_array($this->status, [
                'front_office_approved', 'skaw_generated', 'jadwal_sidang_created',
                'sidang_selesai', 'evidence_uploaded', 'lurah_approved',
                'camat_approved', 'skaw_final', 'completed'
            ]),
            'skaw_generated' => in_array($this->status, [
                'skaw_generated', 'jadwal_sidang_created', 'sidang_selesai',
                'evidence_uploaded', 'lurah_approved', 'camat_approved',
                'skaw_final', 'completed'
            ]),
            'jadwal_sidang_created' => in_array($this->status, [
                'jadwal_sidang_created', 'sidang_selesai', 'evidence_uploaded',
                'lurah_approved', 'camat_approved', 'skaw_final', 'completed'
            ]),
            'sidang_completed' => in_array($this->status, [
                'sidang_selesai', 'evidence_uploaded', 'lurah_approved',
                'camat_approved', 'skaw_final', 'completed'
            ]),
            'evidence_uploaded' => in_array($this->status, [
                'evidence_uploaded', 'lurah_approved', 'camat_approved',
                'skaw_final', 'completed'
            ]),
            'lurah_approved' => in_array($this->status, [
                'lurah_approved', 'camat_approved', 'skaw_final', 'completed'
            ]),
            'camat_approved' => in_array($this->status, [
                'camat_approved', 'skaw_final', 'completed'
            ]),
            'completed' => $this->status === 'completed',
        ];

        return $steps;
    }

    /**
     * Check if sidang can be uploaded (after tanggal_sidang)
     */
    public function canUploadEvidenceSidang()
    {
        return $this->tanggal_sidang &&
               Carbon::today()->greaterThanOrEqualTo($this->tanggal_sidang) &&
               $this->status === 'jadwal_sidang_created';
    }

    /**
     * Check various permissions based on status and user role
     */
    public function canBeEditedBy($user)
    {
        // Admin always can edit
        if ($user->role === 'admin') {
            return true;
        }

        // Owner can edit only if status is draft or submitted (belum di-approve Front Office)
        if ($this->user_id === $user->id) {
            return in_array($this->status, ['draft', 'submitted']);
        }

        return false;
    }

    public function canBeDeletedBy($user)
    {
        // Admin always can delete
        if ($user->role === 'admin') {
            return true;
        }

        // Owner can delete only if status is draft or submitted (belum di-approve Front Office)
        if ($this->user_id === $user->id) {
            return in_array($this->status, ['draft', 'submitted']);
        }

        return false;
    }

    public function canBeApprovedByFrontOffice()
    {
        return $this->status === 'submitted';
    }

    public function canCreateJadwalSidang()
    {
        return $this->status === 'skaw_generated';
    }

    public function canUploadEvidence()
    {
        return $this->status === 'jadwal_sidang_created' && $this->canUploadEvidenceSidang();
    }

    public function canBeApprovedByLurah()
    {
        return $this->status === 'evidence_uploaded';
    }

    public function canBeApprovedByCamat()
    {
        return $this->status === 'lurah_approved';
    }

    public function canUploadSkawFinal()
    {
        return $this->status === 'camat_approved';
    }

    /**
     * File URL getters
     */
    public function getTandaTerimaUrlAttribute()
    {
        return $this->file_tanda_terima ? Storage::url($this->file_tanda_terima) : null;
    }

    public function getSkawDraftUrlAttribute()
    {
        return $this->file_skaw_draft ? Storage::url($this->file_skaw_draft) : null;
    }

    public function getDaftarSidangUrlAttribute()
    {
        return $this->file_daftar_sidang ? Storage::url($this->file_daftar_sidang) : null;
    }

    public function getEvidencePdfUrlAttribute()
    {
        return $this->file_evidence_pdf ? Storage::url($this->file_evidence_pdf) : null;
    }

    public function getSkawTtdScanUrlAttribute()
    {
        return $this->file_skaw_ttd_scan ? Storage::url($this->file_skaw_ttd_scan) : null;
    }

    public function getSkawFinalUrlAttribute()
    {
        return $this->file_skaw_final ? Storage::url($this->file_skaw_final) : null;
    }

    /**
     * Get formatted dates
     */
    public function getFormattedTanggalLahirAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d/m/Y') : '-';
    }

    public function getFormattedTanggalSidangAttribute()
    {
        return $this->tanggal_sidang ? $this->tanggal_sidang->format('d/m/Y') : '-';
    }

    public function getFormattedJamSidangAttribute()
    {
        return $this->jam_sidang ? $this->jam_sidang->format('H:i') : '-';
    }

    /**
     * Progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        $statusProgress = [
            'draft' => 0,
            'submitted' => 10,
            'front_office_approved' => 20,
            'skaw_generated' => 30,
            'jadwal_sidang_created' => 40,
            'sidang_selesai' => 50,
            'evidence_uploaded' => 60,
            'lurah_approved' => 70,
            'camat_approved' => 80,
            'skaw_final' => 90,
            'completed' => 100,
        ];

        return $statusProgress[$this->status] ?? 0;
    }

    /**
     * Scopes
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNeedsFrontOfficeAction($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeNeedsJadwalSidang($query)
    {
        return $query->where('status', 'skaw_generated');
    }

    public function scopeCanUploadEvidence($query)
    {
        return $query->where('status', 'jadwal_sidang_created')
                    ->where('tanggal_sidang', '<=', Carbon::today());
    }

    public function scopeNeedsLurahApproval($query)
    {
        return $query->where('status', 'evidence_uploaded');
    }

    public function scopeNeedsCamatApproval($query)
    {
        return $query->where('status', 'lurah_approved');
    }

    public function scopeCanUploadFinal($query)
    {
        return $query->where('status', 'camat_approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Clean up files when deleting
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($skaw) {
            // Delete main files
            $files = [
                $skaw->file_tanda_terima,
                $skaw->file_skaw_draft,
                $skaw->file_daftar_sidang,
                $skaw->file_evidence_pdf,
                $skaw->file_skaw_ttd_scan,
                $skaw->file_skaw_final,
            ];

            foreach ($files as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            // Delete evidence photos
            if ($skaw->evidence_photos) {
                foreach ($skaw->evidence_photos as $photo) {
                    if (Storage::disk('public')->exists($photo)) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }

            // Delete requirement files
            foreach ($skaw->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }
        });
    }
}
