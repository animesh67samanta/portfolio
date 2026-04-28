<script setup lang="ts">
import { EyeIcon, CalendarIcon, UserIcon, ClockIcon, ArrowLeftIcon, ShareIcon, BookmarkIcon } from '@heroicons/vue/24/outline';
import { XMarkIcon } from '@heroicons/vue/24/solid';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import Modal from '@/Components/Modal.vue';

// Utility: create deep clone of blog array
type BlogItem = {
    id: number;
    title: string;
    excerpt: string | null;
    content?: string | null;
    featured_image: string | null;
    cover_image?: string | null;
    published_at: string | null;
    views_count: number;
    [key: string]: unknown;
};

type Blog = {
    id: number;
    title: string;
    excerpt: string | null;
    content: string | null;
    featured_image: string | null;
    cover_image: string | null;
    published_at: string | null;
    views_count: number;
};

type BlogDetail = {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string | null;
    featured_image: string | null;
    cover_image: string | null;
    published_at: string | null;
    views_count: number;
    author?: {
        name: string;
        avatar?: string;
    };
    reading_time?: number;
};

const props = defineProps<{
    blogs: Blog[];
    imageUrl: (path?: string | null) => string;
    formatDate: (date: string | null) => string;
}>();

const displayCount = ref(5);
const selectedBlog = ref<BlogDetail | null>(null);
const showModal = ref(false);
const isLoading = ref(false);
const showShareTooltip = ref(false);
const isBookmarked = ref(false);

// Create local reactive copy so we can update view counts without reloading
const localBlogs = ref<BlogItem[]>(JSON.parse(JSON.stringify(props.blogs)));

const visibleBlogs = computed(() => localBlogs.value.slice(0, displayCount.value));
const hasMore = computed(() => localBlogs.value.length > displayCount.value);

const loadMore = () => {
    displayCount.value += 5;
};

const getCsrfToken = (): string => {
    const meta = document.querySelector('meta[name="csrf-token"]');

    return meta?.getAttribute('content') ?? '';
};

const calculateReadingTime = (content: string | null): number => {
    if (!content) {
return 1;
}

    const wordsPerMinute = 200;
    const words = content.replace(/<[^>]*>/g, '').split(/\s+/).length;

    return Math.max(1, Math.ceil(words / wordsPerMinute));
};

const openBlog = async (blog: BlogItem) => {
    isLoading.value = true;

    try {
        const response = await fetch(route('blogs.view', blog.id, false), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to load blog details');
        }

        const data = await response.json();

        // Update local blogs array so card view count reflects immediately
        const idx = localBlogs.value.findIndex((b) => b.id === blog.id);

        if (idx !== -1) {
            localBlogs.value[idx].views_count = data.views_count;
        }

        // Add reading time if not provided
        if (data.content && !data.reading_time) {
            data.reading_time = calculateReadingTime(data.content);
        }

        selectedBlog.value = data;
        showModal.value = true;
        document.body.style.overflow = 'hidden';
    } catch (error) {
        console.error('Error loading blog:', error);
    } finally {
        isLoading.value = false;
    }
};

const closeModal = () => {
    showModal.value = false;
    selectedBlog.value = null;
    document.body.style.overflow = '';
};

const shareBlog = async () => {
    if (selectedBlog.value) {
        const shareData = {
            title: selectedBlog.value.title,
            text: selectedBlog.value.excerpt || '',
            url: window.location.href + `/blog/${selectedBlog.value.slug}`,
        };
        
        try {
            await navigator.share(shareData);
        } catch (err) {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(shareData.url);
            showShareTooltip.value = true;
            setTimeout(() => {
                showShareTooltip.value = false;
            }, 2000);
        }
    }
};

const toggleBookmark = () => {
    isBookmarked.value = !isBookmarked.value;

    // Save to localStorage or send to backend
    if (selectedBlog.value) {
        const bookmarks = JSON.parse(localStorage.getItem('blog-bookmarks') || '[]');

        if (isBookmarked.value) {
            if (!bookmarks.includes(selectedBlog.value.id)) {
                bookmarks.push(selectedBlog.value.id);
            }
        } else {
            const index = bookmarks.indexOf(selectedBlog.value.id);

            if (index > -1) {
bookmarks.splice(index, 1);
}
        }

        localStorage.setItem('blog-bookmarks', JSON.stringify(bookmarks));
    }
};

