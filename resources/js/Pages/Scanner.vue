<script setup>
import { onMounted, ref } from 'vue';
import { Html5QrcodeScanner } from 'html5-qrcode';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({ qr_code_content: '' });
const statusMessage = ref('Menunggu izin kamera...');
const isSuccess = ref(false);

onMounted(() => {
    // Inisialisasi scanner
    // qrbox 250x250 akan memaksa area scan menjadi persegi
    const scanner = new Html5QrcodeScanner("reader", { 
        fps: 10, 
        qrbox: { width: 250, height: 250 }, 
        aspectRatio: 1.0, // Memastikan kamera merespon rasio 1:1
        rememberLastUsedCamera: true
    });

    scanner.render((decodedText) => {
        if (form.processing) return;
        form.qr_code_content = decodedText;
        statusMessage.value = 'Memproses absensi...';
        
        form.post(route('absen.store'), {
            onSuccess: () => {
                isSuccess.value = true;
                statusMessage.value = '✓ Absen berhasil!';
                scanner.clear();
            },
            onError: (errors) => {
                isSuccess.value = false;
                statusMessage.value = '❌ ' + (errors.qr_code_content || 'QR tidak valid.');
            }
        });
    }, (errorMessage) => {
        // Abaikan error saat proses scanning berlangsung
    });
});
</script>

<template>
    <Head title="Kamera Scanner Gerbang" />

    <SidebarLayout>
        <div class="max-w-xl mx-auto text-center px-4">
            <div class="mb-6">
                <h1 class="text-2xl font-black text-slate-900">Pemindai Absensi</h1>
            </div>

            <div class="flex justify-center">
                <div id="reader" class="w-full max-w-[300px] aspect-square rounded-2xl overflow-hidden shadow-2xl border-4 border-white bg-slate-900">
                    </div>
            </div>

            <div class="mt-6 max-w-sm mx-auto">
                <div class="text-xs font-mono p-4 rounded-xl border border-slate-200" 
                     :class="isSuccess ? 'bg-emerald-50 text-emerald-700' : 'bg-white text-slate-600'">
                    {{ statusMessage }}
                </div>
            </div>
        </div>
    </SidebarLayout> 
</template>