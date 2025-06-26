<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\Loggable;

class UserApplication extends Model
{
    use HasFactory;
    use Loggable;

    protected $fillable = [
        'nomor_surat',
        'user_id',
        'jenis_permohonan',
        'judul_permohonan',
        'deskripsi_permohonan',
        'nama_pemohon',
        'nik',
        'rt',
        'rw',
        'status',
        'approved_rt_at',
        'approved_rt_by',
        'catatan_rt',
        'approved_rw_at',
        'approved_rw_by',
        'catatan_rw',
        'approved_kelurahan_at',
        'approved_kelurahan_by',
        'catatan_kelurahan',
        'file_pdf',
        'file_lampiran',
        'reference_id',
        'reference_table',
        'metadata',
        'download_count',
        'ditujukan_kepada', // New field for PSU target level
        'level_akhir' // New field to determine final approval level
    ];

    protected $casts = [
        'approved_rt_at' => 'datetime',
        'approved_rw_at' => 'datetime',
        'approved_kelurahan_at' => 'datetime',
        'file_lampiran' => 'array',
        'metadata' => 'array'
    ];

    /**
     * Relationship dengan User (Pemohon)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship dengan User (Approver RT)
     */
    public function approverRT(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_rt_by');
    }

    /**
     * Relationship dengan User (Approver RW)
     */
    public function approverRW(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_rw_by');
    }

