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
    pageTitle: 'HUB Protfolio',
    pageInfo: 'Animesh Samanta',

});

const isScrolled = ref(false);

onMounted(() => {
    const handleScroll = () => {
        isScrolled.value = window.scrollY > 50;
    };

    window.addEventListener('scroll', handleScroll);

    onBeforeUnmount(() => {
        window.removeEventListener('scroll', handleScroll);
    });
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
`${
    activeSection.value === id
        ? 'text-indigo-400 font-semibold'
        : isScrolled.value
        ? 'text-black-700 hover:text-red-500'
        : 'text-black-600 hover:text-red-300'
}`;

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
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    activeSection.value = entry.target.id as string;
                    return; // Exit early - most visible first
                }
            });
        },
        {
            rootMargin: '-20% 0px -60% 0px',
            threshold: [0, 0.5],
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
        <header :class="[
                'fixed top-0 w-full z-50 transition-all duration-300',
                isScrolled
                    ? 'bg-white shadow-md py-3 border-b border-slate-200'
                    : 'bg-transparent py-6'
            ]"
        >
            <!-- <div class="mx-auto flex max-w-6xl items-center justify-between px-6 transition-all duration-300"> -->
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 h-12 transition-all duration-300">
                <div class="flex items-center h-20 overflow-hidden">
                    <Link href="/">
                       <ApplicationLogo class="h-20 w-auto" />
                        
                    </Link>
                </div>
                <!-- <ApplicationLogo /> -->
               <!-- <h1 class="text-lg font-semibold">{{ pageInfo }}</h1> -->
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

        <main class="pt-24 mx-auto px-5 md:py-20">
            <slot />
        </main>

        <footer class="border-t bg-slate-50">
            <div class="mx-auto max-w-6xl px-6 py-4 text-sm text-slate-600">
                © {{ currentYear }} HUB Portfolio Website By Animesh Samanta
            </div>
        </footer>
    </div>
</template>
