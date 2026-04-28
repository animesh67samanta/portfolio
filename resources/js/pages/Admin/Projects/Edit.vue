<script setup lang="ts">
import { PhotoIcon, DocumentArrowUpIcon } from '@heroicons/vue/24/outline';
import { PlusIcon } from '@heroicons/vue/24/outline';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import FormField from '@/Components/FormField.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

type Skill = {
    id: number;
    name: string;
};

const props = defineProps<{
    project: {
        id: number;
        title: string;
        slug: string;
        summary: string;
        description: string;
        thumbnail_path: string;
        project_url: string;
        repository_url: string;
        started_at: string;
        completed_at: string;
        is_featured: boolean;
        status: 'draft' | 'published' | 'archived';
        published_at: string;
        skills: Skill[];
    };
    skills: Skill[];
}>();

const statusOptions = [
    { value: 'draft', label: 'Draft' },
    { value: 'published', label: 'Published' },
    { value: 'archived', label: 'Archived' },
] as const;

const form = useForm<{
  title: string;
    slug: string;
    summary: string;
    description: string;
    thumbnail_path: File | null;
    project_url: string;
    repository_url: string;
    started_at: string;
    completed_at: string;
    is_featured: boolean;
    status: 'draft' | 'published' | 'archived';
    published_at: string;
    skill_ids: number[];
  }>({
    title: props.project.title,
    slug: props.project.slug,
    summary: props.project.summary,
    description: props.project.description,
    thumbnail_path: null,
    project_url: props.project.project_url,
    repository_url: props.project.repository_url,
    started_at: props.project.started_at,
    completed_at: props.project.completed_at,
    is_featured: props.project.is_featured,
    status: props.project.status,
    published_at: props.project.published_at,
    skill_ids: props.project.skills.map(s => s.id),
});

const thumbnailPreview = ref<string | null>(props.project.thumbnail_path ? `/` + props.project.thumbnail_path : null);

watch(() => form.thumbnail_path, (newFile) => {
    if (newFile instanceof File) {
        const reader = new FileReader();
        reader.onload = (e) => {
            thumbnailPreview.value = (e.target?.result as string) || null;
        };
        reader.readAsDataURL(newFile);
    } else {
        thumbnailPreview.value = props.project.thumbnail_path ? `/` + props.project.thumbnail_path : null;
    }
});

