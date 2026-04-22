<script setup lang="ts">
import { ref, watch } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = withDefaults(
    defineProps<{
        show?: boolean;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | '5xl';
        closeable?: boolean;
        title?: string;
    }>(),
    {
        show: false,
        maxWidth: '2xl',
        closeable: true,
        title: '',
    },
);

const emit = defineEmits(['close', 'update:show']);
const isOpen = ref(props.show);

watch(() => props.show, (newVal) => {
    isOpen.value = newVal;
    if (newVal) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

const close = () => {
    if (props.closeable) {
        isOpen.value = false;
        emit('close');
        emit('update:show', false);
        document.body.style.overflow = '';
    }
};

const maxWidthClass = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl',
    '2xl': 'max-w-2xl',
    '3xl': 'max-w-3xl',
    '4xl': 'max-w-4xl',
    '5xl': 'max-w-5xl',
}[props.maxWidth];
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-900/80" @click="close" />
                
                <!-- Modal Container -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <Transition
                        enter-active-class="transition-all duration-300"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition-all duration-300"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            class="relative w-full transform overflow-hidden rounded-lg bg-white shadow-xl transition-all dark:bg-gray-800"
                            :class="maxWidthClass"
                        >
                            <!-- Header -->
                            <div v-if="$slots.header || title" class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                                <div class="flex-1">
                                    <slot name="header">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ title }}
                                        </h3>
                                    </slot>
                                </div>
                                
                                <button
                                    v-if="closeable"
                                    type="button"
                                    @click="close"
                                    class="rounded-lg p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                    aria-label="Close modal"
                                >
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                            </div>

                            <!-- Content -->
                            <div class="px-6 py-4">
                                <slot name="content" />
                                <slot v-if="!$slots.content" />
                            </div>

                            <!-- Footer -->
                            <div v-if="$slots.footer" class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                                <slot name="footer" />
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>