<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    categories: {
        type: Array,
        required: true,
    },
});

const categoryIcons = [
    { bg: 'from-blue-500 to-cyan-500', icon: 'code' },
    { bg: 'from-purple-500 to-pink-500', icon: 'device-mobile' },
    { bg: 'from-orange-500 to-red-500', icon: 'color-swatch' },
    { bg: 'from-green-500 to-emerald-500', icon: 'chart-bar' },
    { bg: 'from-indigo-500 to-blue-500', icon: 'briefcase' },
    { bg: 'from-pink-500 to-rose-500', icon: 'database' },
];

const getCategoryStyle = (index) => {
    return categoryIcons[index % categoryIcons.length];
};
</script>

<template>
    <AppLayout title="Catégories">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        Catégories de Formations
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Explorez nos {{ categories.length }} catégories
                    </p>
                </div>
                <Link href="/courses"
                      class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Toutes les formations
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Categories Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link v-for="(category, index) in categories" :key="category.id" 
                          :href="route('categories.show', category.id)"
                          class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        
                        <!-- Gradient Accent -->
                        <div :class="['absolute top-0 left-0 w-2 h-full bg-gradient-to-b', getCategoryStyle(index).bg]"></div>
                        
                        <div class="p-6 pl-8">
                            <!-- Icon & Title -->
                            <div class="flex items-start justify-between mb-4">
                                <div :class="['w-12 h-12 rounded-xl bg-gradient-to-br flex items-center justify-center', getCategoryStyle(index).bg]">
                                    <svg v-if="getCategoryStyle(index).icon === 'code'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                    <svg v-else-if="getCategoryStyle(index).icon === 'device-mobile'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <svg v-else-if="getCategoryStyle(index).icon === 'color-swatch'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                    <svg v-else-if="getCategoryStyle(index).icon === 'chart-bar'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <svg v-else-if="getCategoryStyle(index).icon === 'briefcase'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <svg v-else class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                    </svg>
                                </div>
                                <span :class="['px-3 py-1 rounded-full text-xs font-semibold text-white bg-gradient-to-r', getCategoryStyle(index).bg]">
                                    {{ category.courses_count }} formations
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                                {{ category.name }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ category.description }}
                            </p>

                            <!-- Arrow -->
                            <div class="flex items-center text-indigo-600 font-medium text-sm">
                                <span>Voir les formations</span>
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-if="categories.length === 0" class="text-center py-20">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune catégorie</h3>
                    <p class="text-gray-500">Aucune catégorie n'est disponible pour le moment.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

