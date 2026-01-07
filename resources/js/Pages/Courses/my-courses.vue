<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    courses: {
        type: Array,
        required: true,
    },
});

const deleteCourse = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')) {
        router.delete(route('courses.destroy', id));
    }
};

const totalStats = () => {
    if (!Array.isArray(courses)) return { courses: 0, episodes: 0, participants: 0 };
    return {
        courses: courses.length,
        episodes: courses.reduce((sum, c) => sum + (c.episodes_count || 0), 0),
        participants: courses.reduce((sum, c) => sum + (c.participants || 0), 0),
    };
};
</script>

<template>
    <AppLayout title="Mes Formations">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Mes Formations</h2>
                    <p class="text-sm text-gray-500 mt-1">Gérez toutes vos formations créées</p>
                </div>
                <Link :href="route('courses.create')" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Nouvelle formation
                </Link>
            </div>
        </template>

        <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6" v-if="courses && courses.length > 0">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Total formations</div>
                    <div class="mt-3 text-3xl font-bold text-gray-900">{{ totalStats().courses }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Total épisodes</div>
                    <div class="mt-3 text-3xl font-bold text-gray-900">{{ totalStats().episodes }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Participants uniques</div>
                    <div class="mt-3 text-3xl font-bold text-gray-900">{{ totalStats().participants }}</div>
                </div>
            </div>

            <!-- Courses Grid -->
            <div v-if="courses && courses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="course in courses" :key="course.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Course Header -->
                    <div class="p-6 border-b border-gray-100">
                        <Link :href="route('courses.show', course.id)" class="block group">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ course.title }}</h3>
                        </Link>
                        <p v-if="course.category" class="text-xs text-indigo-600 mt-1">{{ course.category.name }}</p>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ course.description || 'Pas de description' }}</p>
                    </div>

                    <!-- Course Stats -->
                    <div class="px-6 py-4 bg-gray-50 grid grid-cols-3 gap-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ course.episodes_count || 0 }}</div>
                            <div class="text-xs text-gray-600">Épisodes</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ course.participants || 0 }}</div>
                            <div class="text-xs text-gray-600">Participants</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">✓</div>
                            <div class="text-xs text-gray-600">Publiée</div>
                        </div>
                    </div>

                    <!-- Course Actions -->
                    <div class="p-6 space-y-2">
                        <Link :href="route('courses.show', course.id)" class="w-full px-3 py-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-sm font-medium text-center transition-colors">
                            Voir la formation
                        </Link>
                        <Link :href="route('courses.edit', course.id)" class="w-full px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-medium text-center transition-colors">
                            Modifier
                        </Link>
                        <Link :href="route('episodes.create', course.id)" class="w-full px-3 py-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 text-sm font-medium text-center transition-colors">
                            + Ajouter un épisode
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-gray-600 text-lg font-medium">Vous n'avez pas encore créé de formation</p>
                <p class="text-gray-500 text-sm mt-2 mb-6">Commencez par créer votre première formation pour partager vos connaissances</p>
                <Link :href="route('courses.create')" class="inline-flex px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                    Créer ma première formation
                </Link>
            </div>
        </div>
    </AppLayout>
</template>


