<script setup lang="ts">
import { 
    PlusIcon, 
    PencilIcon, 
    TrashIcon, 
    XMarkIcon,
    CodeBracketIcon
} from '@heroicons/vue/24/outline';
import type { Form } from '@inertiajs/vue3';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SkillFormFields from '@/Components/Admin/Forms/SkillFormFields.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import Modal from '@/Components/Modal.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

interface CreateSkillFormData {
    name: string;
    slug: string;
    proficiency: number | null;
    icon?: File | string | null;
    status: 'active' | 'inactive';
    sort_order: number;
}

type Skill = {
    id: number;
    name: string;
    slug: string;
    proficiency: number | null;
    icon: string | null;
    status: 'active' | 'inactive';
    sort_order: number;
};

const props = defineProps<{
    skills: {
        data: Skill[];
    };
}>();

const statusOptions = [
    { value: 'active', label: 'Active', color: 'bg-green-100 text-green-800', icon: '✓' },
    { value: 'inactive', label: 'Inactive', color: 'bg-gray-100 text-gray-800', icon: '○' },
] as const;

// Create Modal
const isCreateOpen = ref(false);
const createForm = useForm<CreateSkillFormData>({
    name: '',
    slug: '',
    proficiency: null,
    icon: null,
    status: 'active' as 'active' | 'inactive',
    sort_order: 0,
});

// Edit Modal
const isEditOpen = ref(false);
const editing = ref<Skill | null>(null);
const editForm = useForm<CreateSkillFormData>({
    name: '',
    slug: '',
    proficiency: null,
    icon: null,
    status: 'active' as 'active' | 'inactive',
    sort_order: 0,
});

// Delete Modal
const isDeleteOpen = ref(false);
const deleting = ref<Skill | null>(null);

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

// Submit create
const submitCreate = () => {
    // Clean icon before submit if string (existing) - shouldn't happen on create
    if (typeof createForm.icon === 'string') {
        createForm.icon = undefined;
    }

    createForm.post(route('admin.skills.store'), {
        forceFormData: true,
        onSuccess: () => {
            closeCreate();
            showNotification('success', 'Skill created successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to create skill. Please check the form.');
        }
    });
};

// Open edit modal
const openEdit = (skill: Skill) => {
    editing.value = skill;
    editForm.name = skill.name;
    editForm.slug = skill.slug;
    editForm.proficiency = skill.proficiency;
    editForm.icon = skill.icon;
    editForm.status = skill.status;
    editForm.sort_order = skill.sort_order;
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

    // Clean icon before submit if string (existing)
    if (typeof editForm.icon === 'string') {
        editForm.icon = undefined;
    }

    editForm.put(route('admin.skills.update', editing.value.id), {
        method: 'patch',
        forceFormData: true,
        onSuccess: () => {
            closeEdit();
            showNotification('success', 'Skill updated successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to update skill.');
        }
    });
};

// Open delete modal
const openDelete = (skill: Skill) => {
    deleting.value = skill;
    isDeleteOpen.value = true;
};

// Confirm delete
const confirmDelete = () => {
    if (!deleting.value) {
return;
}

    router.delete(route('admin.skills.destroy', deleting.value.id), {
        onSuccess: () => {
            isDeleteOpen.value = false;
            deleting.value = null;
            showNotification('success', 'Skill deleted successfully!');
        },
        onError: () => {
            showNotification('error', 'Failed to delete skill.');
        }
    });
};

// Cancel delete
const cancelDelete = () => {
    isDeleteOpen.value = false;
    deleting.value = null;
};

