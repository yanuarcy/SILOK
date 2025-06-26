<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Traits\Loggable;
use Carbon\Carbon;

class Psu extends Model
{
    use HasFactory;
    use Loggable;

    protected $table = 'psu';

    protected $fillable = [
        'nomor_surat',
        'user_id',
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
        'ditujukan_kepada',
        'target_type',
        'target_rt',
        'target_rw',
        'target_warga_id',
        'target_warga_name',
        'nama_ketua_rt',
        'nama_ketua_rw',
        'bulan',
        'sifat',
        'hal',
        'isi_surat',
        'tujuan_internal',
        'tujuan_eksternal',
        'status',
        'ttd_pemohon',
        'ttd_rt',
        'stempel_rt',
        'ttd_rw',
        'stempel_rw',
        'ttd_kelurahan',
        'stempel_kelurahan',
        'approved_rt_at',
        'approved_rt_by',
        'catatan_rt',
        'approved_rw_at',
        'approved_rw_by',
        'catatan_rw',
        'approved_kelurahan_at',
        'approved_kelurahan_by',
        'catatan_kelurahan',
        'received_kelurahan_at',
        'received_kelurahan_by',
        'processed_lurah_at',
        'processed_lurah_by',
        'processed_back_office_at',
        'processed_back_office_by',
        'catatan_lurah',
        'surat_tanda_terima',
        'surat_disposisi',
        'file_esurat',
        'nomor_nota_dinas',
        'file_pdf',
        'file_lampiran',
        'download_count',
        'level_akhir',
        'metadata',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'approved_rt_at' => 'datetime',
        'approved_rw_at' => 'datetime',
        'approved_kelurahan_at' => 'datetime',
        'received_kelurahan_at' => 'datetime',
        'processed_lurah_at' => 'datetime',
        'processed_back_office_at' => 'datetime',
        'file_lampiran' => 'array',
        'metadata' => 'array',
        'bulan' => 'integer', // PERBAIKAN: Pastikan bulan di-cast sebagai integer
    ];

    // PERBAIKAN: Hapus bulan dari $dates karena bukan tanggal
    protected $dates = [
        'created_at',
        'updated_at',
        'tanggal_lahir',
        'approved_rt_at',
        'approved_rw_at',
        'approved_kelurahan_at'
    ];

    /**
     * Generate nomor surat PSU dengan format yang benar
     * Format: no_agenda/bulan/kode_kecamatan.kode_kelurahan.kode_rw.kode_rt/tahun
     * Contoh: 01/06/01.01.04.03/2025
     */
    public static function generateNomorSurat($rt, $rw, $bulan)
    {
        $tahun = date('Y');
        $bulanNum = sprintf('%02d', $bulan);

        // Format RT dan RW dengan leading zero
        $rtFormatted = sprintf('%02d', $rt);
        $rwFormatted = sprintf('%02d', $rw);

        // Kode lokasi
        $kodeKecamatan = '30';
        $kodeKelurahan = '436.9';

        // Hitung nomor agenda berdasarkan bulan dan tahun
        $lastNumber = self::whereYear('created_at', $tahun)
                         ->whereMonth('created_at', $bulan)
                         ->count();

        $noAgenda = sprintf('%02d', $lastNumber + 1);

        return "{$noAgenda}/{$bulanNum}/{$kodeKecamatan}.{$kodeKelurahan}.{$rwFormatted}.{$rtFormatted}/{$tahun}";
    }

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
     * Relationship dengan User (Front Office)
     */
    public function frontOffice(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_kelurahan_by');
    }

