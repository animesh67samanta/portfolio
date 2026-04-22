<script setup lang="ts">
type Project = {
    id: number;
    title: string;
    summary: string | null;
    thumbnail_path: string | null;
    project_url: string | null;
    repository_url: string | null;
};

defineProps<{
    projects: Project[];
    imageUrl: (path?: string | null) => string;
}>();
</script>

<template>
    <section id="projects" class="mt-20">
        <div class="mb-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-indigo-600">Projects</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">Latest Work</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <article
                v-for="project in projects"
                :key="project.id"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
            >
                <img
                    v-if="project.thumbnail_path"
                    :src="imageUrl(project.thumbnail_path)"
                    :alt="project.title"
                    class="h-52 w-full object-cover"
                />
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-900">{{ project.title }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ project.summary }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a
                            v-if="project.project_url"
                            :href="project.project_url"
                            target="_blank"
                            class="rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700"
                        >
                            Live
                        </a>
                        <a
                            v-if="project.repository_url"
                            :href="project.repository_url"
                            target="_blank"
                            class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Code
                        </a>
                    </div>
                </div>
            </article>
        </div>
    </section>
</template>
