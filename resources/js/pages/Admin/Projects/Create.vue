<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormField from '@/Components/FormField.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import SectionCard from '@/Components/SectionCard.vue';

type Skill = {
    id: number;
    name: string;
};

const props = defineProps<{
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
    title: '',
    slug: '',
    summary: '',
    description: '',
    thumbnail_path: null,
    project_url: '',
    repository_url: '',
    started_at: '',
    completed_at: '',
    is_featured: false,
    status: 'draft',
    published_at: '',
    skill_ids: [],
});

const isDisabled = computed(() => form.processing);

const submit = () => {
    form.post(route('admin.projects.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Create Project" />

    <AdminLayout page-title="Create Project">
        <SectionCard>
            <h2 class="text-xl font-semibold text-slate-900">Create Project</h2>
            <p class="mt-1 text-sm text-slate-600">Add a new project to your portfolio.</p>

            <form class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
                <FormField label="Title" name="title" :error="form.errors.title">
                    <input
                        id="title"
                        v-model="form.title"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="text"
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Slug" name="slug" :error="form.errors.slug" help="Must be unique. Example: my-project">
                    <input
                        id="slug"
                        v-model="form.slug"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="text"
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Summary" name="summary" :error="form.errors.summary">
                    <input
                        id="summary"
                        v-model="form.summary"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="text"
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Status" name="status" :error="form.errors.status">
                    <select
                        id="status"
                        v-model="form.status"
                        class="block w-full rounded-md border-slate-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                        class="block min-h-28 w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </FormField>

                <FormField
                    class="md:col-span-2"
                    label="Thumbnail"
                    name="thumbnail_path"
                    :error="form.errors.thumbnail_path"
                    help="PNG/JPG/WebP up to 2MB."
                >
                    <input
                        id="thumbnail_path"
                        class="block w-full rounded-md border-slate-300 bg-white text-sm shadow-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800"
                        type="file"
                        accept="image/*"
                        @change="form.thumbnail_path = ($event.target as HTMLInputElement).files?.[0] ?? null"
                    />
                </FormField>

                <FormField label="Project URL" name="project_url" :error="form.errors.project_url">
                    <input
                        id="project_url"
                        v-model="form.project_url"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="url"
                        placeholder="https://example.com"
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Repository URL" name="repository_url" :error="form.errors.repository_url">
                    <input
                        id="repository_url"
                        v-model="form.repository_url"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="url"
                        placeholder="https://github.com/..."
                        autocomplete="off"
                    />
                </FormField>

                <FormField label="Started at" name="started_at" :error="form.errors.started_at">
                    <input
                        id="started_at"
                        v-model="form.started_at"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="date"
                    />
                </FormField>

                <FormField label="Completed at" name="completed_at" :error="form.errors.completed_at">
                    <input
                        id="completed_at"
                        v-model="form.completed_at"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="date"
                    />
                </FormField>

                <FormField label="Published at" name="published_at" :error="form.errors.published_at">
                    <input
                        id="published_at"
                        v-model="form.published_at"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="datetime-local"
                    />
                </FormField>

                <FormField label="Featured" name="is_featured" :error="form.errors.is_featured">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input
                            v-model="form.is_featured"
                            class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
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
                        >
                            <input
                                :value="s.id"
                                v-model="form.skill_ids"
                                type="checkbox"
                                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            />
                            <span>{{ s.name }}</span>
                        </label>
                    </div>
                </FormField>

                <div class="md:col-span-2 flex items-center justify-end">
                    <LoadingButton :loading="form.processing" :disabled="isDisabled">
                        Create project
                    </LoadingButton>
                </div>
            </form>
        </SectionCard>
    </AdminLayout>
</template>

