<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { 
  PencilIcon, 
  TrashIcon, 
  ChevronUpIcon, 
  ChevronDownIcon,
  ArrowPathIcon,
  DocumentMagnifyingGlassIcon 
} from '@heroicons/vue/24/outline'

interface Column {
  key: string
  label: string
  sortable?: boolean
  searchable?: boolean
  format?: (value: any) => string
  className?: string
}

interface LengthAwarePaginatorData<T> {
  data: T[]
  total: number
  per_page: number
  current_page: number
  last_page: number
  from: number
  to: number
  prev: string | null
  next: string | null
  links: Array<{ url: string | null; label: string; active: boolean }>
}

interface Props {
  data: LengthAwarePaginatorData<any>
  columns: Column[]
  searchable?: boolean
  routeName?: string
}

const props = withDefaults(defineProps<Props>(), {
  searchable: false,
  routeName: ''
})

const emit = defineEmits<{
  delete: [id: number | string]
  sort: [column: string, direction: string]
}>()

const searchTerm = ref('')
const sortColumn = ref('')
const sortDirection = ref<'asc' | 'desc'>('asc')
const selectedItems = ref<(string | number)[]>([])
const isDeleteModalOpen = ref(false)
const itemToDelete = ref<number | string | null>(null)

const showPagination = computed(() => {
  return (props.data?.total ?? 0) > 0 && (props.data?.links?.length ?? 0) > 0
})

const allSelected = computed({
  get: () => selectedItems.value.length === props.data.data.length,
  set: (value: boolean) => {
    if (value) {
      selectedItems.value = props.data.data.map(item => item.id)
    } else {
      selectedItems.value = []
    }
  }
})

const deleteItem = (id: number | string) => {
  itemToDelete.value = id
  isDeleteModalOpen.value = true
}

const confirmDelete = () => {
  if (itemToDelete.value) {
    emit('delete', itemToDelete.value)
    // Or directly call router if no emit
    // router.delete(route(`admin.${props.routeName}.destroy`, itemToDelete.value))
  }
  isDeleteModalOpen.value = false
  itemToDelete.value = null
}

const bulkDelete = () => {
  if (selectedItems.value.length === 0) return
  if (confirm(`Delete ${selectedItems.value.length} selected items?`)) {
    // Implement bulk delete logic
    selectedItems.value = []
  }
}

const sort = (column: string) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortColumn.value = column
    sortDirection.value = 'asc'
  }
  emit('sort', sortColumn.value, sortDirection.value)
}

const getSortIcon = (column: string) => {
  if (sortColumn.value !== column) return null
  return sortDirection.value === 'asc' ? ChevronUpIcon : ChevronDownIcon
}

const formatValue = (item: any, column: Column) => {
  if (column.format) {
    return column.format(item[column.key])
  }
  if (typeof item[column.key] === 'boolean') {
    return item[column.key] ? 'Yes' : 'No'
  }
  if (item[column.key] === null || item[column.key] === undefined) {
    return '-'
  }
  return item[column.key]
}

const clearFilters = () => {
  searchTerm.value = ''
  sortColumn.value = ''
  sortDirection.value = 'asc'
  selectedItems.value = []
}

// Status badge colors helper
const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-800',
    published: 'bg-green-100 text-green-800',
    archived: 'bg-yellow-100 text-yellow-800',
    active: 'bg-green-100 text-green-800',
    inactive: 'bg-red-100 text-red-800',
    pending: 'bg-yellow-100 text-yellow-800'
  }
  return colors[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Header with Search and Actions -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex-1 min-w-[200px]">
          <div v-if="searchable" class="relative">
            <input
              v-model="searchTerm"
              type="text"
              placeholder="Search..."
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
        
        <div class="flex gap-2">
          <button
            v-if="selectedItems.length > 0"
            @click="bulkDelete"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            <TrashIcon class="h-4 w-4 mr-2" />
            Delete ({{ selectedItems.length }})
          </button>
          
          <button
            @click="clearFilters"
            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <ArrowPathIcon class="h-4 w-4 mr-2" />
            Clear
          </button>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left">
              <input
                type="checkbox"
                v-model="allSelected"
                class="rounded border-gray-300 text-indigo-400 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              />
            </th>
            <th
              v-for="column in props.columns"
              :key="column.key"
              :class="['px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider', column.className]"
            >
              <div class="flex items-center gap-1">
                {{ column.label }}
                <button
                  v-if="column.sortable"
                  @click="sort(column.key)"
                  class="hover:text-gray-700 focus:outline-none"
                >
                  <component
                    :is="getSortIcon(column.key)"
                    v-if="getSortIcon(column.key)"
                    class="h-4 w-4"
                  />
                  <svg v-else class="h-4 w-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                  </svg>
                </button>
              </div>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in props.data.data" :key="item.id" class="hover:bg-gray-50 transition-colors duration-150">
            <td class="px-6 py-4 whitespace-nowrap">
              <input
                type="checkbox"
                v-model="selectedItems"
                :value="item.id"
                class="rounded border-gray-300 text-indigo-400 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              />
            </td>
            <td
              v-for="column in props.columns"
              :key="column.key"
              :class="['px-6 py-4 whitespace-nowrap text-sm text-gray-900', column.className]"
            >
              <!-- Status badge special handling -->
              <span
                v-if="column.key === 'status'"
                :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusColor(formatValue(item, column))]"
              >
                {{ formatValue(item, column) }}
              </span>
              <span v-else>
                {{ formatValue(item, column) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex items-center gap-3">
                <Link
                  :href="route(`admin.${props.routeName}.edit`, item.id)"
                  class="text-indigo-400 hover:text-indigo-900 transition-colors duration-150"
                  title="Edit"
                >
                  <PencilIcon class="h-5 w-5" />
                </Link>
                
                <button
                  @click="deleteItem(item.id)"
                  class="text-red-600 hover:text-red-900 transition-colors duration-150"
                  title="Delete"
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
                
                <!-- View link removed since no show route -->
              </div>
            </td>
          </tr>
          
          <!-- Empty State -->
          <tr v-if="props.data.data.length === 0">
            <td :colspan="props.columns.length + 2" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center justify-center">
                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No data available</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new item.</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="showPagination" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
      <div class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="$inertia.visit(props.data.prev ?? '#')"
            :disabled="!props.data.prev"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button
            @click="$inertia.visit(props.data.next ?? '#')"
            :disabled="!props.data.next"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
        
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ props.data.from || 0 }}</span>
              to
              <span class="font-medium">{{ props.data.to || 0 }}</span>
              of
              <span class="font-medium">{{ props.data.total || 0 }}</span>
              results
            </p>
          </div>
          
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button
                v-for="(link, index) in props.data.links"
                :key="index"
                :class="[
                  'relative inline-flex items-center px-4 py-2 text-sm font-medium border transition-colors duration-150',
                  link.active
                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-400'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  link.url === null ? 'cursor-not-allowed opacity-50' : '',
                  index === 0 ? 'rounded-l-md' : '',
                  index === (props.data.links?.length ?? 0) - 1 ? 'rounded-r-md' : ''
                ]"
                @click="link.url && $inertia.visit(link.url)"
                :disabled="!link.url"
                v-html="link.label"
              />
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="isDeleteModalOpen = false"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <TrashIcon class="h-6 w-6 text-red-600" />
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Delete Item
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this item? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="confirmDelete"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Delete
            </button>
            <button
              type="button"
              @click="isDeleteModalOpen = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>