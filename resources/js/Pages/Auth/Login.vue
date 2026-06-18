<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in - E-Absensi" />

    <div class="min-h-screen flex items-center justify-center bg-slate-50 text-slate-900 font-sans antialiased">
        <div class="w-full max-w-md p-6">
            
            <div v-if="status" class="mb-5 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 p-3 rounded-lg text-center">
                {{ status }}
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-xl shadow-slate-200/50">
                
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-600 text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-slate-900">Gate Attendance</span>
                    </div>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900 mt-4">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Silakan masukkan kredensial resmi sekolah Anda.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    
                    <div>
                        <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            v-model="form.email" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="nama@sekolah.sch.id"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:bg-white transition duration-150"
                        />
                        <p v-if="form.errors.email" class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-medium text-slate-700 uppercase tracking-wider">Password</label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition">
                                Lupa password?
                            </Link>
                        </div>
                        <input 
                            id="password" 
                            type="password" 
                            v-model="form.password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:bg-white transition duration-150"
                        />
                        <p v-if="form.errors.password" class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                v-model="form.remember" 
                                class="rounded bg-slate-50 border-slate-300 text-indigo-600 focus:ring-indigo-600 w-4 h-4 transition cursor-pointer"
                            />
                            <span class="ms-2 text-xs text-slate-600 font-medium">Ingat perangkat ini</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-3 px-4 rounded-xl shadow-sm hover:shadow active:scale-[0.99] transition transform duration-150 disabled:opacity-50 disabled:pointer-events-none"
                        >
                            <span v-if="form.processing" class="inline-flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memvalidasi...
                            </span>
                            <span v-else>Masuk ke Aplikasi</span>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-8 pt-5 border-t border-slate-100">
                    <p class="text-xs text-slate-400">
                        Sistem Informasi Absensi internal Sekolah. Hak Cipta Dilindungi.
                    </p>
                </div>

            </div>
        </div>
    </div>
</template>