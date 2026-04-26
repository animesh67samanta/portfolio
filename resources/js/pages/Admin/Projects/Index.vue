<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { PlusIcon } from '@heroicons/vue/24/outline';

interface Project {
  id: number;
  title: string;
  slug: string;
  summary: string;
  is_featured: boolean;
  started_at: string;
  thumbnail_path?: string;
  status?: 'draft' | 'published' | 'archived';
}

interface PaginatedData {
  data: Project[];
  total: number;
  per_page: number;
  current_page: number;
  last_page: number;
  from: number;
  prev: string | null;
  next: string | null;
  to: number;
  links: Array<{
    url: string | null;
    label: string;
    active: boolean;
  }>;
}

const props = defineProps<{
  projects: PaginatedData;
  skills: unknown[];
}>();

// Define columns configuration
const columns = [
  { 
    key: 'title', 
    label: 'Title', 
    sortable: true,
    searchable: true,
    className: 'font-medium'
  },
  { 
    key: 'slug', 
    label: 'Slug', 
    sortable: true,
    format: (value: string) => value?.length > 50 ? value.substring(0, 50) + '...' : value
  },
  { 
    key: 'summary', 
    label: 'Summary', 
    className: 'max-w-md',
    format: (value: string) => value?.length > 100 ? value.substring(0, 100) + '...' : value
  },
  { 
    key: 'is_featured', 
    label: 'Featured', 
    sortable: true,
    format: (value: boolean) => value ? '⭐ Yes' : 'No'
  },
  { 
    key: 'started_at', 
    label: 'Started Date', 
    sortable: true,
    format: (value: string) => new Date(value).toLocaleDateString()
  },
  { 
    key: 'status', 
    label: 'Status', 
    sortable: true,
    format: (value: string) => value || 'draft'
  }
];

// Handle delete event
const handleDelete = (id:  string | number) => {
  router.delete(route('admin.projects.destroy', id), {
    preserveScroll: true,
    onSuccess: () => {
      // Optional: Show success notification
      console.log('Project deleted successfully');
    }
  });
};

// Handle sort event
const handleSort = (column: string, direction: string) => {
  router.get(
    route('admin.projects.index'),
    { sort: column, direction, page: props.projects.current_page },
    { preserveState: true, preserveScroll: true }
  );
};

// Handle search (if you implement search)
const handleSearch = (searchTerm: string) => {
  router.get(
    route('admin.projects.index'),
    { search: searchTerm, page: 1 },
    { preserveState: true, preserveScroll: true }
  );
};
</script>

<template>
  <Head title="Projects" />

  <AdminLayout page-title="Projects">
    <div class="space-y-6">
      <!-- Header Section -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Projects</h2>
          <p class="mt-1 text-sm text-gray-500">
            Manage your portfolio projects, track progress, and showcase your work
          </p>
        </div>
        
        <Link
          :href="route('admin.projects.create')"
          class="inline-flex items-center px-4 py-2 bg-indigo-400 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Project
        </Link>
      </div>

      <!-- Stats Cards (Optional) -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
              <svg class="h-6 w-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500 truncate">Total Projects</p>
              <p class="text-2xl font-semibold text-gray-900">{{ projects.total || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500 truncate">Published</p>
              <p class="text-2xl font-semibold text-gray-900">
                {{ projects.data.filter(p => p.status === 'published').length }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
              <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500 truncate">Featured</p>
              <p class="text-2xl font-semibold text-gray-900">
                {{ projects.data.filter(p => p.is_featured).length }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
              <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500 truncate">Last Updated</p>
              <p class="text-sm font-semibold text-gray-900">
                {{ new Date().toLocaleDateString() }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Table -->
      <DataTable
        :data="projects"
        :columns="columns"
        :searchable="true"
        route-name="projects"
        @delete="handleDelete"
        @sort="handleSort"
      />
    </div>
  </AdminLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>