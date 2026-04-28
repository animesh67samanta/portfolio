<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ContactFormFields from '@/Components/Admin/Forms/ContactFormFields.vue';
import LoadingButton from '@/Components/LoadingButton.vue';
import Modal from '@/Components/Modal.vue';
import SectionCard from '@/Components/SectionCard.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

type Contact = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    subject: string | null;
    message: string;
    is_read: boolean;
    status: 'new' | 'in_progress' | 'closed';
    responded_at: string | null;
};

const props = defineProps<{
    contacts: {
        data: Contact[];
    };
    selectedContact?: Contact | null;
}>();

// const statusOptions = [
//     { value: 'new', label: 'New' },
//     { value: 'in_progress', label: 'In progress' },
//     { value: 'closed', label: 'Closed' },
// ] as const;

// const createForm = useForm({
//     name: '',
//     email: '',
//     phone: '',
//     subject: '',
//     message: '',
//     is_read: false,
//     status: 'new' as 'new' | 'in_progress' | 'closed',
// });

const isEditOpen = ref(false);
const isReplyOpen = ref(false);
const editing = ref<Contact | null>(props.selectedContact ?? null);

const editForm = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
    is_read: false,
    status: 'new' as 'new' | 'in_progress' | 'closed',
});

const replyForm = useForm({
    message: '',
});

// const submitCreate = () => {
//     createForm.post(route('admin.contacts.store'), {
//         onSuccess: () => {
//             createForm.reset();
//             createForm.clearErrors();
//         },
//     });
// };

// const openEdit = (contact: Contact) => {
//     editing.value = contact;
//     editForm.defaults({
//         name: contact.name,
//         email: contact.email,
//         phone: contact.phone ?? '',
//         subject: contact.subject ?? '',
//         message: contact.message,
//         is_read: contact.is_read,
//         status: contact.status,
//     });
//     editForm.reset();
//     editForm.clearErrors();
//     isEditOpen.value = true;
// };

const closeEdit = () => {
    isEditOpen.value = false;
    editForm.reset();
    editForm.clearErrors();
};

// const submitUpdate = () => {
//     if (!editing.value) {
//         return;
//     }

//     editForm.patch(route('admin.contacts.update', editing.value.id), {
//         onSuccess: () => closeEdit(),
//     });
// };

const markReadState = (contact: Contact, read: boolean) => {
    const form = useForm({});
    form.patch(route(read ? 'admin.contacts.read' : 'admin.contacts.unread', contact.id));
};

const openReply = (contact: Contact) => {
    editing.value = contact;
    replyForm.reset();
    replyForm.clearErrors();
    isReplyOpen.value = true;
};

const closeReply = () => {
    isReplyOpen.value = false;
    replyForm.reset();
    replyForm.clearErrors();
};

const submitReply = () => {
    if (!editing.value) {
        return;
    }

    replyForm.post(route('admin.contacts.reply', editing.value.id), {
        onSuccess: () => closeReply(),
    });
};

const destroyContact = (contact: Contact) => {
    if (!confirm(`Delete message from "${contact.name}"?`)) {
        return;
    }

    const form = useForm({});
    form.delete(route('admin.contacts.destroy', contact.id));
};
</script>

<template>
    <Head title="Contacts" />

    <AdminLayout page-title="Contacts">
        <div class="space-y-6">
        

            <SectionCard>
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-900">Inbox</h2>
                    <p class="text-sm text-slate-600">{{ props.contacts.data.length }} messages</p>
                </div>
                <div class="mt-6 overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Sender</th>
                                <th class="px-4 py-3">Subject</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Read</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="contact in props.contacts.data" :key="contact.id" class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-900">{{ contact.name }}</p>
                                    <p class="text-xs text-slate-500">{{ contact.email }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ contact.subject ?? 'No subject' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ contact.status }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ contact.is_read ? 'Read' : 'Unread' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <!-- <button type="button" class="rounded-md px-2 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100" @click="openEdit(contact)">Edit</button> -->
                                    <button type="button" class="rounded-md px-2 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50" @click="openReply(contact)">Reply</button>
                                    <button type="button" class="rounded-md px-2 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50" @click="markReadState(contact, !contact.is_read)">
                                        {{ contact.is_read ? 'Unread' : 'Read' }}
                                    </button>
                                    <button type="button" class="rounded-md px-2 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-50" @click="destroyContact(contact)">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="props.contacts.data.length === 0">
                                <td colspan="5" class="px-4 py-8 text-center text-slate-600">No contacts yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </SectionCard>
        </div>

        <!-- <Modal :show="isEditOpen" max-width="2xl" @close="closeEdit">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900">Edit Contact</h3>
                <p class="mt-1 text-sm text-slate-600">Update contact and conversation details.</p>
                <form class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submitUpdate">
                    <ContactFormFields :form="editForm" :status-options="statusOptions" prefix="edit" />
                    <div class="md:col-span-2 flex justify-end gap-2">
                        <button type="button" class="rounded-md px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" @click="closeEdit">Cancel</button>
                        <LoadingButton :loading="editForm.processing" :disabled="editForm.processing">Save changes</LoadingButton>
                    </div>
                </form>
            </div>
        </Modal> -->

        <Modal :show="isReplyOpen" max-width="lg" @close="closeReply">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900">Reply to Contact</h3>
                <p class="mt-1 text-sm text-slate-600">Send an email reply to {{ editing?.email }}.</p>
                <form class="mt-6 space-y-4" @submit.prevent="submitReply">
                    <label class="block text-sm font-medium text-slate-700" for="reply_message">Reply message</label>
                    <textarea
                        id="reply_message"
                        v-model="replyForm.message"
                        class="textarea-field min-h-36 w-full focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <p v-if="replyForm.errors.message" class="text-sm text-rose-600">{{ replyForm.errors.message }}</p>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="rounded-md px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" @click="closeReply">Cancel</button>
                        <LoadingButton :loading="replyForm.processing" :disabled="replyForm.processing">Send reply</LoadingButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AdminLayout>
</template>
