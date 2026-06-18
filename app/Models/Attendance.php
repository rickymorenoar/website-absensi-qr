<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi isi QR Code yang dibaca oleh kamera HP siswa
        $request->validate([
            'qr_code_content' => 'required|string'
        ]);

        // Cek apakah yang di-scan beneran QR Gerbang Sekolah asli
        if ($request->qr_code_content !== 'GERBANG_UTAMA_SEKOLAH') {
            return back()->with('error', 'QR Code tidak dikenali! Gunakan QR resmi gerbang sekolah.');
        }

        // 2. Ambil data user yang lagi login
        $user = Auth::user();

        // 3. Deteksi otomatis kelas si siswa dari database
        // (Asumsi: di tabel `users` lu ada kolom `class_id` yang menghubungkan siswa ke kelasnya)
        $class_id = $user->class_id; 

        if (!$class_id) {
            return back()->with('error', 'Akun kamu belum didaftarkan ke kelas manapun oleh admin!');
        }

        // 4. Cek apakah siswa tersebut sudah absen hari ini (biar gak bisa spam scan berkali-kali)
        $sudahAbsen = Attendance::where('user_id', $user->id)
            ->where('class_id', $class_id)
            ->whereDate('created_at', today())
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Kamu sudah melakukan absensi hari ini!');
        }

        // 5. Jika lolos semua cek ombak, masukkan data ke tabel attendances
        Attendance::create([
            'user_id'  => $user->id,
            'class_id' => $class_id,
            'status'   => 'hadir',
            'scan_time'=> now()->format('H:i:s') // Catat jam jam scan saat ini
        ]);

        return back()->with('success', 'Absen berhasil! Pintu gerbang terbuka.');
    }
}