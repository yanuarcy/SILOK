<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'antrians'; // Sesuaikan dengan nama tabel di database

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tanggal',
        'nama',
        'no_whatsapp',
        'alamat',
        'jenis_layanan',
        'keterangan',
        'no_antrian',
        'jenis_antrian',
        'jenis_pengiriman',
        'calling_by',
        'status',
        'updated_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
        'updated_date' => 'datetime',
        'status' => 'string'
    ];
}