onMounted(() => {
    // Check if current blog is bookmarked
    if (selectedBlog.value) {
        const bookmarks = JSON.parse(localStorage.getItem('blog-bookmarks') || '[]');
        isBookmarked.value = bookmarks.includes(selectedBlog.value.id);
    }
});

onUnmounted(() => {
    document.body.style.overflow = '';
});
</script>

<template>
    <section id="blog" class="mt-12 py-10 max-w-6xl mx-auto px-4 sm:px-6">
        <div class="mb-10 text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                Blog
            </p>
        
            <h2 class="mt-2 text-3xl font-bold uppercase tracking-[0.25em] text-slate-900">Recent Posts</h2>
        </div>
        
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <article
                v-for="blog in visibleBlogs"
                :key="blog.id"
                class="group overflow-hidden rounded-2xl border border-slate-200 bg-[#f8f8f8] shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
            >
                <div class="relative overflow-hidden h-48">
                    <img
                        v-if="blog.cover_image || blog.featured_image"
                        :src="imageUrl(blog.cover_image || blog.featured_image)"
                        :alt="blog.title"
                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-indigo-600">
                            {{ formatDate(blog.published_at) }}
                        </p>
                        <span class="flex items-center gap-1 text-xs text-slate-400">
                            <EyeIcon class="h-3.5 w-3.5" />
                            {{ blog.views_count }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-black-900 line-clamp-2 group-hover:text-black-600 transition-colors">
                        {{ blog.title }}
                    </h3>
                    <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ blog.excerpt }}</p>
                    <button
                        type="button"
                        class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-600 transition-all hover:bg-indigo-100 hover:scale-105"
                        :disabled="isLoading"
                        @click="openBlog(blog)"
                    >
                        <EyeIcon class="h-4 w-4" />
                        Read More
                    </button>
                </div>
            </article>
        </div>

        <div v-if="hasMore" class="mt-8 text-center">
            <button
                type="button"
                class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:text-slate-900 hover:shadow-md"
                @click="loadMore"
            >
                Load More Articles
            </button>
        </div>

        <!-- Modern Modal Design -->
        <Modal :show="showModal" max-width="5xl" @close="closeModal">
            <div v-if="selectedBlog" class="relative bg-white rounded-2xl overflow-hidden max-h-[90vh] flex flex-col">
                
                <!-- Hero Image / Cover Image -->
                <div class="relative h-64 md:h-96 overflow-hidden bg-gradient-to-br from-indigo-900 to-purple-900">
                    <img
                        v-if="selectedBlog.cover_image || selectedBlog.featured_image"
                        :src="imageUrl(selectedBlog.cover_image || selectedBlog.featured_image)"
                        :alt="selectedBlog.title"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                    <!-- Small Featured Image on top of Cover Image -->
                    <div
                        v-if="selectedBlog.featured_image"
                        class="absolute bottom-2 right-2 sm:bottom-4 sm:right-4 md:bottom-6 md:right-8 z-20"
                    >
                        <div class="relative group/thumb">

                            <!-- IMAGE -->
                            <img
                                :src="imageUrl(selectedBlog.featured_image)"
                                :alt="selectedBlog.title"
                                class="w-12 h-12 sm:w-16 sm:h-16 md:w-24 md:h-24
                                    rounded-lg sm:rounded-xl
                                    object-cover border-2 sm:border-4 border-white
                                    shadow-lg sm:shadow-2xl
                                    transition-transform duration-300
                                    group-hover/thumb:scale-105"
                            />

                            <!-- BADGE -->
                            <div class="absolute -bottom-1 -right-1 
                                        text-[8px] sm:text-[10px]
                                        px-1.5 sm:px-2 py-0.5
                                        bg-indigo-600 text-white font-semibold
                                        rounded-full shadow">
                                Featured
                            </div>

                        </div>
                    </div>

                    <!-- Floating Header Controls -->
                    <div class="absolute top-4 right-4 flex gap-2 z-10">
                        <button
                            @click="toggleBookmark"
                            class="p-2 rounded-full bg-black hover:text-black backdrop-blur-md text-white hover:bg-white/30 transition-all hover:scale-110"
                            :class="{ 'bg-yellow-500 text-yellow-900': isBookmarked }"
                        >
                            <BookmarkIcon class="h-5 w-5" />
                        </button>
                        <button
                            @click="shareBlog"
                            class="relative p-2 rounded-full hover:text-black bg-black backdrop-blur-md text-white hover:bg-white/30 transition-all hover:scale-110"
                        >
                            <ShareIcon class="h-5 w-5" />
                            <div v-if="showShareTooltip" class="absolute top-full right-0 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap">
                                Link copied!
                            </div>
                        </button>
                        <button
                            @click="closeModal"
                            class="p-2 rounded-full bg-black hover:text-black backdrop-blur-md text-white hover:bg-white/30 transition-all hover:scale-110"
                        >
                            <XMarkIcon class="h-5 w-5" />
                        </button>
                    </div>

                    <!-- Title & Meta Overlay -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                        <h2 class="text-1xl md:text-4xl font-bold mb-3 line-clamp-3">
                            {{ selectedBlog.title }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-3 text-xs md:text-sm md:text-base text-white/90">
                            <div class="flex items-center gap-1">
                                <CalendarIcon class="h-4 w-4" />
                                <span>{{ formatDate(selectedBlog.published_at) }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <EyeIcon class="h-4 w-4" />
                                <span>{{ selectedBlog.views_count }} views</span>
                            </div>
                            <div v-if="selectedBlog.reading_time" class="flex items-center gap-1">
                                <ClockIcon class="h-4 w-4" />
                                <span>{{ selectedBlog.reading_time }} min read</span>
                            </div>
                            <div v-if="selectedBlog.author" class="flex items-center gap-2">
                                <img 
                                    v-if="selectedBlog.author.avatar" 
                                    :src="selectedBlog.author.avatar" 
                                    :alt="selectedBlog.author.name"
                                    class="w-6 h-6 rounded-full border-2 border-white"
                                />
                                <UserIcon v-else class="h-4 w-4" />
                                <span>{{ selectedBlog.author.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scrollable Content Area -->
                <div class="flex-1 overflow-y-auto">
                    <div class="px-4 py-6 sm:px-6 md:px-8">
                        <div class="max-w-3xl mx-auto">
                            <!-- Excerpt -->
                            <div v-if="selectedBlog.excerpt" class="mb-8">
                                <div class="relative pl-4 border-l-4 border-indigo-500">
                                    <p class="text-base md:text-lg text-slate-700 font-medium leading-relaxed italic">
                                        "{{ selectedBlog.excerpt }}"
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div 
                            v-if="selectedBlog.content"
                            class="prose prose-slate max-w-none
                                text-sm sm:text-base md:text-lg leading-relaxed

                                prose-h1:text-xl sm:prose-h1:text-2xl md:prose-h1:text-3xl
                                prose-h2:text-lg sm:prose-h2:text-xl md:prose-h2:text-2xl
                                prose-h3:text-base sm:prose-h3:text-lg md:prose-h3:text-xl

                                prose-p:mt-3 prose-p:mb-3

                                prose-img:w-full prose-img:max-h-[300px] md:prose-img:max-h-[500px]
                                prose-img:object-cover prose-img:rounded-xl prose-img:shadow-md

                                prose-a:text-indigo-600 hover:prose-a:underline
                                prose-strong:text-slate-900
                                prose-code:text-indigo-600 prose-code:bg-indigo-50 prose-code:px-1 prose-code:rounded
                                prose-pre:bg-slate-900 prose-pre:text-white

                                prose-blockquote:border-l-indigo-500 prose-blockquote:bg-slate-50 prose-blockquote:px-4 prose-blockquote:py-2"
                            v-html="selectedBlog.content"
                        />
                        
                        <!-- Empty State for Content -->
                        <div v-else class="text-center py-12">
                            <p class="text-slate-500">No content available for this post.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer with Navigation -->
                <!-- <div class="sticky bottom-0 bg-white border-t border-slate-200 px-6 py-4 flex justify-between items-center">
                  
                    <div class="flex gap-2">
                        <button
                            @click="shareBlog"
                            class="px-4 py-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all flex items-center gap-2"
                        >
                            <ShareIcon class="h-4 w-4" />
                            Share
                        </button>
                        <button
                            @click="closeModal"
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all"
                        >
                            Close
                        </button>
                    </div>
                </div> -->
            </div>
        </Modal>
    </section>
</template>

<style scoped>
/* Smooth scrolling for modal content */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Image loading animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.group {
    animation: fadeIn 0.5s ease-out;
}

/* Reading progress indicator */
.prose {
    animation: fadeIn 0.6s ease-out;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .prose {
        font-size: 0.9rem;
    }
    
    .prose h1 {
        font-size: 1.75rem;
    }
    
    .prose h2 {
        font-size: 1.5rem;
    }
}
</style>