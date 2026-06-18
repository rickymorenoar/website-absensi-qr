<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    teachers: Array
});

// Form buat kelola guru (Udah disesuaikan pakai EMAIL)
const form = useForm({
    name: '',
    email: '' // <-- Menggunakan email bawaan DB
});

const submit = () => {
    form.post(route('admin.guru.store'), {
        onSuccess: () => form.reset()
    });
};
</script>

<template>
    <Head title="Admin - Kelola Guru" />

    <SidebarLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Pendaftaran Akun Guru</h1>
            <p class="text-sm text-slate-500">Daftarkan akun bapak/ibu guru agar mereka bisa memantau absensi real-time masing-masing kelas.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">📝 Tambah Akun Guru</h3>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Lengkap & Gelar</label>
                        <input v-model="form.name" type="text" placeholder="Contoh: Sukadi, S.Pd." class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email / Gmail Guru</label>
                        <input v-model="form.email" type="email" placeholder="Contoh: sukamdi@gmail.com" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>
                    
                    <button type="submit" :disabled="form.processing" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs py-2.5 rounded-xl transition shadow-sm">
                        {{ form.processing ? 'Menyimpan...' : '+ Daftarkan Akun Guru' }}
                    </button>
                </form>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm lg:col-span-2">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Daftar Akun Guru Aktif</h3>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3 px-4 w-12">No</th>
                            <th class="py-3 px-4">Nama Guru</th>
                            <th class="py-3 px-4">Email / Gmail</th>
                            <th class="py-3 px-4">Password Default</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <tr v-for="(guru, idx) in teachers" :key="guru.id" class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-4 text-slate-400 font-medium">#{{ idx + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ guru.name }}</td>
                            <td class="py-3 px-4 font-mono text-indigo-600">{{ guru.email }}</td>
                            <td class="py-3 px-4 text-slate-400 italic">guru123</td>
                        </tr>
                        <tr v-if="!teachers || teachers.length === 0">
                            <td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada akun guru yang terdaftar.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </SidebarLayout>
</template>