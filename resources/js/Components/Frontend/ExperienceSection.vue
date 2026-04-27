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
    <section id="experience" class="py-10 mt-12 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            <!-- HEADER -->
            <div class="text-center mb-12">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                    Experience
                </p>
                <h2 class="mt-3 text-3xl font-bold uppercase tracking-[0.25em] text-slate-900">
                    My Work Experience
                </h2>
            </div>

            <!-- TIMELINE -->
            <div class="relative">

                <!-- LEFT LINE -->
                <div class="absolute left-3 top-0 h-full w-[2px] bg-gradient-to-b from-indigo-500 via-slate-200 to-transparent"></div>

                <div class="space-y-10">

                    <div
                    v-for="(exp, index) in experiences"
                    :key="index"
                    class="relative flex gap-6"
                    >
                        <!-- DOT -->
                        <div class="relative z-10">
                            <div class="w-6 h-6 rounded-full bg-indigo-500 ring-4 ring-white shadow-md"></div>
                        </div>

                        <!-- CARD -->
                        <div class="flex-1">
                            <div class="group bg-black/10 backdrop-blur-md border border-slate-200 rounded-2xl p-6 shadow-sm transition hover:shadow-xl hover:-translate-y-1">

                                <!-- TOP -->
                                <div class="flex flex-wrap justify-between items-center gap-2">

                                    <h3 class="text-lg md:text-xl font-bold text-slate-900">
                                    {{ exp.title }}
                                    </h3>

                                    <span class="text-xs md:text-sm bg-indigo-50 text-indigo-500 px-3 py-1 rounded-full font-medium">
                                    {{ exp.duration }}
                                    </span>

                                </div>

                                <!-- COMPANY -->
                                <p class="mt-1 text-sm text-indigo-500 font-semibold">
                                    {{ exp.company }} • {{ exp.location }}
                                </p>

                                <!-- DESCRIPTION -->
                                <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                    {{ exp.description }}
                                </p>

                                <!-- LINK -->
                                <a
                                    v-if="exp.company_url"
                                    :href="exp.company_url"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 mt-4 text-sm text-indigo-500 hover:text-indigo-800 font-medium"
                                >
                                    Visit Company →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>