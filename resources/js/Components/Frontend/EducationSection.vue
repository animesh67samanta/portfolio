<script setup lang="ts">
type Education = {
    degree: string;
    institution: string;
    year: string;
    description: string;
};

defineProps<{
    educations: Education[];
}>();
</script>

<template>
    <section id="education" class="py-10 bg-white px-4 sm:px-6">
    <div class="max-w-4xl mx-auto">

        <!-- HEADER -->
        <div class="text-center mb-10">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                Education
            </p>
            <h2 class="mt-3 text-3xl font-bold uppercase tracking-[0.25em] text-slate-900">
                Academic Journey
            </h2>
        </div>

        <!-- TIMELINE -->
        <div class="relative">

        <!-- CENTER LINE -->
        <div class="absolute left-4 md:left-1/2 top-0 h-full w-[2px] bg-gradient-to-b from-indigo-500 via-slate-200 to-transparent transform -translate-x-1/2"></div>

        <div class="space-y-16">

            <div
            v-for="(edu, index) in educations"
            :key="index"
            class="relative flex items-center"
            >

            <!-- DOT -->
            <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10">
                <div class="h-5 w-5 rounded-full bg-indigo-500 shadow-lg ring-4 ring-white"></div>
            </div>

            <!-- CARD -->
            <div
                class="ml-10 md:ml-0 w-[calc(100%-3rem)] md:w-[45%] p-6 rounded-2xl backdrop-blur-md bg-black/10 border border-slate-200 shadow-lg transition duration-300 hover:shadow-2xl hover:-translate-y-1"
                :class="index % 2 === 0 ? 'md:mr-auto' : 'md:ml-auto'"
            >

                <!-- YEAR -->
                <span class="text-xs font-semibold text-indigo-500 tracking-widest">
                {{ edu.year }}
                </span>

                <!-- DEGREE -->
                <h3 class="mt-2 text-xl font-bold text-slate-900">
                {{ edu.degree }}
                </h3>

                <!-- INSTITUTE -->
                <p class="text-sm text-slate-500 mt-1">
                {{ edu.institution }}
                </p>

                <!-- DESCRIPTION -->
                <p
                v-if="edu.description"
                class="text-sm text-slate-600 mt-3 leading-relaxed"
                >
                {{ edu.description }}
                </p>

            </div>

            </div>

        </div>
        </div>

    </div>
    </section>
</template>