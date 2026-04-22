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
    <section id="contact" class="mt-20 rounded-3xl bg-indigo-600 px-8 py-12 text-white">
        <div class="mx-auto max-w-4xl">
            <p class="text-center text-sm uppercase tracking-[0.14em] text-indigo-200">Contact</p>
            <h2 class="mt-2 text-center text-3xl font-bold">Have a project in mind?</h2>
            <p class="mx-auto mt-3 max-w-2xl text-center text-indigo-100">
                Let us discuss your idea and build something great together.
            </p>

            <div
                v-if="isSubmitted"
                class="mx-auto mt-8 max-w-2xl rounded-2xl border border-white/20 bg-white/10 px-6 py-8 text-center"
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
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-indigo-200 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="Your name"
                    />
                    <p v-if="form.errors.name" class="mt-2 text-sm text-rose-200">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="contact_email" class="mb-2 block text-sm font-medium text-white">Email</label>
                    <input
                        id="contact_email"
                        v-model="form.email"
                        type="email"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-indigo-200 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="you@example.com"
                    />
                    <p v-if="form.errors.email" class="mt-2 text-sm text-rose-200">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="contact_phone" class="mb-2 block text-sm font-medium text-white">Phone</label>
                    <input
                        id="contact_phone"
                        v-model="form.phone"
                        type="text"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-indigo-200 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="Optional phone number"
                    />
                    <p v-if="form.errors.phone" class="mt-2 text-sm text-rose-200">{{ form.errors.phone }}</p>
                </div>

                <div>
                    <label for="contact_subject" class="mb-2 block text-sm font-medium text-white">Subject</label>
                    <input
                        id="contact_subject"
                        v-model="form.subject"
                        type="text"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-indigo-200 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="What do you need help with?"
                    />
                    <p v-if="form.errors.subject" class="mt-2 text-sm text-rose-200">{{ form.errors.subject }}</p>
                </div>

                <div class="md:col-span-2">
                    <label for="contact_message" class="mb-2 block text-sm font-medium text-white">Message</label>
                    <textarea
                        id="contact_message"
                        v-model="form.message"
                        rows="5"
                        class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-indigo-200 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                        placeholder="Tell me about your project"
                    />
                    <p v-if="form.errors.message" class="mt-2 text-sm text-rose-200">{{ form.errors.message }}</p>
                </div>

                <div class="md:col-span-2 flex justify-center">
                    <LoadingButton
                        :loading="form.processing"
                        :disabled="form.processing"
                        loading-text="Sending..."
                        class="bg-white !text-indigo-700 hover:!bg-indigo-50 focus:!bg-indigo-50 active:!bg-white"
                    >
                        Send message
                    </LoadingButton>
                </div>
            </form>
        </div>
    </section>
</template>
