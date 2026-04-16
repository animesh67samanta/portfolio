<script setup lang="ts">
import { computed } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import type { PaginatorData } from '@/types'

interface Column {
  key: string
  label: string
  sortable?: boolean
}

interface Props {
  data: LengthAwarePaginatorData<any>
  columns: Column[]
}

const props = withDefaults(defineProps<Props>(), {})

const showPagination = computed(() => {
  return (props.data?.total ?? 0) > 0 && (props.data?.links?.length ?? 0) > 0
})

const deleteItem = (id: number | string) => {
  if (confirm('Are you sure?')) {
    router.delete(route(`admin.projects.destroy`, id))
  }
}
</script>

<template>
  <div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th v-for="column in props.columns" :key="column.key" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{ column.label }}
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in props.data.data" :key="item.id">
            <td v-for="column in props.columns" :key="column.key" class="px-6 py-4 whitespace-nowrap">
              {{ item[column.key] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <Link :href="route('admin.projects.edit', item.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
              <button @click="deleteItem(item.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Pagination -->
    <div v-if="showPagination" class="flex items-center justify-between mt-4">
      <div class="flex-1 flex justify-between sm:hidden">
        <button @click="$inertia.visit(props.data.prev ?? '#')" :disabled="!props.data.prev" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</button>
        <button @click="$inertia.visit(props.data.next ?? '#')" :disabled="!props.data.next" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ props.data.from }}</span> to <span class="font-medium">{{ props.data.to }}</span> of <span class="font-medium">{{ props.data.total }}</span> results
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <button v-for="link in props.data.links" :key="link.url" 
              :class="['px-3 py-2 text-sm border', link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', link.url === null ? 'invisible' : '']" 
              @click="link.url && $inertia.visit(link.url)">
              {{ link.label }}
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Add types/index.ts for LengthAwarePaginatorData if needed -->
