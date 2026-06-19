<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Attendance;
use App\Models\ClassModel;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'class_id', 'no_absen'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function kelas(): BelongsTo {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function attendances(): HasMany {
        return $this->hasMany(Attendance::class, 'user_id');
    }
}