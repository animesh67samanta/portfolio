<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

interface Props {
    pageTitle?: string;
}

withDefaults(defineProps<Props>(), {
    pageTitle: 'Portfolio',
});
</script>

<template>
    <div class="min-h-screen bg-white text-slate-900">
        <FlashMessages />
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <h1 class="text-lg font-semibold">{{ pageTitle }}</h1>
                <nav class="flex items-center gap-4 text-sm">
                    <Link class="text-slate-600 hover:text-slate-900" :href="route('home')">Home</Link>
                    <a class="text-slate-600 hover:text-slate-900" href="#">Projects</a>
                    <a class="text-slate-600 hover:text-slate-900" href="#">Contact</a>
                    <Link class="text-slate-600 hover:text-slate-900" :href="route('login')">Login</Link>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-10">
            <slot />
        </main>

        <footer class="border-t bg-slate-50">
            <div class="mx-auto max-w-6xl px-6 py-4 text-sm text-slate-600">
                © {{ new Date().getFullYear() }} Portfolio Website
            </div>
        </footer>
    </div>
</template>
