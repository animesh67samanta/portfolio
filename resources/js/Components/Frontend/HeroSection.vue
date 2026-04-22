<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

type Banner = {
    id: number;
    title: string;
    subtitle: string | null;
    image_path: string;
    cta_text: string | null;
    cta_url: string | null;
};

const props = defineProps<{
    banners: Banner[];
    imageUrl: (path?: string | null) => string;
    resumeUrl?: string | null;
}>();

const currentSlide = ref(0);

const fallbackBanner: Banner = {
    id: 0,
    title: 'Clyde',
    subtitle: 'Creative UI/UX Designer & Developer',
    image_path: '',
    cta_text: null,
    cta_url: null,
};

const slides = computed(() => (props.banners.length ? props.banners : [fallbackBanner]));
const activeBanner = computed(() => slides.value[currentSlide.value] ?? fallbackBanner);

const displayName = computed(() => {
    const raw = activeBanner.value?.title ?? fallbackBanner.title;

    return raw
        .replace(/^i['’]?m\s+/i, '')
        .trim() || fallbackBanner.title;
});

const heading = computed(() => activeBanner.value?.subtitle || fallbackBanner.subtitle);
const description = computed(
    () => 'I design and build modern, accessible digital products with a clean user experience.',
);

let sliderInterval: ReturnType<typeof setInterval> | null = null;

const startSlider = (): void => {
    if (slides.value.length <= 1) {
        return;
    }

    sliderInterval = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % slides.value.length;
    }, 4000);
};

const stopSlider = (): void => {
    if (sliderInterval) {
        clearInterval(sliderInterval);
        sliderInterval = null;
    }
};

const goToSlide = (index: number): void => {
    currentSlide.value = index;
    stopSlider();
    startSlider();
};

const goToPrevious = (): void => {
    currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length;
    stopSlider();
    startSlider();
};

const goToNext = (): void => {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length;
    stopSlider();
    startSlider();
};

watch(
    slides,
    (value) => {
        if (currentSlide.value >= value.length) {
            currentSlide.value = 0;
        }

        stopSlider();
        startSlider();
    },
    { immediate: true },
);

onMounted(() => {
    startSlider();
});

onBeforeUnmount(() => {
    stopSlider();
});
</script>

<template>
    <section id="home" class="relative overflow-hidden rounded-3xl bg-white">
        <!-- Grey diagonal panel behind the hero image -->
        <div class="pointer-events-none absolute inset-y-0 right-0 w-[58%] bg-slate-200 transform -skew-x-12 origin-top-right" />

        <div class="relative grid gap-8 px-6 py-14 md:grid-cols-2 md:items-center md:px-12 md:py-20">
            <div class="flex flex-col justify-center">
                <p class="text-sm uppercase tracking-[0.18em] text-slate-500">
                    Hello! This is <span class="font-semibold text-slate-700">{{ displayName }}</span>
                </p>
                <h1 class="mt-3 text-4xl font-bold leading-tight text-slate-900 md:text-5xl">{{ heading }}</h1>
                <p class="mt-4 max-w-xl text-slate-600">{{ description }}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a
                        v-if="activeBanner.cta_url"
                        :href="activeBanner.cta_url"
                        class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-slate-50"
                    >
                        {{ activeBanner.cta_text || 'Hire me' }}
                    </a>
                    <a
                        v-if="resumeUrl"
                        :href="resumeUrl"
                        class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Download CV
                    </a>
                </div>
            </div>

            <div class="relative">
                <img
                    v-if="props.imageUrl(activeBanner.image_path)"
                    :src="props.imageUrl(activeBanner.image_path)"
                    :alt="activeBanner.title"
                    class="relative z-10 h-72 w-full rounded-2xl object-contain shadow-sm md:h-[420px]"
                />

                <div
                    v-if="slides.length > 1"
                    class="absolute inset-x-6 bottom-5 flex items-center justify-between gap-4"
                >
                    <div class="flex gap-2">
                        <button
                            v-for="(slide, index) in slides"
                            :key="slide.id"
                            type="button"
                            class="h-2.5 w-2.5 rounded-full transition"
                            :class="index === currentSlide ? 'bg-indigo-600' : 'bg-indigo-600/30'"
                            @click="goToSlide(index)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
