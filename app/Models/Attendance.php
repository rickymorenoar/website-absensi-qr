<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    // Tentukan tabel jika nama tabel bukan 'attendances' (default Laravel pakai 'attendances')
    protected $table = 'attendances';

    // Daftar kolom yang boleh diisi melalui mass assignment
    protected $fillable = [
        'user_id', 
        'class_id', 
        'status', 
        'scan_time'
    ];

    /**
     * Relasi ke User (Satu absensi milik satu siswa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke ClassModel (Satu absensi milik satu kelas)
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}