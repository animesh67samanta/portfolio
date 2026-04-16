<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="Contacts" />

    <AdminLayout page-title="Contacts">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">Contact Messages</h2>
            <p class="mt-2 text-slate-600">Track and respond to incoming contact form submissions.</p>
        </SectionCard>
    </AdminLayout>
</template>
