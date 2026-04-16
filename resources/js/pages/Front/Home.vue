<script setup lang="ts">
import FrontLayout from '@/Layouts/FrontLayout.vue';
import ProjectsGrid from '@/Components/Frontend/ProjectsGrid.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Home" />

    <FrontLayout page-title="Portfolio">
        <!-- Banners Hero -->
        <div v-if="banners.length" class="relative h-96 overflow-hidden rounded-lg mb-8">
            <div v-for="(banner, index) in banners.slice(0, 1)" :key="index" class="absolute inset-0">
                <img :src="`/storage/${banner.image_path}`" :alt="banner.subtitle" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                    <div class="text-center text-white">
                        <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ banner.subtitle }}</h1>
                        <a v-if="banner.cta_url" :href="banner.cta_url" class="bg-white text-black px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                            {{ banner.cta_text }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Projects -->
        <section v-if="projects.length" class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-center">Featured Projects</h2>
            <ProjectsGrid :projects="projects" />
        </section>

        <!-- Skills -->
        <section v-if="skills.length" class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-center">Skills</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <div v-for="skill in skills" :key="skill.id" class="text-center p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    {{ skill.name }}
                </div>
            </div>
        </section>

        <!-- Recent Blogs -->
        <section v-if="recent_blogs.length" class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-center">Recent Blogs</h2>
            <!-- Add blog grid -->
        </section>

        <!-- Testimonials -->
        <section v-if="testimonials.length" class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-center">Testimonials</h2>
            <!-- Add testimonial carousel -->
        </section>
    </FrontLayout>
</template>


