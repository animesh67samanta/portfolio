<script setup lang="ts">
import { 
    PlusIcon, 
    PencilIcon, 
    TrashIcon, 
    XMarkIcon,
    PhotoIcon
} from '@heroicons/vue/24/outline';
import type { Form } from '@inertiajs/vue3';
import { Head, useForm, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import BannerFormFields from '@/Components/Admin/Forms/BannerFormFields.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import Modal from '@/Components/Modal.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

interface CreateBannerFormData {
    title: string;
    subtitle: string;
    image_path?: File | string | null;
    cta_text: string;
    cta_url: string;
    status: 'draft' | 'published' | 'archived';
    sort_order: number;
    published_at: string;
}

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
    { value: 'draft', label: 'Draft', color: 'bg-gray-100 text-gray-800', icon: '📝' },
    { value: 'published', label: 'Published', color: 'bg-green-100 text-green-800', icon: '✓' },
    { value: 'archived', label: 'Archived', color: 'bg-yellow-100 text-yellow-800', icon: '📦' },
] as const;

// Create Modal
const isCreateOpen = ref(false);
const createForm = useForm<CreateBannerFormData>({
    title: '',
    subtitle: '',
    image_path: null,
    cta_text: '',
    cta_url: '',
    status: 'draft' as 'draft' | 'published' | 'archived',
    sort_order: 0,
    published_at: '',
});

// Edit Modal
const isEditOpen = ref(false);
const editing = ref<Banner | null>(null);
const editForm = useForm<CreateBannerFormData>({
    title: '',
    subtitle: '',
    image_path: null,
    cta_text: '',
    cta_url: '',
    status: 'draft' as 'draft' | 'published' | 'archived',
    sort_order: 0,
    published_at: '',
});

// Delete Modal
const isDeleteOpen = ref(false);
const deleting = ref<Banner | null>(null);


// Open create modal
const openCreate = () => {
    createForm.reset();
    createForm.clearErrors();
    isCreateOpen.value = true;
};

// Close create modal
const closeCreate = () => {
    isCreateOpen.value = false;
    createForm.reset();
    createForm.clearErrors();
};

// Submit create
const submitCreate = () => {
    // Clean image_path before submit if string (existing) - shouldn't happen on create
    if (typeof createForm.image_path === 'string') {
        createForm.image_path = undefined;
    }

    createForm.post(route('admin.banners.store'), {
        forceFormData: true,
        onSuccess: () => {
            closeCreate();
        },
        onError: (errors) => {
            console.error('Create errors:', errors);
        }
    });
};

// Open edit modal
const openEdit = (banner: Banner) => {
    editing.value = banner;
    editForm.title = banner.title;
    editForm.subtitle = banner.subtitle ?? '';
    editForm.image_path = banner.image_path;
    editForm.cta_text = banner.cta_text ?? '';
    editForm.cta_url = banner.cta_url ?? '';
    editForm.status = banner.status;
    editForm.sort_order = banner.sort_order;
    editForm.published_at = banner.published_at ? banner.published_at.slice(0, 16) : '';
    editForm.clearErrors();
    isEditOpen.value = true;
};

// Close edit modal
const closeEdit = () => {
    isEditOpen.value = false;
    editing.value = null;
    editForm.reset();
    editForm.clearErrors();
};

// Submit update
const submitUpdate = () => {
    if (!editing.value) {
return;
}

    // Clean image_path before submit if string (existing)
    if (typeof editForm.image_path === 'string') {
        editForm.image_path = undefined;
    }

    editForm.put(route('admin.banners.update', editing.value.id), {
        method: 'patch',
        forceFormData: true,
        onSuccess: () => {
            closeEdit();
        },
        onError: (errors) => {
            console.error('Update errors:', errors);
        }
    });
};

// Open delete modal
const openDelete = (banner: Banner) => {
    deleting.value = banner;
    isDeleteOpen.value = true;
};

// Confirm delete
const confirmDelete = () => {
    if (!deleting.value) {
return;
}

    router.delete(route('admin.banners.destroy', deleting.value.id), {
        onSuccess: () => {
            isDeleteOpen.value = false;
            deleting.value = null;
        },
        onError: (errors) => {
            console.error('Delete errors:', errors);
        }
    });
};

// Cancel delete
const cancelDelete = () => {
    isDeleteOpen.value = false;
    deleting.value = null;
};

