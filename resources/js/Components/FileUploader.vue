<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    type: {
        type: String,
        required: true,
        validator: (value) => ['video', 'image'].includes(value),
    },
    modelValue: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    accept: {
        type: String,
        default: '',
    },
    maxSize: {
        type: Number,
        default: 102400, // 100MB in KB
    },
});

const emit = defineEmits(['update:modelValue', 'uploaded']);

const uploading = ref(false);
const progress = ref(0);
const error = ref(null);
const preview = ref(null);
const fileName = ref('');

const handleFileSelect = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size
    if (file.size > props.maxSize * 1024) {
        error.value = `File size exceeds ${props.maxSize / 1024}MB limit`;
        return;
    }

    // Create preview for images
    if (props.type === 'image') {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    fileName.value = file.name;
    error.value = null;
    uploading.value = true;
    progress.value = 0;

    const form = new FormData();
    form.append('file', file);
    form.append('type', props.type);

    try {
        const response = await fetch(route('upload'), {
            method: 'POST',
            body: form,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        });

        const data = await response.json();

        if (data.success) {
            emit('update:modelValue', data.path);
            emit('uploaded', data);
        } else {
            error.value = data.message || 'Upload failed';
        }
    } catch (err) {
        error.value = 'An error occurred during upload';
        console.error('Upload error:', err);
    } finally {
        uploading.value = false;
    }
};

const clearFile = () => {
    preview.value = null;
    fileName.value = '';
    emit('update:modelValue', '');
};
</script>

<template>
    <div class="file-uploader">
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-2">
            {{ label }}
        </label>

        <div 
            class="border-2 border-dashed rounded-lg p-6 text-center"
            :class="[
                uploading ? 'border-indigo-300 bg-indigo-50' : 'border-gray-300 hover:border-indigo-400',
                error ? 'border-red-300' : ''
            ]"
        >
            <!-- Preview for images -->
            <div v-if="preview" class="mb-4 relative inline-block">
                <img :src="preview" alt="Preview" class="max-h-48 rounded-lg shadow-md" />
                <button 
                    @click="clearFile"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Upload area -->
            <div v-else>
                <input
                    type="file"
                    :accept="accept || (type === 'video' ? 'video/*' : 'image/*')"
                    class="hidden"
                    :id="`file-${type}`"
                    @change="handleFileSelect"
                />

                <label :for="`file-${type}`" class="cursor-pointer">
                    <div class="text-gray-600">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm">
                            <span class="text-indigo-600 hover:text-indigo-500">Upload a {{ type }}</span>
                            or drag and drop
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ type === 'video' ? 'MP4, MOV, AVI up to 100MB' : 'PNG, JPG, GIF up to 5MB' }}
                        </p>
                    </div>
                </label>
            </div>

            <!-- Progress bar -->
            <div v-if="uploading" class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div 
                        class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                        :style="{ width: `${progress}%` }"
                    ></div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Uploading...</p>
            </div>

            <!-- File name -->
            <p v-if="fileName && !preview" class="mt-2 text-sm text-gray-600">
                Selected: {{ fileName }}
            </p>

            <!-- Error message -->
            <p v-if="error" class="mt-2 text-sm text-red-600">
                {{ error }}
            </p>
        </div>
    </div>
</template>