// Get image URL
const getImageUrl = (path: string | null) => {
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

// Get proficiency color
const getProficiencyColor = (proficiency: number | null) => {
    const value = proficiency || 0;

    if (value >= 80) {
return 'bg-green-500';
}

    if (value >= 60) {
return 'bg-blue-500';
}

    if (value >= 40) {
return 'bg-yellow-500';
}

    return 'bg-gray-500';
};
</script>

<template>
    <Head title="Skills" />

    <AdminLayout page-title="Skills">

        <div class="space-y-6">
            <!-- Header with Create Button -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Skills</h2>
                    <p class="mt-1 text-sm text-gray-500">Manage your technical skills and expertise</p>
                </div>
                <button
                    @click="openCreate"
                    class="group relative inline-flex items-center gap-2 overflow-hidden rounded-lg bg-gradient-to-r from-indigo-400 to-indigo-500 px-6 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-300 hover:from-indigo-700 hover:to-indigo-400 hover:shadow-lg active:scale-95"
                >
                    <PlusIcon class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" />
                    <span>Add Skill</span>
                </button>
            </div>

            <!-- Skills Grid -->
            <SectionCard>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div
                        v-for="skill in props.skills.data"
                        :key="skill.id"
                        class="group rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-xl hover:scale-[1.02]"
                    >
                        <!-- Skill Content -->
                        <div class="p-4">
                            <!-- Icon and Actions -->
                            <div class="relative mb-3">
                                <div class="flex items-center justify-center">
                                    <div class="relative h-20 w-20 overflow-hidden rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 p-2">
                                        <img 
                                            v-if="skill.icon"
                                            :src="getImageUrl(skill.icon) || 'https://via.placeholder.com/80x80?text=No+Icon'"
                                            :alt="skill.name"
                                            class="h-full w-full object-contain"
                                        />
                                        <CodeBracketIcon v-else class="h-full w-full text-gray-400" />
                                    </div>
                                </div>
                                <div class="absolute top-0 right-0 flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                    <button
                                        @click="openEdit(skill)"
                                        class="rounded-lg bg-white p-1.5 text-indigo-400 shadow-md transition-all hover:bg-indigo-400 hover:text-white"
                                        title="Edit"
                                    >
                                        <PencilIcon class="h-3 w-3" />
                                    </button>
                                    <button
                                        @click="openDelete(skill)"
                                        class="rounded-lg bg-white p-1.5 text-red-600 shadow-md transition-all hover:bg-red-600 hover:text-white"
                                        title="Delete"
                                    >
                                        <TrashIcon class="h-3 w-3" />
                                    </button>
                                </div>
                            </div>

                            <!-- Skill Info -->
                            <div class="text-center">
                                <h3 class="font-semibold text-gray-900 line-clamp-1">{{ skill.name }}</h3>
                                <p class="mt-0.5 text-xs text-gray-500">{{ skill.slug }}</p>
                            </div>

                            <!-- Proficiency Bar -->
                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-gray-600">Proficiency</span>
                                    <span class="font-medium text-gray-900">{{ skill.proficiency || 0 }}%</span>
                                </div>
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-200">
                                    <div 
                                        class="h-full rounded-full transition-all duration-500"
                                        :class="getProficiencyColor(skill.proficiency)"
                                        :style="{ width: `${skill.proficiency || 0}%` }"
                                    ></div>
                                </div>
                            </div>

                            <!-- Status and Order -->
                            <div class="mt-3 flex items-center justify-between">
                                <span :class="['inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold', getStatusInfo(skill.status).color]">
                                    <span>{{ getStatusInfo(skill.status).icon }}</span>
                                    <span>{{ skill.status }}</span>
                                </span>
                                <span class="text-xs text-gray-400">Order: {{ skill.sort_order }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="props.skills.data.length === 0" class="col-span-full">
                        <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                            <CodeBracketIcon class="h-16 w-16 text-gray-400" />
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">No skills yet</h3>
                            <p class="mt-2 text-sm text-gray-500">Get started by adding your first skill</p>
                            <button
                                @click="openCreate"
                                class="group rounded-xl bg-indigo-400/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                                style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                            >
                                <span class="flex items-center">
                                    <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                                    <span class="relative">
                                        Add Skill
                                        <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-white transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </span>
                                <!-- <PlusIcon class="h-4 w-4" /> -->
                                
                            </button>
                        </div>
                    </div>
                </div>
            </SectionCard>
        </div>

        <!-- Create Modal -->
        <Modal :show="isCreateOpen" max-width="3xl" @close="closeCreate">
            <div class="p-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Add New Skill</h3>
                        <p class="mt-1 text-sm text-gray-500">Add a technology or skill to your portfolio</p>
                    </div>
                    <button @click="closeCreate" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>

                <form class="mt-6" @submit.prevent="submitCreate">
                    <SkillFormFields 
                        :form="createForm" 
                        :status-options="statusOptions" 
                        prefix="create"
                    />
                    
                    
                    <div class="md:col-span-2 mt-3 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeCreate"
                            class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="createForm.processing"
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
                                    Create Skill
                                    <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-white transition-all duration-300 group-hover:w-full"></span>
                                </span>
                            </span>
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Modal -->
        <Modal :show="isEditOpen" max-width="3xl" @close="closeEdit">
            <div class="p-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Edit Skill</h3>
                        <p class="mt-1 text-sm text-gray-500">Update skill details</p>
                    </div>
                    <button @click="closeEdit" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>

                <form class="mt-6" @submit.prevent="submitUpdate">
                    <SkillFormFields 
                        :form="editForm" 
                        :status-options="statusOptions" 
                        prefix="edit"
                    />
                    <div class="md:col-span-2 mt-3 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeEdit"
                            class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="editForm.processing"
                        >
                            <svg class="h-4 w-4 text-gray-500 transition-all duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Cancel</span>
                        </button>
                        <LoadingButton 
                            :loading="editForm.processing" 
                            :disabled="editForm.processing"
                            class="group rounded-xl bg-indigo-400/90 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:backdrop-blur-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);"
                        >
                            <span class="flex items-center">
                                <PlusIcon class="mr-2 h-5 w-5 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" />
                                <span class="relative">
                                    Update Skill
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
                    <h3 class="text-lg font-semibold text-gray-900">Delete Skill</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Are you sure you want to delete "<span class="font-semibold">{{ deleting?.name }}</span>"?
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
                        Delete Skill
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

.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>