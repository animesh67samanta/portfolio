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
    <section id="home" class="relative h-screen overflow-hidden bg-white">

        <!-- RIGHT IMAGE -->
        <div class="absolute inset-y-0 right-0 w-[60%]">
            <img
                v-if="props.imageUrl(activeBanner.image_path)"
                :src="props.imageUrl(activeBanner.image_path)"
                class="h-full w-full object-cover"
            />
        </div>

        <!-- DIAGONAL SHAPE -->
        <div class="absolute inset-y-0 right-[55%] w-[30%] bg-slate-100 skew-x-[-20deg] shadow-z-10 shadow-lg"></div>

        <!-- CONTENT -->
        <div class="relative z-20 flex h-full items-center">
            <div class="max-w-6xl mx-auto px-6 w-full">

                <div class="max-w-xl">
                    <!-- SMALL TEXT -->
                    <p class="text-xs tracking-[0.25em] text-[#a3a17e] uppercase mt-10">
                        WE DESIGN & BUILD BRANDS
                    </p>

                    <!-- MAIN HEADING -->
                    <h1 class="mt-4 text-5xl md:text-6xl font-extrabold leading-tight text-black">
                        Hi, I am 
                        <span class="text-[#a3a17e]">{{ displayName }}</span>
                        <br />
                        This is my favorite work.
                    </h1>

                    <!-- BUTTONS -->
                    <div class="mt-8 flex gap-4">
                        <a
                            v-if="activeBanner.cta_url"
                            :href="activeBanner.cta_url"
                            class="px-6 py-3 rounded bg-[#a3a17e] text-white text-sm font-semibold hover:opacity-90 transition"
                        >
                            Hire me
                        </a>

                        <a
                            v-if="resumeUrl"
                            :href="resumeUrl"
                            class="px-6 py-3 rounded border border-[#a3a17e] text-[#a3a17e] text-sm font-semibold hover:bg-[#a3a17e] hover:text-white transition"
                        >
                            Download CV
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </section>
</template>
