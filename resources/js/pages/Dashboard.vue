<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout page-title="Dashboard">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">Dashboard</h2>
            <p class="mt-2 text-slate-600">You are logged in and can manage portfolio content.</p>
        </SectionCard>
    </AdminLayout>
</template>
