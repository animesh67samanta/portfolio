<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

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

const bannerDuration = 3000;

const fallbackBanner: Banner = {
    id: 0,
    title: 'WE DESIGN & BUILD BRANDS',
    subtitle: 'Creative UI/UX Designer & Developer',
    image_path: '',
    cta_text: null,
    cta_url: null,
};

const slides = computed(() =>
    props.banners.length ? props.banners : [fallbackBanner]
);

const activeBanner = computed(
    () => slides.value[currentSlide.value] ?? fallbackBanner
);

const displayName = computed(() => {
    const raw = activeBanner.value?.title ?? fallbackBanner.title;

    return (
        raw.replace(/^i['’]?m\s+/i, '').trim() ||
        fallbackBanner.title
    );
});

const headingParts = computed(() => {
    const text =
        activeBanner.value?.subtitle ||
        fallbackBanner.subtitle ||
        '';

    const words = text.split(' ');

    const partSize = Math.ceil(words.length / 3);

    return [
        words.slice(0, partSize).join(' '),
        words.slice(partSize, partSize * 2).join(' '),
        words.slice(partSize * 2).join(' '),
    ];
});

let sliderInterval: ReturnType<typeof setInterval> | null =
    null;

const nextSlide = () => {
    currentSlide.value =
        (currentSlide.value + 1) % slides.value.length;
};

const goToSlide = (index: number) => {
    currentSlide.value = index;

    stopSlider();
    startSlider();
};

const startSlider = () => {
    if (slides.value.length <= 1) return;

    stopSlider();

    sliderInterval = setInterval(() => {
        nextSlide();
    }, bannerDuration);
};

const stopSlider = () => {
    if (sliderInterval) {
        clearInterval(sliderInterval);
        sliderInterval = null;
    }
};

onMounted(() => {
    startSlider();
});

onBeforeUnmount(() => {
    stopSlider();
});
</script>

<template>
    <section
        id="home"
        class="relative h-screen overflow-hidden bg-white"
        @mouseenter="stopSlider"
        @mouseleave="startSlider"
    >
        <!-- BACKGROUND IMAGE -->
        <div
            class="absolute inset-0 md:left-[40%] overflow-hidden"
        >
            <transition
                name="banner-fade"
                mode="out-in"
            >
                <img
                    :key="currentSlide"
                    :src="
                        props.imageUrl(
                            activeBanner.image_path,
                        )
                    "
                    class="h-full w-full object-cover"
                />
            </transition>

            <!-- IMAGE OVERLAY -->
            <div
                class="absolute inset-0 bg-black/55 md:bg-black/10"
            ></div> 
        </div>

        <!-- DIAGONAL SHAPE -->
        <div
            class="hidden md:block absolute inset-y-0 right-[47%] w-[30%] bg-pink-100 skew-x-[-25deg] shadow-2xl"
        ></div>

        <!-- CONTENT -->
        <div
            class="relative z-20 flex items-center h-full"
        >
            <div
                class="max-w-8xl mx-auto w-full px-6 sm:px-8 lg:px-12"
            >
                <transition
                    name="slide-fade"
                    mode="out-in"
                >
                    <div
                        :key="currentSlide"
                        class="max-w-2xl pt-24 md:pt-0"
                    >
                        <!-- SMALL TITLE -->
                        <p
                            class="inline-flex items-center px-4 py-2 rounded-full backdrop-blur-md bg-white/70 border border-white/20
                            text-xs sm:text-sm md:text-base
                            tracking-[0.25em]
                            uppercase
                            text-indigo-500
                            font-semibold"
                        >
                            {{ displayName }}
                        </p>

                        <!-- MAIN HEADING -->
                        <h1
                            class="mt-5
                            text-4xl
                            sm:text-5xl
                            lg:text-6xl
                            font-black
                            leading-tight
                            tracking-tight"
                        >
                            <span
                                class="block text-[#f5bba7] md:text-black"
                            >
                                {{ headingParts[0] }}
                            </span>

                            <span
                                class="block mt-2
                                bg-linear-to-r
                                from-[#2cf5b1]
                                to-[#022c9c]
                                bg-clip-text
                                text-transparent"
                            >
                                {{ headingParts[1] }}
                            </span>

                            <span
                                class="block mt-2
                                text-[#f5bba7]
                                md:text-slate-700
                                font-semibold"
                            >
                                {{ headingParts[2] }}
                            </span>
                        </h1>

                        <!-- BUTTONS -->
                        <div
                            class="mt-8 flex flex-wrap gap-4"
                        >
                            <a
                                v-if="
                                    activeBanner.cta_url
                                "
                                :href="
                                    activeBanner.cta_url
                                "
                                class="px-7 py-3 rounded-xl
                                bg-linear-to-r
                                from-[#2cf5b1]
                                to-[#022c9c]
                                text-white
                                text-sm
                                md:text-base
                                font-semibold
                                shadow-lg
                                hover:scale-105
                                transition duration-300"
                            >
                                {{
                                    activeBanner.cta_text
                                }}
                            </a>

                            <a
                                v-if="resumeUrl"
                                :href="resumeUrl"
                                target="_blank"
                                class="px-7 py-3 rounded-xl
                                border border-white/30
                                backdrop-blur-md
                                bg-white/10
                                text-white md:text-slate-800
                                text-sm md:text-base
                                font-semibold
                                hover:bg-white/20
                                transition duration-300"
                            >
                                Download CV
                            </a>
                        </div>
                    </div>
                </transition>
            </div>
        </div>

        <!-- SLIDER DOTS -->
        <div
            class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-30"
        >
            <button
                v-for="(slide, index) in slides"
                :key="slide.id"
                @click="goToSlide(index)"
                class="transition-all duration-300 rounded-full"
                :class="
                    index === currentSlide
                        ? 'w-8 h-3 bg-[#2cf5b1] to-[#022c9c] bg-linear-to-r'
                        : 'w-3 h-3 bg-white/50'
                "
            ></button>
        </div>

        <!-- SCROLL INDICATOR -->
        <div
            class="absolute bottom-12 left-1/2 -translate-x-1/2 z-30 hidden md:flex"
        >
            <div
                class="w-6 h-10 rounded-full border-2 border-white/40 flex justify-center"
            >
                <div
                    class="w-1 h-2 bg-white bg-linear-to-r
                                from-[#2cf5b1]
                                to-[#022c9c] rounded-full mt-2 animate-scroll"
                ></div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.banner-fade-enter-active,
.banner-fade-leave-active {
    transition: opacity 0.8s ease;
}

.banner-fade-enter-from,
.banner-fade-leave-to {
    opacity: 0;
}

.banner-fade-enter-to,
.banner-fade-leave-from {
    opacity: 1;
}

.slide-fade-enter-active {
    transition: all 0.6s ease;
}

.slide-fade-leave-active {
    transition: all 0.3s ease;
}

.slide-fade-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.slide-fade-enter-to,
.slide-fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}

@keyframes scroll {
    0% {
        opacity: 1;
        transform: translateY(0);
    }

    100% {
        opacity: 0;
        transform: translateY(10px);
    }
}

.animate-scroll {
    animation: scroll 1.5s infinite;
}
</style>