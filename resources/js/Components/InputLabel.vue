<script setup lang="ts">
const props = defineProps<{
    value?: string;
    for?: string;
}>();
</script>

<template>
    <label class="block text-sm/6 font-semibold text-gray-900 leading-6" :for="props.for">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

