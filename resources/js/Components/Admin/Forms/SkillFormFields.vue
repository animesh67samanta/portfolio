<script setup lang="ts">
import { 
    CodeBracketIcon, 
    ArrowTrendingUpIcon, 
    TagIcon,
    AdjustmentsHorizontalIcon,
    PhotoIcon
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';
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
const generateSlug = (name: string) => {
    return name
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
};

// Watch for name changes to auto-generate slug (only if slug is empty)
const onNameInput = (event: Event) => {
    const name = (event.target as HTMLInputElement).value;

    if (!props.form.slug || props.form.slug === generateSlug(props.form.name)) {
        props.form.slug = generateSlug(name);
    }
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
                                @input="onNameInput"
                            />
                        </FormField>

                        <FormField label="Slug" :name="`${prefix}_slug`" :error="form.errors.slug">
                            <input
                                :id="`${prefix}_slug`"
                                v-model="form.slug"
                                type="text"
                                placeholder="auto-generated-from-name"
                                class="input-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                autocomplete="off"
                            />
                            <p class="mt-1 text-xs text-gray-500">URL-friendly version of the skill name</p>
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
                                    <img 
:src="getImageUrl(form.icon) || ''" 

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
</style>