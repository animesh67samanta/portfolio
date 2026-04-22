<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import AboutSection from '@/Components/Frontend/AboutSection.vue';
import BlogSection from '@/Components/Frontend/BlogSection.vue';
import ContactCtaSection from '@/Components/Frontend/ContactCtaSection.vue';
import HeroSection from '@/Components/Frontend/HeroSection.vue';
import ProjectsSection from '@/Components/Frontend/ProjectsSection.vue';
import SkillsSection from '@/Components/Frontend/SkillsSection.vue';
import TestimonialsSection from '@/Components/Frontend/TestimonialsSection.vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';

type Banner = {
    id: number;
    title: string;
    subtitle: string | null;
    image_path: string;
    cta_text: string | null;
    cta_url: string | null;
};

type About = {
    heading: string;
    content: string;
    photo_path: string;
    resume_url: string | null;
};

type Skill = {
    id: number;
    name: string;
    proficiency: number | null;
    icon: string | null;
};

type Project = {
    id: number;
    title: string;
    summary: string | null;
    thumbnail_path: string | null;
    project_url: string | null;
    repository_url: string | null;
};

type Blog = {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    featured_image: string | null;
    published_at: string | null;
};

type Testimonial = {
    id: number;
    client_name: string;
    client_role: string | null;
    company: string | null;
    quote: string;
    rating: number | null;
};

type CollectionLike<T> = T[] | { data?: T[] } | null | undefined;

const props = defineProps<{
    banners: CollectionLike<Banner>;
    about: About | null;
    skills: CollectionLike<Skill>;
    featured_projects: CollectionLike<Project>;
    testimonials: CollectionLike<Testimonial>;
    recent_blogs: CollectionLike<Blog>;
}>();

const toArray = <T>(value: CollectionLike<T>): T[] => {
    if (Array.isArray(value)) {
        return value;
    }

    if (value && Array.isArray(value.data)) {
        return value.data;
    }

    return [];
};

const banners = computed(() => toArray(props.banners));
const skills = computed(() => toArray(props.skills));
const featuredProjects = computed(() => toArray(props.featured_projects));
const testimonials = computed(() => toArray(props.testimonials));
const recentBlogs = computed(() => toArray(props.recent_blogs));
const imageUrl = (path?: string | null): string => {
    if (!path) {
        return '';
    }

    if (path.startsWith('http')) {
        return path;
    }

    const normalizedPath = path
        .replace(/^\/+/, '')
        .replace(/^public\//, '')
        .replace(/^storage\/public\//, 'storage/');

    return `/${normalizedPath}`;
};

const formatDate = (date: string | null): string => {
    if (!date) {
        return '';
    }

    const parsedDate = new Date(date);

    if (Number.isNaN(parsedDate.getTime())) {
        return '';
    }

    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
    }).format(parsedDate);
};
</script>

<template>
    <Head title="Home" />

    <FrontLayout>
        <HeroSection
            :banners="banners"
            :image-url="imageUrl"
            :resume-url="about?.resume_url"
        />

        <AboutSection
            v-if="about"
            :heading="about.heading"
            :content="about.content"
            :image-url="imageUrl(about.photo_path)"
        />

        <SkillsSection v-if="skills.length" :skills="skills" />
        <ProjectsSection v-if="featuredProjects.length" :projects="featuredProjects" :image-url="imageUrl" />
        <TestimonialsSection v-if="testimonials.length" :testimonials="testimonials" />
        <BlogSection v-if="recentBlogs.length" :blogs="recentBlogs" :image-url="imageUrl" :format-date="formatDate" />
        <ContactCtaSection />
    </FrontLayout>
</template>


