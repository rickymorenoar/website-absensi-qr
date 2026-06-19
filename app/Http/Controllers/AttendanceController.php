<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['qr_code_content' => 'required|string']);

        if ($request->qr_code_content !== 'GERBANG_UTAMA_SEKOLAH') {
            return response()->json(['message' => 'QR Code tidak valid!'], 400);
        }

        $user = Auth::user();

        if (!$user->class_id) {
            return response()->json(['message' => 'Akun belum terdaftar di kelas.'], 403);
        }

        if (Attendance::where('user_id', $user->id)->whereDate('created_at', today())->exists()) {
            return response()->json(['message' => 'Kamu sudah absen hari ini!'], 409);
        }

        $jamSekarang = now();
        $jamBatas = '07:00:00';
        $status = ($jamSekarang->format('H:i:s') > $jamBatas) ? 'Terlambat' : 'Hadir';

        try {
            DB::transaction(function () use ($user, $status, $jamSekarang) {
                Attendance::create([
                    'user_id'   => $user->id,
                    'class_id'  => $user->class_id,
                    'status'    => $status,
                    'scan_time' => $jamSekarang->format('H:i')
                ]);
            });

            return response()->json([
                'message' => 'Absen berhasil! Status: ' . $status,
                'status'  => $status
            ], 200);

        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan absensi, coba lagi.'], 500);
        }
    }

    public function export(Request $request)
    {
        // Mengambil class_id dari query string (?class_id=1)
        $classId = $request->query('class_id');

        // Validasi agar classId tidak kosong
        if (!$classId) {
            return back()->with('error', 'Pilih kelas terlebih dahulu.');
        }

        try {
            // Mengirimkan $classId ke dalam konstruktor AttendanceExport
            return Excel::download(
                new AttendanceExport($classId), 
                'Rekap_Absensi_Kelas_' . $classId . '_' . now()->format('Y-m-d') . '.xlsx'
            );
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mendownload file Excel: ' . $e->getMessage());
        }
    }
}