const submit = () => {
    form.put(route('admin.projects.update', props.project.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Project" />

    <AdminLayout page-title="Edit Project">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">Edit Project</h2>
            <p class="mt-1 text-sm text-slate-600">Update project details.</p>

            <form class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
                <FormField label="Title" name="title" :error="form.errors.title">
                    <input
                        id="title"
                        v-model="form.title"
                        class="input-field"
                        type="text"
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Slug" name="slug" :error="form.errors.slug" help="Must be unique. Example: my-project">
                    <input
                        id="slug"
                        v-model="form.slug"
                        class="input-field"
                        type="text"
                        autocomplete="off"
                    />
                </FormField>
                <FormField label="Summary" name="summary" :error="form.errors.summary">
                    <input
                        id="summary"
                        v-model="form.summary"
                        class="input-field"
                        type="text"
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Status" name="status" :error="form.errors.status">
                    <select
                        id="status"
                        v-model="form.status"
                        class="select-field"
                    >
                        <option v-for="o in statusOptions" :key="o.value" :value="o.value">
                            {{ o.label }}
                        </option>
                    </select>
                </FormField>

                <FormField
                    class="md:col-span-2"
                    label="Description"
                    name="description"
                    :error="form.errors.description"
                >
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="textarea-field"
                    />
                </FormField>

                <FormField
                    class="md:col-span-2"
                    label="Thumbnail"
                    name="thumbnail_path"
                    :error="form.errors.thumbnail_path"
                    help="PNG/JPG/WebP up to 2MB. Recommended: 600x400px"
                >
                    <div class="mt-2 flex items-center gap-6">
                        <!-- Thumbnail Preview -->
                        <div v-if="thumbnailPreview" class="relative">
                            <img
                                :src="thumbnailPreview"
                                alt="Thumbnail preview"
                                class="h-24 w-32 rounded-lg object-cover ring-2 ring-slate-600 ring-offset-2"
                            />
                            <button
                                type="button"
                                v-if="form.thumbnail_path"
                                @click="form.thumbnail_path = null; thumbnailPreview = props.project.thumbnail_path ? '/' + props.project.thumbnail_path : null"
                                class="absolute -right-2 -top-2 rounded-full bg-red-500 p-1.5 text-white hover:bg-red-600"
                            >
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div v-else class="flex h-24 w-32 items-center justify-center rounded-lg bg-slate-200">
                            <PhotoIcon class="h-12 w-12 text-slate-400" />
                        </div>
                        
                        <div class="flex-1">
                            <input
                                id="thumbnail_path"
                                class="block w-full rounded-md border-slate-300 bg-white text-sm shadow-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800"
                                type="file"
                                accept="image/*"
                                @change="form.thumbnail_path = ($event.target as HTMLInputElement).files?.[0] ?? null"
                            />
                        </div>
                    </div>
                </FormField>

                <FormField label="Project URL" name="project_url" :error="form.errors.project_url">
                    <input
                        id="project_url"
                        v-model="form.project_url"
                        class="input-field"
                        type="url"
                        placeholder="https://example.com"
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Repository URL" name="repository_url" :error="form.errors.repository_url">
                    <input
                        id="repository_url"
                        v-model="form.repository_url"
                        class="input-field"
                        type="url"
                        placeholder="https://github.com/..."
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Started at" name="started_at" :error="form.errors.started_at">
                    <input
                        id="started_at"
                        v-model="form.started_at"
                        class="input-field"
                        type="date"
                    />
                </FormField>

                <FormField label="Completed at" name="completed_at" :error="form.errors.completed_at">
                    <input
                        id="completed_at"
                        v-model="form.completed_at"
                        class="input-field"
                        type="date"
                    />
                </FormField>

                <FormField label="Published at" name="published_at" :error="form.errors.published_at">
                    <input
                        id="published_at"
                        v-model="form.published_at"
                        class="input-field"
                        type="datetime-local"
                    />
                </FormField>

                <FormField label="Featured" name="is_featured" :error="form.errors.is_featured">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input
                            v-model="form.is_featured"
                            class="rounded border-slate-300 text-indigo-400 shadow-sm focus:ring-indigo-500"
                            type="checkbox"
                        />
                        Mark as featured
                    </label>
                </FormField>

                <FormField
                    class="md:col-span-2"
                    label="Skills"
                    name="skill_ids"
                    :error="form.errors.skill_ids"
                    help="Select related skills (optional)."
                >
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                        <label
                            v-for="s in props.skills"
                            :key="s.id"
                            class="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                            :class="form.skill_ids.includes(s.id) ? 'border-slate-900 bg-slate-50 ring-2 ring-slate-900 ring-offset-2' : ''"
                        >
                            <input
                                :value="s.id"
                                v-model="form.skill_ids"
                                type="checkbox"
                                class="rounded border-slate-300 text-indigo-400 shadow-sm focus:ring-indigo-500"
                            />
                            <span>{{ s.name }}</span>
                        </label>
                    </div>
                </FormField>
                <div class="md:col-span-2 flex items-center justify-end gap-3">
                  <button
                      type="button"
                      @click="$inertia.visit(route('admin.projects.index'))"
                      class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                      :disabled="form.processing"
                  >
                      <svg class="h-4 w-4 text-gray-500 transition-all duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                      </svg>
                      <span>Cancel</span>
                  </button>
                    <LoadingButton 
                        :loading="form.processing" 
                        :disabled="form.processing"
                        class="group rounded-xl bg-indigo-400/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                    >
                        <span class="flex items-center">
                            <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                            <span class="relative">
                                Update project
                                <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-white transition-all duration-300 group-hover:w-full"></span>
                            </span>
                        </span>
                    </LoadingButton>
                </div>
                
            </form>
        </SectionCard>
    </AdminLayout>
</template>
