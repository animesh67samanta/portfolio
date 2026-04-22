<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import Modal from '@/Components/Modal.vue';
import BlogFormFields from '@/Components/Admin/Forms/BlogFormFields.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    PencilIcon, 
    TrashIcon, 
    PlusIcon, 
    EyeIcon,
    DocumentTextIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline';

type Blog = {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string;
    featured_image: string;
    cover_image: string | null;
    status: 'draft' | 'published' | 'archived';
    published_at: string | null;
    views_count: number;
};

const props = defineProps<{
    blogs: {
        data: Blog[];
    };
}>();

const statusOptions = [
    { value: 'draft', label: 'Draft', color: 'bg-gray-100 text-gray-800', icon: '📝' },
    { value: 'published', label: 'Published', color: 'bg-green-100 text-green-800', icon: '✓' },
    { value: 'archived', label: 'Archived', color: 'bg-yellow-100 text-yellow-800', icon: '📦' },
] as const;

// Create Modal
const isCreateOpen = ref(false);
const createForm = useForm({
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    featured_image: null as File | string | null,
    cover_image: null as File | string | null,
    status: 'draft' as 'draft' | 'published' | 'archived',
    published_at: '',
});

// Edit modal
const isEditOpen = ref(false);
const editing = ref<Blog | null>(null);
const editForm = useForm({
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    featured_image: null as File | string | null,
    cover_image: null as File | string | null,
    status: 'draft' as 'draft' | 'published' | 'archived',
    published_at: '',
});

// Delete modal
const isDeleteOpen = ref(false);
const deleting = ref<Blog | null>(null);

// Notification
const notification = ref<{ show: boolean; type: 'success' | 'error'; message: string }>({
    show: false,
    type: 'success',
    message: ''
});

