<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class ClassController extends Controller
{
    public function index()
    {
        // 1. Ambil tanggal hari ini secara otomatis
        $hariIni = Carbon::today()->toDateString();

        // 2. Ambil SEMUA data kelas beserta data siswa & absensi mereka KHUSUS HARI INI
        $classes = ClassModel::with(['students.attendances' => function($query) use ($hariIni) {
            $query->whereDate('created_at', $hariIni);
        }])->latest()->get();

        return Inertia::render('DashboardGuru', [
            'classes' => $classes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        ClassModel::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Kelas berhasil ditambah!');
    }

    // Menggantikan fungsi showQr lama agar menjadi Master QR tunggal gerbang sekolah
    public function showMasterQr()
    {
        return Inertia::render('QrGenerator');
    }

    public function exportExcel($class_id)
    {
        // Cari data kelas berdasarkan ID
        $class = ClassModel::findOrFail($class_id); 
        $hariIni = Carbon::today()->toDateString();
        
        // Ambil data siswa beserta absen hari ini
        $students = $class->students()->with(['attendances' => function($query) use ($hariIni) {
            $query->whereDate('created_at', $hariIni);
        }])->orderBy('no_absen', 'asc')->get();

        $fileName = 'Rekap_Absen_Kelas_' . $class->name . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No Absen', 'Nama Siswa', 'Jam Scan QR', 'Status Kehadiran'];

        $callback = function() use($students, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($students as $student) {
                // Ambil data absensi hari ini jika ada
                $attendance = $student->attendances->first();

                // Logika: jika ada absensi tampilkan jam format HH:MM, jika tidak tampilkan '--:--'
                $jamScan = $attendance ? Carbon::parse($attendance->created_at)->format('H:i') : '--:--';
                
                // Logika Status Kehadiran
                $status = $attendance ? 'Hadir' : 'Belum Hadir';

                fputcsv($file, [
                    $student->no_absen,
                    $student->name,
                    $jamScan,
                    $status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}