<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

interface Props {
    label: string;
    name: string;
    error?: string | null;
    required?: boolean;
    help?: string | null;
}

withDefaults(defineProps<Props>(), {
    error: null,
    required: false,
    help: null,
});
</script>

<template>
    <div>
        <InputLabel :for="name" :value="label" />

        <div class="mt-1">
            <slot />
        </div>

        <p v-if="help" class="mt-2 text-sm text-slate-500">{{ help }}</p>
        <InputError class="mt-2" :message="error ?? undefined" />
    </div>
</template>

