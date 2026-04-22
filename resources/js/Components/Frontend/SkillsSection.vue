<script setup lang="ts">
type Skill = {
    id: number;
    name: string;
    proficiency: number | null;
};

defineProps<{
    skills: Skill[];
}>();
</script>

<template>
    <section id="skills" class="mt-20">
        <div class="mb-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-indigo-600">Skills</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">My Skills</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="skill in skills"
                :key="skill.id"
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
            >
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900">{{ skill.name }}</h3>
                    <span class="text-sm text-slate-500">{{ skill.proficiency ?? 0 }}%</span>
                </div>
                <div class="mt-3 h-2 w-full rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-indigo-600" :style="{ width: `${skill.proficiency ?? 0}%` }" />
                </div>
            </div>
        </div>
    </section>
</template>
