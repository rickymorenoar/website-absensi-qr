<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex text-slate-900 font-sans antialiased">
        
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between fixed h-full z-20">
            <div class="p-6">
                <div class="flex items-center gap-2.5 mb-8 pb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        G
                    </div>
                    <span class="font-semibold tracking-tight text-slate-900 text-sm">Gate Attendance</span>
                </div>

                <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-3.5 mb-6">
                    <p class="text-xs font-semibold text-slate-800 truncate">{{ user.name }}</p>
                    <p class="text-[11px] font-medium text-indigo-600 uppercase tracking-wider mt-0.5">{{ user.role }}</p>
                </div>
                
                <nav class="space-y-1">
                    <Link href="/dashboard" 
                        :class="page.url === '/dashboard' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition">
                        Dashboard Utama
                    </Link>

                    <!-- MENU KHUSUS ROLE: ADMIN -->
                    <div v-if="user.role === 'admin'" class="pt-4 space-y-1">
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Admin Panel</span>
                    
                        <!-- Menu Baru: Pendaftaran & Kelola Akun Siswa -->
                        <Link href="/admin/siswa" 
                            :class="page.url.startsWith('/admin/siswa') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition">
                            Kelola Akun Siswa
                        </Link>

                        <Link href="/admin/guru" 
                            :class="page.url.startsWith('/admin/guru') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition">
                            Kelola Akun Guru
                        </Link>
                        
                        <Link href="/dashboard-guru" 
                            :class="page.url.startsWith('/dashboard-guru') || page.url.startsWith('/qr') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition">
                            Kelola Kelas (Monitoring)
                        </Link>
                        
                        <Link href="/scanner" 
                            :class="page.url === '/scanner' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition">
                            Kamera Scanner
                        </Link>
                    </div>

                    <!-- MENU KHUSUS ROLE: GURU -->
                    <div v-if="user.role === 'guru'" class="pt-4 space-y-1">
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Menu Guru</span>
                        <Link href="/dashboard-guru" 
                            :class="page.url.startsWith('/dashboard-guru') || page.url.startsWith('/qr') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition">
                            Absen & Kelas
                        </Link>
                    </div>

                    <!-- MENU KHUSUS ROLE: SISWA -->
                    <div v-if="user.role === 'siswa'" class="pt-4 space-y-1">
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Menu Siswa</span>
                        <Link href="/scanner" 
                            :class="page.url === '/scanner' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition">
                            Scan QR Absen
                        </Link>
                    </div>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100">
                <Link href="/logout" method="post" as="button" class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition">
                    Keluar Aplikasi
                </Link>
            </div>
        </aside>

        <main class="flex-1 pl-64 min-h-screen">
            <div class="p-8 max-w-7xl mx-auto">
                <slot />
            </div>
        </main>
    </div>
</template>