<script setup lang="ts">
const props = withDefaults(defineProps<{
    heading: string;
    content: string;
    imageUrl?: string;
    resumeUrl?: string | null;
    name: string | null;
    email: string | null;
    mobile: string | null;
    currentAddress: string | null;
    permanentAddress: string | null;
}>(), {
    heading: '',
    content: '',
});
</script>

<template>
    <section id="about" class="py-20 bg-[#f8f8f8] max-w-8xl mx-auto mt-3">
        <div class="mx-auto grid md:grid-cols-2 gap-12 max-w-5xl bg-white rounded-3xl items-center px-6 py-6">

            <!-- IMAGE -->
            <div class="relative">
                <img
                    v-if="imageUrl"
                    :src="imageUrl"
                    alt="About"
                    class="w-full h-[500px] object-cover"
                />
            </div>

            <!-- CONTENT -->
            <div>
                <!-- SMALL TITLE -->
                <p class="text-xs tracking-[0.25em] text-[#a3a17e] uppercase">
                    My Intro
                </p>

                <!-- HEADING -->
                <h2 class="mt-3 text-4xl font-bold text-slate-900">
                    {{ heading }}
                </h2>

                <!-- DESCRIPTION -->
                <p class="mt-4 text-slate-600 leading-relaxed">
                    {{ content }}
                </p>

                <!-- INFO LIST -->
                <div class="mt-6 space-y-3 text-sm">
                    <p v-if="name"><span class="font-semibold">Name:</span> <span class="text-[#a3a17e]">{{ name }}</span></p>
                    <p v-if="currentAddress"><span class="font-semibold">Address:</span> <span class="text-[#a3a17e]">{{ currentAddress }}</span></p>
                    <p v-if="email"><span class="font-semibold">Email:</span> <span class="text-[#a3a17e]">{{ email }}</span></p>
                    <p v-if="mobile"><span class="font-semibold">Phone:</span> <span class="text-[#a3a17e]">{{ mobile }}</span></p>
                    <p v-if="permanentAddress"><span class="font-semibold">Permanent Address:</span> <span class="text-[#a3a17e]">{{ permanentAddress }}</span></p>
                </div>

                <!-- RESUME BUTTON -->
                <div v-if="resumeUrl" class="mt-6">
                    <a
                        :href="resumeUrl"
                        target="_blank"
                        class="inline-block px-6 py-3 bg-[#a3a17e] text-white font-semibold rounded hover:bg-[#8f8d6b] transition"
                    >
                        Download Resume
                    </a>
                </div>

                <!-- HOBBIES -->
                <!-- <div class="mt-8 flex flex-wrap gap-6 text-sm">

                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-[#a3a17e]/20 text-[#a3a17e]">🎵</span>
                        Music
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-[#a3a17e]/20 text-[#a3a17e]">✈️</span>
                        Travel
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-[#a3a17e]/20 text-[#a3a17e]">🎬</span>
                        Movie
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-[#a3a17e]/20 text-[#a3a17e]">⚽</span>
                        Sports
                    </div>

                </div> -->
            </div>

        </div>
    </section>
</template>

