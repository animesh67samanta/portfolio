<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import FormField from '@/Components/FormField.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ImageUploader from '@/Components/Admin/ImageUploader.vue';
import { PlusIcon, MapPinIcon, UserIcon } from '@heroicons/vue/24/outline';

type About = {
    id: number;
    name: string;
    email: string;
    mobile: string;
    current_address: string;
    permanent_address: string;
    heading: string;
    content: string;
    photo_path: string;
    photo_path_url?: string;
    resume_url: string | null;
    status: 'draft' | 'published' | 'archived';
};

const props = defineProps<{
    abouts: {
        data: About[];
    };
}>();

const statusOptions = [
    { value: 'draft', label: 'Draft', color: 'bg-gray-100 text-gray-800' },
    { value: 'published', label: 'Published', color: 'bg-green-100 text-green-800' },
    { value: 'archived', label: 'Archived', color: 'bg-yellow-100 text-yellow-800' },
] as const;

const currentAbout = computed<About | null>(() => props.abouts.data[0] ?? null);

const form = useForm<{
    name: string;
    email: string;
    mobile: string;
    current_address: string;
    permanent_address: string;
    heading: string;
    content: string;
    photo_path: File | null;
    resume_url: string;
    status: 'draft' | 'published' | 'archived';
}>({
    name: currentAbout.value?.name ?? '',
    email: currentAbout.value?.email ?? '',
    mobile: currentAbout.value?.mobile ?? '',
    current_address: currentAbout.value?.current_address ?? '',
    permanent_address: currentAbout.value?.permanent_address ?? '',
    heading: currentAbout.value?.heading ?? '',
    content: currentAbout.value?.content ?? '',
    photo_path: null,
    resume_url: currentAbout.value?.resume_url ?? '',
    status: currentAbout.value?.status ?? 'draft',
});

// Watch for currentAbout changes (post-submit refresh)
watch(currentAbout, (newAbout) => {
    if (newAbout) {
        form.name = newAbout.name;
        form.email = newAbout.email;
        form.mobile = newAbout.mobile;
        form.current_address = newAbout.current_address;
        form.permanent_address = newAbout.permanent_address;
        form.heading = newAbout.heading;
        form.content = newAbout.content;
        form.resume_url = newAbout.resume_url ?? '';
        form.status = newAbout.status ?? 'draft';
    }
}, { immediate: true });

const submit = () => {
    if (currentAbout.value) {
        form.put(route('admin.abouts.update', currentAbout.value.id), {
            method: 'patch',
            forceFormData: true,
            onSuccess: () => {
                form.photo_path = null;
            },
        });
        return;
    }
    form.post(route('admin.abouts.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.photo_path = null;
        },
    });
};

