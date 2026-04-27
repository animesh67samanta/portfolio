<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import LoadingButton from '@/Components/LoadingButton.vue';

const isSubmitted = ref(false);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
});

const submit = (): void => {
    form.post(route('contacts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isSubmitted.value = true;
            form.reset();
            form.clearErrors();
        },
    });
};
</script>

<template>
    <section id="contact" class="mt-20 bg-[#f8f8f8] px-4 sm:px-8 py-12 text-white max-w-8xl mx-auto shadow-lg">
        <div class="mx-auto max-w-4xl bg-indigo-500 rounded-3xl px-4 sm:px-8 py-12">
            <div class="text-center mb-10">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-800">Contact</p>
                <h2 class="mt-2 text-center text-3xl font-bold">Have a project in mind?</h2>
                <p class="mx-auto mt-3 max-w-2xl text-center text-indigo-100">
                    Let us discuss your idea and build something great together.
                </p>
            </div>

            <div
                v-if="isSubmitted"
                class="mx-auto mt-8 max-w-2xl rounded-2xl border border-white/20 bg-white px-6 py-8 text-center text-slate-900"
            >
                <p class="text-lg font-semibold">Thank you.</p>
                <p class="mt-2 text-indigo-100">I will contact you very soon.</p>
            </div>

            <form v-else class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
                <div>
                    <label for="contact_name" class="mb-2 block text-sm font-medium text-white">Name</label>
                    <input
                        id="contact_name"
                        v-model="form.name"
                        type="text"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="Your name"
                    />
                    <p v-if="form.errors.name" class="mt-2 text-sm text-orange-300">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="contact_email" class="mb-2 block text-sm font-medium text-white">Email</label>
                    <input
                        id="contact_email"
                        v-model="form.email"
                        type="email"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="you@example.com"
                    />
                    <p v-if="form.errors.email" class="mt-2 text-sm text-orange-300">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="contact_phone" class="mb-2 block text-sm font-medium text-white">Phone</label>
                    <input
                        id="contact_phone"
                        v-model="form.phone"
                        type="text"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="Optional phone number"
                    />
                    <p v-if="form.errors.phone" class="mt-2 text-sm text-orange-300">{{ form.errors.phone }}</p>
                </div>

                <div>
                    <label for="contact_subject" class="mb-2 block text-sm font-medium text-white">Subject</label>
                    <input
                        id="contact_subject"
                        v-model="form.subject"
                        type="text"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="What do you need help with?"
                    />
                    <p v-if="form.errors.subject" class="mt-2 text-sm text-orange-300">{{ form.errors.subject }}</p>
                </div>

                <div class="md:col-span-2">
                    <label for="contact_message" class="mb-2 block text-sm font-medium text-white">Message</label>
                    <textarea
                        id="contact_message"
                        v-model="form.message"
                        rows="5"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="Tell me about your project"
                    />
                    <p v-if="form.errors.message" class="mt-2 text-sm text-orange-300">{{ form.errors.message }}</p>
                </div>

                <div class="md:col-span-2 flex justify-center">
                    <LoadingButton
                        :loading="form.processing"
                        :disabled="form.processing"
                        loading-text="Sending..."
                        class="relative px-8 py-3 rounded-xl font-semibold text-white
                            bg-gradient-to-r from-[#2cf5b1] via-[#0aa8d9] to-[#022c9c]
                            shadow-lg shadow-[#022c9c]/30
                            hover:shadow-[#022c9c]/50 hover:scale-[1.03]
                            active:scale-[0.98]
                            transition-all duration-300
                            disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span class="flex items-center gap-2">
                            Send Message
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </span>
                    </LoadingButton>
                </div>
            </form>
        </div>
    </section>
</template>
