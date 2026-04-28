<script setup lang="ts">
import { ExclamationTriangleIcon, KeyIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-4">
        <div class="rounded-lg bg-red-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <ExclamationTriangleIcon class="h-5 w-5 text-red-600" />
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-red-800">Warning: Irreversible Action</h4>
                    <p class="mt-1 text-sm text-red-700">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Before deleting your account, please download any data or information that you wish to retain.
                    </p>
                </div>
            </div>
        </div>

        <DangerButton 
            @click="confirmUserDeletion"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-red-600 to-red-500 px-6 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-200 hover:from-red-700 hover:to-red-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:scale-95"
        >
            <TrashIcon class="h-4 w-4" />
            Delete Account
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal" max-width="lg">
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <div class="rounded-full bg-red-100 p-3">
                        <ExclamationTriangleIcon class="h-8 w-8 text-red-600" />
                    </div>
                </div>
                
                <h2 class="mt-4 text-center text-xl font-semibold text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-2 text-center text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                    Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <KeyIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full rounded-lg border-gray-300 pl-10 focus:border-red-500 focus:ring-red-500"
                            placeholder="Enter your password to confirm"
                            @keyup.enter="deleteUser"
                        />
                    </div>
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton 
                        @click="closeModal"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-red-600 to-red-500 px-4 py-2 text-sm font-medium text-white shadow-md transition-all duration-200 hover:from-red-700 hover:to-red-600"
                    >
                        <TrashIcon class="h-4 w-4" />
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>