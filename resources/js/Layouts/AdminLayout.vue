<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import FlashMessages from '@/Components/FlashMessages.vue';
import {
    ChartBarIcon,
    PhotoIcon,
    UserIcon,
    CodeBracketIcon,
    FolderIcon,
    DocumentTextIcon,
    ChatBubbleLeftRightIcon,
    EnvelopeIcon,
    KeyIcon,
    ArrowRightOnRectangleIcon,
    ChevronLeftIcon,
    Bars3Icon,
    SunIcon,
    MoonIcon,
    ChevronDownIcon,
    Squares2X2Icon
} from '@heroicons/vue/24/outline';

interface Props {
    pageTitle?: string;
}

withDefaults(defineProps<Props>(), {
    pageTitle: 'Admin Panel',
});

interface MenuItem {
    label: string;
    routeName: string;
    icon: any;
    badge?: number;
}

const isDarkMode = ref(false);
const isSidebarOpen = ref(false);
const isUserMenuOpen = ref(false);
const page = usePage<PageProps>();

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

const menuItems: MenuItem[] = [
    { label: 'Dashboard', routeName: 'admin.dashboard', icon: Squares2X2Icon },
    { label: 'Banners', routeName: 'admin.banners.index', icon: PhotoIcon },
    { label: 'About', routeName: 'admin.abouts.index', icon: UserIcon },
    { label: 'Skills', routeName: 'admin.skills.index', icon: CodeBracketIcon },
    { label: 'Projects', routeName: 'admin.projects.index', icon: FolderIcon },
    { label: 'Blogs', routeName: 'admin.blogs.index', icon: DocumentTextIcon, badge: 3 },
    { label: 'Testimonials', routeName: 'admin.testimonials.index', icon: ChatBubbleLeftRightIcon },
    { label: 'Contacts', routeName: 'admin.contacts.index', icon: EnvelopeIcon, badge: 5 },
    { label: 'Profile Settings', routeName: 'profile.edit', icon: KeyIcon },
];

const userName = computed(() => page.props.auth.user?.name ?? 'Admin User');
const userEmail = computed(() => page.props.auth.user?.email ?? 'admin@example.com');
const userAvatar = computed(() => page.props.auth.user?.avatar ?? null);

const resolveHref = (routeName: string): string => {
    if (route().has(routeName)) {
        return route(routeName);
    }
    return '#';
};

const isActiveRoute = (routeName: string): boolean => {
    return route().current(routeName);
};

const logout = (): void => {
    router.post(route('logout'), {}, {
        onSuccess: () => {
            router.visit(route('login'));
        },
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 transition-colors duration-300 dark:bg-gray-900">
        <FlashMessages />
        
        <!-- Mobile Sidebar Backdrop -->
        <div
            v-if="isSidebarOpen"
            class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm transition-all duration-300 lg:hidden"
            @click="isSidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 w-80 transform border-r border-gray-200 bg-white shadow-xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-900 lg:translate-x-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Sidebar Header -->
            <div class="flex h-16 items-center justify-between border-b border-gray-200 px-6 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500">
                        <span class="text-sm font-bold text-white">PA</span>
                    </div>
                    <h1 class="text-lg font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent dark:from-white dark:to-gray-400">
                        Portfolio Admin
                    </h1>
                </div>
                <button
                    type="button"
                    class="rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200 lg:hidden"
                    @click="isSidebarOpen = false"
                >
                    <ChevronLeftIcon class="h-5 w-5" />
                </button>
            </div>

            <!-- User Profile Summary -->
            <div class="border-b border-gray-200 p-4 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img 
                            v-if="userAvatar"
                            :src="userAvatar" 
                            :alt="userName"
                            class="h-10 w-10 rounded-full object-cover ring-2 ring-indigo-500"
                        />
                        <div v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white">
                            {{ userName.charAt(0).toUpperCase() }}
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-green-500 dark:border-gray-900"></div>
                    </div>
                    <div class="flex-1 truncate">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ userName }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ userEmail }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-1 px-3 py-4">
                <Link
                    v-for="item in menuItems"
                    :key="item.routeName"
                    :href="resolveHref(item.routeName)"
                    class="group flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200"
                    :class="
                        isActiveRoute(item.routeName)
                            ? 'bg-gradient-to-r from-indigo-50 to-transparent text-indigo-700 dark:from-indigo-950/50 dark:text-indigo-400'
                            : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800'
                    "
                >
                    <div class="flex items-center gap-3">
                        <component 
                            :is="item.icon" 
                            class="h-5 w-5 transition-transform duration-200 group-hover:scale-110"
                            :class="isActiveRoute(item.routeName) ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'"
                        />
                        <span>{{ item.label }}</span>
                    </div>
                    <span 
                        v-if="item.badge"
                        class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-semibold text-white"
                    >
                        {{ item.badge }}
                    </span>
                </Link>
            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-gray-200 p-4 dark:border-gray-800">
                <button
                    @click="logout"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 transition-all duration-200 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/50"
                >
                    <ArrowRightOnRectangleIcon class="h-5 w-5" />
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:pl-80">
            <!-- Header -->
            <header class="sticky top-0 z-20 border-b border-gray-200 bg-white/80 backdrop-blur-lg dark:border-gray-800 dark:bg-gray-900/80">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200 lg:hidden"
                            @click="isSidebarOpen = true"
                        >
                            <Bars3Icon class="h-5 w-5" />
                        </button>
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-1 rounded-full bg-gradient-to-b from-indigo-500 to-purple-500"></div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ pageTitle }}</h2>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button
                            @click="toggleDarkMode"
                            class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                        >
                            <SunIcon v-if="isDarkMode" class="h-5 w-5" />
                            <MoonIcon v-else class="h-5 w-5" />
                        </button>

                        <!-- User Menu -->
                        <div class="relative">
                            <button
                                @click="isUserMenuOpen = !isUserMenuOpen"
                                class="flex items-center gap-2 rounded-lg p-1.5 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800"
                            >
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white">
                                    {{ userName.charAt(0).toUpperCase() }}
                                </div>
                                <ChevronDownIcon class="h-4 w-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': isUserMenuOpen }" />
                            </button>

                            <!-- Dropdown Menu -->
                            <Transition
                                enter-active-class="transition duration-200 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0"
                            >
                                <div
                                    v-if="isUserMenuOpen"
                                    class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900"
                                >
                                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ userName }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ userEmail }}</p>
                                    </div>
                                    <div class="p-2">
                                        <Link
                                            :href="route('profile.edit')"
                                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                                            @click="isUserMenuOpen = false"
                                        >
                                            <KeyIcon class="h-4 w-4" />
                                            Profile Settings
                                        </Link>
                                        <button
                                            @click="logout"
                                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/50"
                                        >
                                            <ArrowRightOnRectangleIcon class="h-4 w-4" />
                                            Logout
                                        </button>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Custom scrollbar for sidebar */
aside::-webkit-scrollbar {
    width: 4px;
}

aside::-webkit-scrollbar-track {
    background: #f1f1f1;
}

aside::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

aside::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Dark mode scrollbar */
.dark aside::-webkit-scrollbar-track {
    background: #1f2937;
}

.dark aside::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.dark aside::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>