const showNotification = (type: 'success' | 'error', message: string) => {
    notification.value = { show: true, type, message };
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

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

// Create blog
const submitCreate = () => {
    if (createForm.featured_image && typeof createForm.featured_image === 'string') {
        (createForm as any).featured_image = null;
    }
    if (createForm.cover_image && typeof createForm.cover_image === 'string') {
        (createForm as any).cover_image = null;
    }
    
    createForm.post(route('admin.blogs.store'), {
        forceFormData: true,
        onSuccess: () => {
            closeCreate();
            showNotification('success', 'Blog created successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to create blog. Please check the form.');
        }
    });
};

// Open edit modal
const openEdit = (blog: Blog) => {
    editing.value = blog;
    editForm.title = blog.title;
    editForm.slug = blog.slug;
    editForm.excerpt = blog.excerpt ?? '';
    editForm.content = blog.content;
    editForm.featured_image = blog.featured_image;
    editForm.cover_image = blog.cover_image;
    editForm.status = blog.status;
    editForm.published_at = blog.published_at ? blog.published_at.slice(0, 16) : '';
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

// Update blog
const submitUpdate = () => {
    if (!editing.value) return;
    // Clean image fields before submit if string (existing)
    if (editForm.featured_image && typeof editForm.featured_image === 'string') {
        (editForm as any).featured_image = null;
    }
    if (editForm.cover_image && typeof editForm.cover_image === 'string') {
        (editForm as any).cover_image = null;
    }
    editForm.put(route('admin.blogs.update', editing.value.id), {
        method: 'patch',
        forceFormData: true,
        onSuccess: () => {
            closeEdit();
            showNotification('success', 'Blog updated successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to update blog.');
        }
    });
};

// Open delete modal
const openDelete = (blog: Blog) => {
    deleting.value = blog;
    isDeleteOpen.value = true;
};

// Confirm delete
const confirmDelete = () => {
    if (!deleting.value) return;

    router.delete(route('admin.blogs.destroy', deleting.value.id), {
        onSuccess: () => {
            isDeleteOpen.value = false;
            deleting.value = null;
            showNotification('success', 'Blog deleted successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to delete blog.');
        }
    });
};

// Cancel delete
const cancelDelete = () => {
    isDeleteOpen.value = false;
    deleting.value = null;
};

// Get status color and icon
const getStatusInfo = (status: string) => {
    const option = statusOptions.find(opt => opt.value === status);
    return {
        color: option?.color || 'bg-gray-100 text-gray-800',
        icon: option?.icon || '📄'
    };
};

// Format date
const formatDate = (date: string | null) => {
    if (!date) return 'Not published';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Get featured image URL
const getImageUrl = (path: string | null) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage')) return path;
    return `/${path.replace(/^\/?storage\/?/, '')}`;
};
</script>

<template>
    <Head title="Blogs" />

    <AdminLayout page-title="Blogs">
        <!-- Notification Toast -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div
                v-if="notification.show"
                :class="[
                    'fixed top-20 right-4 z-50 rounded-lg p-4 shadow-lg',
                    notification.type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'
                ]"
            >
                <div class="flex items-center gap-3">
                    <div class="flex-1">{{ notification.message }}</div>
                    <button @click="notification.show = false" class="text-gray-400 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </Transition>

        <div class="space-y-6">
            <!-- Header with Create Button -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Blog Posts</h2>
                    <p class="mt-1 text-sm text-gray-500">Manage and organize your blog content</p>
                </div>
                <button
                    @click="openCreate"
                    class="group relative inline-flex items-center gap-2 overflow-hidden rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-300 hover:from-indigo-700 hover:to-indigo-600 hover:shadow-lg active:scale-95"
                >
                    <PlusIcon class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" />
                    <span>Create Blog Post</span>
                </button>
            </div>

            <!-- Blogs List Section -->
            <SectionCard>
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-200 pb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">All Posts</h2>
                        <p class="mt-1 text-sm text-gray-500">Your published and draft blog posts</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-600">
                            {{ props.blogs.data.length }} posts
                        </span>
                    </div>
                </div>

                <!-- Modern Card Grid View -->
                <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="blog in props.blogs.data"
                        :key="blog.id"
                        class="group rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-xl hover:scale-[1.02]"
                    >
                        <!-- Featured Image -->
                        <div class="relative overflow-hidden rounded-t-xl">
                            <img 
                                :src="getImageUrl(blog.featured_image) || 'https://via.placeholder.com/400x200?text=No+Image'"
                                :alt="blog.title"
                                class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                @error="(e) => { (e.target as HTMLImageElement).src = 'https://via.placeholder.com/400x200?text=No+Image' }"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            <div class="absolute top-3 right-3 flex gap-2">
                                <button
                                    @click="openEdit(blog)"
                                    class="rounded-lg bg-white/90 p-2 text-indigo-600 backdrop-blur-sm transition-all hover:bg-indigo-600 hover:text-white"
                                    title="Edit"
                                >
                                    <PencilIcon class="h-4 w-4" />
                                </button>
                                <button
                                    @click="openDelete(blog)"
                                    class="rounded-lg bg-white/90 p-2 text-red-600 backdrop-blur-sm transition-all hover:bg-red-600 hover:text-white"
                                    title="Delete"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span :class="['inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold', getStatusInfo(blog.status).color]">
                                    <span>{{ getStatusInfo(blog.status).icon }}</span>
                                    <span>{{ blog.status }}</span>
                                </span>
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <EyeIcon class="h-3 w-3" />
                                    <span>{{ blog.views_count }} views</span>
                                </div>
                            </div>
                            
                            <h3 class="mb-2 text-lg font-semibold text-gray-900 line-clamp-2">
                                {{ blog.title }}
                            </h3>
                            
                            <p class="mb-3 text-sm text-gray-600 line-clamp-2">
                                {{ blog.excerpt || 'No excerpt available' }}
                            </p>
                            
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-500">
                                    {{ formatDate(blog.published_at) }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    Slug: {{ blog.slug }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="props.blogs.data.length === 0" class="col-span-full">
                        <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                            <DocumentTextIcon class="h-16 w-16 text-gray-400" />
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">No blog posts yet</h3>
                            <p class="mt-2 text-sm text-gray-500">Get started by creating your first blog post</p>
                            <button
                                @click="openCreate"
                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                            >
                                <PlusIcon class="h-4 w-4" />
                                Create Blog Post
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
                        <h3 class="text-xl font-semibold text-gray-900">Create New Blog Post</h3>
                        <p class="mt-1 text-sm text-gray-500">Write and publish engaging content for your audience</p>
                    </div>
                    <button @click="closeCreate" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>

                <form class="mt-6" @submit.prevent="submitCreate">
                    <BlogFormFields 
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
                            class="group rounded-xl bg-indigo-600/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                        >
                            <span class="flex items-center">
                                <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                                <span class="relative">
                                    Create Blog Post
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
                        <h3 class="text-xl font-semibold text-gray-900">Edit Blog Post</h3>
                        <p class="mt-1 text-sm text-gray-500">Update your blog content and settings</p>
                    </div>
                    <button @click="closeEdit" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>
                
                <form class="mt-6" @submit.prevent="submitUpdate">
                    <BlogFormFields 
                        :form="editForm" 
                        :status-options="statusOptions" 
                        prefix="edit"
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
                            class="group rounded-xl bg-indigo-600/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                        >
                            <span class="flex items-center">
                                <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                                <span class="relative">
                                    Update Blog Post
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
                    <h3 class="text-lg font-semibold text-gray-900">Delete Blog Post</h3>
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
                        Delete Post
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions */
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar for modals */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>