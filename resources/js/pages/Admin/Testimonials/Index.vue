<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import Modal from '@/Components/Modal.vue';
import TestimonialFormFields from '@/Components/Admin/Forms/TestimonialFormFields.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
  PencilIcon, 
  TrashIcon, 
  PlusIcon, 
  XMarkIcon
} from '@heroicons/vue/24/outline';

type Testimonial = {
    id: number;
    client_name: string;
    client_role: string | null;
    company: string | null;
    avatar_path: string | null;
    quote: string;
    rating: number | null;
    status: 'active' | 'inactive';
    sort_order: number;
};

const props = defineProps<{
    testimonials: {
        data: Testimonial[];
    };
}>();

const statusOptions = [
    { value: 'active', label: 'Active', color: 'bg-green-100 text-green-800' },
    { value: 'inactive', label: 'Inactive', color: 'bg-gray-100 text-gray-800' },
] as const;

// Create Modal
const isCreateOpen = ref(false);
const createForm = useForm({
    client_name: '',
    client_role: '',
    company: '',
    avatar_path: null as File | string | null,
    quote: '',
    rating: null as number | null,
    status: 'active' as 'active' | 'inactive',
    sort_order: 0,
});


// Edit modal
const isEditOpen = ref(false);
const editing = ref<Testimonial | null>(null);

const editForm = useForm({
    client_name: '',
    client_role: '',
    company: '',
    avatar_path: null as File | string | null,
    quote: '',
    rating: null as number | null,
    status: 'active' as 'active' | 'inactive',
    sort_order: 0,
});


// Delete modal
const isDeleteOpen = ref(false);
const deleting = ref<Testimonial | null>(null);

// Success/Error notifications
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

// Create testimonial
const submitCreate = () => {
  // Clean string paths - backend handles null for no change
    if (typeof createForm.avatar_path === 'string') {
        createForm.avatar_path = null;
    }

    createForm.post(route('admin.testimonials.store'), {
        forceFormData: true,
        onSuccess: () => {
            closeCreate();
            showNotification('success', 'Testimonial created successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to create testimonial. Please check the form.');
        }
    });
};

