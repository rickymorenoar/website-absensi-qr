<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
    Monitor, QrCode, FileSpreadsheet, 
    RefreshCcw, Lightbulb, Users, UserCog, ScanLine 
} from 'lucide-vue-next';

// Mengambil data user yang sedang login dari Inertia props
const page = usePage();
const user = computed(() => page.props.auth.user);

// Helper untuk format tanggal
const today = new Date().toLocaleDateString('id-ID', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
});
</script>

<template>
    <Head title="Dashboard Utama" />

    <SidebarLayout>
        <div class="space-y-6 max-w-5xl mx-auto">
            
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-lg font-semibold text-slate-800 mb-1">
                        Selamat datang, {{ user?.name }}!
                    </h1>
                    <p class="text-sm text-slate-500">
                        Sistem berjalan dengan hak akses: 
                        <span class="font-semibold text-indigo-600 uppercase text-xs bg-indigo-50 px-1.5 py-0.5 rounded">{{ user?.role }}</span>.
                    </p>
                </div>
                <div class="text-xs font-medium text-slate-500 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg flex items-center gap-2">
                    <span>📅</span> {{ today }}
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-5">
                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Sistem Gerbang Sekolah</span>
                    <h2 class="text-base font-semibold text-slate-800 mt-1">Alur Kerja Absensi QR</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-if="user?.role === 'siswa'" class="p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                        <ScanLine class="text-indigo-600 mb-2" :size="20" />
                        <span class="text-sm font-semibold text-slate-700 block mb-1">Scan Kehadiran</span>
                        <p class="text-[12px] text-slate-600 leading-relaxed">Arahkan kamera smartphone ke QR Code di gerbang untuk presensi.</p>
                    </div>

                    <template v-if="user?.role === 'guru' || user?.role === 'admin'">
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg">
                            <QrCode class="text-indigo-600 mb-2" :size="20" />
                            <span class="text-sm font-semibold text-slate-700 block mb-1">Siapkan QR</span>
                            <p class="text-[12px] text-slate-500 leading-relaxed">Cetak QR Code dan tempelkan di area kamera gerbang utama.</p>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg">
                            <Monitor class="text-indigo-600 mb-2" :size="20" />
                            <span class="text-sm font-semibold text-slate-700 block mb-1">Pantau Kelas</span>
                            <p class="text-[12px] text-slate-500 leading-relaxed">Pilih kelas di menu monitoring untuk melihat data siswa.</p>
                        </div>
                    </template>

                    <template v-if="user?.role === 'admin'">
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg">
                            <FileSpreadsheet class="text-indigo-600 mb-2" :size="20" />
                            <span class="text-sm font-semibold text-slate-700 block mb-1">Rekap Data</span>
                            <p class="text-[12px] text-slate-500 leading-relaxed">Unduh laporan kehadiran siswa ke format Excel untuk TU.</p>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg">
                            <RefreshCcw class="text-indigo-600 mb-2" :size="20" />
                            <span class="text-sm font-semibold text-slate-700 block mb-1">Auto Reset</span>
                            <p class="text-[12px] text-slate-500 leading-relaxed">Status kehadiran di-reset otomatis setiap pukul 00:00.</p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                        <Lightbulb class="text-amber-500" :size="18" /> Panduan Akses
                    </h2>
                    
                    <div v-if="user?.role === 'admin'">
                        <p class="text-[13px] text-slate-600">Tugas Admin:</p>
                        <ul class="mt-2 space-y-1 text-[12px] text-slate-500">
                            <li>• Kelola data siswa & guru.</li>
                            <li>• Monitoring sistem & rekapitulasi data.</li>
                        </ul>
                    </div>
                    <div v-else-if="user?.role === 'guru'">
                        <p class="text-[13px] text-slate-600">Tugas Guru:</p>
                        <p class="mt-2 text-[12px] text-slate-500">Pantau jam kedatangan siswa di menu <strong>Monitoring</strong>.</p>
                    </div>
                    <div v-else>
                        <p class="text-[13px] text-slate-600">Instruksi Siswa:</p>
                        <p class="mt-2 text-[12px] text-slate-500">Gunakan menu <strong>Scan QR</strong> di sidebar untuk presensi.</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                        <span>📌</span> Jalan Pintas
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <Link v-if="user?.role === 'admin'" href="/admin/siswa" class="px-4 py-3 bg-slate-50 border rounded-lg text-center flex flex-col items-center hover:bg-indigo-50 transition">
                            <Users :size="20" class="text-slate-400" />
                            <span class="text-[11px] text-slate-600 mt-1">Data Siswa</span>
                        </Link>
                        <Link v-if="user?.role === 'admin'" href="/admin/guru" class="px-4 py-3 bg-slate-50 border rounded-lg text-center flex flex-col items-center hover:bg-indigo-50 transition">
                            <UserCog :size="20" class="text-slate-400" />
                            <span class="text-[11px] text-slate-600 mt-1">Data Guru</span>
                        </Link>
                        <Link v-if="user?.role === 'admin' || user?.role === 'guru'" href="/dashboard-guru" class="col-span-2 px-4 py-3 bg-slate-50 border rounded-lg text-center flex flex-col items-center hover:bg-indigo-50 transition">
                            <Monitor :size="20" class="text-slate-400" />
                            <span class="text-[11px] text-slate-600 mt-1">Monitoring Kelas</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>