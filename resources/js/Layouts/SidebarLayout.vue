<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
// Import icon dari lucide
import { 
  LayoutDashboard, Users, UserCog, BookOpenText, Printer, QrCode, LogOut, Menu 
} from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth.user);
const isSidebarOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex text-slate-900 font-sans antialiased">
        
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 md:hidden transition-opacity"></div>
        
        <aside 
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between fixed h-full z-30 transition-transform duration-300 md:translate-x-0">
            
            <div class="p-6">
                <div class="flex items-center gap-2.5 mb-8 pb-4 border-b border-slate-100">
                    <img src="/spenturan-logo.ico" alt="Logo" class="w-7 h-7 object-contain">
                    <span class="font-semibold tracking-tight text-slate-900 text-sm">Gate Attendance</span>
                </div>

                <nav class="space-y-1">
                    <Link href="/dashboard" 
                        :class="page.url === '/dashboard' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition">
                        <LayoutDashboard :size="18" /> Dashboard
                    </Link>

                    <div v-if="user.role === 'admin'" class="pt-4 space-y-1">
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Admin Panel</span>
                        
                        <Link href="/admin/siswa" :class="page.url.startsWith('/admin/siswa') ? 'bg-slate-100' : ''" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <Users :size="18" /> Siswa
                        </Link>
                        <Link href="/admin/guru" :class="page.url.startsWith('/admin/guru') ? 'bg-slate-100' : ''" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <UserCog :size="18" /> Guru
                        </Link>
                        <Link href="/dashboard-guru" :class="page.url.startsWith('/dashboard-guru') ? 'bg-slate-100' : ''" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <BookOpenText :size="18" /> Kelas
                        </Link>
                        <Link href="/cetak-qr" :class="page.url === '/cetak-qr' ? 'bg-slate-100' : ''" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <Printer :size="18" /> Cetak QR
                        </Link>
                        <Link href="/scanner" :class="page.url === '/scanner' ? 'bg-slate-100' : ''" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <QrCode :size="18" /> Scanner
                        </Link>
                    </div>

                    <div v-if="user.role === 'guru'" class="pt-4 space-y-1">
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Menu Guru</span>
                        <Link href="/dashboard-guru" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <BookOpenText :size="18" /> Absen & Kelas
                        </Link>
                    </div>

                    <div v-if="user.role === 'siswa'" class="pt-4 space-y-1">
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Menu Siswa</span>
                        <Link href="/scanner" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <QrCode :size="18" /> Scan QR Absen
                        </Link>
                    </div>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100">
                <Link href="/logout" method="post" as="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition">
                    <LogOut :size="18" /> Keluar
                </Link>
            </div>
        </aside>

        <main class="flex-1 min-h-screen md:pl-64 flex flex-col w-full">
            <div class="md:hidden sticky top-0 z-10 flex items-center justify-between p-4 bg-white border-b border-slate-200">
                <span class="font-semibold text-sm">Gate Attendance</span>
                <button @click="isSidebarOpen = true" class="p-1 text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    <Menu :size="24" />
                </button>
            </div>
            <div class="p-4 md:p-8 max-w-7xl mx-auto w-full">
                <slot />
            </div>
        </main>
    </div>
</template>