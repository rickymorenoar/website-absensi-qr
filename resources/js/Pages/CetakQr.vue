<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
  Printer
} from 'lucide-vue-next';

const qrData = "GATE-ABSEN-UTAMA-01";
const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${qrData}`;

const printDoc = () => {
    window.print();
};
</script>

<template>
    <Head title="Cetak Master QR" />
    
    <div class="min-h-screen bg-slate-100 flex items-center justify-center p-4 font-sans">
        
        <div class="absolute top-6 left-6 print:hidden">
            <Link href="/dashboard" class="px-4 py-2 bg-white text-slate-700 rounded-lg shadow-sm border border-slate-200 text-sm hover:bg-slate-50 transition">
                ← Kembali
            </Link>
        </div>

        <div class="bg-white w-full max-w-sm p-6 shadow-xl border border-slate-200 rounded-3xl text-center print:shadow-none print:border-none">
            
            <!-- Logo Konsisten -->
            <img src="/spenturan-logo.ico" alt="Logo" class="w-16 h-16 mx-auto mb-3 object-contain">
            
            <h1 class="text-2xl font-bold text-slate-900 mb-0.5">Scan Kehadiran</h1>
            <p class="text-xs text-slate-500 uppercase tracking-widest mb-5">Gate Attendance System</p>
            
            <div class="bg-white p-3 rounded-2xl border-4 border-slate-100 inline-block mb-5">
                <img :src="qrImageUrl" alt="QR Code" class="w-40 h-40">
            </div>

            <div class="text-left text-[11px] text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="font-bold text-slate-800 mb-2 uppercase tracking-wide">Cara Presensi:</p>
                <ol class="list-decimal pl-4 space-y-1">
                    <li>Buka aplikasi absensi di HP.</li>
                    <li>Pilih menu <strong>Kamera Scanner</strong>.</li>
                    <li>Arahkan kamera ke QR Code ini.</li>
                    <li>Tunggu notifikasi berhasil.</li>
                </ol>
            </div>
        </div>

        <button 
    @click="printDoc" 
    class="fixed bottom-6 right-6 print:hidden bg-slate-900 text-white px-6 py-3 rounded-full shadow-lg font-bold text-sm hover:bg-slate-800 transition-all hover:scale-105 active:scale-95 flex items-center gap-2"
>
    <Printer :size="18" /> 
    <span>Cetak QR Sekarang</span>
</button>
    </div>
</template>