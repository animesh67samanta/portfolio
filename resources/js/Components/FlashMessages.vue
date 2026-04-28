<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import type { PageProps } from '@/types';

type Variant = 'success' | 'error';

const page = usePage<PageProps>();
const isVisible = ref(false);
const variant = ref<Variant>('success');
const message = ref<string>('');

const flashSuccess = computed(() => page.props.flash?.success ?? null);
const flashError = computed(() => page.props.flash?.error ?? null);

watch(
    [flashSuccess, flashError],
    ([success, error]) => {
        if (success) {
            variant.value = 'success';
            message.value = success;
            isVisible.value = true;
        } else if (error) {
            variant.value = 'error';
            message.value = error;
            isVisible.value = true;
        }

        if (success || error) {
            window.setTimeout(() => {
                isVisible.value = false;
            }, 4000);
        }
    },
    { immediate: true },
);

const styles = computed(() => {
    if (variant.value === 'success') {
        return {
            container: 'border-emerald-200 bg-emerald-50 text-emerald-900',
            icon: 'text-indigo-400',
        };
    }

    return {
        container: 'border-rose-200 bg-rose-50 text-rose-900',
        icon: 'text-rose-600',
    };
});
</script>

<template>
    <div class="pointer-events-none fixed inset-x-0 top-4 z-50 flex justify-center px-4">
        <transition
        enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div
                v-if="isVisible"
                class="pointer-events-auto w-full max-w-xl rounded-xl border px-4 py-3 shadow-lg"
                :class="styles.container"
            >
                <div class="flex items-start gap-3">
                    <div class="mt-0.5" :class="styles.icon">
                        <svg v-if="variant === 'success'" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M16.704 5.29a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <svg v-else class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm0 7a1 1 0 10-2 0 1 1 0 002 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="flex-1 text-sm leading-5">
                        <p class="font-medium">{{ variant === 'success' ? 'Success' : 'Error' }}</p>
                        <p class="mt-0.5 opacity-90">{{ message }}</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-md p-1 text-current/70 hover:bg-black/5 hover:text-current"
                        @click="isVisible = false"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>
