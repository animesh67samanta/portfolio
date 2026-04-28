<script setup lang="ts">
import { 
  PhotoIcon, 
  DocumentTextIcon, 
  CalendarIcon,
  LinkIcon,
  EyeIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline';
import { ref, computed, watch } from 'vue';
import FormField from '@/Components/FormField.vue';
import ImageUploader from '../ImageUploader.vue';

const props = defineProps<{
    form: any;
    statusOptions: ReadonlyArray<{ value: 'draft' | 'published' | 'archived'; label: string; color?: string; icon?: string }>;
    prefix: string;
    avatarLabel?: string;
    avatarUrl?: string;
}>();



// Auto-generate slug from title
const generateSlug = (title: string) => {
    return title
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
};

// Watch title to auto-generate slug (only if slug is empty or matches previous title)
watch(() => props.form.title, (newTitle) => {
    if (!props.form.slug || props.form.slug === generateSlug(props.form.oldTitle)) {
        props.form.slug = generateSlug(newTitle);
    }

    props.form.oldTitle = newTitle;
});


// Character count for excerpt
const excerptLength = computed(() => props.form.excerpt?.length || 0);
const contentLength = computed(() => props.form.content?.length || 0);

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
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column - Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Title & Slug Section -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <DocumentTextIcon class="h-5 w-5 text-indigo-400" />
                        <h3 class="text-lg font-semibold text-gray-900">Blog Post Details</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <FormField label="Title" :name="`${prefix}_title`" :error="form.errors.title" required>
                            <input
                                :id="`${prefix}_title`"
                                v-model="form.title"
                                type="text"
                                placeholder="Enter an engaging title..."
                                class="input-field"
                                autocomplete="off"
                            />
                        </FormField>

                        <FormField label="Slug" :name="`${prefix}_slug`" :error="form.errors.slug">
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <!-- <LinkIcon class="h-4 w-4 text-gray-400" /> -->
                                </div>
                                <input
                                    :id="`${prefix}_slug`"
                                    v-model="form.slug"
                                    type="text"
                                    placeholder="auto-generated-from-title"
                                    class="input-field"
                                    autocomplete="off"
                                />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">URL-friendly version of the title</p>
                        </FormField>

                        <FormField label="Content" :name="`${prefix}_content`" :error="form.errors.content" required>
                            <textarea
                                :id="`${prefix}_content`"
                                v-model="form.content"
                                rows="12"
                                placeholder="Write your blog content here... (Markdown supported)"
                                class="font-mono textarea-field"
                            />
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Markdown formatting supported</span>
                                <span>{{ contentLength }} characters</span>
                            </div>
                        </FormField>
                    </div>
                </div>
                <!-- Cover Image Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <PhotoIcon class="h-5 w-5 text-indigo-400" />
                        <h3 class="text-lg font-semibold text-gray-900">Cover Image</h3>
                    </div>
                    
                    <FormField 
                            :label="`${prefix === 'create' ? 'Upload' : 'Replace'} Cover Image`" 
                            :name="`${prefix}_cover_image`" 
                            :error="form.errors.cover_image"
                            :required="false"
                        >
                            <div class="space-y-4">
                                <!-- Existing Image -->
                                <div v-if="typeof form.cover_image === 'string' && form.cover_image" class="text-center mb-4 p-4 bg-blue-50 rounded-lg">
                                    <img 
                                        :src="form.cover_image.startsWith('http') ? form.cover_image : `/${form.cover_image}`" 
                                        :alt="form.title || 'Current cover'"
                                        class="max-w-full h-32 mx-auto rounded-lg object-cover shadow-md"
                                    />
                                    <button type="button" @click="form.cover_image = null" class="text-xs text-red-600 hover:text-red-800 underline mt-2">Remove</button>
                                </div>
                                
                                <!-- Image Uploader (only if no current image) -->
                                <ImageUploader 
                                    v-if="!form.cover_image || typeof form.cover_image !== 'string'"
                                    v-model="form.cover_image"
                                    folder="blogs/cover"
                                    class="max-w-md mx-auto"
                                />
                                <p v-else class="text-xs text-gray-500 mt-2">No new upload needed</p>
                                
                                <!-- Image Guidelines -->
                                <div class="rounded-lg bg-gray-50 p-4">
                                    <p class="text-xs font-medium text-gray-900 mb-3">Image Guidelines:</p>
                                    <ul class="space-y-1 text-xs text-gray-600">
                                        <li class="flex items-center gap-2">
                                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                            Recommended: 1200 × 630 pixels
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                            Formats: PNG, JPG, WebP • Max: 5MB
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </FormField>
                </div>
            </div>

            <!-- Right Column - Meta & Settings -->
            <div class="space-y-6">
                <!-- Status Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <EyeIcon class="h-5 w-5 text-indigo-400" />
                        <h3 class="text-lg font-semibold text-gray-900">Publication Status</h3>
                    </div>
                    
                    <FormField label="Status" :name="`${prefix}_status`" :error="form.errors.status">
                        <div class="space-y-2 mb-1">
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
                    

                    <FormField label="Published at" :name="`${prefix}_published_at`" :error="form.errors.published_at">
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
                        <p class="mt-1 mb-1 text-xs text-gray-500">Set a future date to schedule publication</p>
                    </FormField>

                    <FormField label="Excerpt" :name="`${prefix}_excerpt`" :error="form.errors.excerpt">
                            <textarea
                                :id="`${prefix}_excerpt`"
                                v-model="form.excerpt"
                                rows="3"
                                placeholder="Write a brief summary of your blog post..."
                                class="textarea-field"
                            />
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Brief summary for blog listings</span>
                                <span>{{ excerptLength }} / 200 characters</span>
                            </div>
                        </FormField>
                </div>

                <!-- Featured Image Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <PhotoIcon class="h-5 w-5 text-indigo-400" />
                        <h3 class="text-lg font-semibold text-gray-900">Featured Image</h3>
                    </div>
                    
                    <FormField 
                            :label="`${prefix === 'create' ? 'Upload' : 'Replace'} Featured Image`" 
                            :name="`${prefix}_featured_image`" 
                            :error="form.errors.featured_image"
                            :required="false"
                        >
                            <div class="space-y-4">
                                <!-- Existing Image -->
                                <div v-if="typeof form.featured_image === 'string' && form.featured_image" class="text-center mb-4 p-4 bg-blue-50 rounded-lg">
                                    <img 
                                        :src="form.featured_image.startsWith('http') ? form.featured_image : `/${form.featured_image}`" 
                                        :alt="form.title || 'Current featured'"
                                        class="max-w-full h-32 mx-auto rounded-lg object-cover shadow-md"
                                    />
                                    <button type="button" @click="form.featured_image = null" class="text-xs text-red-600 hover:text-red-800 underline mt-2">Remove</button>
                                </div>
                                
                                <!-- Image Uploader (only if no current image) -->
                                <ImageUploader 
                                    v-if="!form.featured_image || typeof form.featured_image !== 'string'"
                                    v-model="form.featured_image"
                                    folder="blogs/featured"
                                    class="max-w-md mx-auto"
                                />
                                <p v-else class="text-xs text-gray-500 mt-2">No new upload needed</p>
                                
                                <!-- Image Guidelines -->
                                <div class="rounded-lg bg-gray-50 p-4">
                                    <p class="text-xs font-medium text-gray-900 mb-3">Image Guidelines:</p>
                                    <ul class="space-y-1 text-xs text-gray-600">
                                        <li class="flex items-center gap-2">
                                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                            Recommended: 1200 × 630 pixels
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                            Formats: PNG, JPG, WebP • Max: 5MB
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </FormField>
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
    /* outline: none; */
    ring: 2px solid #4f46e5;
}
</style>