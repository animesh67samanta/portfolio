<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import FlashMessages from '@/Components/FlashMessages.vue';

interface Props {
    pageTitle?: string;
}

withDefaults(defineProps<Props>(), {
    pageTitle: 'Admin Panel',
});

type MenuItem = {
    label: string;
    routeName: string;
};

const menuItems: MenuItem[] = [
    { label: 'Dashboard', routeName: 'admin.dashboard' },
    { label: 'Banner', routeName: 'admin.banners.index' },
    { label: 'About', routeName: 'admin.abouts.index' },
    { label: 'Skills', routeName: 'admin.skills.index' },
    { label: 'Projects', routeName: 'admin.projects.index' },
    { label: 'Blogs', routeName: 'admin.blogs.index' },
    { label: 'Testimonials', routeName: 'admin.testimonials.index' },
    { label: 'Contacts', routeName: 'admin.contacts.index' },
];

const isSidebarOpen = ref(false);
const page = usePage<PageProps>();

const userName = computed(() => page.props.auth.user?.name ?? 'Admin User');
const userEmail = computed(() => page.props.auth.user?.email ?? 'admin@example.com');

const resolveHref = (routeName: string): string => {
    if (route().has(routeName)) {
        return route(routeName);
    }

    return '#';
};

const isActiveRoute = (routeName: string): boolean => {
    return route().current(routeName);
};
</script>

<template>
    <div class="min-h-screen bg-slate-100">
        <FlashMessages />
        <div
            v-if="isSidebarOpen"
            class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
            @click="isSidebarOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-40 w-72 transform border-r border-slate-800 bg-slate-900 text-slate-100 transition-transform duration-300 lg:translate-x-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-16 items-center justify-between border-b border-slate-800 px-6">
                <h1 class="text-lg font-semibold tracking-wide">Portfolio Admin</h1>
                <button
                    type="button"
                    class="rounded p-1 text-slate-300 hover:bg-slate-800 hover:text-white lg:hidden"
                    @click="isSidebarOpen = false"
                >
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>

            <nav class="space-y-1 px-4 py-6">
                <Link
                    v-for="item in menuItems"
                    :key="item.routeName"
                    :href="resolveHref(item.routeName)"
                    class="flex items-center rounded-md px-3 py-2 text-sm font-medium transition"
                    :class="
                        isActiveRoute(item.routeName)
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    "
                >
                    {{ item.label }}
                </Link>
            </nav>
        </aside>

        <div class="lg:pl-72">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="rounded-md border border-slate-200 p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
                            @click="isSidebarOpen = true"
                        >
                            <span class="sr-only">Open sidebar</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path
                                    fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                        <h2 class="text-lg font-semibold text-slate-900">{{ pageTitle }}</h2>
                    </div>

                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-900">{{ userName }}</p>
                        <p class="text-xs text-slate-500">{{ userEmail }}</p>
                    </div>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
