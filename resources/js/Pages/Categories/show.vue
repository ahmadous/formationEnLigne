<template>
    <app-layout :title="category.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ category.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <p class="text-gray-600">{{ category.description }}</p>
                </div>

                <div v-if="category.courses && category.courses.length > 0" 
                     class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="course in category.courses" :key="course.id" 
                         class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                {{ course.title }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ course.description }}
                            </p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                <span>{{ course.episodes_count }} épisodes</span>
                                <span>Par {{ course.user.name }}</span>
                            </div>
                            <Link :href="route('courses.show', course.id)"
                                  class="inline-flex items-center justify-center w-full px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Voir la formation
                            </Link>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 text-gray-500">
                    Aucune formation dans cette catégorie pour le moment.
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
        category: {
            type: Object,
            required: true,
        },
    },
};
</script>

