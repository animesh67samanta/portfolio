<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="Blogs" />

    <AdminLayout page-title="Blogs">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">Blog Management</h2>
            <p class="mt-2 text-slate-600">Create and publish blog posts with SEO-friendly slugs.</p>
        </SectionCard>
    </AdminLayout>
</template>
