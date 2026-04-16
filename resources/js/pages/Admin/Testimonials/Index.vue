<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="Testimonials" />

    <AdminLayout page-title="Testimonials">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">Testimonials</h2>
            <p class="mt-2 text-slate-600">Review and organize client testimonials shown on your site.</p>
        </SectionCard>
    </AdminLayout>
</template>
