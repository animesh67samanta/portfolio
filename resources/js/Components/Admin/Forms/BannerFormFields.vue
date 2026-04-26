<script setup lang="ts">
import FormField from '@/Components/FormField.vue';
import ImageUploader from '../ImageUploader.vue';
import { ref, computed, watch } from 'vue';
import { 
    PhotoIcon, 
    CalendarIcon, 
    LinkIcon,
    XMarkIcon,
    EyeIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline';

const props = defineProps<{
    form: any;
    statusOptions: ReadonlyArray<{ value: 'draft' | 'published' | 'archived'; label: string; color?: string; icon?: string }>;
    prefix: string;
}>();



// Character count
const titleLength = computed(() => props.form.title?.length || 0);
const subtitleLength = computed(() => props.form.subtitle?.length || 0);

// Get status color
const getStatusColor = (status: string) => {
    const colors: Record<string, string> = {
        draft: 'border-gray-300 bg-gray-50',
        published: 'border-green-500 bg-green-50',
        archived: 'border-yellow-500 bg-yellow-50'
    };
    return colors[status] || 'border-gray-300 bg-gray-50';
};


</script>

<template>
    <div class="space-y-6">
        <!-- Main Form Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column - Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Title & Description Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <DocumentTextIcon class="h-5 w-5 text-indigo-400" />
                        <h3 class="text-lg font-semibold text-gray-900">Banner Content</h3>
                    </div>

                    <!-- Image Section -->
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <PhotoIcon class="h-5 w-5 text-indigo-400" />
                            <h3 class="text-lg font-semibold text-gray-900">Banner Image</h3>
                        </div>
                        
                        <FormField 
                            :label="`${prefix === 'create' ? 'Upload' : 'Replace'} Image`" 
                            :name="`${prefix}_image_path`" 
                            :error="form.errors.image_path"
                            :required="false"
                        >
                            <div class="space-y-4">
                                <!-- Existing Image -->
                                <div v-if="typeof form.image_path === 'string' && form.image_path" class="text-center mb-4 p-4 bg-blue-50 rounded-lg">
                                    <img 
                                        :src="form.image_path.startsWith('http') ? form.image_path : `/${form.image_path}`" 
                                        :alt="form.title || 'Current banner'"
                                        class="max-w-full h-32 mx-auto rounded-lg object-cover shadow-md"
                                    />
                                    <button type="button" @click="form.image_path = null" class="text-xs text-red-600 hover:text-red-800 underline mt-2">Remove</button>
                                </div>
                                
                                <!-- Image Uploader (only if no current image) -->
                                <ImageUploader 
                                    v-if="!form.image_path || typeof form.image_path !== 'string'"
                                    v-model="form.image_path"
                                    folder="banners"
                                    class="max-w-md mx-auto"
                                />
                                <p v-else class="text-xs text-gray-500 mt-2">No new upload needed</p>
                                
                                <!-- Image Guidelines -->
                                <div class="rounded-lg bg-gray-50 p-4">
                                    <p class="text-xs font-medium text-gray-900 mb-3">Image Guidelines:</p>
                                    <ul class="space-y-1 text-xs text-gray-600">
                                        <li class="flex items-center gap-2">
                                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                            Recommended: 1200 × 400 pixels (3:1 ratio)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                            Formats: PNG, JPG, WebP • Max: 2MB
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </FormField>
                    </div>
                    
                    <div class="space-y-4 mt-2">
                        <FormField 
                            label="Title" 
                            :name="`${prefix}_title`" 
                            :error="form.errors.title" 
                            required
                        >
                            <input
                                :id="`${prefix}_title`"
                                v-model="form.title"
                                type="text"
                                placeholder="Enter an engaging title..."
                                class="input-field"
                                autocomplete="off"
                            />
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Main headline for the banner</span>
                                <span>{{ titleLength }} / 100 characters</span>
                            </div>
                        </FormField>

                        <FormField 
                            label="Subtitle" 
                            :name="`${prefix}_subtitle`" 
                            :error="form.errors.subtitle"
                        >
                            <textarea
                                :id="`${prefix}_subtitle`"
                                v-model="form.subtitle"
                                rows="2"
                                placeholder="A brief description or supporting text..."
                                class="textarea-field"
                            />
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Optional supporting text</span>
                                <span>{{ subtitleLength }} / 200 characters</span>
                            </div>
                        </FormField>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <LinkIcon class="h-5 w-5 text-indigo-400" />
                        <h3 class="text-lg font-semibold text-gray-900">Call to Action</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <FormField 
                            label="Button Text" 
                            :name="`${prefix}_cta_text`" 
                            :error="form.errors.cta_text"
                        >
                            <input
                                :id="`${prefix}_cta_text`"
                                v-model="form.cta_text"
                                type="text"
                                placeholder="Shop Now, Learn More, etc."
                                class="input-field"
                                autocomplete="off"
                            />
                        </FormField>

                        <FormField 
                            label="Button URL" 
                            :name="`${prefix}_cta_url`" 
                            :error="form.errors.cta_url"
                        >
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                </div>
                                <input
                                    :id="`${prefix}_cta_url`"
                                    v-model="form.cta_url"
                                    type="url"
                                    placeholder="https://example.com/page"
                                    class="input-field"
                                    autocomplete="off"
                                />
                            </div>
                        </FormField>
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings & Image -->
            <div class="space-y-6">
                <!-- Status Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <EyeIcon class="h-5 w-5 text-indigo-400" />
                        <h3 class="text-lg font-semibold text-gray-900">Publication Settings</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <FormField 
                            label="Status" 
                            :name="`${prefix}_status`" 
                            :error="form.errors.status"
                        >
                            <div class="space-y-2">
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
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-900">{{ option.label }}</span>
                                        <p class="text-xs text-gray-500">
                                            {{ option.value === 'draft' ? 'Not visible to public' : '' }}
                                            {{ option.value === 'published' ? 'Visible to everyone' : '' }}
                                            {{ option.value === 'archived' ? 'Hidden from public view' : '' }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </FormField>

                        <FormField 
                            label="Sort Order" 
                            :name="`${prefix}_sort_order`" 
                            :error="form.errors.sort_order"
                        >
                            <input
                                :id="`${prefix}_sort_order`"
                                v-model.number="form.sort_order"
                                type="number"
                                min="0"
                                placeholder="0"
                                class="input-field"
                            />
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        </FormField>

                        <FormField 
                            label="Publish Date" 
                            :name="`${prefix}_published_at`" 
                            :error="form.errors.published_at"
                        >
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                 
                                </div>
                                <input
                                    :id="`${prefix}_published_at`"
                                    v-model="form.published_at"
                                    type="datetime-local"
                                    class="input-field"
                                />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Set a future date to schedule publication</p>
                        </FormField>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Smooth transitions */
input, textarea, select {
    transition: all 0.2s ease;
}

/* Custom file input styling */
::file-selector-button {
    transition: all 0.2s ease;
    cursor: pointer;
}

/* Focus styles */
input:focus, textarea:focus, select:focus {
    outline: none;
    ring: 2px solid #4f46e5;
}

/* Group hover effects */
.group {
    transition: all 0.3s ease;
}
</style>