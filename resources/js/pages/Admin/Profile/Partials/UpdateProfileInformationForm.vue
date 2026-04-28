<script setup lang="ts">
import { EnvelopeIcon, UserIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import type { PageProps } from '@/types';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();

const user = usePage<PageProps>().props.auth.user;

const form = useForm({
    name: user?.name ?? '',
    email: user?.email ?? '',
});
</script>

<template>
    <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Name Field -->
            <div class="md:col-span-2">
                <InputLabel for="name" value="Full Name" class="text-sm font-medium text-gray-700" />
                <div class="relative mt-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <UserIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="name"
                        type="text"
                        class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email Field -->
            <div class="md:col-span-2">
                <InputLabel for="email" value="Email Address" class="text-sm font-medium text-gray-700" />
                <div class="relative mt-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        placeholder="john@example.com"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
        </div>

        <!-- Email Verification -->
        <div v-if="mustVerifyEmail && user?.email_verified_at === null" class="rounded-lg bg-yellow-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-yellow-800">
                        Your email address is unverified.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="font-medium text-yellow-800 underline hover:text-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>
                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 text-sm font-medium text-green-600"
                    >
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-4">
            <PrimaryButton 
                :disabled="form.processing"
                class="relative inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-400 to-indigo-500 px-6 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-200 hover:from-indigo-700 hover:to-indigo-400 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50"
            >
                <CheckCircleIcon class="h-4 w-4" />
                Save Changes
            </PrimaryButton>

            <Transition
                enter-active-class="transition ease-in-out duration-200"
                enter-from-class="opacity-0 transform scale-95"
                enter-to-class="opacity-100 transform scale-100"
                leave-active-class="transition ease-in-out duration-200"
                leave-from-class="opacity-100 transform scale-100"
                leave-to-class="opacity-0 transform scale-95"
            >
                <div
                    v-if="form.recentlySuccessful"
                    class="flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm text-green-700"
                >
                    <CheckCircleIcon class="h-4 w-4" />
                    Saved successfully!
                </div>
            </Transition>
        </div>
    </form>
</template>