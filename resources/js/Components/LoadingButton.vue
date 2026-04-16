<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface Props {
    loading?: boolean;
    disabled?: boolean;
    loadingText?: string;
    type?: 'button' | 'submit' | 'reset';
}

withDefaults(defineProps<Props>(), {
    loading: false,
    disabled: false,
    loadingText: 'Saving...',
    type: 'submit',
});
</script>

<template>
    <PrimaryButton
        :type="type"
        :disabled="disabled || loading"
        class="inline-flex items-center gap-2"
        :class="{ 'opacity-70': disabled || loading }"
    >
        <svg
            v-if="loading"
            class="h-4 w-4 animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
            />
        </svg>
        <span>
            <slot v-if="!loading" />
            <span v-else>{{ loadingText }}</span>
        </span>
    </PrimaryButton>
</template>

