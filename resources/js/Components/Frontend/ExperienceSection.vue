<script setup lang="ts">
type Experience = {
    title: string;
    company: string;
    duration: string;
    description: string;
    company_url: string;
    location: string;
};

defineProps<{
    experiences: Experience[];
}>();
</script>

<template>
<section id="experience" class="mt-24 max-w-6xl mx-auto ">
    <!-- Header -->
        <div class="text-center mb-12">
            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-indigo-400">
                Experience
            </p>
            <h2 class="mt-2 text-3xl font-bold text-black">
                My Work Experience
            </h2>
        </div>
    <!-- <div class="mb-12">
        <h2 class="text-4xl font-bold text-slate-900">Experience</h2>
        <div class="mt-4 h-[1px] bg-slate-300"></div>
    </div> -->

    <!-- Timeline -->
    <div class="relative">
        <!-- vertical line -->
        <div class="absolute left-1/2 top-0 h-full w-[1px] bg-slate-300"></div>

        <div
            v-for="(exp, index) in experiences"
            :key="index"
            class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 items-start"
        >
            <!-- LEFT -->
            <div class="text-right pr-8">
                <h3 class="text-3xl font-bold text-[#a3a17e]">
                    {{ exp.duration }}
                </h3>
                <p class="text-sm text-slate-600 mt-1">
                    {{ exp.title }}
                </p>
            </div>

            <!-- CENTER DOT -->
            <div class="relative flex justify-center">
                <span class="h-4 w-4 rounded-full border-2 bg-indigo-600 border-black"></span>
            </div>

            <!-- RIGHT -->
            <div class="pl-8">
                <h4 class="font-semibold text-[#a3a17e]">
                    {{ exp.company }}
                </h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ exp.description }}
                </p>
            </div>
        </div>
    </div>
</section>
</template>