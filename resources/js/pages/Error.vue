<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

interface Props {
    status: number;
}

const props = defineProps<Props>();

const title = `${props.status} - Page Not Founded`;

const descriptions: Record<number, string> = {
    401: 'Unauthorized',
    403: 'Sorry, you are forbidden from accessing this page.',
    404: 'Sorry, the page you are looking for could not be found.',
    419: 'Page Expired',
    429: 'Too Many Requests',
    500: 'Whoops, something went wrong on our servers.',
    503: 'Sorry, we are doing some maintenance. Please check back soon.',
};

const description = descriptions[props.status] || 'An error occurred';
</script>

<template>
    <Head :title="title" />

    <FrontLayout>
        <div class="flex min-h-[60vh] flex-col items-center justify-center text-center px-6">
            <h1 class="text-9xl font-bold text-slate-200">{{ status }}</h1>
            <h2 class="mt-4 text-3xl font-semibold text-slate-800">{{ description }}</h2>
            <p class="mt-2 text-slate-500">Sorry, the page you are looking for does not exist.</p>
            <Link
                href="/"
                class="mt-8 inline-flex items-center rounded-md bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition"
            >
                Back to Home
            </Link>
        </div>
    </FrontLayout>
</template>

