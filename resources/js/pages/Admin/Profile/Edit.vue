<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { UserCircleIcon, KeyIcon, TrashIcon } from '@heroicons/vue/24/outline';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <AdminLayout page-title="Profile Settings">
        <div class="space-y-6">
            <!-- Profile Information -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md">
                <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-transparent px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-indigo-100 p-2">
                            <UserCircleIcon class="h-6 w-6 text-indigo-600" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                            <p class="text-sm text-gray-500">Update your account's profile information and email address</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />
                </div>
            </div>

            <!-- Update Password -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md">
                <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-transparent px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-indigo-100 p-2">
                            <KeyIcon class="h-6 w-6 text-indigo-600" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Update Password</h3>
                            <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <UpdatePasswordForm />
                </div>
            </div>

            <!-- Delete Account -->
            <div class="overflow-hidden rounded-2xl border border-red-200 bg-white shadow-sm transition-all hover:shadow-md">
                <div class="border-b border-red-200 bg-gradient-to-r from-red-50 to-transparent px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-red-100 p-2">
                            <TrashIcon class="h-6 w-6 text-red-600" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Delete Account</h3>
                            <p class="text-sm text-gray-500">Permanently delete your account and all associated data</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>