    /**
     * Relationship dengan User (Lurah)
     */
    public function lurah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_lurah_by');
    }

     /**
     * Relationship dengan User (Back Office)
     */
    public function backOffice(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_back_office_by');
    }

    /**
     * Get lurah processor relationship
     */
    public function lurahProcessor()
    {
        return $this->belongsTo(User::class, 'processed_lurah_by');
    }

    /**
     * Relationship dengan UserApplication (untuk surat masuk)
     */
    public function userApplications()
    {
        return $this->hasMany(UserApplication::class, 'reference_id')->where('reference_table', 'psu');
    }

    /**
     * Get target warga untuk PSU Internal
     */
    public function getTargetWarga()
    {
        return $this->userApplications()
                    ->where('jenis_permohonan', 'PSU')
                    ->whereJsonContains('metadata->is_surat_masuk', true)
                    ->with('user')
                    ->get();
    }

    // public function hasSignedDisposisiLurah()
    // {
    //     $metadata = $this->metadata ?? [];
    //     return isset($metadata['file_disposisi_signed']) &&
    //         Storage::disk('public')->exists($metadata['file_disposisi_signed']);
    // }



    /**
     * Get file URLs for workflow documents
     */
    public function getTandaTerimaUrlAttribute()
    {
        return $this->surat_tanda_terima ? Storage::url($this->surat_tanda_terima) : null;
    }

    public function getDisposisiUrlAttribute()
    {
        return $this->surat_disposisi ? Storage::url($this->surat_disposisi) : null;
    }

    public function getDisposisiSignedUrlAttribute()
    {
        $metadata = $this->metadata ?? [];
        $file = $metadata['file_disposisi_signed'] ?? null;
        return $file ? Storage::url($file) : null;
    }

    public function getTtdLurahDisposisiUrlAttribute()
    {
        $metadata = $this->metadata ?? [];
        $file = $metadata['ttd_lurah_disposisi'] ?? null;
        return $file ? Storage::url($file) : null;
    }

    /**
     * Check workflow permissions
     */
    public function canBeReceivedAtKelurahan()
    {
        return $this->status === 'approved_rw' && $this->needsKelurahanApproval();
    }

    public function canBeProcessedByLurah()
    {
        return $this->status === 'pending_kelurahan';
    }

    public function canBeProcessedByBackOffice()
    {
        return $this->status === 'processed_lurah';
    }

    /**
     * Determine if PSU is internal (for own constituents) or external (needs approval)
     */
    public function isPSUInternal()
    {
        return in_array($this->ditujukan_kepada, ['warga_rt', 'warga_rw']);
    }

    /**
     * Determine if application needs Kelurahan approval
     */
    public function needsKelurahanApproval()
    {
        // Internal PSU (for own constituents) don't need any approval
        if ($this->isPSUInternal()) {
            return false;
        }

        // External PSU to Kelurahan needs approval
        return $this->ditujukan_kepada === 'kelurahan';
    }

    /**
     * Determine if application needs RW approval
     */
    public function needsRWApproval()
    {
        // Internal PSU (for own constituents) don't need any approval
        if ($this->isPSUInternal()) {
            return false;
        }

        // External PSU to RW or Kelurahan needs RW approval
        return in_array($this->ditujukan_kepada, ['rw', 'kelurahan']);
    }

    /**
     * Determine if application needs RT approval
     */
    public function needsRTApproval()
    {
        // Internal PSU (for own constituents) don't need any approval
        if ($this->isPSUInternal()) {
            return false;
        }

        // External PSU always needs RT approval first
        return true;
    }

    /**
     * Get final approval level for this application
     */
    public function getFinalApprovalLevel()
    {
        // PSU Internal (for own constituents) - auto approved, no workflow needed
        if ($this->isPSUInternal()) {
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
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan RT
     */
    public function scopeByRT($query, $rt)
    {
        return $query->where('rt', $rt);
    }

    /**
     * Scope untuk filter berdasarkan RW
     */
    public function scopeByRW($query, $rw)
    {
        return $query->where('rw', $rw);
    }

    private function buildApprovalUpdateData(Psu $psu, $level, $spesimen, $data)
    {
        $finalLevel = $psu->getFinalApprovalLevel();

        // PERBAIKAN: Logic yang lebih tepat untuk status transitions
        if ($finalLevel === $level) {
            // Ini adalah level akhir approval - langsung completed
            $newStatus = 'completed';
        } else {
            // Masih ada level selanjutnya
            switch ($level) {
                case 'rt':
                    if ($finalLevel === 'rw') {
                        $newStatus = 'pending_rw';
                    } elseif ($finalLevel === 'kelurahan') {
                        $newStatus = 'approved_rt'; // Akan lanjut ke RW dulu
                    } else {
                        $newStatus = 'completed'; // RT adalah final
                    }
                    break;

                case 'rw':
                    if ($finalLevel === 'kelurahan') {
                        $newStatus = 'approved_rw'; // Akan lanjut ke Kelurahan
                    } else {
                        $newStatus = 'completed'; // RW adalah final
                    }
                    break;

                case 'kelurahan':
                    $newStatus = 'completed'; // Kelurahan selalu final
                    break;

                default:
                    $newStatus = "approved_{$level}";
            }
        }

        $updateData = [
            'status' => $newStatus,
            "ttd_{$level}" => $spesimen->file_ttd,
            "stempel_{$level}" => $spesimen->file_stempel,
            "catatan_{$level}" => $data["catatan_{$level}"] ?? null,
            "approved_{$level}_at" => now(),
            "approved_{$level}_by" => Auth::id(),
        ];

        return $updateData;
    }

    /**
     * Get status badge HTML - FIXED untuk Carbon error
     */
    public function getStatusBadgeAttribute()
    {
        try {
            $finalLevel = $this->getFinalApprovalLevel();

            // PSU Internal atau Completed
            if ($finalLevel === 'auto_approved' || $this->status === 'completed') {
                return '<span class="badge badge-success">Selesai</span>';
            }

            // PERBAIKAN: Logic badge yang lebih akurat dengan indikator tanda terima
            switch ($this->status) {
                case 'pending_rt':
                    return '<span class="badge badge-warning">Menunggu Persetujuan RT</span>';

                case 'approved_rt':
                    if ($finalLevel === 'rt') {
                        return '<span class="badge badge-success">Selesai</span>';
                    } elseif ($finalLevel === 'rw') {
                        return '<span class="badge badge-info">Menunggu Persetujuan RW</span>';
                    } else { // kelurahan
                        return '<span class="badge badge-info">Menunggu Persetujuan RW</span>';
                    }

                case 'pending_rw':
                    return '<span class="badge badge-info">Menunggu Persetujuan RW</span>';

                case 'approved_rw':
                    if ($finalLevel === 'rw') {
                        return '<span class="badge badge-success">Selesai</span>';
                    } else { // kelurahan
                        // PERBAIKAN: Cek apakah sudah diterima di kelurahan
                        if ($this->received_kelurahan_at && $this->surat_tanda_terima) {
                            return '<span class="badge badge-primary">
                                        <i class="fas fa-receipt text-white mr-1"></i>Diterima Kelurahan
                                    </span>';
                        } else {
                            return '<span class="badge badge-primary">Menunggu Kelurahan</span>';
                        }
                    }

                case 'pending_kelurahan':
                    return '<span class="badge badge-primary">
                                <i class="fas fa-receipt text-white mr-1"></i>Menunggu Disposisi Lurah
                            </span>';

                case 'processing_lurah':
                    return '<span class="badge badge-info">Diproses Lurah</span>';

                case 'processed_lurah':
                    return '<span class="badge badge-warning">Menunggu Back Office</span>';

                case 'processing_back_office':
                    return '<span class="badge badge-info">Diproses Back Office</span>';

                case 'approved_kelurahan':
                    return '<span class="badge badge-success">Selesai</span>';

                case 'completed':
                    return '<span class="badge badge-success">Selesai</span>';

                case 'rejected_rt':
                    return '<span class="badge badge-danger">Ditolak RT</span>';

                case 'rejected_rw':
                    return '<span class="badge badge-danger">Ditolak RW</span>';

                case 'rejected_kelurahan':
                    return '<span class="badge badge-danger">Ditolak Kelurahan</span>';

                default:
                    return '<span class="badge badge-secondary">Status Tidak Diketahui</span>';
            }
        } catch (\Exception $e) {
            Log::error('Error getting status badge for PSU ID ' . $this->id . ': ' . $e->getMessage());
            return '<span class="badge badge-secondary">Error</span>';
        }
    }

    /**
     * Check if document has been received at Kelurahan with Tanda Terima
     */
    public function hasBeenReceivedAtKelurahan()
    {
        return $this->received_kelurahan_at &&
               $this->surat_tanda_terima &&
               Storage::disk('public')->exists($this->surat_tanda_terima);
    }

    /**
     * Check if document has Disposisi Lurah
     */
    public function hasDisposisiLurah()
    {
        return $this->surat_disposisi &&
               Storage::disk('public')->exists($this->surat_disposisi);
    }

    /**
     * Check if Lurah has processed the disposisi (signed)
     */
    public function hasSignedDisposisiLurah()
    {
        $metadata = $this->metadata ?? [];
        $signedFile = $metadata['file_disposisi_signed'] ?? null;
        return $signedFile && Storage::disk('public')->exists($signedFile);
    }

    /**
     * Get workflow indicators for display
     */
    public function getWorkflowIndicatorsAttribute()
    {
        $indicators = [];

        // Indicator untuk tanda terima
        if ($this->hasBeenReceivedAtKelurahan()) {
            $indicators[] = [
                'icon' => 'fas fa-receipt',
                'color' => 'success',
                'title' => 'Tanda Terima Tersedia',
                'url' => Storage::url($this->surat_tanda_terima)
            ];
        }

        // Indicator untuk disposisi
        if ($this->hasDisposisiLurah()) {
            $indicators[] = [
                'icon' => 'fas fa-clipboard-list',
                'color' => 'info',
                'title' => 'Disposisi Lurah',
                'url' => Storage::url($this->surat_disposisi)
            ];
        }

        // Indicator untuk disposisi signed
        if ($this->hasSignedDisposisiLurah()) {
            $metadata = $this->metadata ?? [];
            $indicators[] = [
                'icon' => 'fas fa-signature',
                'color' => 'warning',
                'title' => 'Disposisi Ditandatangani',
                'url' => Storage::url($metadata['file_disposisi_signed'])
            ];
        }

        return $indicators;
    }

    /**
     * Get badge for approved_rt status based on final level
     */
    private function getApprovedRTBadge()
    {
        try {
            $finalLevel = $this->getFinalApprovalLevel();

            if ($finalLevel === 'rt') {
                return '<span class="badge badge-success">Selesai</span>';
            } elseif ($finalLevel === 'rw') {
                return '<span class="badge badge-info">Menunggu Persetujuan RW</span>';
            } else { // kelurahan
                return '<span class="badge badge-info">Menunggu Persetujuan RW</span>';
            }
        } catch (\Exception $e) {
            return '<span class="badge badge-warning">Disetujui RT</span>';
        }
    }

    /**
     * Get badge for approved_rw status based on final level
     */
    private function getApprovedRWBadge()
    {
        try {
            $finalLevel = $this->getFinalApprovalLevel();

            if ($finalLevel === 'rw') {
                return '<span class="badge badge-success">Selesai</span>';
            } else { // kelurahan
                return '<span class="badge badge-primary">Menunggu Kelurahan</span>';
            }
        } catch (\Exception $e) {
            return '<span class="badge badge-info">Disetujui RW</span>';
        }
    }

    private function getApprovedKelurahanBadge()
    {
        try {
            $finalLevel = $this->getFinalApprovalLevel();

            if ($finalLevel === 'kelurahan') {
                return '<span class="badge badge-success">Selesai</span>';
            } else {
                return '<span class="badge badge-success">Disetujui</span>';
            }
        } catch (\Exception $e) {
            return '<span class="badge badge-success">Disetujui Kelurahan</span>';
        }
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        try {
            $finalLevel = $this->getFinalApprovalLevel();

            if ($finalLevel === 'auto_approved') {
                return 'Selesai (Auto Approved)';
            }

            switch ($this->status) {
                case 'pending_rt':
                    return 'Menunggu Persetujuan RT';

                case 'approved_rt':
                    if ($finalLevel === 'rt') {
                        return 'Selesai';
                    } elseif ($finalLevel === 'rw') {
                        return 'Menunggu Persetujuan RW';
                    } else {
                        return 'Menunggu Persetujuan RW';
                    }

                case 'pending_rw':
                    return 'Menunggi Persetujuan RW';

                case 'approved_rw':
                    if ($finalLevel === 'rw') {
                        return 'Selesai';
                    } else {
                        return 'Menunggu Kelurahan';
                    }

                case 'pending_kelurahan':
                    return 'Menunggu Kelurahan';

                case 'processing_lurah':
                    return 'Diproses Lurah';

                case 'processed_lurah':
                    return 'Menunggu Back Office';

                case 'processing_back_office':
                    return 'Diproses Back Office';

                case 'approved_kelurahan':
                case 'completed':
                    return 'Selesai';

                case 'rejected_rt':
                    return 'Ditolak RT';

                case 'rejected_rw':
                    return 'Ditolak RW';

                case 'rejected_kelurahan':
                    return 'Ditolak Kelurahan';

                default:
                    return 'Status Tidak Diketahui';
            }
        } catch (\Exception $e) {
            return 'Error Status';
        }
    }

    /**
     * Get workflow progress - FIXED untuk Carbon error
     */
    public function getWorkflowProgressAttribute()
    {
        try {
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
                    'pending_kelurahan', 'approved_kelurahan', 'completed',
                    'processing_lurah', 'processed_lurah', 'processing_back_office'
                ]),
                'rw_approved' => in_array($this->status, [
                    'approved_rw', 'pending_kelurahan', 'approved_kelurahan', 'completed',
                    'processing_lurah', 'processed_lurah', 'processing_back_office'
                ]),
                'kelurahan_approved' => in_array($this->status, [
                    'approved_kelurahan', 'completed', 'processing_lurah', 'processed_lurah', 'processing_back_office'
                ]),
                'needs_kelurahan' => $needsKelurahan,
                'needs_rw' => $needsRW,
                'needs_rt' => $needsRT,
                'final_level' => $finalLevel,
                'auto_approved' => false
            ];

            // PERBAIKAN: Determine completion berdasarkan status dan final level
            switch ($finalLevel) {
                case 'rt':
                    $progress['completed'] = in_array($this->status, ['approved_rt', 'completed']);
                    break;
                case 'rw':
                    $progress['completed'] = in_array($this->status, ['approved_rw', 'completed']);
                    break;
                case 'kelurahan':
                    $progress['completed'] = in_array($this->status, ['approved_kelurahan', 'completed']);
                    break;
                case 'auto_approved':
                    $progress['completed'] = true;
                    break;
                default:
                    $progress['completed'] = $this->status === 'completed';
            }

            return $progress;
        } catch (\Exception $e) {
            Log::error('Error getting workflow progress for PSU ID ' . $this->id . ': ' . $e->getMessage());
            return [
                'submitted' => true,
                'rt_approved' => false,
                'rw_approved' => false,
                'kelurahan_approved' => false,
                'needs_kelurahan' => false,
                'needs_rw' => false,
                'needs_rt' => false,
                'final_level' => 'unknown',
                'completed' => false,
                'auto_approved' => false
            ];
        }
    }

    /**
     * Get formatted created date - FIXED dengan null check yang lebih robust
     */
    public function getFormattedCreatedDateAttribute()
    {
        try {
            if (!$this->created_at) {
                return '-';
            }

            // Pastikan ini adalah Carbon instance
            if (is_string($this->created_at)) {
                $carbon = Carbon::parse($this->created_at);
            } else {
                $carbon = $this->created_at;
            }

            return $carbon->format('d/m/Y H:i');
        } catch (\Exception $e) {
            Log::error('Error formatting created date for PSU ID ' . $this->id . ': ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * Get formatted approved RT date - FIXED dengan null check yang lebih robust
     */
    public function getFormattedApprovedRtDateAttribute()
    {
        try {
            if (!$this->approved_rt_at) {
                return '-';
            }

            // Pastikan ini adalah Carbon instance
            if (is_string($this->approved_rt_at)) {
                $carbon = Carbon::parse($this->approved_rt_at);
            } else {
                $carbon = $this->approved_rt_at;
            }

            return $carbon->format('d/m/Y H:i');
        } catch (\Exception $e) {
            Log::error('Error formatting approved RT date for PSU ID ' . $this->id . ': ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * Get formatted approved RW date - FIXED dengan null check yang lebih robust
     */
    public function getFormattedApprovedRwDateAttribute()
    {
        try {
            if (!$this->approved_rw_at) {
                return '-';
            }

            // Pastikan ini adalah Carbon instance
            if (is_string($this->approved_rw_at)) {
                $carbon = Carbon::parse($this->approved_rw_at);
            } else {
                $carbon = $this->approved_rw_at;
            }

            return $carbon->format('d/m/Y H:i');
        } catch (\Exception $e) {
            Log::error('Error formatting approved RW date for PSU ID ' . $this->id . ': ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * Get formatted approved Kelurahan date - FIXED dengan null check yang lebih robust
     */
    public function getFormattedApprovedKelurahanDateAttribute()
    {
        try {
            if (!$this->approved_kelurahan_at) {
                return '-';
            }

            // Pastikan ini adalah Carbon instance
            if (is_string($this->approved_kelurahan_at)) {
                $carbon = Carbon::parse($this->approved_kelurahan_at);
            } else {
                $carbon = $this->approved_kelurahan_at;
            }

            return $carbon->format('d/m/Y H:i');
        } catch (\Exception $e) {
            Log::error('Error formatting approved Kelurahan date for PSU ID ' . $this->id . ': ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * Get formatted tanggal lahir - FIXED dengan null check yang lebih robust
     */
    public function getFormattedTanggalLahirAttribute()
    {
        try {
            if (!$this->tanggal_lahir) {
                return '-';
            }

            // Pastikan ini adalah Carbon instance
            if (is_string($this->tanggal_lahir)) {
                $carbon = Carbon::parse($this->tanggal_lahir);
            } else {
                $carbon = $this->tanggal_lahir;
            }

            return $carbon->format('d/m/Y');
        } catch (\Exception $e) {
            Log::error('Error formatting tanggal lahir for PSU ID ' . $this->id . ': ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * Get TTD URLs
     */
    public function getTtdPemohonUrlAttribute()
    {
        return $this->ttd_pemohon ? Storage::url($this->ttd_pemohon) : null;
    }

    public function getTtdRtUrlAttribute()
    {
        return $this->ttd_rt ? Storage::url($this->ttd_rt) : null;
    }

    public function getStempelRtUrlAttribute()
    {
        return $this->stempel_rt ? Storage::url($this->stempel_rt) : null;
    }

    public function getTtdRwUrlAttribute()
    {
        return $this->ttd_rw ? Storage::url($this->ttd_rw) : null;
    }

    public function getStempelRwUrlAttribute()
    {
        return $this->stempel_rw ? Storage::url($this->stempel_rw) : null;
    }

    public function getTtdKelurahanUrlAttribute()
    {
        return $this->ttd_kelurahan ? Storage::url($this->ttd_kelurahan) : null;
    }

    public function getStempelKelurahanUrlAttribute()
    {
        return $this->stempel_kelurahan ? Storage::url($this->stempel_kelurahan) : null;
    }

    /**
     * Get final approver name for Kelurahan level PSU
     */
    public function getFinalApproverName()
    {
        // Traditional Kelurahan approval
        if ($this->approverKelurahan) {
            return $this->approverKelurahan->name;
        }

        // Workflow Lurah-Back Office
        if ($this->lurahProcessor) {
            return $this->lurahProcessor->name;
        }

        // Back Office as final approver
        if ($this->backOffice) {
            $metadata = $this->metadata ?? [];
            $ttdSource = $metadata['ttd_source'] ?? '';

            if ($ttdSource === 'lurah_disposisi_or_front_office' && $this->lurahProcessor) {
                return $this->lurahProcessor->name;
            } else {
                return $this->backOffice->name . ' (atas nama Lurah)';
            }
        }

        // Fallback
        return '...................................';
    }

    /**
     * Get signature source information
     */
    public function getSignatureSourceInfo()
    {
        $metadata = $this->metadata ?? [];

        return [
            'ttd_source' => $metadata['ttd_source'] ?? 'unknown',
            'stempel_source' => $metadata['stempel_source'] ?? 'unknown',
            'approval_method' => $metadata['final_approval_method'] ?? 'traditional',
            'approver_name' => $this->getFinalApproverName(),
        ];
    }

    /**
     * Check if PSU uses new Kelurahan workflow (Front Office → Lurah → Back Office)
     */
    public function usesNewKelurahanWorkflow()
    {
        $metadata = $this->metadata ?? [];
        return ($metadata['final_approval_method'] ?? '') === 'back_office_kelurahan_workflow';
    }

    /**
     * Check if can be edited by pemohon
     */
    public function canBeEdited()
    {
        return $this->status === 'pending_rt' || $this->status === 'auto_approved';
    }

    /**
     * Check if can be approved by RT
     */
    public function canBeApprovedByRT()
    {
        return $this->status === 'pending_rt' && $this->needsRTApproval();
    }

    /**
     * Check if can be approved by RW
     */
    public function canBeApprovedByRW()
    {
        // PERBAIKAN: RW bisa approve jika status approved_rt DAN level_akhir bukan 'rt'
        // Ini termasuk PSU yang dibuat oleh Ketua RT dengan tujuan Kelurahan
        return $this->status === 'approved_rt' &&
            $this->needsRWApproval() &&
            $this->level_akhir !== 'rt';
    }

    /**
     * Check if can be approved by Kelurahan
     */
    public function canBeApprovedByKelurahan()
    {
        return $this->status === 'approved_rw' && $this->needsKelurahanApproval();
    }

    /**
     * Check if PDF can be downloaded
     */
    public function canDownloadPDF()
    {
        $finalLevel = $this->getFinalApprovalLevel();

        // Jika status completed, bisa download
        if ($this->status === 'completed') {
            return $this->file_pdf !== null;
        }

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
     * Check if PDF can be previewed
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

        // Jika status completed, berarti sudah selesai
        if ($this->status === 'completed') {
            return true;
        }

        // PSU Internal auto approved
        if ($finalLevel === 'auto_approved') {
            return $this->status === 'completed';
        }

        // PERBAIKAN: Check berdasarkan final level yang tepat
        switch ($finalLevel) {
            case 'rt':
                return in_array($this->status, ['approved_rt', 'completed']);
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
     * Check signatures
     */
    public function hasPemohonSignature()
    {
        return !empty($this->ttd_pemohon) && Storage::disk('public')->exists($this->ttd_pemohon);
    }

    public function hasRTSignature()
    {
        return !empty($this->ttd_rt) && Storage::disk('public')->exists($this->ttd_rt);
    }

    public function hasRTStamp()
    {
        return !empty($this->stempel_rt) && Storage::disk('public')->exists($this->stempel_rt);
    }

    public function hasRWSignature()
    {
        return !empty($this->ttd_rw) && Storage::disk('public')->exists($this->ttd_rw);
    }

    public function hasRWStamp()
    {
        return !empty($this->stempel_rw) && Storage::disk('public')->exists($this->stempel_rw);
    }

    public function hasKelurahanSignature()
    {
        return !empty($this->ttd_kelurahan) && Storage::disk('public')->exists($this->ttd_kelurahan);
    }

    public function hasKelurahanStamp()
    {
        return !empty($this->stempel_kelurahan) && Storage::disk('public')->exists($this->stempel_kelurahan);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        try {
            $finalLevel = $this->getFinalApprovalLevel();

            if ($finalLevel === 'auto_approved' || $this->status === 'completed') {
                return 100;
            }

            switch ($this->status) {
                case 'pending_rt':
                    return 25;
                case 'approved_rt':
                    return $finalLevel === 'rt' ? 100 : 50;
                case 'pending_rw':
                    return 50;
                case 'approved_rw':
                    return $finalLevel === 'rw' ? 100 : 75;
                case 'pending_kelurahan':
                    return 75;
                case 'approved_kelurahan':
                    return 100;
                case 'completed':
                    return 100;
                default:
                    return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get ditujukan kepada display text
     */
    public function getDitujukanKepadaDisplayAttribute()
    {
        $displays = [
            'warga_rt' => 'Warga RT (Internal)',
            'warga_rw' => 'Warga RW (Internal)',
            'rt' => 'RT (Persetujuan)',
            'rw' => 'RW (Persetujuan)',
            'kelurahan' => 'Kelurahan (Persetujuan)'
        ];

        return $displays[$this->ditujukan_kepada] ?? $this->ditujukan_kepada;
    }

    /**
     * Clean up associated files when deleting
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($psu) {
            // Delete signature files
            if ($psu->ttd_pemohon && Storage::disk('public')->exists($psu->ttd_pemohon)) {
                Storage::disk('public')->delete($psu->ttd_pemohon);
            }

            // Delete PDF file
            if ($psu->file_pdf && Storage::disk('public')->exists($psu->file_pdf)) {
                Storage::disk('public')->delete($psu->file_pdf);
            }

            // Delete file lampiran
            if ($psu->file_lampiran && is_array($psu->file_lampiran)) {
                foreach ($psu->file_lampiran as $file) {
                    if (Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        });
    }
}