// Get image URL
const getImageUrl = (path: string): string | undefined => {
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

// Get status info
const getStatusInfo = (status: string) => {
    const option = statusOptions.find(opt => opt.value === status);

    return {
        color: option?.color || 'bg-gray-100 text-gray-800',
        icon: option?.icon || '📄'
    };
};
</script>

<template>
    <Head title="Banners" />

    <AdminLayout page-title="Banners">
       
        <div class="space-y-6">
            <!-- Header with Create Button -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Banners</h2>
                    <p class="mt-1 text-sm text-gray-500">Manage your promotional banners</p>
                </div>
                <button
                    @click="openCreate"
                    class="group relative inline-flex items-center gap-2 overflow-hidden rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-700 px-6 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:from-indigo-700 hover:to-indigo-500 hover:shadow-lg active:scale-95"
                >
                    <PlusIcon class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" />
                    <span>Create Banner</span>
                </button>
            </div>

            <!-- Banners Grid -->
            <SectionCard>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="banner in props.banners.data"
                        :key="banner.id"
                        class="group rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-xl hover:scale-[1.02]"
                    >
                        <!-- Banner Image -->
                        <div class="relative overflow-hidden rounded-t-xl">
                            <img 
                                :src="getImageUrl(banner.image_path)"
                                :alt="banner.title"
                                class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                @error="(e) => { (e.target as HTMLImageElement).src = 'https://via.placeholder.com/400x200?text=No+Image' }"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            <div class="absolute top-3 right-3 flex gap-2">
                                <button
                                    @click="openEdit(banner)"
                                    class="rounded-lg bg-white/90 p-2 text-indigo-500 backdrop-blur-sm transition-all hover:bg-indigo-800 hover:text-white"
                                    title="Edit"
                                >
                                    <PencilIcon class="h-4 w-4" />
                                </button>
                                <button
                                    @click="openDelete(banner)"
                                    class="rounded-lg bg-white/90 p-2 text-red-600 backdrop-blur-sm transition-all hover:bg-red-600 hover:text-white"
                                    title="Delete"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Banner Content -->
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span :class="['inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold', getStatusInfo(banner.status).color]">
                                    <span>{{ getStatusInfo(banner.status).icon }}</span>
                                    <span>{{ banner.status }}</span>
                                </span>
                                <span class="text-xs text-gray-500">Order: {{ banner.sort_order }}</span>
                            </div>
                            
                            <h3 class="mb-1 text-lg font-semibold text-gray-900 line-clamp-1">
                                {{ banner.title }}
                            </h3>
                            
                            <p v-if="banner.subtitle" class="mb-3 text-sm text-gray-600 line-clamp-2">
                                {{ banner.subtitle }}
                            </p>
                            
                            <div v-if="banner.cta_text" class="mt-3">
                                <a 
                                    :href="banner.cta_url || '#'" 
                                    target="_blank"
                                    class="inline-flex items-center gap-1 text-sm font-medium text-indigo-500 hover:text-indigo-700"
                                >
                                    {{ banner.cta_text }}
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="props.banners.data.length === 0" class="col-span-full">
                        <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                            <PhotoIcon class="h-16 w-16 text-gray-400" />
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">No banners yet</h3>
                            <p class="mt-2 text-sm text-gray-500">Get started by creating your first banner</p>
                            <button
                                @click="openCreate"
                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-800"
                            >
                                <PlusIcon class="h-4 w-4" />
                                Create Banner
                            </button>
                        </div>
                    </div>
                </div>
            </SectionCard>
        </div>

        <!-- Create Modal -->
        <Modal :show="isCreateOpen" max-width="4xl" @close="closeCreate">
            <div class="p-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Create New Banner</h3>
                        <p class="mt-1 text-sm text-gray-500">Add a new promotional banner</p>
                    </div>
                    <button @click="closeCreate" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>

                <form class="mt-6" @submit.prevent="submitCreate">
                    <BannerFormFields 
                        :form="createForm" 
                        :status-options="statusOptions" 
                        prefix="create"
                    />
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeCreate"
                            class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            
                        >
                            <svg class="h-4 w-4 text-gray-500 transition-all duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Cancel</span>
                        </button>
                        <LoadingButton 
                            :loading="createForm.processing" 
                            :disabled="createForm.processing"
                            class="group rounded-xl bg-indigo-500/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-700/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                        >
                            <span class="flex items-center">
                                <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                                <span class="relative">
                                    Create Banner
                                    <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-white transition-all duration-300 group-hover:w-full"></span>
                                </span>
                            </span>
                        </LoadingButton>
                            
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Modal -->
        <Modal :show="isEditOpen" max-width="4xl" @close="closeEdit">
            <div class="p-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Edit Banner</h3>
                        <p class="mt-1 text-sm text-gray-500">Update banner details</p>
                    </div>
                    <button @click="closeEdit" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>

                <form class="mt-6" @submit.prevent="submitUpdate">
                            <BannerFormFields 
                        :form="editForm" 
                        :status-options="statusOptions" 
                        prefix="edit"
                        :current-image-url="editing?.image_path ? getImageUrl(editing.image_path) : undefined"
                    />
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeEdit"
                            class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="h-4 w-4 text-gray-500 transition-all duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Cancel</span>
                        </button>
                        
                        <LoadingButton 
                            :loading="editForm.processing" 
                            :disabled="editForm.processing"
                            class="group rounded-xl bg-indigo-500/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-800 hover:shadow-lg hover:shadow-indigo-800/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                        >
                            <span class="flex items-center">
                                <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                                <span class="relative">
                                    Update Banner
                                    <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-white transition-all duration-300 group-hover:w-full"></span>
                                </span>
                            </span>
                        </LoadingButton>
                        
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteOpen" max-width="md" @close="cancelDelete">
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <div class="rounded-full bg-red-100 p-3">
                        <TrashIcon class="h-6 w-6 text-red-600" />
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">Delete Banner</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Are you sure you want to delete "<span class="font-semibold">{{ deleting?.title }}</span>"?
                        This action cannot be undone.
                    </p>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="cancelDelete"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="confirmDelete"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                    >
                        Delete Banner
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>