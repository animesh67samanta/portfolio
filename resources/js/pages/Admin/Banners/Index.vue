<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import FormField from '@/Components/FormField.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

type Banner = {
    id: number;
    title: string;
    subtitle: string | null;
    image_path: string;
    cta_text: string | null;
    cta_url: string | null;
    status: 'draft' | 'published' | 'archived';
    sort_order: number;
    published_at: string | null;
    created_at: string;
};

const props = defineProps<{
    banners: {
        data: Banner[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
}>();

const statusOptions = [
    { value: 'draft', label: 'Draft' },
    { value: 'published', label: 'Published' },
    { value: 'archived', label: 'Archived' },
] as const;

const createForm = useForm<{
    title: string;
    subtitle: string;
    image_path: File | null;
    cta_text: string;
    cta_url: string;
    status: 'draft' | 'published' | 'archived';
    sort_order: number;
    published_at: string;
}>({
    title: '',
    subtitle: '',
    image_path: null,
    cta_text: '',
    cta_url: '',
    status: 'draft',
    sort_order: 0,
    published_at: '',
});

const isEditOpen = ref(false);
const editing = ref<Banner | null>(null);

const editForm = useForm<{
    title: string;
    subtitle: string;
    image_path: File | null;
    cta_text: string;
    cta_url: string;
    status: 'draft' | 'published' | 'archived';
    sort_order: number;
    published_at: string;
}>({
    title: '',
    subtitle: '',
    image_path: null,
    cta_text: '',
    cta_url: '',
    status: 'draft',
    sort_order: 0,
    published_at: '',
});

const openEdit = (banner: Banner) => {
    editing.value = banner;
    editForm.clearErrors();
    editForm.defaults({
        title: banner.title,
        subtitle: banner.subtitle ?? '',
        image_path: null,
        cta_text: banner.cta_text ?? '',
        cta_url: banner.cta_url ?? '',
        status: banner.status,
        sort_order: banner.sort_order ?? 0,
        published_at: banner.published_at ? banner.published_at.slice(0, 16) : '',
    });
    editForm.reset();
    isEditOpen.value = true;
};

const closeEdit = () => {
    isEditOpen.value = false;
    editing.value = null;
    editForm.reset();
    editForm.clearErrors();
};

const storageUrl = (path: string) => `/${path}`;

const createIsDisabled = computed(() => createForm.processing);
const editIsDisabled = computed(() => editForm.processing || !editing.value);

const submitCreate = () => {
    createForm.post(route('admin.banners.store'), {
        forceFormData: true,
        onSuccess: () => {
            createForm.reset();
            createForm.clearErrors();
        },
    });
};

const submitUpdate = () => {
    if (!editing.value) {
        return;
    }

    editForm.post(route('admin.banners.update', editing.value.id), {
        method: 'patch',
        forceFormData: true,
        onSuccess: () => {
            closeEdit();
        },
    });
};

const destroyBanner = (banner: Banner) => {
    if (!confirm(`Delete banner "${banner.title}"?`)) {
        return;
    }

    const form = useForm({});
    form.delete(route('admin.banners.destroy', banner.id));
};
</script>

<template>
    <Head title="Banners" />

    <AdminLayout page-title="Banner">
        <div class="space-y-6">
            <SectionCard>
                <h2 class="text-xl font-semibold text-slate-900">Create Banner</h2>
                <p class="mt-1 text-sm text-slate-600">Upload an image and publish when ready.</p>

                <form class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submitCreate">
                    <FormField label="Title" name="title" :error="createForm.errors.title">
                        <input
                            id="title"
                            v-model="createForm.title"
                            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="text"
                            autocomplete="off"
                        />
                    </FormField>

                    <FormField label="Subtitle" name="subtitle" :error="createForm.errors.subtitle">
                        <input
                            id="subtitle"
                            v-model="createForm.subtitle"
                            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="text"
                            autocomplete="off"
                        />
                    </FormField>

                    <FormField
                        label="Image"
                        name="image_path"
                        :error="createForm.errors.image_path"
                        help="PNG/JPG/WebP up to 2MB."
                    >
                        <input
                            id="image_path"
                            class="block w-full rounded-md border-slate-300 bg-white text-sm shadow-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800"
                            type="file"
                            accept="image/*"
                            @change="createForm.image_path = ($event.target as HTMLInputElement).files?.[0] ?? null"
                        />
                    </FormField>

                    <FormField label="Status" name="status" :error="createForm.errors.status">
                        <select
                            id="status"
                            v-model="createForm.status"
                            class="block w-full rounded-md border-slate-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option v-for="o in statusOptions" :key="o.value" :value="o.value">
                                {{ o.label }}
                            </option>
                        </select>
                    </FormField>

                    <FormField label="CTA Text" name="cta_text" :error="createForm.errors.cta_text">
                        <input
                            id="cta_text"
                            v-model="createForm.cta_text"
                            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="text"
                            autocomplete="off"
                        />
                    </FormField>

                    <FormField label="CTA URL" name="cta_url" :error="createForm.errors.cta_url">
                        <input
                            id="cta_url"
                            v-model="createForm.cta_url"
                            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="url"
                            placeholder="https://example.com"
                            autocomplete="off"
                        />
                    </FormField>

                    <FormField label="Sort order" name="sort_order" :error="createForm.errors.sort_order">
                        <input
                            id="sort_order"
                            v-model.number="createForm.sort_order"
                            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="number"
                            min="0"
                        />
                    </FormField>

                    <FormField
                        label="Published at"
                        name="published_at"
                        :error="createForm.errors.published_at"
                        help="Optional. Useful for scheduling."
                    >
                        <input
                            id="published_at"
                            v-model="createForm.published_at"
                            class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="datetime-local"
                        />
                    </FormField>

                    <div class="md:col-span-2 flex items-center justify-end">
                        <LoadingButton :loading="createForm.processing" :disabled="createIsDisabled">
                            Create
                        </LoadingButton>
                    </div>
                </form>
            </SectionCard>

            <SectionCard>
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-900">Banners</h2>
                    <p class="text-sm text-slate-600">{{ props.banners.data.length }} items</p>
                </div>

                <div class="mt-6 overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Image</th>
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Order</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="b in props.banners.data" :key="b.id" class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <img
                                        :src="storageUrl(b.image_path)"
                                        :alt="b.title"
                                        class="h-10 w-16 rounded object-cover ring-1 ring-slate-200"
                                        loading="lazy"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-900">{{ b.title }}</p>
                                    <p v-if="b.subtitle" class="mt-0.5 text-xs text-slate-500">{{ b.subtitle }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                        :class="
                                            b.status === 'published'
                                                ? 'bg-emerald-50 text-emerald-700'
                                                : b.status === 'archived'
                                                  ? 'bg-slate-100 text-slate-700'
                                                  : 'bg-amber-50 text-amber-700'
                                        "
                                    >
                                        {{ b.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ b.sort_order }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        class="rounded-md px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100"
                                        @click="openEdit(b)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-50"
                                        @click="destroyBanner(b)"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="props.banners.data.length === 0">
                                <td colspan="5" class="px-4 py-8 text-center text-slate-600">No banners yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </SectionCard>
        </div>

        <div v-if="isEditOpen" class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-slate-900/50" @click="closeEdit" />
            <div class="absolute inset-x-0 top-10 mx-auto w-full max-w-2xl px-4">
                <div class="rounded-xl bg-white p-6 shadow-xl">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Edit Banner</h3>
                            <p class="mt-1 text-sm text-slate-600">Update fields and save changes.</p>
                        </div>
                        <button
                            type="button"
                            class="rounded-md p-2 text-slate-600 hover:bg-slate-100"
                            @click="closeEdit"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </div>

                    <form class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submitUpdate">
                        <FormField label="Title" name="edit_title" :error="editForm.errors.title">
                            <input
                                id="edit_title"
                                v-model="editForm.title"
                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="text"
                            />
                        </FormField>

                        <FormField label="Subtitle" name="edit_subtitle" :error="editForm.errors.subtitle">
                            <input
                                id="edit_subtitle"
                                v-model="editForm.subtitle"
                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="text"
                            />
                        </FormField>

                        <FormField label="Replace image" name="edit_image_path" :error="editForm.errors.image_path">
                            <input
                                id="edit_image_path"
                                class="block w-full rounded-md border-slate-300 bg-white text-sm shadow-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800"
                                type="file"
                                accept="image/*"
                                @change="editForm.image_path = ($event.target as HTMLInputElement).files?.[0] ?? null"
                            />
                        </FormField>

                        <FormField label="Status" name="edit_status" :error="editForm.errors.status">
                            <select
                                id="edit_status"
                                v-model="editForm.status"
                                class="block w-full rounded-md border-slate-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option v-for="o in statusOptions" :key="o.value" :value="o.value">
                                    {{ o.label }}
                                </option>
                            </select>
                        </FormField>

                        <FormField label="CTA Text" name="edit_cta_text" :error="editForm.errors.cta_text">
                            <input
                                id="edit_cta_text"
                                v-model="editForm.cta_text"
                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="text"
                            />
                        </FormField>

                        <FormField label="CTA URL" name="edit_cta_url" :error="editForm.errors.cta_url">
                            <input
                                id="edit_cta_url"
                                v-model="editForm.cta_url"
                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="url"
                            />
                        </FormField>

                        <FormField label="Sort order" name="edit_sort_order" :error="editForm.errors.sort_order">
                            <input
                                id="edit_sort_order"
                                v-model.number="editForm.sort_order"
                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="number"
                                min="0"
                            />
                        </FormField>

                        <FormField label="Published at" name="edit_published_at" :error="editForm.errors.published_at">
                            <input
                                id="edit_published_at"
                                v-model="editForm.published_at"
                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="datetime-local"
                            />
                        </FormField>

                        <div class="md:col-span-2 flex items-center justify-end gap-2">
                            <button
                                type="button"
                                class="rounded-md px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                                @click="closeEdit"
                            >
                                Cancel
                            </button>
                            <LoadingButton :loading="editForm.processing" :disabled="editIsDisabled">
                                Save changes
                            </LoadingButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
