<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'

type Testimonial = {
    id: number;
    client_name: string;
    client_role: string | null;
    company: string | null;
    quote: string;
    image?: string;
};

const props = defineProps<{
    testimonials: Testimonial[];
}>();

const currentIndex = ref(0)
let interval: any = null

const nextSlide = () => {
    currentIndex.value =
        (currentIndex.value + 1) % props.testimonials.length
}

const prevSlide = () => {
    currentIndex.value =
        (currentIndex.value - 1 + props.testimonials.length) %
        props.testimonials.length
}

// Auto slide
onMounted(() => {
    interval = setInterval(nextSlide, 4000)
})

onBeforeUnmount(() => {
    clearInterval(interval)
})
</script>

<template>
    <section id="testimonials" class="mt-24">
        <div class="text-center mb-12">
            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-indigo-400">
                Testimonials
            </p>
            <h2 class="mt-2 text-3xl font-bold text-white">
                What Clients Say
            </h2>
        </div>

        <div
            v-if="testimonials.length"
            class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8 md:p-12 shadow-xl"
        >
            <div class="grid items-center gap-8 md:grid-cols-2 transition-all duration-500">

                <!-- Image -->
                <div class="flex justify-center md:justify-start">
                    <img
                        :src="testimonials[currentIndex].image || 'https://via.placeholder.com/300'"
                        class="h-64 w-64 rounded-2xl object-cover shadow-lg transition-all duration-500"
                    />
                </div>

                <!-- Content -->
                <div class="text-white">
                    <p class="text-lg leading-8 text-slate-300 transition-all duration-500">
                        "{{ testimonials[currentIndex].quote }}"
                    </p>

                    <div class="mt-6">
                        <p class="text-lg font-semibold">
                            {{ testimonials[currentIndex].client_name }}
                        </p>
                        <p class="text-sm text-slate-400">
                            {{ testimonials[currentIndex].client_role || 'Client' }}
                            <span v-if="testimonials[currentIndex].company">
                                , {{ testimonials[currentIndex].company }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Decorative Quote -->
            <div class="absolute top-6 right-10 text-6xl text-slate-700 opacity-20">
                “
            </div>

            <!-- Controls -->
            <button
                @click="prevSlide"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white p-2 rounded-full"
            >
                ‹
            </button>

            <button
                @click="nextSlide"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white p-2 rounded-full"
            >
                ›
            </button>

            <!-- Dots -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                <span
                    v-for="(t, index) in testimonials"
                    :key="t.id"
                    @click="currentIndex = index"
                    class="h-2 w-2 rounded-full cursor-pointer"
                    :class="index === currentIndex ? 'bg-white' : 'bg-white/40'"
                />
            </div>
        </div>
    </section>
</template>