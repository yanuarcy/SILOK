<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait Loggable
{
    /**
     * Boot the trait
     */
    protected static function bootLoggable()
    {
        // Log when model is created
        static::created(function ($model) {
            if (auth()->check()) {
                $description = static::getActivityDescription('create', $model);
                $properties = [
                    'attributes' => static::filterSensitiveData($model->getAttributes()),
                    'model_type' => get_class($model),
                    'created_by' => auth()->user()->name . ' (' . auth()->user()->role . ')'
                ];

                ActivityLog::log('create', $description, $model, $properties);
            }
        });

        // Log when model is updated
        static::updated(function ($model) {
            if (auth()->check()) {
                $changes = $model->getChanges();
                $original = $model->getOriginal();

                // Remove timestamps from changes if not significant
                unset($changes['updated_at']);

                // PERBAIKAN: Skip jika hanya remember_token yang berubah (biasanya saat login)
                if (count($changes) === 1 && isset($changes['remember_token'])) {
                    // Ini kemungkinan aktivitas login, skip logging disini
                    // Karena login akan di-log terpisah oleh LoginController
                    return;
                }

                if (!empty($changes)) {
                    // PERBAIKAN: Deteksi jenis aktivitas dengan lebih baik
                    $activityInfo = static::detectActivityType($model, $changes, $original);

                    $description = $activityInfo['description'];
                    $action = $activityInfo['action'];
                    $properties = [
                        'old' => static::filterSensitiveData(array_intersect_key($original, $changes)),
                        'new' => static::filterSensitiveData($changes),
                        'model_type' => get_class($model),
                        'updated_by' => auth()->user()->name . ' (' . auth()->user()->role . ')',
                        'activity_type' => $activityInfo['type']
                    ];

                    // Tambah properties khusus untuk approval
                    if (in_array($action, ['approve', 'reject'])) {
                        $properties['level'] = $activityInfo['level'] ?? '';
                        $properties['catatan'] = $activityInfo['catatan'] ?? null;
                    }

                    ActivityLog::log($action, $description, $model, $properties);
                }
            }
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            if (auth()->check()) {
                $description = static::getActivityDescription('delete', $model);
                $properties = [
                    'attributes' => static::filterSensitiveData($model->getOriginal()),
                    'model_type' => get_class($model),
                    'deleted_by' => auth()->user()->name . ' (' . auth()->user()->role . ')'
                ];

                ActivityLog::log('delete', $description, $model, $properties);
            }
        });
    }

    /**
     * Filter sensitive data from array - BARU
     */
    protected static function filterSensitiveData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $sensitiveFields = [
            'password',
            'remember_token',
            'email_verified_at',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'api_token',
            'access_token',
            'refresh_token'
        ];

        $filtered = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $sensitiveFields)) {
                // Ganti dengan placeholder yang aman
                if ($key === 'remember_token') {
                    $filtered[$key] = $value ? '[TOKEN_EXISTS]' : '[NO_TOKEN]';
                } elseif ($key === 'password') {
                    $filtered[$key] = '[PASSWORD_HASH]';
                } elseif ($key === 'email_verified_at') {
                    $filtered[$key] = $value ? '[VERIFIED]' : '[NOT_VERIFIED]';
                } else {
                    $filtered[$key] = $value ? '[HIDDEN]' : '[EMPTY]';
                }
            } else {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Detect activity type based on changes - DIPERBAIKI
     */
    protected static function detectActivityType($model, $changes, $original)
    {
        $modelName = class_basename($model);

        // PERBAIKAN: Deteksi login untuk User model dengan lebih akurat
        if ($modelName === 'User') {
            // Jika hanya updated_at yang berubah (tanpa remember_token)
            if (count($changes) === 1 && isset($changes['updated_at'])) {
                return [
                    'action' => 'login',
                    'description' => "Masuk ke sistem",
                    'type' => 'login'
                ];
            }

            // Jika remember_token berubah bersama updated_at saja (login dengan remember me)
            if (count($changes) === 2 &&
                isset($changes['updated_at']) &&
                isset($changes['remember_token'])) {
                return [
                    'action' => 'login',
                    'description' => "Masuk ke sistem (dengan Remember Me)",
                    'type' => 'login_remember'
                ];
            }

            // Jika ada perubahan data profile yang signifikan
            $profileFields = ['name', 'email', 'telp', 'address', 'rt', 'rw', 'pekerjaan', 'gender', 'tanggal_lahir'];
            $hasProfileChanges = false;
            foreach ($profileFields as $field) {
                if (isset($changes[$field])) {
                    $hasProfileChanges = true;
                    break;
                }
            }

            if ($hasProfileChanges) {
                return [
                    'action' => 'profile_update',
                    'description' => "Memperbarui profile: " . ($model->name ?? 'User'),
                    'type' => 'profile_update'
                ];
            }

            // Jika hanya remember_token yang berubah (logout atau security)
            if (count($changes) === 1 && isset($changes['remember_token'])) {
                if (empty($changes['remember_token'])) {
                    return [
                        'action' => 'logout',
                        'description' => "Keluar dari sistem",
                        'type' => 'logout'
                    ];
                } else {
                    return [
                        'action' => 'security_update',
                        'description' => "Memperbarui token keamanan",
                        'type' => 'security'
                    ];
                }
            }
        }

        // Deteksi approval/rejection untuk models dengan status
        if (isset($changes['status'])) {
            $oldStatus = $original['status'] ?? '';
            $newStatus = $changes['status'];

            if (in_array($newStatus, ['approved_rt', 'approved_rw', 'approved_kelurahan'])) {
                $level = '';
                if ($newStatus === 'approved_rt') $level = 'RT';
                if ($newStatus === 'approved_rw') $level = 'RW';
                if ($newStatus === 'approved_kelurahan') $level = 'Kelurahan';

                $identifier = static::getModelIdentifier($model);
                $modelDisplayName = static::getModelDisplayName($modelName);

                return [
                    'action' => 'approve',
                    'description' => "Menyetujui {$modelDisplayName}: {$identifier}",
                    'type' => 'approval',
                    'level' => $level,
                    'catatan' => $changes['catatan_' . strtolower($level)] ?? null
                ];

            } elseif (in_array($newStatus, ['rejected_rt', 'rejected_rw', 'rejected_kelurahan'])) {
                $level = '';
                if ($newStatus === 'rejected_rt') $level = 'RT';
                if ($newStatus === 'rejected_rw') $level = 'RW';
                if ($newStatus === 'rejected_kelurahan') $level = 'Kelurahan';

                $identifier = static::getModelIdentifier($model);
                $modelDisplayName = static::getModelDisplayName($modelName);

                return [
                    'action' => 'reject',
                    'description' => "Menolak {$modelDisplayName}: {$identifier}",
                    'type' => 'rejection',
                    'level' => $level,
                    'catatan' => $changes['catatan_' . strtolower($level)] ?? null
                ];
            } elseif ($newStatus === 'auto_approved') {
                $identifier = static::getModelIdentifier($model);
                $modelDisplayName = static::getModelDisplayName($modelName);

                return [
                    'action' => 'auto_approve',
                    'description' => "Auto approval {$modelDisplayName}: {$identifier}",
                    'type' => 'auto_approval'
                ];
            }
        }

        // Default update
        $description = static::getActivityDescription('update', $model);
        return [
            'action' => 'update',
            'description' => $description,
            'type' => 'update'
        ];
    }

    /**
     * Get model identifier for descriptions
     */
    protected static function getModelIdentifier($model)
    {
        if (isset($model->nomor_surat)) {
            return $model->nomor_surat;
        } elseif (isset($model->name)) {
            return $model->name;
        } elseif (isset($model->title)) {
            return $model->title;
        } elseif (isset($model->jenis_permohonan)) {
            return $model->jenis_permohonan;
        }

        return "ID: {$model->id}";
    }

    /**
     * Get activity description based on action and model
     */
    protected static function getActivityDescription($action, $model)
    {
        $modelName = class_basename($model);
        $modelDisplayName = static::getModelDisplayName($modelName);

        $descriptions = [
            'create' => "Mengajukan {$modelDisplayName}",
            'update' => "Memperbarui {$modelDisplayName}",
            'delete' => "Menghapus {$modelDisplayName}",
        ];

        $baseDescription = $descriptions[$action] ?? "Melakukan {$action} pada {$modelDisplayName}";

        // Add specific identifier
        $identifier = static::getModelIdentifier($model);
        return $baseDescription . ": {$identifier}";
    }

    /**
     * Get human readable model name
     */
    protected static function getModelDisplayName($modelName)
    {
        $displayNames = [
            'User' => 'data pengguna',
            'UserApplication' => 'permohonan',
            'Puntadewa' => 'permohonan PUNTADEWA',
            'SuratPengantar' => 'surat pengantar',
            'Psu' => 'PSU (Permohonan Surat Umum)',
            'DataKependudukan' => 'data kependudukan',
            'Spesimen' => 'spesimen TTD/Stempel',
            'ActivityLog' => 'log aktivitas'
        ];

        return $displayNames[$modelName] ?? strtolower($modelName);
    }

    /**
     * Manually log activity for this model
     */
    public function logActivity($action, $description, $properties = null)
    {
        // Filter properties jika ada
        if ($properties && is_array($properties)) {
            $properties = static::filterSensitiveData($properties);
        }

        return ActivityLog::log($action, $description, $this, $properties);
    }

    /**
     * Log custom approval activity
     */
    public function logApproval($level, $actionType, $catatan = null)
    {
        if (!auth()->check()) {
            return null;
        }

        $modelDisplayName = static::getModelDisplayName(class_basename($this));
        $identifier = static::getModelIdentifier($this);

        if ($actionType === 'approve') {
            $description = "Menyetujui {$modelDisplayName}: {$identifier}";
        } else {
            $description = "Menolak {$modelDisplayName}: {$identifier}";
        }

        $properties = [
            'level' => $level,
            'catatan' => $catatan,
            'approved_by' => auth()->user()->name . ' (' . auth()->user()->role . ')',
            'model_type' => get_class($this),
            'activity_type' => $actionType === 'approve' ? 'approval' : 'rejection'
        ];

        return ActivityLog::log($actionType, $description, $this, $properties);
    }
}
