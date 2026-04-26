<script setup lang="ts">
import { reactive, toRefs, watch, onMounted, nextTick } from 'vue';
import FormField from '@/Components/FormField.vue';
import ImageUploader from '@/Components/Admin/ImageUploader.vue';
import { UserIcon, MapPinIcon, BriefcaseIcon, AcademicCapIcon, TrashIcon, PlusIcon } from '@heroicons/vue/24/outline';

interface ExperienceItem {
  title: string;
  company: string;
  duration: string;
  description: string;
  company_url: string;
  location: string;
}

interface EducationItem {
  degree: string;
  institution: string;
  year: string;
  description: string;
}

interface StatusOption {
  value: string;
  label: string;
  color: string;
}

interface FormData {
  name: string;
  email: string;
  mobile: string;
  current_address: string;
  permanent_address: string;
  heading: string;
  content: string;
  photo_path: File | null;
  resume_url: string;
  status: string;
  experiences: ExperienceItem[];
  educations: EducationItem[];
  errors: Record<string, string>;
}

interface Props {
  form: FormData;
  about: any | null;
  statusOptions: StatusOption[];
}

const props = withDefaults(defineProps<Props>(), {
  about: null
});

const formLocal = reactive({...props.form});
const { name, email, mobile, current_address, permanent_address, heading, content, photo_path, resume_url, status, experiences, educations, errors } = toRefs(formLocal);

// Add new experience entry
const addExperience = () => {
  experiences.value.push({ 
    title: '', 
    company: '', 
    duration: '', 
    description: '' ,
    company_url: '',
    location: ''
  });
};

// Remove experience entry
const removeExperience = (index: number) => {
  experiences.value.splice(index, 1);
};

// Add new education entry
const addEducation = () => {
  educations.value.push({ 
    degree: '', 
    institution: '', 
    year: '', 
    description: '' 
  });
};

// Remove education entry
const removeEducation = (index: number) => {
  educations.value.splice(index, 1);
};

// Ensure at least one entry always exists - called by parent if needed
const ensureMinimumEntries = () => {
  if (experiences.value.length === 0) {
    experiences.value.push({ title: '', company: '', duration: '', description: '', company_url: '', location: ''});
  }
  if (educations.value.length === 0) {
    educations.value.push({ degree: '', institution: '', year: '', description: '' });
  }
};

watch(
  () => props.form,
  (newForm) => {
    // Clear arrays first to avoid duplication
    experiences.value = [];
    educations.value = [];
    Object.assign(formLocal, newForm as FormData);
    nextTick(() => ensureMinimumEntries());
  },
  { immediate: true, deep: true }
);

// Removed bidirectional sync that interferes with array mutations
watch(
  () => props.form,
  (newForm) => {
    experiences.value = [];
    educations.value = [];
    Object.assign(formLocal, newForm as FormData);
    nextTick(() => ensureMinimumEntries());
  },
  { immediate: true }
);

// Auto ensure minimum on mount
onMounted(() => {
  ensureMinimumEntries();
});

</script>

