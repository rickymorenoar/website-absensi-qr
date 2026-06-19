<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $classId;

    // Konstruktor menerima $classId dari Controller
    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    public function collection()
    {
        return Attendance::with(['user:id,name'])
            ->where('class_id', $this->classId)
            ->whereDate('created_at', today())
            ->get()
            ->map(function ($item) {
                return [
                    $item->user->name ?? 'N/A',
                    $item->status,
                    $item->scan_time,
                    $item->created_at->format('d-m-Y'),
                ];
            });
    }

    public function headings(): array
    {
        return ["Nama Siswa", "Status", "Jam Scan", "Tanggal"];
    }
}