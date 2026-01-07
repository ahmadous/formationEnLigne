<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        required: true,
    },
});

const statCards = [
    { 
        label: 'Total Users', 
        value: 'total_users', 
        icon: 'users', 
        color: 'from-blue-500 to-cyan-500',
        bgColor: 'bg-blue-50'
    },
    { 
        label: 'Total Instructors', 
        value: 'total_instructors', 
        icon: 'academic-cap', 
        color: 'from-purple-500 to-pink-500',
        bgColor: 'bg-purple-50'
    },
    { 
        label: 'Total Courses', 
        value: 'total_courses', 
        icon: 'book-open', 
        color: 'from-orange-500 to-red-500',
        bgColor: 'bg-orange-50'
    },
    { 
        label: 'Total Episodes', 
        value: 'total_episodes', 
        icon: 'play', 
        color: 'from-green-500 to-emerald-500',
        bgColor: 'bg-green-50'
    },
    { 
        label: 'Total Categories', 
        value: 'total_categories', 
        icon: 'collection', 
        color: 'from-indigo-500 to-blue-500',
        bgColor: 'bg-indigo-50'
    },
];

const quickActions = [
    { 
        title: 'Manage Users', 
        description: 'View and manage all platform users',
        href: '/admin/users',
        icon: 'users',
        color: 'bg-blue-500'
    },
    { 
        title: 'Manage Instructors', 
        description: 'View and manage all instructors',
        href: '/admin/instructors',
        icon: 'academic-cap',
        color: 'bg-purple-500'
    },
    { 
        title: 'Manage Roles', 
        description: 'Configure user roles and permissions',
        href: '/admin/roles',
        icon: 'shield-check',
        color: 'bg-indigo-500'
    },
    { 
        title: 'All Courses', 
        description: 'View and manage all courses',
        href: '/courses',
        icon: 'book-open',
        color: 'bg-orange-500'
    },
    { 
        title: 'Categories', 
        description: 'Browse course categories',
        href: '/categories',
        icon: 'collection',
        color: 'bg-green-500'
    },
];
</script>

<template>
    <AppLayout>
        <Head title="Admin Dashboard" />
        
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        Admin Dashboard
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Manage your platform from here
                    </p>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <div v-for="stat in statCards" :key="stat.label" 
                         class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ stat.label }}</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats[stat.value] || 0 }}</p>
                            </div>
                            <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', stat.bgColor]">
                                <svg v-if="stat.icon === 'users'" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <svg v-else-if="stat.icon === 'academic-cap'" class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                                <svg v-else-if="stat.icon === 'book-open'" class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <svg v-else-if="stat.icon === 'play'" class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg v-else class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        <p class="text-sm text-gray-500">Common administrative tasks</p>
                    </div>
                    <div class="p-6">
                        <div class="grid sm:grid-cols-5 gap-4">
                            <a v-for="action in quickActions" :key="action.title" 
                               :href="action.href"
                               class="flex flex-col items-center p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors group text-center">
                                <div :class="['w-12 h-12 rounded-xl flex items-center justify-center text-white mb-3', action.color]">
                                    <svg v-if="action.icon === 'users'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <svg v-else-if="action.icon === 'academic-cap'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                    <svg v-else-if="action.icon === 'shield-check'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <svg v-else-if="action.icon === 'book-open'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                    {{ action.title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ action.description }}</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="mt-8 grid lg:grid-cols-2 gap-6">
                    <!-- Recent Users -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                                <p class="text-sm text-gray-500">Latest registered users</p>
                            </div>
                            <a href="/admin/users" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View all</a>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">A</div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Admin User</p>
                                        <p class="text-xs text-gray-500">admin@example.com</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Admin</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-semibold">I</div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Instructor User</p>
                                        <p class="text-xs text-gray-500">instructor@example.com</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Instructor</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-semibold">S</div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Student User</p>
                                        <p class="text-xs text-gray-500">student@example.com</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Student</span>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Stats -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Platform Overview</h3>
                            <p class="text-sm text-gray-500">Quick statistics</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Active Instructors</span>
                                <span class="font-semibold text-gray-900">{{ stats.total_instructors || 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Total Courses</span>
                                <span class="font-semibold text-gray-900">{{ stats.total_courses || 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Total Episodes</span>
                                <span class="font-semibold text-gray-900">{{ stats.total_episodes || 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Categories</span>
                                <span class="font-semibold text-gray-900">{{ stats.total_categories || 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-600">Total Users</span>
                                <span class="font-semibold text-gray-900">{{ stats.total_users || 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

