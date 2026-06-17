<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { QrcodeStream } from 'vue-qrcode-reader'; // Pastikan library terinstall

const onDecode = (result: any) => {
    console.log(result);
    alert('QR Terdeteksi: ' + result);
};
</script>

<template>
    <Head title="Scanner Absen" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Scanner Absen</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <qrcode-stream @detect="onDecode" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>