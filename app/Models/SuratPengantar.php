<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Traits\Loggable;

class SuratPengantar extends Model
{
    use HasFactory;
    use Loggable;

    protected $table = 'surat_pengantar';

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
        'tujuan',
        'keperluan',
        'keterangan_lain',
        'rt',
        'rw',
        'status',
        'ttd_pemohon',
        'ttd_rt',
        'stempel_rt',
        'approved_rt_at',
        'approved_rt_by',
        'catatan_rt',
        'ttd_rw',
        'stempel_rw',
        'approved_rw_at',
        'approved_rw_by',
        'catatan_rw',
        'file_pdf',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'approved_rt_at' => 'datetime',
        'approved_rw_at' => 'datetime',
    ];

    /**
     * Generate nomor surat otomatis
     */
    public static function generateNomorSurat()
    {
        $tahun = date('Y');
        $bulan = date('m');

        $lastNumber = self::whereYear('created_at', $tahun)
                         ->whereMonth('created_at', $bulan)
                         ->count();

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "SP/{$newNumber}/{$bulan}/{$tahun}";
    }

    /**
     * Get safe filename version of nomor surat (replace / with _)
     */
    public function getSafeNomorSuratAttribute()
    {
        return str_replace(['/', '\\'], '_', $this->nomor_surat);
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

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending_rt' => '<span class="badge bg-warning">Menunggu Persetujuan RT</span>',
            'approved_rt' => '<span class="badge bg-info">Menunggu Persetujuan RW</span>',
            'pending_rw' => '<span class="badge bg-info">Menunggu Persetujuan RW</span>',
            'approved_rw' => '<span class="badge bg-success">Disetujui</span>',
            'rejected_rt' => '<span class="badge bg-danger">Ditolak RT</span>',
            'rejected_rw' => '<span class="badge bg-danger">Ditolak RW</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending_rt' => 'Menunggu Persetujuan RT',
            'approved_rt' => 'Menunggu Persetujuan RW',
            'pending_rw' => 'Menunggu Persetujuan RW',
            'approved_rw' => 'Disetujui',
            'rejected_rt' => 'Ditolak RT',
            'rejected_rw' => 'Ditolak RW',
        ];

        return $statuses[$this->status] ?? 'Tidak Diketahui';
    }

    /**
     * Get workflow progress
     */
    public function getWorkflowProgressAttribute()
    {
        $progress = [
            'submitted' => true,
            'rt_approved' => in_array($this->status, ['approved_rt', 'pending_rw', 'approved_rw']),
            'rw_approved' => $this->status === 'approved_rw',
            'completed' => $this->status === 'approved_rw'
        ];

        return $progress;
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedDateAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    /**
     * Get formatted approved RT date
     */
    public function getFormattedApprovedRtDateAttribute()
    {
        return $this->approved_rt_at ? $this->approved_rt_at->format('d/m/Y') : '-';
    }

    /**
     * Get formatted approved RW date
     */
    public function getFormattedApprovedRwDateAttribute()
    {
        return $this->approved_rw_at ? $this->approved_rw_at->format('d/m/Y') : '-';
    }

    /**
     * Get formatted tanggal lahir
     */
    public function getFormattedTanggalLahirAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d/m/Y') : '-';
    }

    /**
     * Get TTD Pemohon URL
     */
    public function getTtdPemohonUrlAttribute()
    {
        return $this->ttd_pemohon ? Storage::url($this->ttd_pemohon) : null;
    }

    /**
     * Get TTD Pemilik URL
     */
    public function getTtdPemilikUrlAttribute()
    {
        return $this->ttd_pemilik ? Storage::url($this->ttd_pemilik) : null;
    }

    /**
     * Get TTD RT URL
     */
    public function getTtdRtUrlAttribute()
    {
        return $this->ttd_rt ? Storage::url($this->ttd_rt) : null;
    }

    /**
     * Get Stempel RT URL
     */
    public function getStempelRtUrlAttribute()
    {
        return $this->stempel_rt ? Storage::url($this->stempel_rt) : null;
    }

    /**
     * Get TTD RW URL
     */
    public function getTtdRwUrlAttribute()
    {
        return $this->ttd_rw ? Storage::url($this->ttd_rw) : null;
    }

    /**
     * Get Stempel RW URL
     */
    public function getStempelRwUrlAttribute()
    {
        return $this->stempel_rw ? Storage::url($this->stempel_rw) : null;
    }

    /**
     * Check if can be edited by pemohon
     */
    public function canBeEdited()
    {
        return $this->status === 'pending_rt';
    }

    /**
     * Check if can be approved by RT
     */
    public function canBeApprovedByRT()
    {
        return $this->status === 'pending_rt';
    }

    /**
     * Check if can be approved by RW
     */
    public function canBeApprovedByRW()
    {
        return $this->status === 'approved_rt';
    }

    /**
     * Check if PDF can be downloaded (fully approved)
     */
    public function canDownloadPDF()
    {
        return $this->status === 'approved_rw' && $this->file_pdf;
    }

    /**
     * Check if PDF can be previewed (at least submitted)
     */
    public function canPreviewPDF()
    {
        return true; // Allow preview at any stage
    }

    /**
     * Get next approver role needed
     */
    public function getNextApproverAttribute()
    {
        switch ($this->status) {
            case 'pending_rt':
                return 'Ketua RT';
            case 'approved_rt':
            case 'pending_rw':
                return 'Ketua RW';
            case 'approved_rw':
                return null; // Fully approved
            default:
                return null;
        }
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
        return in_array($this->status, ['rejected_rt', 'rejected_rw']);
    }

    /**
     * Check if document is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved_rw';
    }

    /**
     * Check if document is pending
     */
    public function isPending()
    {
        return in_array($this->status, ['pending_rt', 'approved_rt', 'pending_rw']);
    }

    /**
     * Get rejection reason
     */
    public function getRejectionReasonAttribute()
    {
        if ($this->status === 'rejected_rt') {
            return $this->catatan_rt;
        } elseif ($this->status === 'rejected_rw') {
            return $this->catatan_rw;
        }
        return null;
    }

    /**
     * Get approval notes
     */
    public function getApprovalNotesAttribute()
    {
        $notes = [];

        if ($this->catatan_rt) {
            $notes['rt'] = $this->catatan_rt;
        }

        if ($this->catatan_rw) {
            $notes['rw'] = $this->catatan_rw;
        }

        return $notes;
    }

    /**
     * Check if pemohon signature exists
     */
    public function hasPemohonSignature()
    {
        return !empty($this->ttd_pemohon) && Storage::disk('public')->exists($this->ttd_pemohon);
    }

    /**
     * Check if pemilik signature exists
     */
    public function hasPemilikSignature()
    {
        return !empty($this->ttd_pemilik) && Storage::disk('public')->exists($this->ttd_pemilik);
    }

    /**
     * Check if RT signature exists
     */
    public function hasRTSignature()
    {
        return !empty($this->ttd_rt) && Storage::disk('public')->exists($this->ttd_rt);
    }

    /**
     * Check if RT stamp exists
     */
    public function hasRTStamp()
    {
        return !empty($this->stempel_rt) && Storage::disk('public')->exists($this->stempel_rt);
    }

    /**
     * Check if RW signature exists
     */
    public function hasRWSignature()
    {
        return !empty($this->ttd_rw) && Storage::disk('public')->exists($this->ttd_rw);
    }

    /**
     * Check if RW stamp exists
     */
    public function hasRWStamp()
    {
        return !empty($this->stempel_rw) && Storage::disk('public')->exists($this->stempel_rw);
    }

    /**
     * Get all signatures info
     */
    public function getSignaturesInfoAttribute()
    {
        return [
            'pemohon' => [
                'exists' => $this->hasPemohonSignature(),
                'url' => $this->ttd_pemohon_url,
                'path' => $this->ttd_pemohon
            ],
            'pemilik' => [
                'exists' => $this->hasPemilikSignature(),
                'url' => $this->ttd_pemilik_url,
                'path' => $this->ttd_pemilik
            ],
            'rt' => [
                'signature_exists' => $this->hasRTSignature(),
                'stamp_exists' => $this->hasRTStamp(),
                'signature_url' => $this->ttd_rt_url,
                'stamp_url' => $this->stempel_rt_url,
                'signature_path' => $this->ttd_rt,
                'stamp_path' => $this->stempel_rt
            ],
            'rw' => [
                'signature_exists' => $this->hasRWSignature(),
                'stamp_exists' => $this->hasRWStamp(),
                'signature_url' => $this->ttd_rw_url,
                'stamp_url' => $this->stempel_rw_url,
                'signature_path' => $this->ttd_rw,
                'stamp_path' => $this->stempel_rw
            ]
        ];
    }

    /**
     * Get completion percentage based on signatures and approvals
     */
    public function getCompletionPercentageAttribute()
    {
        $totalSteps = 4; // Pemohon signature, RT approval, RW approval, PDF generation
        $completedSteps = 0;

        // Step 1: Pemohon signature (always required)
        if ($this->hasPemohonSignature()) {
            $completedSteps++;
        }

        // Step 2: RT approval
        if (in_array($this->status, ['approved_rt', 'approved_rw'])) {
            $completedSteps++;
        }

        // Step 3: RW approval
        if ($this->status === 'approved_rw') {
            $completedSteps++;
        }

        // Step 4: PDF generation
        if ($this->file_pdf && Storage::disk('public')->exists($this->file_pdf)) {
            $completedSteps++;
        }

        return round(($completedSteps / $totalSteps) * 100);
    }

    /**
     * Get detailed workflow status
     */
    public function getDetailedWorkflowStatusAttribute()
    {
        $workflow = [
            'submission' => [
                'completed' => true,
                'timestamp' => $this->created_at,
                'description' => 'Permohonan diajukan',
                'icon' => 'fa-file-upload',
                'color' => 'success'
            ],
            'rt_approval' => [
                'completed' => in_array($this->status, ['approved_rt', 'approved_rw']),
                'timestamp' => $this->approved_rt_at,
                'description' => $this->status === 'rejected_rt' ? 'Ditolak oleh RT' : 'Persetujuan RT',
                'icon' => $this->status === 'rejected_rt' ? 'fa-times' : 'fa-check',
                'color' => $this->status === 'rejected_rt' ? 'danger' : ($this->status === 'approved_rt' || $this->status === 'approved_rw' ? 'success' : 'warning'),
                'notes' => $this->catatan_rt,
                'approver' => $this->approverRT ? $this->approverRT->name : null
            ],
            'rw_approval' => [
                'completed' => $this->status === 'approved_rw',
                'timestamp' => $this->approved_rw_at,
                'description' => $this->status === 'rejected_rw' ? 'Ditolak oleh RW' : 'Persetujuan RW',
                'icon' => $this->status === 'rejected_rw' ? 'fa-times' : 'fa-check-double',
                'color' => $this->status === 'rejected_rw' ? 'danger' : ($this->status === 'approved_rw' ? 'success' : 'secondary'),
                'notes' => $this->catatan_rw,
                'approver' => $this->approverRW ? $this->approverRW->name : null
            ],
            'completion' => [
                'completed' => $this->status === 'approved_rw' && $this->file_pdf,
                'timestamp' => $this->status === 'approved_rw' ? $this->approved_rw_at : null,
                'description' => 'Dokumen siap diunduh',
                'icon' => 'fa-download',
                'color' => $this->status === 'approved_rw' && $this->file_pdf ? 'success' : 'secondary'
            ]
        ];

        return $workflow;
    }

    /**
     * Get tempat lahir dan tanggal lahir combined
     */
    public function getTempatTanggalLahirAttribute()
    {
        return $this->tempat_lahir . ', ' . $this->formatted_tanggal_lahir;
    }

    /**
     * Get full name with title if any
     */
    public function getFullNameAttribute()
    {
        return $this->nama_lengkap;
    }

    /**
     * Get signature count
     */
    public function getSignatureCountAttribute()
    {
        $count = 0;

        if ($this->hasPemohonSignature()) $count++;
        if ($this->hasPemilikSignature()) $count++;
        if ($this->hasRTSignature()) $count++;
        if ($this->hasRWSignature()) $count++;

        return $count;
    }

    /**
     * Check if all required signatures are present
     */
    public function hasAllRequiredSignatures()
    {
        // Minimal requirement: pemohon signature
        return $this->hasPemohonSignature();
    }

    /**
     * Check if document is ready for final approval
     */
    public function isReadyForFinalApproval()
    {
        return $this->status === 'approved_rt' &&
               $this->hasPemohonSignature() &&
               $this->hasRTSignature();
    }

    /**
     * Get document progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        switch ($this->status) {
            case 'pending_rt':
                return 25;
            case 'approved_rt':
                return 50;
            case 'pending_rw':
                return 75;
            case 'approved_rw':
                return 100;
            case 'rejected_rt':
            case 'rejected_rw':
                return 0;
            default:
                return 0;
        }
    }

    /**
     * Clean up associated files when deleting
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($suratPengantar) {
            // Delete signature files
            if ($suratPengantar->ttd_pemohon && Storage::disk('public')->exists($suratPengantar->ttd_pemohon)) {
                Storage::disk('public')->delete($suratPengantar->ttd_pemohon);
            }

            if ($suratPengantar->ttd_pemilik && Storage::disk('public')->exists($suratPengantar->ttd_pemilik)) {
                Storage::disk('public')->delete($suratPengantar->ttd_pemilik);
            }

            // Note: We don't delete RT/RW signatures and stamps as they are from Spesimen table

            // Delete PDF file
            if ($suratPengantar->file_pdf && Storage::disk('public')->exists($suratPengantar->file_pdf)) {
                Storage::disk('public')->delete($suratPengantar->file_pdf);
            }
        });
    }
}
