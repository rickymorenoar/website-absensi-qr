<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

// Reset otomatis status absen siswa jadi "belum_absen" setiap jam 12 malam (00:00)
Schedule::call(function () {
    DB::table('students')->update([
        'status' => 'belum_absen',
        'scan_time' => null
    ]);
})->daily();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
