<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClassController extends Controller
{
    /**
     * Generate QR Gerbang
     */
    public function showMasterQr()
    {
        $qrString = 'GERBANG_UTAMA_SEKOLAH'; 
        $qr = QrCode::size(300)->generate($qrString);
        
        return Inertia::render('QrGenerator', ['qr' => $qr]);
    }

    /**
     * Menampilkan daftar semua kelas beserta siswanya sekaligus (Optimized)
     */
    public function index()
    {
        $hariIni = Carbon::today()->toDateString();

        // Mengambil semua kelas, beserta siswa, dan absensi hari ini dalam SATU query
        $classes = ClassModel::with(['students' => function($query) use ($hariIni) {
            $query->where('role', 'siswa')
                  ->with(['attendances' => function($q) use ($hariIni) {
                      $q->whereDate('created_at', $hariIni);
                  }]);
        }])->get();

        return Inertia::render('DashboardGuru', [
            'classes' => $classes
        ]);
    }

    /**
     * Simpan kelas baru
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        ClassModel::create(['name' => $request->name]);
        return back()->with('success', 'Kelas berhasil ditambah!');
    }

    /**
     * Export Excel/CSV
     */
    public function exportExcel($class_id)
    {
        $class = ClassModel::findOrFail($class_id);
        $hariIni = Carbon::today()->toDateString();
        
        $students = User::where('role', 'siswa')
            ->where('class_id', $class_id)
            ->with(['attendances' => function($query) use ($hariIni) {
                $query->whereDate('created_at', $hariIni);
            }])
            ->orderBy('name', 'asc')
            ->get();

        $fileName = 'Rekap_Absen_' . $class->name . '_' . $hariIni . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function() use($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama Siswa', 'Waktu Hadir', 'Status']);

            foreach ($students as $index => $student) {
                $attendance = $student->attendances->first();
                fputcsv($file, [
                    $index + 1,
                    $student->name,
                    $attendance ? Carbon::parse($attendance->created_at)->format('H:i:s') : '-',
                    $attendance ? 'Hadir' : 'Belum Hadir'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}