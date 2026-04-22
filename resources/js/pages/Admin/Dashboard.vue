<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SectionCard from '@/Components/SectionCard.vue';
import {
  ChartBarIcon,
  DocumentTextIcon,
  UserGroupIcon,
  EnvelopeIcon,
  PlusIcon,
  EyeIcon,
  PencilIcon,
  CalendarIcon,
  ArrowTopRightOnSquareIcon,
  StarIcon,
  CheckCircleIcon,
  ClockIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/24/outline';

interface Stats {
  projects: number;
  blogs: number;
  testimonials: number;
  contacts: number;
}

interface RecentItem {
  id: number;
  title: string;
  excerpt?: string;
  status?: string;
  date: string;
  url?: string;
  client_name?: string;
  email?: string;
}

const props = defineProps<{
  stats: Stats;
  recentProjects: RecentItem[];
  recentBlogs: RecentItem[];
  recentContacts: RecentItem[];
}>();

const activeTab = ref<'projects' | 'blogs' | 'contacts'>('projects');

// Stats Cards Configuration
const statsCards = [
  { 
    title: 'Total Projects', 
    value: props.stats.projects, 
    icon: ChartBarIcon, 
    href: '/admin/projects', 
    color: 'from-blue-500 to-indigo-600',
    bgColor: 'bg-blue-50',
    textColor: 'text-blue-600',
    trend: '+12%',
    trendUp: true
  },
  { 
    title: 'Total Blogs', 
    value: props.stats.blogs, 
    icon: DocumentTextIcon, 
    href: '/admin/blogs', 
    color: 'from-emerald-500 to-teal-600',
    bgColor: 'bg-emerald-50',
    textColor: 'text-emerald-600',
    trend: '+8%',
    trendUp: true
  },
  { 
    title: 'Total Testimonials', 
    value: props.stats.testimonials, 
    icon: UserGroupIcon, 
    href: '/admin/testimonials', 
    color: 'from-purple-500 to-pink-600',
    bgColor: 'bg-purple-50',
    textColor: 'text-purple-600',
    trend: '+5%',
    trendUp: true
  },
  { 
    title: 'Total Contacts', 
    value: props.stats.contacts, 
    icon: EnvelopeIcon, 
    href: '/admin/contacts', 
    color: 'from-orange-500 to-red-600',
    bgColor: 'bg-orange-50',
    textColor: 'text-orange-600',
    trend: '+15%',
    trendUp: true
  },
];

const quickActions = [
  { label: 'Create New Project', href: '/admin/projects/create', icon: PlusIcon, description: 'Add a portfolio project' },
  { label: 'Write Blog Post', href: '/admin/blogs', icon: DocumentTextIcon, description: 'Share your knowledge' },
  { label: 'Add Testimonial', href: '/admin/testimonials', icon: StarIcon, description: 'Showcase client feedback' },
  { label: 'Manage Skills', href: '/admin/skills', icon: ChartBarIcon, description: 'Update your skills' },
];

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    published: 'bg-emerald-100 text-emerald-800',
    draft: 'bg-gray-100 text-gray-800',
    archived: 'bg-yellow-100 text-yellow-800',
    active: 'bg-emerald-100 text-emerald-800',
    inactive: 'bg-gray-100 text-gray-800',
    pending: 'bg-yellow-100 text-yellow-800',
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusIcon = (status: string) => {
  const icons: Record<string, any> = {
    published: CheckCircleIcon,
    draft: ClockIcon,
    archived: ExclamationCircleIcon,
    active: CheckCircleIcon,
    inactive: ClockIcon,
    pending: ClockIcon,
  };
  return icons[status] || ClockIcon;
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffTime = Math.abs(now.getTime() - date.getTime());
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  
  if (diffDays === 1) return 'Yesterday';
  if (diffDays < 7) return `${diffDays} days ago`;
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const getGreeting = () => {
  const hour = new Date().getHours();
  if (hour < 12) return 'Good morning';
  if (hour < 18) return 'Good afternoon';
  return 'Good evening';
};
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout page-title="Dashboard">
    <!-- Welcome Section -->
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-800 p-6 text-white shadow-xl">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-sm font-medium text-indigo-200">{{ getGreeting() }}!</p>
          <h1 class="mt-1 text-2xl font-bold">Welcome to your Dashboard</h1>
          <p class="mt-1 text-sm text-indigo-200">Here's what's happening with your portfolio today</p>
        </div>
        <div class="flex gap-3">
          <div class="rounded-lg bg-white/10 px-4 py-2 backdrop-blur-sm">
            <p class="text-xs text-indigo-200">Total Views</p>
            <p class="text-2xl text-center font-bold">{{ stats.projects + stats.blogs + stats.testimonials }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <div 
        v-for="(card, index) in statsCards" 
        :key="index"
        class="group relative cursor-pointer overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
        @click="router.visit(card.href)"
      >
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">{{ card.title }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ card.value.toLocaleString() }}</p>
            <div class="mt-3 flex items-center gap-1">
              <span class="text-xs font-medium text-green-600">{{ card.trend }}</span>
              <span class="text-xs text-gray-400">vs last month</span>
            </div>
          </div>
          <div class="rounded-2xl p-3 shadow-lg transition-all duration-300 group-hover:scale-110" :class="card.bgColor">
            <component :is="card.icon" class="h-6 w-6" :class="card.textColor" />
          </div>
        </div>
        <div class="absolute bottom-0 left-0 h-1 w-0 bg-gradient-to-r transition-all duration-300 group-hover:w-full" :class="card.color"></div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <!-- Quick Actions -->
      <SectionCard>
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-3">
            <div class="rounded-lg bg-indigo-100 p-2">
              <EyeIcon class="h-5 w-5 text-indigo-600" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
          </div>
          <span class="rounded-full bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-600">
            {{ quickActions.length }} items
          </span>
        </div>
        
        <div class="space-y-3">
          <a 
            v-for="(action, index) in quickActions" 
            :key="index"
            :href="action.href"
            class="group flex items-center gap-4 rounded-xl border border-gray-200 p-4 transition-all duration-300 hover:border-indigo-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-transparent hover:shadow-md"
          >
            <div class="rounded-lg bg-indigo-100 p-3 transition-all duration-300 group-hover:bg-indigo-200 group-hover:scale-110">
              <component :is="action.icon" class="h-5 w-5 text-indigo-600" />
            </div>
            <div class="flex-1">
              <p class="font-medium text-gray-900 group-hover:text-indigo-700">{{ action.label }}</p>
              <p class="text-xs text-gray-500">{{ action.description }}</p>
            </div>
            <ArrowTopRightOnSquareIcon class="h-4 w-4 text-gray-400 opacity-0 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-1" />
          </a>
        </div>
      </SectionCard>

      <!-- Recent Activity -->
      <div class="lg:col-span-2">
        <SectionCard>
          <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
              <div class="rounded-lg bg-indigo-100 p-2">
                <PencilIcon class="h-5 w-5 text-indigo-600" />
              </div>
              <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            </div>
            
            <!-- Tabs -->
            <div class="flex gap-1 rounded-lg bg-gray-100 p-1">
              <button 
                class="flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all duration-200"
                :class="activeTab === 'projects' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                @click="activeTab = 'projects'"
              >
                <ChartBarIcon class="h-4 w-4" />
                Projects
              </button>
              <button 
                class="flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all duration-200"
                :class="activeTab === 'blogs' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                @click="activeTab = 'blogs'"
              >
                <DocumentTextIcon class="h-4 w-4" />
                Blogs
              </button>
              <button 
                class="flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all duration-200"
                :class="activeTab === 'contacts' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                @click="activeTab = 'contacts'"
              >
                <EnvelopeIcon class="h-4 w-4" />
                Contacts
              </button>
            </div>
          </div>

          <!-- Projects Tab -->
          <div v-if="activeTab === 'projects'" class="space-y-3">
            <div 
              v-for="(project, index) in recentProjects" 
              :key="index"
              class="group cursor-pointer rounded-xl border border-gray-200 p-4 transition-all duration-300 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-md"
              @click="router.visit(`/admin/projects/${project.id}/edit`)"
            >
              <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md">
                  <ChartBarIcon class="h-6 w-6" />
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex items-start justify-between gap-2">
                    <div>
                      <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ project.title }}</p>
                      <p class="mt-1 text-sm text-gray-500 line-clamp-1">{{ project.excerpt || 'No description provided' }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                      <span :class="['inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium', getStatusColor(project.status || 'draft')]">
                        <component :is="getStatusIcon(project.status || 'draft')" class="h-3 w-3" />
                        {{ project.status || 'draft' }}
                      </span>
                      <span class="text-xs text-gray-400">{{ formatDate(project.date) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="recentProjects.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
              <div class="rounded-full bg-gray-100 p-4">
                <ChartBarIcon class="h-8 w-8 text-gray-400" />
              </div>
              <p class="mt-3 text-sm text-gray-500">No recent projects</p>
              <a href="/admin/projects/create" class="mt-2 text-sm text-indigo-600 hover:text-indigo-700">Create your first project →</a>
            </div>
          </div>

          <!-- Blogs Tab -->
          <div v-if="activeTab === 'blogs'" class="space-y-3">
            <div 
              v-for="(blog, index) in recentBlogs" 
              :key="index"
              class="group cursor-pointer rounded-xl border border-gray-200 p-4 transition-all duration-300 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-md"
              @click="router.visit(`/admin/blogs/${blog.id}/edit`)"
            >
              <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md">
                  <DocumentTextIcon class="h-6 w-6" />
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex items-start justify-between gap-2">
                    <div>
                      <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ blog.title }}</p>
                      <p class="mt-1 text-sm text-gray-500 line-clamp-1">{{ blog.excerpt || 'No excerpt available' }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                      <span :class="['inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium', getStatusColor(blog.status || 'draft')]">
                        <component :is="getStatusIcon(blog.status || 'draft')" class="h-3 w-3" />
                        {{ blog.status || 'draft' }}
                      </span>
                      <span class="text-xs text-gray-400">{{ formatDate(blog.date) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="recentBlogs.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
              <div class="rounded-full bg-gray-100 p-4">
                <DocumentTextIcon class="h-8 w-8 text-gray-400" />
              </div>
              <p class="mt-3 text-sm text-gray-500">No recent blogs</p>
              <a href="/admin/blogs" class="mt-2 text-sm text-indigo-600 hover:text-indigo-700">Write your first blog post →</a>
            </div>
          </div>

          <!-- Contacts Tab -->
          <div v-if="activeTab === 'contacts'" class="space-y-3">
            <div 
              v-for="(contact, index) in recentContacts" 
              :key="index"
              class="group rounded-xl border border-gray-200 p-4 transition-all duration-300 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-md"
            >
              <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-md">
                  <EnvelopeIcon class="h-6 w-6" />
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex items-start justify-between gap-2">
                    <div>
                      <p class="font-semibold text-gray-900">{{ contact.title || contact.client_name || 'New Contact' }}</p>
                      <p class="mt-1 text-sm text-gray-500">{{ contact.email || contact.excerpt || 'Contact inquiry' }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                      <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                        <EnvelopeIcon class="h-3 w-3" />
                        New
                      </span>
                      <span class="text-xs text-gray-400">{{ formatDate(contact.date) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="recentContacts.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
              <div class="rounded-full bg-gray-100 p-4">
                <EnvelopeIcon class="h-8 w-8 text-gray-400" />
              </div>
              <p class="mt-3 text-sm text-gray-500">No recent contacts</p>
              <p class="text-xs text-gray-400">Contact form submissions will appear here</p>
            </div>
          </div>

          <!-- View All Link -->
          <div class="mt-4 border-t border-gray-100 pt-4 text-center">
            <a 
              :href="`/admin/${activeTab}`" 
              class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors"
            >
              View all {{ activeTab }}
              <ArrowTopRightOnSquareIcon class="h-4 w-4" />
            </a>
          </div>
        </SectionCard>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Smooth transitions */
.group {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>