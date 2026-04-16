<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="Skills" />

    <AdminLayout page-title="Skills">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">Skills Library</h2>
            <p class="mt-2 text-slate-600">Maintain technology skills and proficiency values.</p>
        </SectionCard>
    </AdminLayout>
</template>
