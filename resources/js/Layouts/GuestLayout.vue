<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gradient-to-b from-white to-slate-50 px-4 py-10"
    >
        <div class="w-full max-w-md">
            <div class="mb-6 flex justify-center">
                <Link href="/">
                    <ApplicationLogo class="h-20 w-auto fill-current text-indigo-400" />
                </Link>
            </div>

            <div
                class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white/90 px-6 py-8 shadow-sm backdrop-blur"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
