<template>
    <app-layout :title="'Ajouter un épisode - ' + course.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ajouter un épisode à "{{ course.title }}"
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submitForm">
                            <!-- Titre -->
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Titre de l'épisode *
                                </label>
                                <input 
                                    v-model="form.title"
                                    type="text" 
                                    id="title" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Entrez le titre de l'épisode"
                                >
                                <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description *
                                </label>
                                <textarea 
                                    v-model="form.description"
                                    id="description" 
                                    rows="4"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Décrivez le contenu de l'épisode"
                                ></textarea>
                                <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                            </div>

                            <!-- URL Vidéo -->
                            <div class="mb-6">
                                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    URL de la vidéo *
                                </label>
                                <input 
                                    v-model="form.video_url"
                                    type="url" 
                                    id="video_url" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                >
                                <p class="mt-1 text-sm text-gray-500">
                                    Supports: YouTube, Vimeo, ou liens directs MP4
                                </p>
                                <p v-if="errors.video_url" class="mt-1 text-sm text-red-600">{{ errors.video_url }}</p>
                            </div>

                            <!-- Submit -->
                            <div class="flex justify-end">
                                <Link :href="route('courses.show', course.id)"
                                      class="mr-4 px-4 py-2 text-gray-700 hover:text-gray-900">
                                    Annuler
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                                >
                                    {{ isSubmitting ? 'Création...' : 'Ajouter l\'Épisode' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        AppLayout,
        Link,
    },
    props: {
        course: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            form: {
                title: '',
                description: '',
                video_url: '',
                course_id: this.course.id,
            },
            errors: {},
            isSubmitting: false,
        };
    },
    methods: {
        submitForm() {
            this.isSubmitting = true;
            this.errors = {};
            
            this.$inertia.post(route('episodes.store'), this.form, {
                onError: (errors) => {
                    this.errors = errors;
                },
                onFinish: () => {
                    this.isSubmitting = false;
                },
            });
        },
    },
};
</script>

