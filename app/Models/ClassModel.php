<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    // Ini biar Laravel tau dia harus nyari tabel 'classes' di database
    protected $table = 'classes'; 

    // Ini kolom yang boleh diisi (user_id dihapus)
    protected $fillable = ['name'];

    /**
     * Hubungan relasi satu kelas memiliki banyak siswa
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'class_id')->where('role', 'siswa')->orderBy('no_absen', 'asc');
    }
}