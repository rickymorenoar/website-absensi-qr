<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Menerima data kiriman $classes dari ClassController.php
defineProps({
    classes: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <Head title="Monitoring Kelas" />

    <SidebarLayout>
        <div class="space-y-6">
            
            <!-- Header Page -->
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Kelola Kelas (Monitoring)</h1>
                <p class="text-sm text-slate-500">Pantau kehadiran siswa secara real-time khusus hari ini.</p>
            </div>

            <!-- Grid Card Daftar Kelas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="kelas in classes" :key="kelas.id" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between space-y-4">
                    
                    <!-- Detail Kelas Atas -->
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">{{ kelas.name }}</h3>
                            <p class="text-xs text-slate-400">Total Siswa: {{ kelas.students ? kelas.students.length : 0 }}</p>
                        </div>
                        
                        <!-- Tombol ke Halaman Cetak QR -->
                        <Link :href="route('qr.show', kelas.id)" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl text-xs font-semibold transition">
                            🖨️ Cetak QR
                        </Link>
                    </div>

                    <!-- Live Count Absen Hari Ini -->
                    <div class="bg-slate-50 rounded-xl p-3 flex justify-between text-center text-xs">
                        <div class="w-1/2">
                            <p class="text-slate-400 font-medium mb-0.5">Hadir</p>
                            <p class="text-lg font-bold text-emerald-600">
                                {{ kelas.students ? kelas.students.filter(s => s.attendances && s.attendances.length > 0).length : 0 }}
                            </p>
                        </div>
                        <div class="border-r border-slate-200"></div>
                        <div class="w-1/2">
                            <p class="text-slate-400 font-medium mb-0.5">Belum Hadir</p>
                            <p class="text-lg font-bold text-amber-600">
                                {{ kelas.students ? kelas.students.filter(s => !s.attendances || s.attendances.length === 0).length : 0 }}
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Download Rekap -->
                    <div class="pt-2">
                        <a :href="route('classes.export', kelas.id)" class="w-full block text-center py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-medium transition shadow-sm">
                            📥 Download Excel (.CSV)
                        </a>
                    </div>

                </div>
            </div>

            <!-- State Kalo Data Kelas Masih Kosong -->
            <div v-if="classes.length === 0" class="text-center py-12 bg-white border border-slate-200 rounded-2xl">
                <p class="text-slate-400 text-sm">Belum ada data kelas yang terdaftar di database.</p>
            </div>

        </div>
    </SidebarLayout>
</template>