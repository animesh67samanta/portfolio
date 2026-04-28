<script setup lang="ts">
import { 
    CodeBracketIcon, 
    ArrowTrendingUpIcon, 
    TagIcon,
    AdjustmentsHorizontalIcon,
    PhotoIcon,
    BarsArrowUpIcon
} from '@heroicons/vue/24/outline';
import { computed, watch } from 'vue';
import ImageUploader from '@/Components/Admin/ImageUploader.vue';
import FormField from '@/Components/FormField.vue';

const props = defineProps<{
    form: any;
    statusOptions: ReadonlyArray<{ value: 'active' | 'inactive'; label: string; color?: string }>;
    prefix: string;
}>();

const getImageUrl = (path: string | null) => {
    if (!path) {
        return '/uploads/No_Image_Available.jpg';
    }

    if (path.startsWith('http')) {
        return path;
    }

    if (path.startsWith('/storage')) {
        return path;
    }

    return `/${path.replace(/^\/?storage\/?/, '')}`;
};

// Auto-generate slug from name
// Improved slug generation function
const generateSlug = (text: string) => {
    if (!text) return '';
    
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
};

// Watch for name changes to auto-generate slug
watch(() => props.form.name, (newName) => {
    if (newName) {
        // Only auto-generate if slug is empty OR slug matches the previous name's slug
        if (!props.form.slug || props.form.slug === generateSlug(props.form.name)) {
            props.form.slug = generateSlug(newName);
        }
    }
}, { immediate: true });

// Manual slug edit handler
const onSlugInput = (event: Event) => {
    const slug = (event.target as HTMLInputElement).value;
    props.form.slug = generateSlug(slug);
};

const proficiencyColor = computed(() => {
    const value = props.form.proficiency || 0;

    if (value >= 80) {
        return 'bg-green-500';
    }

    if (value >= 60) {
        return 'bg-blue-500';
    }

    if (value >= 40) {
        return 'bg-yellow-500';
    }

    return 'bg-gray-500';
});
</script>

