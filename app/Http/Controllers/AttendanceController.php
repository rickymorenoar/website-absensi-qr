<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data QR
        $request->validate([
            'qr_code_id' => 'required',
        ]);

        // Simpan ke database
        Attendance::create([
            'user_id' => auth()->id() ?? 1, 
            'qr_code_id' => $request->qr_code_id,
            'scanned_at' => now(),
        ]);

        return response()->json(['message' => 'Absensi berhasil tersimpan!']);
    }
}