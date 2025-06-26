<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class Puntadewa extends Model
{
    use HasFactory;
    use Loggable;

    protected $table = 'puntadewa';

    protected $fillable = [
        'nomor_surat',
        'user_id',
        'nama_pemohon',
        'nik',
        'alamat_asal',
        'alasan_tinggal',
        'rt',
        'rw',
        'nama_perusahaan',
        'alamat_perusahaan',
        'nama_sekolah',
        'alamat_sekolah',
        'nama_rumah_sakit',
        'alamat_rumah_sakit',
        'alasan_lainnya',
        'nama_penjamin',
        'nik_penjamin',
        'alamat_penjamin',
        'no_telp_penjamin',
        'file_kk_asal',
        'ttd_pemohon',
        'ttd_pemilik_kost',
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
        'latitude',
        'longitude',
        'alamat_lokasi',
        'status',
        'file_pdf',
    ];

    protected $casts = [
        'approved_rt_at' => 'datetime',
        'approved_rw_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
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

        return "PUN/{$newNumber}/{$bulan}/{$tahun}";
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
     * Get download count
     */
    public function getDownloadCountAttribute()
    {
        // Placeholder untuk download count - bisa ditambahkan tracking table nanti
        return 0;
    }

    /**
     * Get PDF file name for download
     */
    public function getPdfDownloadNameAttribute()
    {
        return 'PUNTADEWA_' . $this->safe_nomor_surat . '.pdf';
    }

    /**
     * Get full PDF file path
     */
    public function getFullPdfPathAttribute()
    {
        return $this->file_pdf ? storage_path('app/public/' . $this->file_pdf) : null;
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
     * Get RT and RW display
     */
    public function getRtRwDisplayAttribute()
    {
        return "RT {$this->rt} / RW {$this->rw}";
    }
}
