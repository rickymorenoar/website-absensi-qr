<?php

use App\Http\Controllers\QrController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Halaman utama langsung redirect ke login
Route::get('/', function () {
    return redirect('/login'); 
});

// ========================================================
// GERBANG UTAMA: Wajib Login Baru Bisa Akses Semua Ini
// ========================================================
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard Utama (Semua role login langsung mendarat di sini)
    Route::get('/dashboard', function () {
        // Tanpa pilih kasih, semua role langsung melihat halaman penjelasan cara kerja absensi
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // 2. AREA ADMIN (Kelola Siswa & Kelola Guru) - AMAN DI DALAM AUTH
    Route::middleware(['role:admin'])->group(function () {
        // Kelola Siswa
        Route::get('/admin/siswa', [AdminController::class, 'index'])->name('admin.siswa.index');
        Route::post('/admin/siswa', [AdminController::class, 'store'])->name('admin.siswa.store');
        Route::post('/admin/siswa/import', [AdminController::class, 'importExcel'])->name('admin.siswa.import');

        // Kelola Guru
        Route::get('/admin/guru', [AdminController::class, 'indexGuru'])->name('admin.guru.index');
        Route::post('/admin/guru', [AdminController::class, 'storeGuru'])->name('admin.guru.store');
    });

    // 3. AREA BERSAMA: GURU & ADMIN (Bisa lihat daftar kelas, cetak QR, dan download Excel)
    Route::middleware(['role:guru,admin'])->group(function () {
        Route::get('/dashboard-guru', [ClassController::class, 'index'])->name('dashboard.guru');
        Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
        Route::get('/qr/{id}', [ClassController::class, 'showQr'])->name('qr.show'); 
        
        // Route untuk download rekap Excel per kelas
        Route::get('/export-excel/{class_id}', [ClassController::class, 'exportExcel'])->name('classes.export');
    });

    // 4. AREA SISWA: Hanya bisa akses Kamera Scanner & Kirim Data Absen
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/scanner', fn () => Inertia::render('Scanner'))->name('scanner');
        Route::post('/absen', [AttendanceController::class, 'store'])->name('absen.store');
        
        // Fitur tes absen lewat URL
        Route::get('/tes-absen/{class_id}', function ($class_id) {
            \App\Models\Attendance::create([
                'user_id' => auth()->id(), 
                'class_id' => $class_id,
                'status' => 'hadir'
            ]);
            return "Absen berhasil untuk kelas ID: " . $class_id;
        });
    });

    // 5. Fitur Edit Profil User (Berlaku untuk semua Role setelah login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';