    /**
     * Relationship dengan User (Approver Kelurahan)
     */
    public function approverKelurahan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_kelurahan_by');
    }

    /**
     * Relationship dengan PSU
     */
    public function psu()
    {
        return $this->belongsTo(Psu::class, 'reference_id')->where('reference_table', 'psu');
    }

    /**
     * Scope untuk PSU Internal yang diterima
     */
    public function scopePsuMasuk($query)
    {
        return $query->where('jenis_permohonan', 'PSU')
                    ->whereJsonContains('metadata->is_surat_masuk', true);
    }

    /**
     * Check if this is PSU Internal received
     */
    public function isPsuMasuk()
    {
        $metadata = $this->metadata ?? [];
        return $this->jenis_permohonan === 'PSU' &&
            isset($metadata['is_surat_masuk']) &&
            $metadata['is_surat_masuk'] === true;
    }

    /**
     * Get the reference model (polymorphic relation)
     */
    public function reference()
    {
        $modelClass = $this->getModelClass();
        if ($modelClass) {
            return $modelClass::find($this->reference_id);
        }
        return null;
    }

    /**
     * Get model class based on reference table
     */
    private function getModelClass()
    {
        $modelMap = [
            'puntadewa' => 'App\Models\Puntadewa',
            'psu' => 'App\Models\Psu',
            'skaw' => 'App\Models\Skaw',
            'surat_pengantar' => 'App\Models\SuratPengantar',
            'verifikasi_domisili' => 'App\Models\VerifikasiDomisili',
        ];

        return $modelMap[$this->reference_table] ?? null;
    }

    /**
     * Determine if PSU is internal (for own constituents) or external (needs approval)
     */
    public function isPSUInternal()
    {
        if ($this->jenis_permohonan !== 'PSU') {
            return false;
        }

        // Check based on ditujukan_kepada field
        return in_array($this->ditujukan_kepada, ['warga_rt', 'warga_rw']);
    }

    /**
     * Determine if application needs Kelurahan approval
     */
    public function needsKelurahanApproval()
    {
        // SKAW and VERIFIKASI_DOMISILI always need Kelurahan
        if (in_array($this->jenis_permohonan, ['SKAW', 'VERIFIKASI_DOMISILI'])) {
            return true;
        }

        // PSU logic
        if ($this->jenis_permohonan === 'PSU') {
            // Internal PSU (for own constituents) don't need any approval
            if ($this->isPSUInternal()) {
                return false;
            }
            // External PSU to Kelurahan needs approval
            return $this->ditujukan_kepada === 'kelurahan';
        }

        // PUNTADEWA and SURAT_PENGANTAR only need up to RW
        return false;
    }

    /**
     * Determine if application needs RW approval
     */
    public function needsRWApproval()
    {
        // PSU logic
        if ($this->jenis_permohonan === 'PSU') {
            // Internal PSU (for own constituents) don't need any approval
            if ($this->isPSUInternal()) {
                return false;
            }
            // External PSU to RW or Kelurahan needs RW approval
            return in_array($this->ditujukan_kepada, ['rw', 'kelurahan']);
        }

        // All other applications need RW approval
        return true;
    }

    /**
     * Determine if application needs RT approval
     */
    public function needsRTApproval()
    {
        // PSU logic
        if ($this->jenis_permohonan === 'PSU') {
            // Internal PSU (for own constituents) don't need any approval
            if ($this->isPSUInternal()) {
                return false;
            }
            // External PSU always needs RT approval first
            return true;
        }

        // All other applications need RT approval
        return true;
    }

    /**
     * Get final approval level for this application
     */
    public function getFinalApprovalLevel()
    {
        // PSU Internal (for own constituents) - auto approved, no workflow needed
        if ($this->jenis_permohonan === 'PSU' && $this->isPSUInternal()) {
            return 'auto_approved';
        }

        if ($this->needsKelurahanApproval()) {
            return 'kelurahan';
        } elseif ($this->needsRWApproval()) {
            return 'rw';
        } elseif ($this->needsRTApproval()) {
            return 'rt';
        } else {
            return 'auto_approved';
        }
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan jenis permohonan
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_permohonan', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $finalLevel = $this->getFinalApprovalLevel();

        // PSU Internal - auto approved
        if ($finalLevel === 'auto_approved') {
            return '<span class="badge bg-success">Selesai (Auto Approved)</span>';
        }

        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'pending_rt' => '<span class="badge bg-warning">Menunggu Persetujuan RT</span>',
            'approved_rt' => $this->getApprovedRTBadge(),
            'rejected_rt' => '<span class="badge bg-danger">Ditolak RT</span>',
            'pending_rw' => '<span class="badge bg-info">Menunggu Persetujuan RW</span>',
            'approved_rw' => $this->getApprovedRWBadge(),
            'rejected_rw' => '<span class="badge bg-danger">Ditolak RW</span>',
            'pending_kelurahan' => '<span class="badge bg-primary">Menunggu Kelurahan</span>',
            'approved_kelurahan' => '<span class="badge bg-success">Disetujui</span>',
            'rejected_kelurahan' => '<span class="badge bg-danger">Ditolak Kelurahan</span>',
            'completed' => '<span class="badge bg-success">Selesai</span>',
            'auto_approved' => '<span class="badge bg-success">Selesai (Auto Approved)</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }

    /**
     * Get badge for approved_rt status based on final level
     */
    private function getApprovedRTBadge()
    {
        $finalLevel = $this->getFinalApprovalLevel();

        if ($finalLevel === 'rt') {
            return '<span class="badge bg-success">Selesai</span>';
        } elseif ($finalLevel === 'rw') {
            return '<span class="badge bg-info">Menunggu Persetujuan RW</span>';
        } else { // kelurahan
            return '<span class="badge bg-info">Menunggu Persetujuan RW</span>';
        }
    }

    /**
     * Get badge for approved_rw status based on final level
     */
    private function getApprovedRWBadge()
    {
        $finalLevel = $this->getFinalApprovalLevel();

        if ($finalLevel === 'rw') {
            return '<span class="badge bg-success">Selesai</span>';
        } else { // kelurahan
            return '<span class="badge bg-primary">Menunggu Kelurahan</span>';
        }
    }

    /**
     * Get jenis permohonan badge
     */
    public function getJenisBadgeAttribute()
    {
        $badges = [
            'PUNTADEWA' => '<span class="badge bg-primary">PUNTADEWA</span>',
            'PSU' => '<span class="badge bg-success">PSU</span>',
            'SKAW' => '<span class="badge bg-warning">SKAW</span>',
            'SURAT PENGANTAR' => '<span class="badge bg-info">Surat Pengantar</span>',
            'VERIFIKASI DOMISILI' => '<span class="badge bg-purple">Verifikasi Domisili</span>',
        ];

        return $badges[$this->jenis_permohonan] ?? '<span class="badge bg-secondary">Lainnya</span>';
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedDateAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    /**
     * Get workflow progress - UPDATED LOGIC
     */
    public function getWorkflowProgressAttribute()
    {
        $needsKelurahan = $this->needsKelurahanApproval();
        $needsRW = $this->needsRWApproval();
        $needsRT = $this->needsRTApproval();
        $finalLevel = $this->getFinalApprovalLevel();

        // PSU Internal - no workflow needed
        if ($finalLevel === 'auto_approved') {
            return [
                'submitted' => true,
                'rt_approved' => false,
                'rw_approved' => false,
                'kelurahan_approved' => false,
                'needs_kelurahan' => false,
                'needs_rw' => false,
                'needs_rt' => false,
                'final_level' => 'auto_approved',
                'completed' => true,
                'auto_approved' => true
            ];
        }

        $progress = [
            'submitted' => true,
            'rt_approved' => in_array($this->status, [
                'approved_rt', 'pending_rw', 'approved_rw',
                'pending_kelurahan', 'approved_kelurahan', 'completed'
            ]),
            'rw_approved' => in_array($this->status, [
                'approved_rw', 'pending_kelurahan', 'approved_kelurahan', 'completed'
            ]),
            'kelurahan_approved' => in_array($this->status, ['approved_kelurahan', 'completed']),
            'needs_kelurahan' => $needsKelurahan,
            'needs_rw' => $needsRW,
            'needs_rt' => $needsRT,
            'final_level' => $finalLevel,
            'auto_approved' => false
        ];

        // Determine completion based on final level
        switch ($finalLevel) {
            case 'rt':
                $progress['completed'] = $this->status === 'approved_rt' || $this->status === 'completed';
                break;
            case 'rw':
                $progress['completed'] = $this->status === 'approved_rw' || $this->status === 'completed';
                break;
            case 'kelurahan':
                $progress['completed'] = $this->status === 'approved_kelurahan' || $this->status === 'completed';
                break;
            case 'auto_approved':
                $progress['completed'] = true;
                break;
        }

        return $progress;
    }

    /**
     * Check if can download PDF
     */
    public function canDownloadPDF()
    {
        $finalLevel = $this->getFinalApprovalLevel();

        // PSU Internal - can download immediately
        if ($finalLevel === 'auto_approved') {
            return $this->file_pdf !== null;
        }

        $approvedStatuses = [];
        switch ($finalLevel) {
            case 'rt':
                $approvedStatuses = ['approved_rt', 'completed'];
                break;
            case 'rw':
                $approvedStatuses = ['approved_rw', 'completed'];
                break;
            case 'kelurahan':
                $approvedStatuses = ['approved_kelurahan', 'completed'];
                break;
        }

        return in_array($this->status, $approvedStatuses) && $this->file_pdf;
    }

    /**
     * Check if can preview PDF
     */
    public function canPreviewPDF()
    {
        return true; // Allow preview at any stage
    }

    /**
     * Get RT and RW display
     */
    public function getRtRwDisplayAttribute()
    {
        return "RT {$this->rt} / RW {$this->rw}";
    }

    /**
     * Check if document is rejected
     */
    public function isRejected()
    {
        return in_array($this->status, ['rejected_rt', 'rejected_rw', 'rejected_kelurahan']);
    }

    /**
     * Check if document is approved (final approval based on type)
     */
    public function isApproved()
    {
        $finalLevel = $this->getFinalApprovalLevel();

        // PSU Internal - always approved
        if ($finalLevel === 'auto_approved') {
            return $this->status === 'auto_approved';
        }

        switch ($finalLevel) {
            case 'rt':
                return $this->status === 'approved_rt';
            case 'rw':
                return in_array($this->status, ['approved_rw', 'completed']);
            case 'kelurahan':
                return in_array($this->status, ['approved_kelurahan', 'completed']);
            default:
                return false;
        }
    }

    /**
     * Check if document is pending
     */
    public function isPending()
    {
        $finalLevel = $this->getFinalApprovalLevel();

        // PSU Internal - never pending
        if ($finalLevel === 'auto_approved') {
            return false;
        }

        $pendingStatuses = ['pending_rt'];

        if ($finalLevel !== 'rt') {
            $pendingStatuses[] = 'approved_rt';
            $pendingStatuses[] = 'pending_rw';
        }

        if ($finalLevel === 'kelurahan') {
            $pendingStatuses[] = 'approved_rw';
            $pendingStatuses[] = 'pending_kelurahan';
        }

        return in_array($this->status, $pendingStatuses);
    }

    /**
     * Get detail URL based on application type
     */
    public function getDetailUrlAttribute()
    {
        $baseRoutes = [
            'PUNTADEWA' => 'puntadewa.show',
            'PSU' => 'psu.show',
            'SKAW' => 'skaw.show',
            'SURAT PENGANTAR' => 'surat-pengantar.show',
            'VERIFIKASI DOMISILI' => 'verifikasi-domisili.show',
        ];

        $route = $baseRoutes[$this->jenis_permohonan] ?? null;

        if ($route) {
            return route($route, $this->reference_id);
        }

        return route('user-applications.show', $this->id);
    }

    /**
     * Create or update application record when source record changes
     */
    public static function syncFromSource($sourceModel, $jenisPermohonan, $referenceTable)
    {
        $application = self::where('reference_id', $sourceModel->id)
                          ->where('reference_table', $referenceTable)
                          ->first();

        $data = [
            'nomor_surat' => $sourceModel->nomor_surat,
            'user_id' => $sourceModel->user_id,
            'jenis_permohonan' => $jenisPermohonan,
            'judul_permohonan' => self::generateJudul($sourceModel, $jenisPermohonan),
            'deskripsi_permohonan' => self::generateDeskripsi($sourceModel, $jenisPermohonan),
            'nama_pemohon' => $sourceModel->nama_pemohon,
            'nik' => $sourceModel->nik,
            'rt' => $sourceModel->rt,
            'rw' => $sourceModel->rw,
            'status' => $sourceModel->status,
            'approved_rt_at' => $sourceModel->approved_rt_at,
            'approved_rt_by' => $sourceModel->approved_rt_by,
            'catatan_rt' => $sourceModel->catatan_rt,
            'approved_rw_at' => $sourceModel->approved_rw_at,
            'approved_rw_by' => $sourceModel->approved_rw_by,
            'catatan_rw' => $sourceModel->catatan_rw,
            'file_pdf' => $sourceModel->file_pdf,
            'reference_id' => $sourceModel->id,
            'reference_table' => $referenceTable,
            'metadata' => self::generateMetadata($sourceModel, $jenisPermohonan),
            'ditujukan_kepada' => $sourceModel->ditujukan_kepada ?? null,
            'level_akhir' => $sourceModel->level_akhir ?? null
        ];

        // Add kelurahan fields if applicable
        if (isset($sourceModel->approved_kelurahan_at)) {
            $data['approved_kelurahan_at'] = $sourceModel->approved_kelurahan_at;
            $data['approved_kelurahan_by'] = $sourceModel->approved_kelurahan_by;
            $data['catatan_kelurahan'] = $sourceModel->catatan_kelurahan;
        }

        if ($application) {
            $application->update($data);
        } else {
            self::create($data);
        }
    }

    /**
     * Generate judul based on application type
     */
    private static function generateJudul($sourceModel, $jenisPermohonan)
    {
        switch ($jenisPermohonan) {
            case 'PUNTADEWA':
                return 'Pernyataan Tempat Tinggal Non Permanen';
            case 'PSU':
                return 'Permohonan Surat Umum';
            case 'SKAW':
                return 'Surat Keterangan Ahli Waris';
            case 'SURAT PENGANTAR':
                return 'Surat Pengantar';
            case 'VERIFIKASI DOMISILI':
                return 'Verifikasi Domisili';
            default:
                return 'Permohonan';
        }
    }

    /**
     * Generate deskripsi based on source model
     */
    private static function generateDeskripsi($sourceModel, $jenisPermohonan)
    {
        switch ($jenisPermohonan) {
            case 'PUNTADEWA':
                return $sourceModel->alasan_tinggal ?? '';
            case 'PSU':
                $target = $sourceModel->ditujukan_kepada ?? 'rw';
                $description = "Ditujukan kepada: " . strtoupper($target);

                // Add PSU type info
                if (in_array($target, ['warga_rt', 'warga_rw'])) {
                    $description .= " (Internal - Auto Approved)";
                } else {
                    $description .= " (External - Butuh Approval)";
                }

                return $description;
            default:
                return '';
        }
    }

    /**
     * Generate metadata based on source model
     */
    private static function generateMetadata($sourceModel, $jenisPermohonan)
    {
        $metadata = [];

        switch ($jenisPermohonan) {
            case 'PUNTADEWA':
                $metadata = [
                    'alamat_asal' => $sourceModel->alamat_asal,
                    'nama_penjamin' => $sourceModel->nama_penjamin,
                    'alamat_penjamin' => $sourceModel->alamat_penjamin,
                    'no_telp_penjamin' => $sourceModel->no_telp_penjamin,
                ];
                break;
            case 'PSU':
                $metadata = [
                    'ditujukan_kepada' => $sourceModel->ditujukan_kepada ?? $sourceModel->level_akhir,
                    'jenis_usaha' => $sourceModel->jenis_usaha ?? null,
                ];
                break;
        }

        return $metadata;
    }

    // Override untuk custom logging
    protected static function getActivityDescription($action, $model)
    {
        $descriptions = [
            'create' => "Mengajukan permohonan {$model->jenis_permohonan}",
            'update' => "Memperbarui permohonan {$model->jenis_permohonan}",
            'delete' => "Menghapus permohonan {$model->jenis_permohonan}",
        ];

        $baseDescription = $descriptions[$action] ?? parent::getActivityDescription($action, $model);

        if ($model->nomor_surat) {
            return $baseDescription . " ({$model->nomor_surat})";
        }

        return $baseDescription;
    }
}
