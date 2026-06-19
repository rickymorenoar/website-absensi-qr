<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['qr_code_content' => 'required|string']);

        // 1. Validasi QR
        if ($request->qr_code_content !== 'GERBANG_UTAMA_SEKOLAH') {
            return response()->json(['message' => 'QR Code tidak valid!'], 400);
        }

        $user = Auth::user();

        // 2. Validasi Kelas
        if (!$user->class_id) {
            return response()->json(['message' => 'Akun belum terdaftar di kelas.'], 403);
        }

        // 3. Cek apakah sudah absen hari ini
        if (Attendance::where('user_id', $user->id)->whereDate('created_at', today())->exists()) {
            return response()->json(['message' => 'Kamu sudah absen hari ini!'], 409);
        }

        // 4. Simpan ke database
        Attendance::create([
            'user_id'   => $user->id,
            'class_id'  => $user->class_id,
            'status'    => 'hadir',
            'scan_time' => now()->format('H:i') // Simpan format jam:menit
        ]);

        return response()->json(['message' => 'Absen berhasil! Selamat belajar.'], 200);
    }
}