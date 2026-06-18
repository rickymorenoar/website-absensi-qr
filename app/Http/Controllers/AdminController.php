<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    // Kelola Data Siswa
    public function index()
    {
        return Inertia::render('Admin/ManageStudents', [
            // Manggil 'kelas' (bukan 'class') sesuai perubahan nama relasi di model User
            'students' => User::where('role', 'siswa')->with('kelas')->orderBy('name', 'asc')->get(),
            'classes' => ClassModel::orderBy('name', 'asc')->get()
        ]);
    }

    // Pendaftaran Siswa Satuan
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'class_id' => 'required',
            'no_absen' => 'required|numeric',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'siswa', 
            'class_id' => $request->class_id,
            'no_absen' => $request->no_absen,
            'password' => Hash::make('siswa123'), // Password default siswa
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    // Import Massal Siswa via CSV
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file_excel')->getRealPath(), 'r');
        fgetcsv($file); // Skip baris paling atas (header)

        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            $cekUser = User::where('email', $row[1])->exists();
            if (!$cekUser) {
                User::create([
                    'name'     => $row[0], 
                    'email'    => $row[1], 
                    'role'     => 'siswa', 
                    'class_id' => $row[2], // Mengisi ID Kelas
                    'no_absen' => $row[3], // Mengisi Nomor Absen
                    'password' => Hash::make('siswa123'),
                ]);
            }
        }

        fclose($file);
        return back()->with('success', 'Seluruh data siswa berhasil di-import!');
    }

    // ==========================================
    // Kelola Data Guru
    // ==========================================
    public function indexGuru()
    {
        return Inertia::render('Admin/ManageTeachers', [
            'teachers' => User::where('role', 'guru')->orderBy('name', 'asc')->get()
        ]);
    }

    public function storeGuru(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'guru', 
            'password' => Hash::make('guru123'), // Password default guru
        ]);

        return back()->with('success', 'Akun Guru berhasil didaftarkan!');
    }
}