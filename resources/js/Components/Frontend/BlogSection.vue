<script setup lang="ts">
type Blog = {
    id: number;
    title: string;
    excerpt: string | null;
    featured_image: string | null;
    published_at: string | null;
};

defineProps<{
    blogs: Blog[];
    imageUrl: (path?: string | null) => string;
    formatDate: (date: string | null) => string;
}>();
</script>

<template>
    <section id="blog" class="mt-20">
        <div class="mb-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-indigo-600">Blog</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">Recent Posts</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            <article
                v-for="blog in blogs"
                :key="blog.id"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
            >
                <img
                    v-if="blog.featured_image"
                    :src="imageUrl(blog.featured_image)"
                    :alt="blog.title"
                    class="h-44 w-full object-cover"
                />
                <div class="p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">{{ formatDate(blog.published_at) }}</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ blog.title }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ blog.excerpt }}</p>
                </div>
            </article>
        </div>
    </section>
</template>
