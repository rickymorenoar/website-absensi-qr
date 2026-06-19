<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 1. Halaman Awal
Route::get('/', function () {
    return redirect('/login'); 
});

// 2. Semua yang butuh Login
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // ====================================================================
    // AREA ADMIN (Wajib Role: admin)
    // ====================================================================
    Route::middleware(['role:admin'])->group(function () {
        
        // Halaman Cetak QR
        Route::get('/cetak-qr', function () {
            return Inertia::render('CetakQr');
        })->name('cetak.qr');

        // Kelola Siswa 
        Route::get('/admin/siswa', [AdminController::class, 'index'])->name('admin.siswa.index');
        Route::post('/admin/siswa', [AdminController::class, 'store'])->name('admin.siswa.store');
        Route::post('/admin/siswa/import', [AdminController::class, 'importExcel'])->name('admin.siswa.import');

        // Kelola Guru 
        Route::get('/admin/guru', [AdminController::class, 'indexGuru'])->name('admin.guru.index');
        Route::post('/admin/guru', [AdminController::class, 'storeGuru'])->name('admin.guru.store');
    });

    // ====================================================================
    // AREA GURU & ADMIN
    // ====================================================================
    Route::middleware(['role:guru,admin'])->group(function () {
        Route::get('/dashboard-guru', [ClassController::class, 'index'])->name('dashboard.guru');
        Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
        
        // Rute Export Excel (Ditambah sesuai request)
        Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
        
        Route::get('/export-excel/{class_id}', [ClassController::class, 'exportExcel'])->name('classes.export');
        Route::get('/master-qr', [ClassController::class, 'showMasterQr'])->name('qr.master');
    });

    // ====================================================================
    // AREA SISWA (Bisa diakses juga oleh Admin & Guru)
    // ====================================================================
    Route::middleware(['role:siswa,admin,guru'])->group(function () {
        Route::get('/scanner', fn () => Inertia::render('Scanner'))->name('scanner');
        Route::post('/absen', [AttendanceController::class, 'store'])->name('absen.store');
        
        Route::get('/tes-absen/{class_id}', function ($class_id) {
            \App\Models\Attendance::create([
                'user_id' => auth()->id(), 
                'class_id' => $class_id,
                'status' => 'hadir'
            ]);
            return "Absen berhasil untuk kelas ID: " . $class_id;
        });
    });

    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';