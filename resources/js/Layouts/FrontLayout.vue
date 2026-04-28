<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import type { PageProps } from '@/types';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashMessages from '@/Components/FlashMessages.vue';
import ApplicationIcon from '@/Components/ApplicationIcon.vue';

interface Props {
    pageTitle?: string;
    pageInfo?: string;
}

withDefaults(defineProps<Props>(), {
    pageTitle: 'HUB Protfolio',
    pageInfo: 'Animesh Samanta',

});

const page = usePage<PageProps>();

const socialLinks = computed(() => page.props.socialLinks ?? {
    linkedin: '#',
    github: '#',
    facebook: '#',
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
        ? 'text-indigo-500 font-semibold'
        : isScrolled.value
        ? 'text-black-700 hover:text-indigo-500'
        : 'text-black-600 hover:text-indigo-500'
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
                </div>
            </nav>
        </header>

        <main class="pt-24 pb-12 md:py-20 px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

        <footer class="bg-white border-t border-gray-200">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                    <!-- Copyright - Hidden on mobile, visible on desktop -->
                    <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
                        <ApplicationIcon class="w-4 h-4 text-gray-400" />
                        <span>© {{ currentYear }} <span class="font-medium text-gray-700">HUB Portfolio</span>. All rights reserved.</span>
                    </div>
                    
                    <!-- Credit - Hidden on mobile, visible on desktop -->
                    <div class="hidden md:block text-sm text-gray-500">
                        Built by <span class="font-medium text-gray-700">Animesh Samanta</span>
                    </div>
                    
                    <!-- Social Links -->
                    <div class="flex gap-4">
                        <a :href="socialLinks.linkedin" target="_blank" rel="noopener noreferrer" 
                        class="text-gray-400 hover:text-[#0077B5] transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.204 0 22.225 0z"/>
                            </svg>
                        </a>
                        
                        <a :href="socialLinks.github" target="_blank" rel="noopener noreferrer" 
                        class="text-gray-400 hover:text-gray-900 transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.302 3.438 9.8 8.205 11.387.6.113.82-.26.82-.58 0-.287-.01-1.05-.015-2.06-3.338.726-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.082-.73.082-.73 1.205.085 1.838 1.237 1.838 1.237 1.07 1.834 2.807 1.304 3.492.997.108-.775.418-1.305.762-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.468-2.38 1.235-3.22-.123-.3-.535-1.52.117-3.16 0 0 1.008-.322 3.3 1.23.96-.267 1.98-.4 3-.405 1.02.005 2.04.138 3 .405 2.29-1.552 3.297-1.23 3.297-1.23.653 1.64.24 2.86.118 3.16.768.84 1.233 1.91 1.233 3.22 0 4.61-2.804 5.62-5.476 5.92.43.37.824 1.102.824 2.22 0 1.602-.015 2.894-.015 3.287 0 .322.216.698.83.578C20.565 21.795 24 17.3 24 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                        </a>
                        
                        <a :href="socialLinks.facebook" target="_blank" rel="noopener noreferrer" 
                        class="text-gray-400 hover:text-[#1877F2] transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Mobile Bottom Section - ApplicationIcon and Copyright/Credit -->
                <div class="flex flex-col items-center gap-3 mt-4 pt-4 border-t border-gray-100 md:hidden">
                    <!-- Application Icon -->
                    <ApplicationIcon class="w-6 h-6 text-indigo-500" />
                    
                    <!-- Copyright -->
                    <div class="text-xs text-gray-500">
                        © {{ currentYear }} <span class="font-medium text-gray-700">HUB Portfolio</span>
                    </div>
                    
                    <!-- Credit -->
                    <div class="text-xs text-gray-500">
                        Built by <span class="font-medium text-gray-700">Animesh Samanta</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
