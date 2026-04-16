<script setup lang="ts">
import { ref, watch } from 'vue'


interface Props {
  modelValue?: File | null
  folder: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
  (e: 'update:modelValue', value: File | null): void
}>()

const preview = ref<string | null>(null)
const dragOver = ref(false)

const handleFile = (file: File) => {
  emit('update:modelValue', file)
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      preview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  } else {
    preview.value = null
  }
}

const handleDrop = (e: DragEvent) => {
  e.preventDefault()
  dragOver.value = false
  const file = e.dataTransfer?.files[0]
  if (file?.type.startsWith('image/')) {
    handleFile(file)
  }
}

const handleDragOver = (e: DragEvent) => {
  e.preventDefault()
  dragOver.value = true
}

watch(() => props.modelValue, handleFile)
</script>

<template>
  <div>
    <div 
      class="border-2 border-dashed rounded-lg p-8 text-center transition-colors"
      :class="dragOver ? 'border-blue-400 bg-blue-50' : 'border-gray-300 hover:border-gray-400'"
      @drop="handleDrop"
      @dragover="handleDragOver"
      @dragleave="() => dragOver = false"
    >
      <div v-if="preview" class="space-y-4">
        <img :src="preview" class="max-h-48 mx-auto rounded-lg shadow-md" />
        <button type="button" @click="emit('update:modelValue', null)" class="text-sm text-red-500 hover:text-red-700">Remove</button>
      </div>
      <div v-else class="space-y-2">
        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <div>
          <label class="cursor-pointer">
@change="(e) => { const file = (e.target as HTMLInputElement).files?.[0]; if (file) handleFile(file) }" 
            <span class="text-sm font-medium text-blue-600 hover:text-blue-500">Click to upload or drag & drop</span>
          </label>
        </div>
        <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
      </div>
    </div>
  </div>
</template>