// Open edit modal
const openEdit = (testimonial: Testimonial) => {
    editing.value = testimonial;
    editForm.client_name = testimonial.client_name;
    editForm.client_role = testimonial.client_role ?? '';
    editForm.company = testimonial.company ?? '';

    editForm.avatar_path = editing.value!.avatar_path;

    editForm.quote = testimonial.quote;
    editForm.rating = testimonial.rating;
    editForm.status = testimonial.status;
    editForm.sort_order = testimonial.sort_order;
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

// Update testimonial
const submitUpdate = () => {
    if (!editing.value) return;
    // Clean image fields before submit if string (existing)
  // Clean string paths - backend handles null for no change
    if (typeof editForm.avatar_path === 'string') {
        editForm.avatar_path = null;
    }


    editForm.put(route('admin.testimonials.update', editing.value.id), {
        method: 'patch',
        forceFormData: true,
        onSuccess: () => {
            closeEdit();
            showNotification('success', 'Testimonial updated successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to update testimonial.');
        }
    });
};

// Open delete modal
const openDelete = (testimonial: Testimonial) => {
    deleting.value = testimonial;
    isDeleteOpen.value = true;
};

// Confirm delete
const confirmDelete = () => {
    if (!deleting.value) return;

    router.delete(route('admin.testimonials.destroy', deleting.value.id), {
        onSuccess: () => {
            isDeleteOpen.value = false;
            deleting.value = null;
            showNotification('success', 'Testimonial deleted successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to delete testimonial.');
        }
    });
};

// Cancel delete
const cancelDelete = () => {
    isDeleteOpen.value = false;
    deleting.value = null;
};

// Get status color class
const getStatusColor = (status: string) => {
    return statusOptions.find(opt => opt.value === status)?.color || 'bg-gray-100 text-gray-800';
};

// Get avatar URL
const getAvatarUrl = (path: string | null | undefined) => {
    if (!path) return '/uploads/No_Image_Available.jpg';
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage')) return path;
    return `/${path.replace(/^\/?storage\/?/, '')}`;
};
</script>

<template>
    <Head title="Testimonials" />

    <AdminLayout page-title="Testimonials">
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
                    <h2 class="text-2xl font-bold text-gray-900">Testimonials</h2>
                    <p class="mt-1 text-sm text-gray-500">Manage your client testimonials and social proof</p>
                </div>
                <button
                    @click="openCreate"
                    class="group relative inline-flex items-center gap-2 overflow-hidden rounded-lg bg-gradient-to-r from-indigo-400 to-indigo-500 px-6 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-300 hover:from-indigo-700 hover:to-indigo-400 hover:shadow-lg active:scale-95"
                >
                    <PlusIcon class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" />
                    <span>Add Testimonial</span>
                </button>
            </div>

            <!-- Testimonials List Section -->
            <SectionCard>
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">All Testimonials</h2>
                        <p class="mt-1 text-sm text-gray-500">Client feedback and reviews</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-400">
                            {{ props.testimonials.data.length }} items
                        </span>
                    </div>
                </div>

                <!-- Desktop Table View -->
                <div class="mt-6 hidden overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Company/Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Sort Order</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr 
                                v-for="testimonial in props.testimonials.data" 
                                :key="testimonial.id" 
                                class="transition-colors hover:bg-gray-50"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-full bg-gray-100">
                                            <img 
                                                v-if="testimonial.avatar_path"
                                                :src="getAvatarUrl(testimonial.avatar_path)" 
                                                :alt="testimonial.client_name"
                                                class="h-full w-full object-cover"
                                            />
                                            <div v-else class="flex h-full w-full items-center justify-center bg-indigo-100 text-indigo-400">
                                                {{ testimonial.client_name.charAt(0) }}
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ testimonial.client_name }}</p>
                                            <p v-if="testimonial.client_role" class="text-sm text-gray-500">{{ testimonial.client_role }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ testimonial.company || '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <span class="text-sm text-gray-900">{{ testimonial.rating || '—' }}</span>
                                        <span v-if="testimonial.rating" class="text-yellow-400">★</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['inline-flex rounded-full px-2 py-1 text-xs font-semibold', getStatusColor(testimonial.status)]">
                                        {{ testimonial.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ testimonial.sort_order }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            @click="openEdit(testimonial)"
                                            class="rounded-lg p-2 text-indigo-400 transition-colors hover:bg-indigo-50"
                                            title="Edit"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </button>
                                        <button
                                            type="button"
                                            @click="openDelete(testimonial)"
                                            class="rounded-lg p-2 text-red-600 transition-colors hover:bg-red-50"
                                            title="Delete"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="props.testimonials.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p>No testimonials yet</p>
                                        <p class="text-sm">Click "Add Testimonial" to create your first one</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="mt-6 space-y-4 md:hidden">
                    <div
                        v-for="testimonial in props.testimonials.data"
                        :key="testimonial.id"
                        class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                    <img 
                                        v-if="testimonial.avatar_path"
                                        :src="getAvatarUrl(testimonial.avatar_path)" 
                                        :alt="testimonial.client_name"
                                        class="h-full w-full object-cover"
                                    />
                                    <div v-else class="flex h-full w-full items-center justify-center bg-indigo-100 text-indigo-400">
                                        {{ testimonial.client_name.charAt(0) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ testimonial.client_name }}</p>
                                    <p v-if="testimonial.client_role" class="text-sm text-gray-500">{{ testimonial.client_role }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button @click="openEdit(testimonial)" class="rounded-lg p-2 text-indigo-400">
                                    <PencilIcon class="h-4 w-4" />
                                </button>
                                <button @click="openDelete(testimonial)" class="rounded-lg p-2 text-red-600">
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Company:</span>
                                <span class="text-gray-900">{{ testimonial.company || '—' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Rating:</span>
                                <span class="text-gray-900">{{ testimonial.rating || '—' }} ★</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Status:</span>
                                <span :class="['rounded-full px-2 py-1 text-xs font-semibold', getStatusColor(testimonial.status)]">
                                    {{ testimonial.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="props.testimonials.data.length === 0" class="rounded-lg border border-gray-200 bg-white p-8 text-center text-gray-500">
                        No testimonials yet
                    </div>
                </div>
            </SectionCard>
        </div>

        <!-- Create Modal -->
        <Modal :show="isCreateOpen" max-width="4xl" @close="closeCreate">
            <div class="p-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Add New Testimonial</h3>
                        <p class="mt-1 text-sm text-gray-500">Add client feedback to showcase social proof</p>
                    </div>
                    <button @click="closeCreate" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>

                <form class="mt-6" @submit.prevent="submitCreate">
                    <TestimonialFormFields 
                        :form="createForm" 
                        :status-options="statusOptions" 
                        prefix="create"
                    />
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeCreate"
                            class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95"
                        >
                            <svg class="h-4 w-4 text-gray-500 transition-all duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Cancel</span>
                        </button>
                        <LoadingButton 
                            :loading="createForm.processing" 
                            :disabled="createForm.processing"
                            class="group rounded-xl bg-indigo-400/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                        >
                            <span class="flex items-center">
                                <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                                <span class="relative">
                                    Create Testimonial
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
                        <h3 class="text-xl font-semibold text-gray-900">Edit Testimonial</h3>
                        <p class="mt-1 text-sm text-gray-500">Update testimonial details</p>
                    </div>
                    <button @click="closeEdit" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>
                
                <form class="mt-6" @submit.prevent="submitUpdate">
                    <TestimonialFormFields 
                        :form="editForm" 
                        :status-options="statusOptions" 
                        prefix="edit"
                        :avatar-url="getAvatarUrl(editing?.avatar_path)"
                    />
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeEdit"
                            class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95"
                        >
                            <svg class="h-4 w-4 text-gray-500 transition-all duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Cancel</span>
                        </button>
                        <LoadingButton 
                            :loading="editForm.processing" 
                            :disabled="editForm.processing"
                            class="bg-indigo-400 hover:bg-indigo-700"
                        >
                            Save Changes
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
                    <h3 class="text-lg font-semibold text-gray-900">Delete Testimonial</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Are you sure you want to delete the testimonial from <span class="font-semibold">{{ deleting?.client_name }}</span>?
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
                        Delete Testimonial
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>