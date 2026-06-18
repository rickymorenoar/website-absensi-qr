<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    students: Array,
    classes: Array
});

// Form buat tambah manual satuan (Udah disesuaikan pakai EMAIL)
const formManual = useForm({
    name: '',
    email: '', // <-- Menggunakan email bawaan DB
    class_id: '',
    no_absen: ''
});

// Form buat upload Excel/CSV massal
const formImport = useForm({
    file_excel: null
});

const submitManual = () => {
    formManual.post(route('admin.siswa.store'), {
        onSuccess: () => formManual.reset()
    });
};

const handleFileUpload = (e) => {
    formImport.file_excel = e.target.files[0];
};

const submitImport = () => {
    formImport.post(route('admin.siswa.import'), {
        onSuccess: () => formImport.reset()
    });
};
</script>

<template>
    <Head title="Admin - Kelola Siswa" />

    <SidebarLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Pendaftaran Akun Siswa</h1>
            <p class="text-sm text-slate-500">Gunakan fitur import massal menggunakan file CSV/Excel agar pendaftaran ratusan siswa selesai dalam 3 detik.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-1">⚡ Import Massal via CSV/Excel</h3>
                <p class="text-xs text-slate-400 mb-4">Format kolom: Nama, Email, ID_Kelas, No_Absen</p>
                
                <form @submit.prevent="submitImport" class="space-y-4">
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:bg-slate-50 transition relative">
                        <input type="file" @change="handleFileUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".csv" required />
                        <p class="text-xs font-medium text-slate-600">
                            {{ formImport.file_excel ? formImport.file_excel.name : 'Pilih / Drop File .CSV Kelompok Kelas' }}
                        </p>
                    </div>
                    <button type="submit" :disabled="formImport.processing" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs py-2.5 rounded-xl transition">
                        {{ formImport.processing ? 'Sedang Mengunggah...' : '🚀 Jalankan Eksekusi Import' }}
                    </button>
                </form>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm lg:col-span-2">
                <h3 class="font-bold text-slate-800 text-sm mb-4">📝 Input Manual Siswa Baru</h3>
                
                <form @submit.prevent="submitManual" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Lengkap</label>
                        <input v-model="formManual.name" type="text" placeholder="Contoh: Ricky Moreno" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email / Gmail Siswa</label>
                        <input v-model="formManual.email" type="email" placeholder="Contoh: ricky@gmail.com" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pilih Kelas</label>
                        <select v-model="formManual.class_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option v-for="c in classes" :key="c.id" :value="c.id">Kelas {{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nomor Absen</label>
                        <input v-model="formManual.no_absen" type="number" placeholder="Contoh: 12" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>
                    <div class="sm:col-span-2 pt-2">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs py-2.5 px-4 rounded-xl transition">
                            + Daftarkan Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-sm">Database Akun Log Siswa</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-4 w-20">Absen</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Email / Gmail</th>
                        <th class="py-3 px-4">Ruang Kelas</th>
                        <th class="py-3 px-4">Password Default</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    <tr v-for="student in students" :key="student.id" class="hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">#{{ student.no_absen }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ student.name }}</td>
                        <td class="py-3 px-4 font-mono text-indigo-600">{{ student.email }}</td>
                        <td class="py-3 px-4 font-medium text-slate-600">Kelas {{ student.class?.name || 'Kosong' }}</td>
                        <td class="py-3 px-4 text-slate-400 italic">siswa123</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </SidebarLayout>
</template>