<script setup lang="ts">
import FormField from '@/Components/FormField.vue';

defineProps<{
    form: any;
    statusOptions: ReadonlyArray<{ value: 'new' | 'in_progress' | 'closed'; label: string }>;
    prefix: string;
}>();
</script>

<template>
    <FormField label="Name" :name="`${prefix}_name`" :error="form.errors.name">
        <input
            :id="`${prefix}_name`"
            v-model="form.name"
            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            type="text"
            autocomplete="off"
        />
    </FormField>

    <FormField label="Email" :name="`${prefix}_email`" :error="form.errors.email">
        <input
            :id="`${prefix}_email`"
            v-model="form.email"
            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            type="email"
            autocomplete="off"
        />
    </FormField>

    <FormField label="Phone" :name="`${prefix}_phone`" :error="form.errors.phone">
        <input
            :id="`${prefix}_phone`"
            v-model="form.phone"
            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            type="text"
            autocomplete="off"
        />
    </FormField>

    <FormField label="Subject" :name="`${prefix}_subject`" :error="form.errors.subject">
        <input
            :id="`${prefix}_subject`"
            v-model="form.subject"
            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            type="text"
            autocomplete="off"
        />
    </FormField>

    <FormField label="Status" :name="`${prefix}_status`" :error="form.errors.status">
        <select
            :id="`${prefix}_status`"
            v-model="form.status"
            class="block w-full rounded-md border-slate-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                {{ option.label }}
            </option>
        </select>
    </FormField>

    <FormField label="Read status" :name="`${prefix}_is_read`" :error="form.errors.is_read">
        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input
                v-model="form.is_read"
                type="checkbox"
                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            />
            Mark as read
        </label>
    </FormField>

    <FormField class="md:col-span-2" label="Message" :name="`${prefix}_message`" :error="form.errors.message">
        <textarea
            :id="`${prefix}_message`"
            v-model="form.message"
            class="block min-h-32 w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        />
    </FormField>
</template>
