<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="About" />

    <AdminLayout page-title="About">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">About Section</h2>
            <p class="mt-2 text-slate-600">Update your profile summary and about content here.</p>
        </SectionCard>
    </AdminLayout>
</template>
