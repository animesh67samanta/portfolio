<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/24/outline';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, watch, onMounted } from 'vue';
import AboutFormFields from '@/Components/Admin/Forms/AboutFormFields.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

type ExperienceItem = {
  title: string;
  company: string;
  duration: string;
  description: string;
  company_url: string;
  location: string;
};

type EducationItem = {
  degree: string;
  institution: string;
  year: string;
  description: string;
};

type StatusOption = {
  value: string;
  label: string;
  color: string;
};

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
  experiences?: ExperienceItem[];
  educations?: EducationItem[];
};

const props = defineProps<{
    abouts: {
        data: About[];
    };
}>();

const statusOptions: StatusOption[] = [
    { value: 'draft', label: 'Draft', color: 'bg-gray-100 text-gray-800' },
    { value: 'published', label: 'Published', color: 'bg-green-100 text-green-800' },
    { value: 'archived', label: 'Archived', color: 'bg-yellow-100 text-yellow-800' },
];

const currentAbout = computed<About | null>(() => props.abouts.data[0] ?? null);

// Helper function to initialize with empty array or existing data
const initializeExperiences = (experiences?: ExperienceItem[]): ExperienceItem[] => {
    if (experiences && experiences.length > 0) {
        return experiences;
    }

    return [{ title: '', company: '', duration: '', description: '', company_url: '', location: '' }];
};

const initializeEducations = (educations?: EducationItem[]): EducationItem[] => {
    if (educations && educations.length > 0) {
        return educations;
    }

    return [{ degree: '', institution: '', year: '', description: '' }];
};

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
    experiences: ExperienceItem[];
    educations: EducationItem[];
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
    experiences: initializeExperiences(currentAbout.value?.experiences),
    educations: initializeEducations(currentAbout.value?.educations),
});

// Array manipulation methods
const addExperience = () => {
    form.experiences.push({ title: '', company: '', duration: '', description: '', company_url: '', location: '' });
};

const removeExperience = (index: number) => {
    form.experiences.splice(index, 1);
};

const addEducation = () => {
    form.educations.push({ degree: '', institution: '', year: '', description: '' });
};

const removeEducation = (index: number) => {
    form.educations.splice(index, 1);
};

const ensureMinimumEntries = () => {
    if (form.experiences.length === 0) {
        form.experiences.push({ title: '', company: '', duration: '', description: '', company_url: '', location: '' });
    }

    if (form.educations.length === 0) {
        form.educations.push({ degree: '', institution: '', year: '', description: '' });
    }
};

// Watch for currentAbout changes
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
        
        if (newAbout.experiences && newAbout.experiences.length > 0) {
            form.experiences = [...newAbout.experiences];
        }

        if (newAbout.educations && newAbout.educations.length > 0) {
            form.educations = [...newAbout.educations];
        }

        ensureMinimumEntries();
    }
}, { deep: true });

onMounted(() => {
    ensureMinimumEntries();
});

const submit = () => {
    // console.log('Submitting form data:', form.data());
    
    if (currentAbout.value) {
        form.put(route('admin.abouts.update', currentAbout.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                form.photo_path = null;
            },
            onError: (errors) => {
                console.error('Validation errors:', errors);
            },
        });

        return;
    }
    
    form.post(route('admin.abouts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.photo_path = null;
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
    });
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
                    <span :class="['inline-flex rounded-full px-3 py-1 text-xs font-semibold', statusOptions.find((opt) => opt.value === form.status)?.color || 'bg-gray-100 text-gray-800']">
                        {{ statusOptions.find((opt) => opt.value === form.status)?.label }}
                    </span>
                </div>
            </div>
            
            <!-- Display validation errors -->
            <div v-if="Object.keys(form.errors).length > 0" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                <ul class="list-disc list-inside text-red-600">
                    <li v-for="(error, field) in form.errors" :key="field">
                        <strong>{{ field }}:</strong> {{ error }}
                    </li>
                </ul>
            </div>
            
            <form class="mt-6" @submit.prevent="submit">
                <AboutFormFields 
                    :form="form" 
                    :about="currentAbout" 
                    :status-options="statusOptions"
                    :add-experience="addExperience"
                    :remove-experience="removeExperience"
                    :add-education="addEducation"
                    :remove-education="removeEducation"
                />
                            
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
                        class="group rounded-xl bg-indigo-500/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-700/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
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