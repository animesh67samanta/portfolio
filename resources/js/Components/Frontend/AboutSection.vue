<script setup lang="ts">
const props = withDefaults(defineProps<{
    heading: string;
    content: string;
    imageUrl?: string;
}>(), {
    heading: '',
    content: '',
});
</script>

<template>
    <section id="about" class="mt-20 grid gap-8 md:grid-cols-2 md:items-center">
        <img
            v-if="imageUrl"
            :src="imageUrl"
            alt="About profile"
            class="h-80 w-full rounded-2xl object-cover shadow-sm"
        />
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-indigo-600">About</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ heading }}</h2>
            <p class="mt-4 whitespace-pre-line leading-7 text-slate-600">{{ content }}</p>
        </div>
    </section>
</template>
