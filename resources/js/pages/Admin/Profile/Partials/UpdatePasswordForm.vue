<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { KeyIcon, LockClosedIcon, ShieldCheckIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};

const getPasswordStrength = () => {
    const password = form.password;
    if (!password) return { level: 0, text: 'Too Short', color: 'bg-gray-400', message: '' };
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    const levels: Array<{ text: string; color: string; message?: string }> = [
        { text: 'Very Weak', color: 'bg-red-500', message: 'Use a longer password' },
        { text: 'Weak', color: 'bg-orange-500', message: 'Add numbers and symbols' },
        { text: 'Fair', color: 'bg-yellow-500', message: 'Good start, make it stronger' },
        { text: 'Strong', color: 'bg-green-500', message: 'Excellent password!' },
        { text: 'Very Strong', color: 'bg-emerald-500', message: 'Perfect! Very secure' },
    ];
    
    const strengthInfo = levels[strength] || levels[0];
    return { 
        level: strength, 
        text: strengthInfo.text, 
        color: strengthInfo.color,
        message: strengthInfo.message || '' 
    };
};


</script>

<template>
    <form @submit.prevent="updatePassword" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
            <!-- Current Password -->
            <div>
                <InputLabel for="current_password" value="Current Password" class="text-sm font-medium text-gray-700" />
                <div class="relative mt-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <KeyIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500"
                        autocomplete="current-password"
                        placeholder="Enter your current password"
                    />
                </div>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <!-- New Password -->
            <div>
                <InputLabel for="password" value="New Password" class="text-sm font-medium text-gray-700" />
                <div class="relative mt-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <LockClosedIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500"
                        autocomplete="new-password"
                        placeholder="Enter new password"
                    />
                </div>
                
                <!-- Password Strength Indicator -->
                <div v-if="form.password" class="mt-3 space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-gray-200">
                            <div 
                                class="h-full transition-all duration-300"
                                :class="getPasswordStrength().color"
                                :style="{ width: `${(getPasswordStrength().level / 4) * 100}%` }"
                            ></div>
                        </div>
                        <span class="text-xs font-medium" :class="getPasswordStrength().color.replace('bg-', 'text-')">
                            {{ getPasswordStrength().text }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ getPasswordStrength().message }}</p>
                </div>
                
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <InputLabel for="password_confirmation" value="Confirm New Password" class="text-sm font-medium text-gray-700" />
                <div class="relative mt-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <ShieldCheckIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500"
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                    />
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>
        </div>

        <!-- Password Requirements -->
        <div class="rounded-lg bg-blue-50 p-4">
            <p class="text-sm font-medium text-blue-800 mb-2">Password requirements:</p>
            <ul class="space-y-1 text-xs text-blue-700">
                <li class="flex items-center gap-2">
                    <span :class="form.password.length >= 8 ? 'text-green-600' : 'text-blue-600'">
                        {{ form.password.length >= 8 ? '✓' : '○' }}
                    </span>
                    At least 8 characters
                </li>
                <li class="flex items-center gap-2">
                    <span :class="/[a-z]/.test(form.password) && /[A-Z]/.test(form.password) ? 'text-green-600' : 'text-blue-600'">
                        {{ /[a-z]/.test(form.password) && /[A-Z]/.test(form.password) ? '✓' : '○' }}
                    </span>
                    Uppercase and lowercase letters
                </li>
                <li class="flex items-center gap-2">
                    <span :class="/\d/.test(form.password) ? 'text-green-600' : 'text-blue-600'">
                        {{ /\d/.test(form.password) ? '✓' : '○' }}
                    </span>
                    At least one number
                </li>
                <li class="flex items-center gap-2">
                    <span :class="/[^a-zA-Z0-9]/.test(form.password) ? 'text-green-600' : 'text-blue-600'">
                        {{ /[^a-zA-Z0-9]/.test(form.password) ? '✓' : '○' }}
                    </span>
                    At least one special character
                </li>
            </ul>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-4">
            <PrimaryButton 
                :disabled="form.processing"
                class="relative inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-200 hover:from-indigo-700 hover:to-indigo-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50"
            >
                <CheckCircleIcon class="h-4 w-4" />
                Update Password
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
                    Password updated!
                </div>
            </Transition>
        </div>
    </form>
</template>