<script setup lang="ts">
import FormField from '@/Components/FormField.vue';
import { ref } from 'vue';
import { StarIcon } from '@heroicons/vue/24/outline';
import ImageUploader from '../ImageUploader.vue';

const props = defineProps<{
    form: any;
    statusOptions: ReadonlyArray<{ value: 'active' | 'inactive'; label: string; color?: string }>;
    prefix: string;
    avatarLabel?: string;
    avatarUrl?: string | null;
}>();

const ratingHover = ref<number | null>(null);

const setRating = (value: number) => {
    props.form.rating = value;
};

const getStarClass = (index: number, rating: number | null, hover: number | null) => {
    const value = hover !== null ? hover : rating;
    if (value !== null && index <= value) {
        return 'text-yellow-400';
    }
    return 'text-gray-300';
};

const getRatingText = (rating: number | null) => {
    if (!rating) return '';
    const texts: Record<number, string> = {
        1: 'Very Poor',
        2: 'Poor',
        3: 'Average',
        4: 'Good',
        5: 'Excellent'
    };
    return texts[rating];
};
</script>

<template>
    <div class="space-y-9">
        <!-- Basic Information Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h3>
                <div class="rounded-lg bg-gray-50 p-4">
                    <!-- Avatar Section -->
                    <FormField :label="avatarLabel ?? 'Avatar'" :name="`${prefix}_avatar_path`"
                        :error="props.form.errors.avatar_path">
        
                        <div class="space-y-4">
                            <!-- Current Avatar Preview -->
                            <div v-if="typeof props.form.avatar_path === 'string' && props.form.avatar_path"
                                class="text-center">
                                <div class="relative inline-block">
                                    <img :src="props.form.avatar_path.startsWith('http') ? props.form.avatar_path : `/` + props.form.avatar_path"
                                        alt="Current avatar"
                                        class="mx-auto h-24 w-24 rounded-xl object-cover ring-2 ring-indigo-400 ring-offset-2 shadow-lg" />
                                    <div
                                        class="absolute inset-0 flex items-center justify-center rounded-xl bg-black/50 opacity-0 transition-opacity hover:opacity-100">
                                        <span class="text-xs text-white">Current</span>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Current avatar (upload new to replace)</p>
                                <button type="button" @click="props.form.avatar_path = null"
                                    class="text-xs text-red-600 hover:text-red-800 underline">Remove</button>
                            </div>
        
                            <!-- Image Uploader -->
                            <ImageUploader v-if="!props.form.avatar_path || typeof props.form.avatar_path !== 'string'"
                                v-model="props.form.avatar_path" folder="testimonials" />
                        </div>
        
                        <!-- <ImageUploader v-model="props.form.avatar_path" folder="testimonials" /> -->
                    </FormField>
                </div>

                <!-- Rating Section -->
                <div class="rounded-lg bg-gray-50 p-4">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Rating</h3>
                    <FormField label="Rating (1-5)" :name="`${prefix}_rating`" :error="props.form.errors.rating">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1">
                                <button v-for="i in 5" :key="i" type="button" @click="setRating(i)"
                                    @mouseenter="ratingHover = i" @mouseleave="ratingHover = null"
                                    class="focus:outline-none transition-transform hover:scale-110">
                                    <StarIcon :class="[
                                        'h-8 w-8 transition-all duration-150',
                                        getStarClass(i, props.form.rating, ratingHover),
                                        props.form.rating && i <= props.form.rating ? 'fill-current' : 'fill-none'
                                    ]" />
                                </button>
                            </div>
                            <span v-if="props.form.rating" class="ml-2 text-sm font-medium text-gray-600">
                                {{ getRatingText(props.form.rating) }}
                            </span>
                            <span v-else class="ml-2 text-sm text-gray-400">
                                Not rated
                            </span>
                        </div>
                        <input :id="`${prefix}_rating`" v-model="props.form.rating" type="hidden" />
                    </FormField>
                </div>

                <!-- Quote Section -->
                <div class="rounded-lg bg-gray-50 p-4">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Testimonial Quote</h3>
                    <FormField label="Quote" :name="`${prefix}_quote`" :error="props.form.errors.quote" required>
                        <textarea :id="`${prefix}_quote`" v-model="props.form.quote" rows="4"
                            placeholder="What did the client say about your work? Write their testimonial here..."
                            class="input-field" />
                        <div class="mt-2 flex justify-between text-xs text-gray-500">
                            <span>Support markdown formatting</span>
                            <span>{{ props.form.quote?.length || 0 }} characters</span>
                        </div>
                    </FormField>
                </div>

            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4 md:grid-cols-1">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <FormField label="Client name" :name="`${prefix}_client_name`" :error="props.form.errors.client_name"
                            required>
                            <input :id="`${prefix}_client_name`" v-model="props.form.client_name" type="text"
                                placeholder="John Doe" class="input-field" autocomplete="off" />
                        </FormField>
    
                        <FormField label="Client role" :name="`${prefix}_client_role`" :error="props.form.errors.client_role">
                            <input :id="`${prefix}_client_role`" v-model="props.form.client_role" type="text"
                                placeholder="CEO, Founder, Director" class="input-field" autocomplete="off" />
                        </FormField>
    
                        <FormField label="Company" :name="`${prefix}_company`" :error="props.form.errors.company">
                            <input :id="`${prefix}_company`" v-model="props.form.company" type="text" placeholder="Company Name"
                                class="input-field" autocomplete="off" />
                        </FormField>
    
                        <FormField label="Sort order" :name="`${prefix}_sort_order`" :error="props.form.errors.sort_order">
                            <input :id="`${prefix}_sort_order`" type="number" min="0" placeholder="0" class="input-field"
                                v-model.number="props.form.sort_order" />
                        </FormField>
                    </div>

                    <!-- Status Section -->
                    <div class="rounded-lg bg-gray-50 p-4">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Publication Status</h3>
                        <FormField label="Status" :name="`${prefix}_status`" :error="props.form.errors.status">
                            <div class="flex flex-wrap gap-4">
                                <label v-for="option in statusOptions" :key="option.value"
                                    class="relative flex cursor-pointer items-center gap-3 rounded-lg border-2 p-3 transition-all"
                                    :class="[
                                        props.form.status === option.value
                                            ? 'border-indigo-400 bg-indigo-50'
                                            : 'border-gray-200 bg-white hover:border-gray-300'
                                    ]">
                                    <input type="radio" :value="option.value" v-model="props.form.status"
                                        class="h-4 w-4 text-indigo-400 focus:ring-indigo-500" />
                                    <div>
                                        <span class="font-medium text-gray-900">{{ option.label }}</span>
                                        <p class="text-xs text-gray-500">
                                            {{ option.value === 'active' ? 'Visible on the website' : 'Hidden from public view' }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </FormField>
                    </div>
                </div>
            </div>


            
        </div>

        

        
    </div>
</template>

<style scoped>
button {
    transition: all 0.2s ease;
}

@keyframes starPop {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.2);
    }

    100% {
        transform: scale(1);
    }
}

button:active svg {
    animation: starPop 0.2s ease;
}
</style>
