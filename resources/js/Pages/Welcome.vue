<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    coursesCount: Number,
    usersCount: Number,
});

const features = [
    {
        icon: 'play',
        title: 'Apprentissage Vidéo',
        description: 'Accédez à des formations vidéo de haute qualité anytime, anywhere.'
    },
    {
        icon: 'users',
        title: 'Communauté Active',
        description: 'Rejoignez une communauté d\'apprenants passionnés et motivés.'
    },
    {
        icon: 'certificate',
        title: 'Certificats',
        description: 'Obtenez des certificats reconnus pour valider vos compétences.'
    },
    {
        icon: 'mobile',
        title: 'Multi-Devices',
        description: 'Apprenez sur votre ordinateur, tablette ou mobile.'
    }
];

const stats = [
    { value: '500+', label: 'Formations' },
    { value: '10K+', label: 'Apprenants' },
    { value: '50+', label: 'Instructeurs' },
    { value: '95%', label: 'Satisfaction' }
];
</script>

<template>
    <Head title="Formation en Ligne - Apprenez à votre rythme" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            FormationPro
                        </span>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-4">
                        <template v-if="canLogin">
                            <Link v-if="$page.props.auth.user" 
                                  :href="route('dashboard')"
                                  class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link :href="route('login')" 
                                      class="px-4 py-2 text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                                    Connexion
                                </Link>
                                <Link v-if="canRegister" 
                                      :href="route('register')"
                                      class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all font-medium shadow-lg shadow-indigo-500/25">
                                    Commencer Gratuitement
                                </Link>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-8">
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2 animate-pulse"></span>
                            Nouvelle plateforme d'apprentissage
                        </div>
                        
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                            Apprenez sans limites,
                            <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                grandissez toujours
                            </span>
                        </h1>
                        
                        <p class="text-xl text-gray-600 leading-relaxed max-w-lg">
                            Découvrez des milliers de formations créées par des experts. Développez vos compétences, avancez votre carrière et atteignez vos objectifs.
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <Link :href="canRegister ? route('register') : route('courses.index')"
                                  class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all font-semibold text-lg shadow-xl shadow-indigo-500/25 flex items-center">
                                Commencer maintenant
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </Link>
                            <Link href="/courses" 
                                  class="px-8 py-4 bg-white text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-semibold text-lg border-2 border-gray-200 flex items-center">
                                Voir les formations
                            </Link>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-4 gap-6 pt-8 border-t border-gray-200">
                            <div v-for="stat in stats" :key="stat.label" class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ stat.value }}</div>
                                <div class="text-sm text-gray-500">{{ stat.label }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Image/Illustration -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-3xl transform rotate-3 opacity-10"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden p-8">
                            <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-10 h-10 text-indigo-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 space-y-3">
                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                            </div>
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    <div class="w-8 h-8 bg-indigo-500 rounded-full border-2 border-white"></div>
                                    <div class="w-8 h-8 bg-purple-500 rounded-full border-2 border-white"></div>
                                    <div class="w-8 h-8 bg-pink-500 rounded-full border-2 border-white"></div>
                                </div>
                                <div class="text-sm text-gray-500">+2.5k inscrits</div>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-400 rounded-full flex items-center justify-center shadow-xl animate-bounce">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-white rounded-xl shadow-xl p-4 flex items-center space-x-2">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Certification</div>
                                <div class="text-xs text-gray-500">Reconnue internationalement</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Pourquoi choisir FormationPro ?
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Une plateforme complète pour développer vos compétences professionnelles
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="feature in features" :key="feature.title" 
                         class="group p-6 bg-gradient-to-br from-gray-50 to-white rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg v-if="feature.icon === 'play'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg v-else-if="feature.icon === 'users'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg v-else-if="feature.icon === 'certificate'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <svg v-else class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ feature.title }}</h3>
                        <p class="text-gray-600">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                    Prêt à commencer votre voyage d'apprentissage ?
                </h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Rejoignez des milliers d'apprenants et transformez votre carrière dès aujourd'hui.
                </p>
                <Link :href="canRegister ? route('register') : route('courses.index')"
                      class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 rounded-xl hover:bg-gray-50 transition-all font-semibold text-lg shadow-xl">
                    Commencer gratuitement
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-2 mb-4 md:mb-0">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">FormationPro</span>
                    </div>
                    <div class="text-sm">
                        © 2024 FormationPro. Tous droits réservés.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

