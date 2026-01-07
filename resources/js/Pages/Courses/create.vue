<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import FileUploader from '@/Components/FileUploader.vue';

defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    title: '',
    description: '',
    category_id: '',
    episodes: [
        { title: '', description: '', video_url: '', video_path: '' },
    ],
});

const errors = ref({});
const currentStep = ref(1);
const totalSteps = 3;

const steps = [
    { number: 1, title: 'Informations', description: 'Détails de la formation' },
    { number: 2, title: 'Catégorie', description: 'Classification' },
    { number: 3, title: 'Épisodes', description: 'Contenu vidéo' },
];

const addEpisode = () => {
    form.episodes.push({ title: '', description: '', video_url: '', video_path: '' });
};

const removeEpisode = (index) => {
    if (form.episodes.length > 1) {
        form.episodes.splice(index, 1);
    }
};

const handleVideoUploaded = (episodeIndex, data) => {
    form.episodes[episodeIndex].video_path = data.path;
    form.episodes[episodeIndex].video_url = data.url;
};

const nextStep = () => {
    if (currentStep.value < totalSteps) {
        // Validate current step
        if (currentStep.value === 1) {
            if (!form.title || !form.description) {
                errors.value = { title: 'Le titre et la description sont requis', description: '' };
                return;
            }
        }
        currentStep.value++;
        errors.value = {};
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const submitForm = () => {
    // Validate episodes
    const validEpisodes = form.episodes.filter(ep => ep.title && ep.video_url);
    
    if (validEpisodes.length === 0) {
        errors.value = { episodes: 'Ajoutez au moins un épisode avec un titre et une vidéo' };
        currentStep.value = 3;
        return;
    }

    form.episodes = validEpisodes;
    
    form.post(route('courses.store'), {
        onSuccess: () => {
            // Success handled by Inertia
        },
        onError: (err) => {
            errors.value = err;
        },
    });
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};
</script>

<template>
    <AppLayout title="Créer une Formation">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        Créer une Formation
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Partagez votre savoir avec le monde
                    </p>
                </div>
                <Link href="/dashboard" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Progress Steps -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div v-for="(step, index) in steps" :key="step.number" class="flex items-center">
                            <div class="flex flex-col items-center">
                                <div 
                                    :class="[
                                        'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-300',
                                        currentStep >= step.number 
                                            ? 'bg-indigo-600 text-white' 
                                            : 'bg-gray-200 text-gray-500'
                                    ]"
                                >
                                    <svg v-if="currentStep > step.number" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span v-else>{{ step.number }}</span>
                                </div>
                                <div class="mt-2 text-center hidden sm:block">
                                    <p :class="['text-sm font-medium', currentStep >= step.number ? 'text-gray-900' : 'text-gray-500']">
                                        {{ step.title }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ step.description }}</p>
                                </div>
                            </div>
                            <div 
                                v-if="index < steps.length - 1"
                                :class="['flex-1 h-1 mx-4 rounded', currentStep > step.number ? 'bg-indigo-600' : 'bg-gray-200']"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Step 1: Basic Info -->
                    <div v-show="currentStep === 1" class="p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Informations de la Formation</h3>
                        
                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Titre de la formation <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    v-model="form.title"
                                    type="text" 
                                    id="title" 
                                    :class="[
                                        'w-full px-4 py-3 border rounded-xl transition-all duration-200',
                                        errors.title ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'
                                    ]"
                                    placeholder="Ex: Apprendre Vue.js de A à Z"
                                >
                                <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    v-model="form.description"
                                    id="description" 
                                    rows="4"
                                    :class="[
                                        'w-full px-4 py-3 border rounded-xl transition-all duration-200 resize-none',
                                        errors.description ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'
                                    ]"
                                    placeholder="Décrivez votre formation en détail. What will students learn?"
                                ></textarea>
                                <p class="mt-1 text-sm text-gray-500">{{ form.description.length }}/500 caractères</p>
                            </div>

                            <!-- Tips -->
                            <div class="bg-indigo-50 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-semibold text-indigo-900">Conseils pour un bon titre</h4>
                                        <ul class="mt-2 text-sm text-indigo-700 space-y-1">
                                            <li>• Soyez clair et concis</li>
                                            <li>• Mentionnez les technologies ou compétences clés</li>
                                            <li>• Indiquez le niveau (débutant, avancé, etc.)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button 
                                @click="nextStep"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium flex items-center"
                            >
                                Suivant
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Category -->
                    <div v-show="currentStep === 2" class="p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Catégorie de la Formation</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <label 
                                v-for="category in categories" 
                                :key="category.id"
                                :class="[
                                    'relative p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                                    form.category_id === category.id 
                                        ? 'border-indigo-600 bg-indigo-50' 
                                        : 'border-gray-200 hover:border-gray-300'
                                ]"
                            >
                                <input 
                                    type="radio" 
                                    :value="category.id" 
                                    v-model="form.category_id"
                                    class="sr-only"
                                >
                                <div class="text-center">
                                    <div :class="[
                                        'w-12 h-12 mx-auto rounded-xl flex items-center justify-center mb-3',
                                        form.category_id === category.id ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600'
                                    ]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <p class="font-medium text-gray-900 text-sm">{{ category.name }}</p>
                                </div>
                                <div 
                                    v-if="form.category_id === category.id"
                                    class="absolute top-2 right-2 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center"
                                >
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button 
                                @click="prevStep"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium flex items-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Précédent
                            </button>
                            <button 
                                @click="nextStep"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium flex items-center"
                            >
                                Suivant
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Episodes -->
                    <div v-show="currentStep === 3" class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Épisodes</h3>
                                <p class="text-sm text-gray-500">Ajoutez le contenu vidéo de votre formation</p>
                            </div>
                            <button 
                                @click="addEpisode"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Ajouter un épisode
                            </button>
                        </div>

                        <div v-if="errors.episodes" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm text-red-600">{{ errors.episodes }}</p>
                        </div>

                        <div class="space-y-4">
                            <div v-for="(episode, index) in form.episodes" :key="index" 
                                 class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-semibold">
                                            {{ index + 1 }}
                                        </div>
                                        <span class="ml-3 font-medium text-gray-900">Épisode {{ index + 1 }}</span>
                                    </div>
                                    <button 
                                        v-if="form.episodes.length > 1"
                                        @click="removeEpisode(index)"
                                        class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid gap-4">
                                    <div>
                                        <input 
                                            v-model="episode.title"
                                            type="text"
                                            placeholder="Titre de l'épisode *"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                    <div>
                                        <textarea 
                                            v-model="episode.description"
                                            rows="2"
                                            placeholder="Description de l'épisode"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 resize-none"
                                        ></textarea>
                                    </div>
                                    <div>
                                        <FileUploader
                                            type="video"
                                            v-model="episode.video_path"
                                            label="Vidéo de l'épisode"
                                            @uploaded="(data) => handleVideoUploaded(index, data)"
                                        />
                                        <input 
                                            v-model="episode.video_url"
                                            type="hidden"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button 
                                @click="prevStep"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium flex items-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Précédent
                            </button>
                            <button 
                                @click="submitForm"
                                :disabled="form.processing"
                                class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all font-medium flex items-center shadow-lg shadow-indigo-500/25 disabled:opacity-50"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Création...' : 'Créer la Formation' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

