<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
    <a href="#"><img src="https://img.shields.io/badge/Status-Development-green" alt="Status"></a>
    <a href="#"><img src="https://img.shields.io/badge/Laravel-13.x-red" alt="Laravel Version"></a>
    <a href="#"><img src="https://img.shields.io/badge/Database-MySQL-blue" alt="Database"></a>
</p>

# Sistem Absensi QR

Aplikasi berbasis web untuk mengelola kehadiran siswa secara efisien menggunakan pemindaian kode QR. Sistem ini memastikan proses absensi berlangsung **real-time**, transparan, dan terintegrasi penuh dengan manajemen data sekolah.

---

## 📁 Project Structure

Aplikasi ini menggunakan arsitektur **Model-View-Controller (MVC)** Laravel dengan struktur sebagai berikut:

```text
absen-qr/
├── app/
│   ├── Http/Controllers/    # Logika Scan QR & Absensi
│   ├── Models/              # User.php, ClassModel.php, Attendance.php
├── database/
│   ├── factories/           # UserFactory.php (generate data dummy)
│   ├── migrations/          # Skema database (users, classes, attendances)
│   ├── seeders/             # DatabaseSeeder.php & ClassSeeder.php
├── resources/
│   ├── js/Pages/            # Antarmuka Vue (Inertia.js)
├── routes/
│   └── web.php              # Pengaturan rute aplikasi
└── .env                     # Konfigurasi database
```

---

## 🚀 Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan aplikasi secara lokal.

### 1. Install Dependencies

```bash
composer install
npm install
```

Aplikasi ini menggunakan [`lucide-react`](https://lucide.dev/) untuk ikon antarmuka. Install dengan:

```bash
npm install lucide-react
```

### 2. Konfigurasi Environment

Salin `.env.example` ke `.env`, lalu sesuaikan koneksi database Anda:

```env
DB_DATABASE=absensi-qr
```

### 3. Inisialisasi Database (Wajib)

> ⚠️ Perintah berikut akan **menghapus semua data lama**, menjalankan ulang migrasi tabel, dan mengisi data awal (Admin & Kelas).

```bash
php artisan key:generate
php artisan migrate:fresh --seed
composer dump-autoload
```

### 4. Jalankan Aplikasi

```bash
php artisan serve
npm run dev
```

---

## 🛠 Database & Relasi

Sistem ini menggunakan relasi antar tabel untuk menjaga konsistensi data:

| Tabel | Keterangan |
|---|---|
| `users` | Menyimpan data akun, role (`admin`/`guru`/`siswa`), serta relasi `class_id`. |
| `classes` | Menyimpan daftar master kelas (contoh: 7A – 9I). |
| `attendances` | Tabel transaksi yang mencatat `user_id`, `class_id`, `status` (Hadir/Terlambat), dan `scan_time`. |

---

## 💡 Cara Penggunaan

1. **Login** — Gunakan akun Admin yang telah terdaftar (`rickymoreno851@gmail.com`).
2. **Dashboard** — Admin/Guru dapat memantau kehadiran siswa secara real-time.
3. **Absensi Siswa** — Siswa melakukan scan QR Code yang disediakan oleh guru. Sistem akan memvalidasi waktu kedatangan secara otomatis dan menentukan status kehadiran.
4. **Monitoring** — Gunakan menu "Monitoring" untuk melihat rekapitulasi kehadiran berdasarkan kelas dan tanggal.

---

## ⚠️ Aturan Pengembang

- **Database**: Jangan menambah atau menghapus tabel secara manual melalui aplikasi manajemen database (seperti HeidiSQL/phpMyAdmin). Selalu gunakan `php artisan make:migration`.
- **Cache**: Jika terjadi perubahan kode yang tidak muncul di browser, jalankan:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 📄 License

Sistem ini merupakan perangkat lunak open-source untuk kebutuhan internal sekolah.