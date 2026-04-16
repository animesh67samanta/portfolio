<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';

defineProps<{
    projects: unknown;
    skills: unknown[];
}>();
</script>

<template>
    <Head title="Projects" />

    <AdminLayout page-title="Projects">
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-900">Projects</h2>
                <Link
                    :href="route('admin.projects.create')"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                >
                    Add Project
                </Link>
            </div>

            <DataTable 
              :data="projects"
              :columns="[
                { key: 'title', label: 'Title' },
                { key: 'summary', label: 'Summary' },
                { key: 'is_featured', label: 'Featured' },
                { key: 'started_at', label: 'Started' }
              ]"
            />
        </div>
    </AdminLayout>
</template>
