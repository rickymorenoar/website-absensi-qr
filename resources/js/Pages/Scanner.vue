<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    qr_code_content: '',
});

const statusMessage = ref('Menunggu perangkat QR didekatkan...');
const isSuccess = ref(false);

// FUNGSI SIMULASI: Ketika kamera HP mendeteksi QR Gerbang
const handleScanSukses = () => {
    // Kita isi value sesuai QR Code Master Sekolah
    form.qr_code_content = 'GERBANG_UTAMA_SEKOLAH';
    statusMessage.value = 'Memproses absensi kamu, tunggu sebentar...';

    // Kirim data ke route /absen via Inertia POST
    form.post(route('absen.store'), {
        onSuccess: () => {
            isSuccess.value = true;
            statusMessage.value = '✓ Absen berhasil dicatat! Gerbang Terbuka.';
        },
        onError: (errors) => {
            isSuccess.value = false;
            statusMessage.value = '❌ Gagal: ' + (errors.message || 'Terjadi kesalahan sistem.');
        }
    });
};
</script>

<template>
    <Head title="Kamera Scanner Gerbang" />

    <SidebarLayout>
        <div class="max-w-2xl mx-auto text-center">
            <div class="mb-6">
                <span class="px-3 py-1 text-[10px] font-bold bg-indigo-50 text-indigo-600 rounded-full border border-indigo-200 uppercase tracking-widest">Live Scanner Student</span>
                <h1 class="text-2xl font-black text-slate-900 mt-2">Pemindai Absensi Siswa</h1>
                <p class="text-xs text-slate-500">Arahkan kamera HP kamu ke QR Code resmi yang tertempel di tiang gerbang sekolah.</p>
            </div>

            <div class="relative bg-slate-900 aspect-video rounded-2xl overflow-hidden shadow-2xl border-4 border-white max-w-lg mx-auto flex items-center justify-center">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-transparent to-slate-900/60 z-10"></div>
                
                <div class="relative w-52 h-52 border-2 border-indigo-400 rounded-xl z-20 flex items-center justify-center shadow-[0_0_30px_rgba(99,102,241,0.3)]">
                    <div class="absolute -top-1 -left-1 w-5 h-5 border-t-4 border-l-4 border-indigo-500 rounded-tl-sm"></div>
                    <div class="absolute -top-1 -right-1 w-5 h-5 border-t-4 border-r-4 border-indigo-500 rounded-tr-sm"></div>
                    <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-4 border-l-4 border-indigo-500 rounded-bl-sm"></div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-4 border-r-4 border-indigo-500 rounded-br-sm"></div>
                    <div class="w-full h-0.5 bg-indigo-400 absolute top-0 shadow-[0_0_10px_#6366f1] animate-bounce"></div>
                    
                    <span class="text-[10px] text-indigo-200 tracking-wider font-mono opacity-60">SCANNING MODE</span>
                </div>

                <button @click="handleScanSukses" class="absolute bottom-4 z-30 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold px-4 py-1.5 rounded-full shadow-lg transition">
                    [Klik Sini Buat Simulasi Berhasil Scan QR]
                </button>
            </div>

            <div class="mt-6 max-w-lg mx-auto bg-white border border-slate-200 rounded-xl p-4 text-left shadow-sm">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status Pemindaian</h4>
                <div class="text-xs font-mono p-2.5 rounded-lg" :class="isSuccess ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-600'">
                    {{ statusMessage }}
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>