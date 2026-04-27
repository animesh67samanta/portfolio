<script setup lang="ts">
type Skill = {
    id: number;
    name: string;
    proficiency: number | null;
    icon?: string | null;
};

defineProps<{
    skills: Skill[];
}>();
const imageUrl = (path?: string | null) => {
    if (!path) return '/uploads/No_Image_Available.jpg';
    if (path.startsWith('http')) return path;
    return `/${path}`;
};
const getColor = (value: number | null) => {
    const v = value ?? 0;

    if (v >= 80) return '#22c55e';   // green
    if (v >= 50) return '#f59e0b';   // yellow
    return '#ef4444';                // red
};
</script>

<template>
    <section id="skills" class="max-w-8xl bg-[#f8f8f8] mt-12 py-10 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">Skills</p>
            <h2 class="mt-2 text-3xl font-bold uppercase tracking-[0.25em] text-slate-900">My Skills</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 max-w-6xl mx-auto py-3">
            <div
                v-for="skill in skills"
                :key="skill.id"
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
            >
                <!-- TOP -->
                <div class="flex items-center justify-between gap-3">

                    <!-- LEFT: ICON + NAME -->
                    <div class="flex items-center gap-3">
                        <img
                            :src="imageUrl(skill.icon)"
                            class="h-10 w-10 rounded-lg object-contain bg-slate-50 p-1"
                        />

                        <h3 class="font-semibold text-slate-900">
                            {{ skill.name }}
                        </h3>
                    </div>

                    <!-- RIGHT: % -->
                    <span class="text-sm text-slate-500">
                        {{ skill.proficiency ?? 0 }}%
                    </span>
                </div>

                <!-- PROGRESS -->
                <div class="mt-4 h-2 w-full rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full transition-all duration-500"
                        :style="{
                            width: `${skill.proficiency ?? 0}%`,
                            backgroundColor: getColor(skill.proficiency)
                        }"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
