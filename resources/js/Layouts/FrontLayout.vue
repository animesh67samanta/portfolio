<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashMessages from '@/Components/FlashMessages.vue';

interface Props {
    pageTitle?: string;
    pageInfo?: string;
}

withDefaults(defineProps<Props>(), {
    pageTitle: 'LAVUE',
    pageInfo: 'Animesh Samanta',

});



const menuOpen = ref(false);
const activeSection = ref('home');

const menuItems = [
    { id: 'home', label: 'Home' },
    { id: 'about', label: 'About' },
    { id: 'skills', label: 'Skills' },
    { id: 'projects', label: 'Projects' },
    { id: 'testimonials', label: 'Testimonials' },
    { id: 'blog', label: 'Blog' },
    { id: 'contact', label: 'Contact' },
] as const;

let observer: IntersectionObserver | null = null;

const linkClass = (id: string): string =>
    activeSection.value === id
        ? 'text-emerald-600 font-semibold'
        : 'text-slate-600 hover:text-red-500';

const closeMenu = (): void => {
    menuOpen.value = false;
};

const currentYear = computed(() => new Date().getFullYear());

onMounted(() => {
    if (typeof window === 'undefined') {
        return;
    }

    observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    activeSection.value = entry.target.id;
                }
            }
        },
        {
            rootMargin: '-40% 0px -50% 0px',
            threshold: 0.1,
        },
    );

    for (const item of menuItems) {
        const target = document.getElementById(item.id);
        if (target) {
            observer.observe(target);
        }
    }
});

onBeforeUnmount(() => {
    observer?.disconnect();
});
</script>

<template>
    <div class="min-h-screen bg-white text-slate-900">
        <FlashMessages />
        <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div>
                    <Link href="/">
                        <div class="flex items-center gap-2 border-2 border-red-500 p-1 rounded">
                          <ApplicationLogo class="h-10 w-auto" />
                          <h1 class="text-lg font-semibold text-emerald-600">{{ pageTitle }}</h1>
                        </div>
                    </Link>
                </div>
                <!-- <ApplicationLogo /> -->
               <h1 class="text-lg font-semibold">{{ pageInfo }}</h1>
                <button
                    type="button"
                    class="rounded-md border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 md:hidden"
                    @click="menuOpen = !menuOpen"
                >
                    Menu
                </button>
                <nav class="hidden items-center gap-5 text-sm md:flex">
                    <a
                        v-for="item in menuItems"
                        :key="item.id"
                        :href="`#${item.id}`"
                        :class="linkClass(item.id)"
                        class="transition"
                    >
                        {{ item.label }}
                    </a>
                </nav>
            </div>
            <nav v-if="menuOpen" class="border-t border-slate-200 bg-white px-6 py-3 md:hidden">
                <div class="flex flex-col gap-2">
                    <a
                        v-for="item in menuItems"
                        :key="item.id"
                        :href="`#${item.id}`"
                        :class="linkClass(item.id)"
                        class="py-1.5 text-sm transition"
                        @click="closeMenu"
                    >
                        {{ item.label }}
                    </a>
                    <Link class="py-1.5 text-sm text-slate-600 transition hover:text-slate-900" :href="route('login')">Login</Link>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-10 md:py-12">
            <slot />
        </main>

        <footer class="border-t bg-slate-50">
            <div class="mx-auto max-w-6xl px-6 py-4 text-sm text-slate-600">
                © {{ currentYear }} Portfolio Website By Animesh Samanta
            </div>
        </footer>
    </div>
</template>