const getStatusColor = (status: string) => {
    return statusOptions.find(opt => opt.value === status)?.color || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="About" />

    <AdminLayout page-title="About">
        <SectionCard>
            <!-- Header with Status Badge -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ currentAbout ? 'Update About Section' : 'Create About Section' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ currentAbout ? 'Update your profile summary and personal information.' : 'Add your profile summary and personal information.' }}
                    </p>
                </div>
                <div v-if="currentAbout" class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Status:</span>
                    <span :class="['inline-flex rounded-full px-3 py-1 text-xs font-semibold', getStatusColor(form.status)]">
                        {{ statusOptions.find(opt => opt.value === form.status)?.label }}
                    </span>
                </div>
            </div>
            
            <form class="mt-6" @submit.prevent="submit">
                <div class="space-y-6">
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <!-- Left Column - Main Content -->
                        <div class="space-y-6 lg:col-span-2">
                            <!-- Personal Information Section -->
                            <div class="rounded-lg bg-gradient-to-br from-gray-50 to-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="rounded-lg bg-indigo-100 p-2">
                                        <UserIcon class="h-5 w-5 text-indigo-600" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <FormField label="Full Name" name="name" :error="form.errors.name" required>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            placeholder="John Doe"
                                            class="input-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            autocomplete="off"
                                        />
                                    </FormField>

                                    <FormField label="Email Address" name="email" :error="form.errors.email" required>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                
                                            </div>
                                            <input
                                                id="email"
                                                v-model="form.email"
                                                type="email"
                                                placeholder="john@example.com"
                                                class="input-field pl-10 transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                                autocomplete="off"
                                            />
                                        </div>
                                    </FormField>

                                    <FormField label="Mobile Number" name="mobile" :error="form.errors.mobile" required>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            
                                            </div>
                                            <input
                                                id="mobile"
                                                v-model="form.mobile"
                                                type="tel"
                                                placeholder="+1 234 567 8900"
                                                class="input-field pl-10 transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                                autocomplete="off"
                                            />
                                        </div>
                                    </FormField>

                                    <FormField label="Resume URL" name="resume_url" :error="form.errors.resume_url">
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                
                                            </div>
                                            <input
                                                id="resume_url"
                                                v-model="form.resume_url"
                                                type="url"
                                                placeholder="https://example.com/resume.pdf"
                                                class="input-field pl-10 transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                                autocomplete="off"
                                            />
                                        </div>
                                    </FormField>
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="rounded-lg bg-gradient-to-br from-gray-50 to-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="rounded-lg bg-indigo-100 p-2">
                                        <MapPinIcon class="h-5 w-5 text-indigo-600" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Address Information</h3>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <FormField label="Current Address" name="current_address" :error="form.errors.current_address" required>
                                        <textarea
                                            id="current_address"
                                            v-model="form.current_address"
                                            rows="3"
                                            placeholder="Your current address"
                                            class="textarea-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                        />
                                    </FormField>

                                    <FormField label="Permanent Address" name="permanent_address" :error="form.errors.permanent_address" required>
                                        <textarea
                                            id="permanent_address"
                                            v-model="form.permanent_address"
                                            rows="3"
                                            placeholder="Your permanent address"
                                            class="textarea-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                        />
                                    </FormField>
                                </div>
                            </div>

                            <!-- About Content Section -->
                            <div class="rounded-lg bg-gradient-to-br from-gray-50 to-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="rounded-lg bg-indigo-100 p-2">
                                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">About Content</h3>
                                </div>
                                <div class="space-y-4">
                                    <FormField label="Heading" name="heading" :error="form.errors.heading" required>
                                        <input
                                            id="heading"
                                            v-model="form.heading"
                                            type="text"
                                            placeholder="e.g., About Me, Who I Am, etc."
                                            class="input-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                            autocomplete="off"
                                        />
                                    </FormField>

                                    <FormField label="Content" name="content" :error="form.errors.content" required>
                                        <textarea
                                            id="content"
                                            v-model="form.content"
                                            rows="8"
                                            placeholder="Tell your story, background, experience, and what makes you unique..."
                                            class="textarea-field transition-all duration-200 focus:ring-2 focus:ring-indigo-500"
                                        />
                                        <div class="mt-2 text-right text-xs text-gray-500">
                                            <span>{{ form.content.length }} characters</span>
                                        </div>
                                    </FormField>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <!-- Photo Upload Section -->
                            <div class="rounded-lg bg-gradient-to-br from-gray-50 to-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="rounded-lg bg-indigo-100 p-2">
                                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
                                </div>
                                <FormField
                                    :label="currentAbout ? 'Replace Photo' : 'Upload Photo'"
                                    name="photo_path"
                                    :error="form.errors.photo_path"
                                    help="PNG, JPG, WebP up to 2MB. Recommended: 400x400px square"
                                >
                                    <div class="space-y-4">
                                        <!-- Existing Photo Preview -->
                                        <div v-if="currentAbout?.photo_path" class="text-center">
                                            <div class="relative inline-block">
                                                <img 
                                                    :src="currentAbout.photo_path_url || (currentAbout.photo_path.startsWith('http') ? currentAbout.photo_path : `/${currentAbout.photo_path}`)"
                                                    alt="Current profile photo"
                                                    class="mx-auto h-28 w-28 rounded-full object-cover ring-4 ring-indigo-600 ring-offset-2 shadow-lg"
                                                />
                                                <div class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 transition-opacity hover:opacity-100">
                                                    <span class="text-xs text-white">Current</span>
                                                </div>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-500">Current photo - upload new to replace</p>
                                        </div>
                                        
                                        <!-- Image Uploader -->
                                        <div class="max-w-sm mx-auto w-full">
                                            <ImageUploader 
                                                v-model="form.photo_path" 
                                                folder="abouts"
                                            />
                                        </div>
                                    </div>
                                </FormField>
                            </div>
    
                            <!-- Status Selection -->
                            <div class="rounded-lg bg-gradient-to-br from-gray-50 to-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="rounded-lg bg-indigo-100 p-2">
                                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Publication Status</h3>
                                </div>
                                <FormField label="Status" name="status" :error="form.errors.status">
                                    <div class="flex flex-wrap gap-4">
                                        <label
                                            v-for="option in statusOptions"
                                            :key="option.value"
                                            class="relative flex cursor-pointer items-center gap-3 rounded-lg border-2 p-3 transition-all duration-200 hover:shadow-md"
                                            :class="[
                                                form.status === option.value
                                                    ? 'border-indigo-600 bg-indigo-50 shadow-md'
                                                    : 'border-gray-200 bg-white hover:border-gray-300'
                                            ]"
                                        >
                                            <input
                                                type="radio"
                                                :value="option.value"
                                                v-model="form.status"
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                            />
                                            <div>
                                                <span class="font-medium text-gray-900">{{ option.label }}</span>
                                                <p class="text-xs text-gray-500">
                                                    {{ option.value === 'draft' ? 'Not visible to public' : '' }}
                                                    {{ option.value === 'published' ? 'Visible to everyone' : '' }}
                                                    {{ option.value === 'archived' ? 'Hidden from public' : '' }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                </FormField>
                            </div>
                        </div>
                    </div>

                </div>
                            
                
                <!-- Form Actions -->
                <div class="mt-6 flex justify-end gap-4">
                    <button
                        type="button"
                        @click="$inertia.visit(route('admin.dashboard'))"
                        class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="form.processing"
                    >
                        <svg class="h-4 w-4 text-gray-500 transition-all duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Dashboard</span>
                    </button>
                  
                    <LoadingButton 
                        :loading="form.processing" 
                        :disabled="form.processing"
                        class="group rounded-xl bg-indigo-600/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                    >
                        <span class="flex items-center">
                            <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                            <span class="relative">
                                <span>{{ currentAbout ? 'Update About' : 'Create About' }}</span>
                                <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-white transition-all duration-300 group-hover:w-full"></span>
                            </span>
                        </span>
                    </LoadingButton>
                   
                </div>
            </form>
              
        </SectionCard>
    </AdminLayout>
</template>

<style scoped>
/* Smooth transitions for form fields */
.input-field, .textarea-field {
    transition: all 0.2s ease-in-out;
}

.input-field:focus, .textarea-field:focus {
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

/* Hover effect for sections */
.rounded-lg {
    transition: all 0.3s ease-in-out;
}

/* Radio button animation */
label:has(input[type="radio"]) {
    transition: all 0.2s ease;
}

/* Button active effect */
button:active:not(:disabled) {
    transform: scale(0.98);
}
</style>