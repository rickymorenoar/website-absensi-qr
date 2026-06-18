<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Menerima data kelas dari backend
defineProps({ 
    classes: {
        type: Array,
        default: () => []
    }
});

// Form handling bawaan Inertia
const form = useForm({ 
    name: '' 
});

const submit = () => {
    form.post('/classes', { 
        onSuccess: () => form.reset() 
    });
};
</script>

<template>
    <Head title="Dashboard Guru - Gate Attendance" />

    <SidebarLayout>
        <div class="pb-6 mb-8 border-b border-slate-200">
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Manajemen Kelas & Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">
                Kelola ruang kelas lu dan pantau pembuatan kode QR absensi di sini.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Tambah Kelas Baru</h3>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Nama Kelas</label>
                        <input 
                            v-model="form.name" 
                            type="text"
                            placeholder="Contoh: Kelas 9A" 
                            required 
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:bg-white transition"
                        />
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs py-2.5 px-4 rounded-xl transition disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Kelas' }}
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Daftar Kelas Aktif</h3>
                </div>

                <div v-if="classes.length === 0" class="p-8 text-center text-slate-500 text-sm">
                    Belum ada kelas yang dibuat. Silakan tambah di panel sebelah kiri.
                </div>

                <table v-else class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3 px-6 w-16">ID</th>
                            <th class="py-3 px-6">Nama Kelas</th>
                            <th class="py-3 px-6 text-right">Aksi QR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        <tr v-for="c in classes" :key="c.id" class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400">#{{ c.id }}</td>
                            <td class="py-4 px-6 font-medium text-slate-900">{{ c.name }}</td>
                            <td class="py-4 px-6 text-right">
                                <a 
                                    :href="'/qr/' + c.id" 
                                    target="_blank"
                                    class="inline-flex items-center justify-center bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs py-1.5 px-3 rounded-lg transition"
                                >
                                    Lihat QR Code
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </SidebarLayout>
</template>