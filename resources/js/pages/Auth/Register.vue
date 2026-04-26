<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { EyeIcon, EyeSlashIcon, UserIcon, EnvelopeIcon, KeyIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);
const termsAccepted = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create an account" />

        <div class="w-full max-w-md mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Create an account</h1>
                <p class="mt-2 text-gray-600">Join us today and get started</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full name
                    </label>
                    <div class="relative">
                        <input
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                            :class="{ 'border-red-500 focus:border-red-500': form.errors.name }"
                            placeholder="John Doe"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <UserIcon class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email address
                    </label>
                    <div class="relative">
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                            :class="{ 'border-red-500 focus:border-red-500': form.errors.email }"
                            placeholder="hello@example.com"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <EnvelopeIcon class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                    <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                            :class="{ 'border-red-500 focus:border-red-500': form.errors.password }"
                            placeholder="Create a password"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="focus:outline-none"
                            >
                                <EyeIcon v-if="!showPassword" class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                                <EyeSlashIcon v-else class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                            </button>
                        </div>
                    </div>
                    <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <p>Password must contain:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>At least 8 characters</li>
                            <li>At least one uppercase letter</li>
                            <li>At least one number</li>
                        </ul>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm password
                    </label>
                    <div class="relative">
                        <input
                            :type="showPasswordConfirmation ? 'text' : 'password'"
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200"
                            :class="{ 'border-red-500 focus:border-red-500': form.errors.password_confirmation }"
                            placeholder="Confirm your password"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button
                                type="button"
                                @click="showPasswordConfirmation = !showPasswordConfirmation"
                                class="focus:outline-none"
                            >
                                <EyeIcon v-if="!showPasswordConfirmation" class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                                <EyeSlashIcon v-else class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                            </button>
                        </div>
                    </div>
                    <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ form.errors.password_confirmation }}</p>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="terms"
                            v-model="termsAccepted"
                            type="checkbox"
                            required
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                        />
                    </div>
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        I agree to the 
                        <a href="#" class="text-blue-600 hover:text-blue-700">Terms of Service</a> 
                        and 
                        <a href="#" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing || !termsAccepted"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <span v-else>Create account</span>
                </button>

                <!-- Sign In Link -->
                <div class="text-center pt-4">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <Link 
                            :href="route('login')" 
                            class="font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200"
                        >
                            Sign in
                        </Link>
                    </p>
                </div>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">Or sign up with</span>
                </div>
            </div>

            <!-- Social Registration Options -->
            <div class="grid grid-cols-2 gap-4">
                <button class="flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Google</span>
                </button>
                <button class="flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.07C24 5.41 18.63 0 12 0S0 5.41 0 12.07C0 18.1 4.39 23.09 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.8-4.7 4.56-4.7 1.32 0 2.7.24 2.7.24v2.97h-1.52c-1.5 0-1.96.93-1.96 1.89v2.27h3.34l-.53 3.49h-2.81V24C19.61 23.09 24 18.1 24 12.07z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Facebook</span>
                </button>
            </div>
        </div>
    </GuestLayout>
</template>