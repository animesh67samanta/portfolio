<script setup lang="ts">
import { ChatBubbleLeftRightIcon } from '@heroicons/vue/24/outline'
import { ref, onMounted, computed, onBeforeUnmount } from 'vue'

type Testimonial = {
    id: number;
    client_name: string;
    client_role: string | null;
    company: string | null;
    quote: string;
    avatar_path?: string | null;
    rating: number | null;

};

const props = defineProps<{
    testimonials: Testimonial[];
}>();

const itemsPerPage = 3
const currentIndex = ref(0)
let interval: any = null

const totalPages = computed(() =>
    Math.max(1, Math.ceil(props.testimonials.length / itemsPerPage))
)

const startIndex = computed(() => currentIndex.value * itemsPerPage)

const nextSlide = () => {
    if (props.testimonials.length <= itemsPerPage) {
return
}

    currentIndex.value = (currentIndex.value + 1) % totalPages.value
}

const prevSlide = () => {
    if (props.testimonials.length <= itemsPerPage) {
return
}

    currentIndex.value =
        (currentIndex.value - 1 + totalPages.value) % totalPages.value
}

const imageUrl = (path?: string | null) => {
    if (!path) {
return '/uploads/No_Image_Available.jpg';
}

    if (path.startsWith('http')) {
return path;
}

    return `/${path}`;
};

// Auto slide
onMounted(() => {
    if (props.testimonials.length > itemsPerPage) {
        interval = setInterval(nextSlide, 4000)
    }
})

onBeforeUnmount(() => {
    clearInterval(interval)
})
</script>

<template>
    <section id="testimonials" class="py-10 bg-[#f8f8f8] mt-12 text-center max-w-8xl mx-auto">

        <!-- HEADER -->
        <div class="mb-12 text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                Testimonials
            </p>
            <h2 class="mt-2 text-3xl font-bold uppercase tracking-[0.25em] text-slate-900">
                What client says about?
            </h2>
        </div>

        <!-- CARDS -->
        <div class="max-w-6xl mx-auto px-6 grid gap-6 md:grid-cols-3">

            <div
                v-for="t in testimonials.slice(startIndex, startIndex + itemsPerPage)"
                :key="t.id"
                class="bg-white p-6 rounded-xl shadow-md text-left flex flex-col h-full transition hover:-translate-y-1 hover:shadow-xl"
            >
            <ChatBubbleLeftRightIcon class="h-6 w-6 text-[#a3a17e] mb-3" />
                <!-- QUOTE -->
                <p class="text-slate-600 leading-relaxed min-h-[120px]">
                    "{{ t.quote }}"
                </p>

                <!-- USER -->
                <div class="flex items-center gap-4 mt-auto pt-6">
                    <img
                        :src="imageUrl(t.avatar_path)"
                        class="h-12 w-12 rounded-full object-cover flex-shrink-0"
                    />

                    <div>
                        <p class="font-semibold text-slate-900">
                            {{ t.client_name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ t.client_role }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- DOTS -->
        <div v-if="totalPages > 1" class="mt-8 flex justify-center gap-2">
            <span
                v-for="page in totalPages"
                :key="page"
                @click="currentIndex = page - 1"
                class="h-2 w-2 rounded-full cursor-pointer"
                :class="(page - 1) === currentIndex ? 'bg-white' : 'bg-white/40'"
            />
        </div>

    </section>
</template>

