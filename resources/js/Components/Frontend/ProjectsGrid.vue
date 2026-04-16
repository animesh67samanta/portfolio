<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Props {
  projects: any[]
}

const props = defineProps<Props>()
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div v-for="project in props.projects" :key="project.id" class="group">
      <div class="relative overflow-hidden rounded-lg shadow-lg group-hover:shadow-xl transition-shadow">
        <img v-if="project.thumbnail_path" :src="`/storage/${project.thumbnail_path}`" :alt="project.title" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300" />
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-2">{{ project.title }}</h3>
          <p class="text-gray-600 mb-4 line-clamp-3">{{ project.summary }}</p>
          <div class="flex flex-wrap gap-2 mb-4">
            <span v-for="skill in project.skills" :key="skill.id" class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
              {{ skill.name }}
            </span>
          </div>
          <div class="flex gap-2">
            <Link v-if="project.project_url" :href="project.project_url" target="_blank" class="flex-1 bg-blue-600 text-white py-2 px-4 text-center rounded-md hover:bg-blue-700 transition-colors">
              Live Demo
            </Link>
            <Link v-if="project.repository_url" :href="project.repository_url" target="_blank" class="flex-1 border border-gray-300 py-2 px-4 text-center rounded-md hover:bg-gray-50 transition-colors">
              Repository
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
