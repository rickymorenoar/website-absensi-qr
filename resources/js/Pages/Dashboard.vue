<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Dashboard Utama" />

    <SidebarLayout>
        <div class="space-y-6">
            
            <!-- Banner Selamat Datang -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold mb-1">Selamat Datang, {{ user?.name }}! 👋</h1>
                    <p class="text-blue-100 text-sm">
                        Sistem Absensi Gate QR siap digunakan. Anda masuk dengan hak akses 
                        <span class="font-bold uppercase bg-white/20 px-2 py-0.5 rounded text-xs tracking-wider">{{ user?.role }}</span>.
                    </p>
                </div>
                <div class="text-xs bg-black/20 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                    Hari Ini: {{ new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>

            <!-- Section Penjelasan Cara Kerja Absensi -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Sistem Gerbang IoT</span>
                <h2 class="text-xl font-extrabold text-slate-900 mt-1 flex items-center gap-2">
                    🚀 Alur Kerja Sistem Absensi Gate QR
                </h2>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Sistem ini mengintegrasikan pemindaian QR Code pada perangkat keras gerbang sekolah (ESP32) dengan database absensi siswa secara real-time. Prosesnya dibagi menjadi 4 bagian utama:
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                        <div class="text-indigo-600 font-bold text-base mb-1">01. Menu Kelola Kelas</div>
                        <p class="text-xs text-slate-500 leading-relaxed">Guru atau Admin dapat melihat daftar seluruh kelas. Klik pada baris kelas untuk memantau data absensi siswa hari ini secara langsung.</p>
                    </div>
                    <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                        <div class="text-indigo-600 font-bold text-base mb-1">02. Cetak Master QR</div>
                        <p class="text-xs text-slate-500 leading-relaxed">Di dalam detail kelas terdapat tombol cetak QR. Tempelkan cetakan QR tersebut di area kamera scanning gerbang sekolah.</p>
                    </div>
                    <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                        <div class="text-indigo-600 font-bold text-base mb-1">03. Rekap & Download</div>
                        <p class="text-xs text-slate-500 leading-relaxed">Gunakan tombol eksport Excel yang tersedia di setiap kelas untuk mengunduh laporan kehadiran harian guna rekapitulasi tata usaha.</p>
                    </div>
                    <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                        <div class="text-indigo-600 font-bold text-base mb-1">04. Sistem Auto-Reset</div>
                        <p class="text-xs text-slate-500 leading-relaxed">Database dirancang mandiri. Tepat pada pukul 00:00 malam, status seluruh siswa otomatis di-reset kembali menjadi "Belum Absen".</p>
                    </div>
                </div>
            </div>

            <!-- Panduan Menu & Shortcut -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                    <h2 class="text-base font-bold text-gray-800">💡 Panduan Menu Anda</h2>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div v-if="user?.role === 'admin'" class="space-y-2">
                            <p>Sebagai <strong>Admin</strong>, Anda bertanggung jawab penuh atas manajemen data dasar:</p>
                            <ul class="list-disc pl-5 space-y-1 text-xs">
                                <li>Menu <span class="font-semibold text-blue-600">Kelola Akun Siswa</span> untuk kelola data siswa & import Excel.</li>
                                <li>Menu <span class="font-semibold text-blue-600">Kelola Akun Guru</span> untuk mendaftarkan guru baru.</li>
                                <li>Menu <span class="font-semibold text-blue-600">Kelola Kelas</span> untuk memantau kelas harian.</li>
                            </ul>
                        </div>
                        <div v-if="user?.role === 'guru'" class="space-y-2">
                            <p>Sebagai <strong>Guru</strong>, Anda berfokus pada pengawasan kehadiran kelas:</p>
                            <ul class="list-disc pl-5 space-y-1 text-xs">
                                <li>Buka menu <span class="font-semibold text-indigo-600">Kelola Kelas (Monitoring)</span> untuk melihat siswa yang hadir.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-gray-800">📌 Akses Cepat Menu</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <Link v-if="user?.role === 'admin'" href="/admin/siswa" class="p-3 text-center rounded-xl bg-gray-50 border border-gray-100 hover:bg-blue-50 text-sm font-medium text-gray-700">
                            👥 Kelola Siswa
                        </Link>
                        <Link v-if="user?.role === 'admin'" href="/admin/guru" class="p-3 text-center rounded-xl bg-gray-50 border border-gray-100 hover:bg-blue-50 text-sm font-medium text-gray-700">
                            👨‍🏫 Kelola Guru
                        </Link>
                        <Link v-if="user?.role === 'admin' || user?.role === 'guru'" href="/dashboard-guru" class="p-3 text-center rounded-xl bg-gray-50 border border-gray-100 hover:bg-indigo-50 text-sm font-medium text-gray-700 col-span-2">
                            📊 Monitoring Kelas
                        </Link>
                    </div>
                </div>
            </div>

        </div>
    </SidebarLayout>
</template>