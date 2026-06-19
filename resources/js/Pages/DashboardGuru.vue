<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Monitor, Camera, ChevronDown, ChevronUp, Download } from 'lucide-vue-next';

defineProps({ classes: Array });

const expandedClassId = ref(null);

const toggleDetail = (id) => {
    expandedClassId.value = expandedClassId.value === id ? null : id;
};

// Fungsi Export Excel Per Kelas
const exportClassToExcel = (classId) => {
    // Mengarahkan ke rute export dengan parameter class_id
    window.location.href = route('attendance.export', { class_id: classId });
};

// Fungsi cek status dan ambil jam scan
const getAttendanceData = (siswa) => {
    const record = siswa.attendances && siswa.attendances.length > 0 ? siswa.attendances[0] : null;
    return {
        status: record ? 'HADIR' : 'BELUM',
        time: record ? record.scan_time : '-'
    };
};
</script>

<template>
    <Head title="Kelola Kelas" />
    
    <SidebarLayout>
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                    <Monitor :size="24" />
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Monitoring Kelas</h1>
            </div>
            
            <Link :href="route('scanner')" class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                <Camera :size="16" /> Scan QR
            </Link>
        </div>

        <div class="space-y-3">
            <div v-for="kelas in classes" :key="kelas.id" class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <div class="flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition">
                    <div class="flex items-center gap-4 cursor-pointer" @click="toggleDetail(kelas.id)">
                        <span class="font-bold text-slate-700">{{ kelas.name }}</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <button 
                            @click="exportClassToExcel(kelas.id)"
                            class="flex items-center gap-1 text-emerald-600 hover:text-emerald-800 text-xs font-bold"
                        >
                            <Download :size="14" /> Export Excel
                        </button>

                        <span @click="toggleDetail(kelas.id)" class="text-xs text-slate-400 font-medium flex items-center gap-1 cursor-pointer">
                            {{ expandedClassId === kelas.id ? 'Tutup' : 'Lihat Detail' }}
                            <component :is="expandedClassId === kelas.id ? ChevronUp : ChevronDown" :size="14" />
                        </span>
                    </div>
                </div>

                <div v-if="expandedClassId === kelas.id" class="px-4 pb-4 animate-in fade-in duration-200">
                    <div class="border-t border-slate-100 pt-3">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-slate-400 text-[10px] uppercase">
                                    <th class="py-2 w-10">No</th>
                                    <th class="py-2">Nama Siswa</th>
                                    <th class="py-2 text-center">Waktu</th>
                                    <th class="py-2 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="(siswa, index) in kelas.students" :key="siswa.id">
                                    <td class="py-2.5 text-slate-400 font-mono">{{ index + 1 }}</td>
                                    <td class="py-2.5 text-slate-600">{{ siswa.name }}</td>
                                    <td class="py-2.5 text-center text-xs text-slate-500 font-mono">{{ getAttendanceData(siswa).time }}</td>
                                    <td class="py-2.5 text-right">
                                        <span :class="getAttendanceData(siswa).status === 'HADIR' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-500 border-red-200'"
                                            class="px-2 py-0.5 rounded text-[10px] font-bold border">
                                            {{ getAttendanceData(siswa).status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>