<template>
    <div class="space-y-6">
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Information Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="rounded-lg bg-indigo-100 p-2">
                            <CodeBracketIcon class="h-5 w-5 text-indigo-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <FormField label="Skill Name" :name="`${prefix}_name`" :error="form.errors.name" required>
                            <input
                                :id="`${prefix}_name`"
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Laravel, React, Python"
                                class="input-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                autocomplete="off"
                                @input="onSlugInput($event)"
                            />
                        </FormField>

                        <FormField label="Slug" :name="`${prefix}_slug`" :error="form.errors.slug">
                            <div class="space-y-2">
                                <div class="flex gap-2">
                                    <input
                                        :id="`${prefix}_slug`"
                                        v-model="form.slug"
                                        type="text"
                                        placeholder="auto-generated-from-name"
                                        class="input-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                        autocomplete="off"
                                        @input="onSlugInput"
                                    />
                                    <button 
                                        type="button"
                                        @click="form.slug = generateSlug(form.name)"
                                        class="px-3 py-2 text-sm text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors"
                                        title="Generate slug from name"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">
                                    URL-friendly version. Auto-generated from name. 
                                    <span v-if="form.name" class="text-indigo-600">
                                        Preview: {{ generateSlug(form.name) }}
                                    </span>
                                </p>
                            </div>
                        </FormField>
                    </div>
                </div>

                <!-- Proficiency Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="rounded-lg bg-indigo-100 p-2">
                            <ArrowTrendingUpIcon class="h-5 w-5 text-indigo-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Proficiency Level</h3>
                    </div>
                    
                    <FormField label="Proficiency (%)" :name="`${prefix}_proficiency`" :error="form.errors.proficiency">
                        <div class="space-y-3">
                            <input
                                :id="`${prefix}_proficiency`"
                                :value="form.proficiency ?? ''"
                                type="range"
                                min="0"
                                max="100"
                                step="5"
                                class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200"
                                :class="proficiencyColor"
                                @input="form.proficiency = Number(($event.target as HTMLInputElement).value)"
                            />
                            <div class="flex items-center justify-between">
                                <input
                                    type="number"
                                    :value="form.proficiency ?? ''"
                                    min="0"
                                    max="100"
                                    class="input-field w-24 text-center"
                                    @input="form.proficiency = Number(($event.target as HTMLInputElement).value)"
                                />
                                <span class="text-sm font-medium ml-1 text-gray-600">
                                    {{ form.proficiency || 0 }}%
                                </span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Beginner</span>
                                <span>Intermediate</span>
                                <span>Advanced</span>
                                <span>Expert</span>
                            </div>
                        </div>
                    </FormField>
                </div>
                <!-- Sort Order & Settings Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="rounded-lg bg-indigo-100 p-2">
                            <BarsArrowUpIcon class="h-5 w-5 text-indigo-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Display Order</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <FormField label="Sort Order" :name="`${prefix}_sort_order`" :error="form.errors.sort_order">
                            <div class="flex items-center gap-3">
                                <input
                                    :id="`${prefix}_sort_order`"
                                    v-model="form.sort_order"
                                    type="number"
                                    min="0"
                                    step="1"
                                    placeholder="e.g., 1, 2, 3"
                                    class="input-field w-full transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                />
                                <div class="flex gap-1">
                                    <button 
                                        type="button"
                                        @click="form.sort_order = (Number(form.sort_order) || 0) - 1"
                                        class="p-2 text-gray-500 hover:text-indigo-600 transition-colors rounded-lg hover:bg-gray-100"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button"
                                        @click="form.sort_order = (Number(form.sort_order) || 0) + 1"
                                        class="p-2 text-gray-500 hover:text-indigo-600 transition-colors rounded-lg hover:bg-gray-100"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Lower numbers appear first. Skills are sorted in ascending order (0, 1, 2...)
                            </p>
                        </FormField>
                    </div>
                </div>
            </div>
            

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Icon Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="rounded-lg bg-indigo-100 p-2">
                            <PhotoIcon class="h-5 w-5 text-indigo-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Skill Icon</h3>
                    </div>
                    
                    <FormField label="Icon" :name="`${prefix}_icon`" :error="form.errors.icon">
                        <div class="space-y-4">
                            <!-- Current Icon Preview -->
                            <div v-if="typeof form.icon === 'string' && form.icon" class="text-center">
                                <div class="relative inline-block">
                                    <img :src="getImageUrl(form.icon) || ''" 
                                        :alt="form.name || 'Skill icon'"
                                        class="mx-auto h-24 w-24 rounded-xl object-cover ring-2 ring-indigo-400 ring-offset-2 shadow-lg"
                                    />
                                    <div class="absolute inset-0 flex items-center justify-center rounded-xl bg-black/50 opacity-0 transition-opacity hover:opacity-100">
                                        <span class="text-xs text-white">Current</span>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Current icon (upload new to replace)</p>
                                <button type="button" @click="form.icon = null" class="text-xs text-red-600 hover:text-red-800 underline">Remove</button>
                            </div>
                            
                            <!-- Image Uploader -->
                            <ImageUploader 
                                v-if="!form.icon || typeof form.icon !== 'string'"
                                v-model="form.icon" 
                                folder="skills/icons"
                                class="w-full"
                            />
                        </div>
                    </FormField>
                </div>

                <!-- Settings Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="rounded-lg bg-indigo-100 p-2">
                            <AdjustmentsHorizontalIcon class="h-5 w-5 text-indigo-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <FormField label="Status" :name="`${prefix}_status`" :error="form.errors.status">
                            <div class="flex flex-wrap gap-4">
                                <label
                                    v-for="option in statusOptions"
                                    :key="option.value"
                                    class="flex cursor-pointer items-center gap-3 rounded-lg border-2 p-3 transition-all"
                                    :class="[
                                        form.status === option.value
                                            ? 'border-indigo-400 bg-indigo-50'
                                            : 'border-gray-200 bg-white hover:border-gray-300'
                                    ]"
                                >
                                    <input
                                        type="radio"
                                        :value="option.value"
                                        v-model="form.status"
                                        class="h-4 w-4 text-indigo-400 focus:ring-indigo-500"
                                    />
                                    <div>
                                        <span class="font-medium text-gray-900">{{ option.label }}</span>
                                        <p class="text-xs text-gray-500">
                                            {{ option.value === 'active' ? 'Visible on portfolio' : 'Hidden from public' }}
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
/* Range input styling */
input[type="range"] {
    -webkit-appearance: none;
}

input[type="range"]:focus {
    outline: none;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #4f46e5;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

/* Number input spinner hide for Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>