<template>
  <div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Personal Information Section -->
        <section class="p-6 rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center gap-2 mb-4">
            <div class="p-2 rounded-lg bg-indigo-100">
              <UserIcon class="h-5 w-5 text-indigo-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
          </div>
          <div class="grid md:grid-cols-2 gap-4">
            <FormField label="Full Name" name="name" :error="errors.name" required>
              <input v-model="name" type="text" placeholder="John Doe" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
            </FormField>
            <FormField label="Email" name="email" :error="errors.email" required>
              <input v-model="email" type="email" placeholder="john@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
            </FormField>
            <FormField label="Mobile" name="mobile" :error="errors.mobile" required>
              <input v-model="mobile" type="tel" placeholder="+1 234 567 8900" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
            </FormField>
            <FormField label="Resume URL" name="resume_url" :error="errors.resume_url">
              <input v-model="resume_url" type="url" placeholder="https://resume.example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
            </FormField>
          </div>
        </section>

        <!-- Address Section -->
        <section class="p-6 rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center gap-2 mb-4">
            <div class="p-2 rounded-lg bg-indigo-100">
              <MapPinIcon class="h-5 w-5 text-indigo-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Address</h3>
          </div>
          <div class="grid md:grid-cols-2 gap-4">
            <FormField label="Current Address" name="current_address" :error="errors.current_address" required>
              <textarea v-model="current_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-vertical" placeholder="Current address" />
            </FormField>
            <FormField label="Permanent Address" name="permanent_address" :error="errors.permanent_address" required>
              <textarea v-model="permanent_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-vertical" placeholder="Permanent address" />
            </FormField>
          </div>
        </section>

        <!-- Content Section -->
        <section class="p-6 rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center gap-2 mb-4">
            <div class="p-2 rounded-lg bg-indigo-100">
              <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Content</h3>
          </div>
          <div class="space-y-4">
            <FormField label="Heading" name="heading" :error="errors.heading" required>
              <input v-model="heading" type="text" placeholder="About Me" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
            </FormField>
            <FormField label="Content" name="content" :error="errors.content" required>
              <textarea v-model="content" rows="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-vertical" placeholder="Your bio..." />
              <p class="text-right text-xs text-gray-500 mt-1">{{ content?.length || 0 }}/2000</p>
            </FormField>
          </div>
        </section>

        <!-- Experiences Section -->
        <section class="p-6 rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="p-2 rounded-lg bg-indigo-100">
                <BriefcaseIcon class="h-5 w-5 text-indigo-400" />
              </div>
              <h3 class="text-lg font-semibold text-gray-900">Experiences</h3>
            </div>
            <span class="text-xs text-gray-500">{{ experiences.length }} entry(ies)</span>
          </div>
          
          <div class="space-y-4">
            <div 
              v-for="(exp, i) in experiences" 
              :key="i" 
              class="p-4 border border-gray-200 rounded-lg bg-white shadow-sm"
            >
              <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                <h4 class="font-medium text-gray-700">Entry {{ i + 1 }}</h4>
                <button 
                  v-if="experiences.length > 1" 
                  @click="removeExperience(i)" 
                  type="button" 
                  class="flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all text-sm"
                >
                  <TrashIcon class="h-4 w-4" />
                  Remove
                </button>
              </div>
              
              <div class="grid md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                  <input 
                    v-model="exp.title" 
                    type="text" 
                    placeholder="Job title" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                  <input 
                    v-model="exp.company" 
                    type="text" 
                    placeholder="Company name" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  />
                </div>
              </div>
              <div class="grid md:grid-cols-2 gap-4">
                
                <div class="mt-3">
                  <label for="company_url" class="">Company Url</label>
                  <input 
                    v-model="exp.company_url" 
                    type="text" 
                    placeholder="e.g., https://example.com" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  />
                </div>
                <div class="mt-3">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                  <input 
                    v-model="exp.duration" 
                    type="text" 
                    placeholder="e.g., 2020-Present" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  />
                </div>
                <div class="mt-3">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                  <input 
                    v-model="exp.location" 
                    type="text" 
                    placeholder="e.g., ghatal, West Wedinipur, India" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  />
                </div>

              </div>
              
              
              <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea 
                  v-model="exp.description" 
                  rows="3" 
                  placeholder="Describe your responsibilities and achievements..." 
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-vertical"
                />
              </div>
            </div>
          </div>
          
          <button 
            @click="addExperience" 
            class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-400 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-lg transition-all shadow-sm hover:shadow-md text-sm"
          >
            <PlusIcon class="h-4 w-4" />
            Add Experience
          </button>
        </section>

        <!-- Education Section -->
        <section class="p-6 rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="p-2 rounded-lg bg-indigo-100">
                <AcademicCapIcon class="h-5 w-5 text-indigo-400" />
              </div>
              <h3 class="text-lg font-semibold text-gray-900">Education</h3>
            </div>
            <span class="text-xs text-gray-500">{{ educations.length }} entry(ies)</span>
          </div>
          
          <div class="space-y-4">
            <div 
              v-for="(edu, i) in educations" 
              :key="i" 
              class="p-4 border border-gray-200 rounded-lg bg-white shadow-sm"
            >
              <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                <h4 class="font-medium text-gray-700">Entry {{ i + 1 }}</h4>
                <button 
                  v-if="educations.length > 1" 
                  @click="removeEducation(i)" 
                  type="button" 
                  class="flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all text-sm"
                >
                  <TrashIcon class="h-4 w-4" />
                  Remove
                </button>
              </div>
              
              <div class="grid md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Degree</label>
                  <input 
                    v-model="edu.degree" 
                    type="text" 
                    placeholder="e.g., B.Sc Computer Science" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                  <input 
                    v-model="edu.institution" 
                    type="text" 
                    placeholder="University Name" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  />
                </div>
              </div>
              
              <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <input 
                  v-model="edu.year" 
                  type="text" 
                  placeholder="e.g., 2024" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                />
              </div>
              
              <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea 
                  v-model="edu.description" 
                  rows="3" 
                  placeholder="Describe your studies, achievements, etc..." 
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-vertical"
                />
              </div>
            </div>
          </div>
          
          <button 
            @click="addEducation" 
            class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-medium rounded-lg transition-all shadow-sm hover:shadow-md text-sm"
          >
            <PlusIcon class="h-4 w-4" />
            Add Education
          </button>
        </section>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Photo Section -->
        <section class="p-6 rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center gap-2 mb-4">
            <div class="p-2 rounded-lg bg-indigo-100">
              <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
          </div>
          <div class="space-y-4">
            <div v-if="about?.photo_path" class="text-center">
              <img :src="about.photo_path_url || `/${about.photo_path}`" alt="Current photo" class="h-28 w-28 rounded-full object-cover mx-auto ring-4 ring-indigo-400 ring-offset-2 shadow-lg" />
              <p class="text-xs text-gray-500 mt-2">Current photo</p>
            </div>
            <ImageUploader v-model="photo_path" folder="abouts" />
          </div>
        </section>

        <!-- Status Section -->
        <section class="p-6 rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center gap-2 mb-4">
            <div class="p-2 rounded-lg bg-indigo-100">
              <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Status</h3>
          </div>
          <div class="flex flex-col gap-2">
            <label 
              v-for="option in statusOptions" 
              :key="option.value" 
              class="flex items-center gap-3 p-3 rounded-lg border-2 cursor-pointer hover:shadow-md transition-all" 
              :class="status === option.value ? 'border-indigo-500 bg-indigo-50 shadow-md' : 'border-gray-200 bg-white hover:border-gray-300'"
            >
              <input 
                type="radio" 
                v-model="status" 
                :value="option.value" 
                class="h-4 w-4 rounded text-indigo-400 border-gray-300 focus:ring-indigo-500" 
              />
              <div>
                <span class="font-medium text-gray-900">{{ option.label }}</span>
              </div>
            </label>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>