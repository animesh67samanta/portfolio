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
    title: 'WE DESIGN & BUILD BRANDS',
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

const headingParts = computed(() => {
    const text = activeBanner.value?.subtitle || fallbackBanner.subtitle;
    const words = text?.split(' ') || [];

    const partSize = Math.ceil(words.length / 3);

    return [
        words.slice(0, partSize).join(' '),
        words.slice(partSize, partSize * 2).join(' '),
        words.slice(partSize * 2).join(' ')
    ];
});

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
        <div class="absolute inset-y-0 right-0 w-full md:w-[60%]">
            <img
                v-if="props.imageUrl(activeBanner.image_path)"
                :src="props.imageUrl(activeBanner.image_path)"
                class="h-full w-full object-cover opacity-70 md:opacity-100"
            />
        </div>

        <!-- DIAGONAL SHAPE -->
        <div class="hidden md:block absolute inset-y-0 right-[45%] w-[30%] bg-slate-200 skew-x-[-20deg] shadow-z-10 shadow-lg">
            
        </div>

        <!-- MOBILE OVERLAY FOR TEXT READABILITY -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent/100  md:hidden z-10"></div>

        <!-- CONTENT -->
        <div class="relative z-20 flex h-full items-center">
            <div class="max-w-6xl mx-auto px-6 w-full">

                <div class="max-w-xl">
                    <!-- SMALL TEXT -->
                    <p class="text-lg xs:text-xs tracking-[0.25em] text-[#a3a17e] uppercase mt-10">
                        {{ displayName }}
                    </p>

                    <!-- MAIN HEADING -->
                    <!-- <h1 class="mt-4 text-3xl sm:text-5xl md:text-5xl font-extrabold leading-tight text-black">
                        {{ headingParts[0] }}
                        <span class="text-[#a3a17e]">{{ headingParts[1] }}</span>
                        <br />
                        {{ headingParts[2] }}
                    </h1> -->
                    <h1 class="mt-4 text-3xl sm:text-4xl md:text-5xl lg:text-5xl 
                            font-extrabold leading-tight text-black
                            max-w-2xl">

                        {{ headingParts[0] }}

                        <span class="block mt-2 bg-gradient-to-r from-[#2cf5b1] to-[#022c9c] bg-clip-text text-transparent">
                            {{ headingParts[1] }}
                        </span>
                        <span class="block mt-2 text-slate-700 font-semibold">
                            {{ headingParts[2] }}
                        </span>
                    </h1>

                    <!-- BUTTONS -->
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a
                            v-if="activeBanner.cta_url"
                            :href="activeBanner.cta_url"
                            class="px-6 py-3 rounded bg-[#a3a17e] text-white text-sm font-semibold hover:opacity-90 transition"
                        >
                            {{activeBanner.cta_text}}
                        </a>

                    </div>
                </div>

            </div>
        </div>

    </section>
</template>
