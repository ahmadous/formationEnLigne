<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    user: Object,
    stats: Object,
    myCourses: Array,
    categories: {
        type: Array,
        default: () => [],
    },
});

const welcome = computed(() => `Bonjour, ${props.user.name.split(' ')[0]}`);
const showQuickModal = ref(false);
const showToast = ref(false);
const toastMessage = ref('');
const redirectToEdit = ref(false);

const quickForm = useForm({
    title: '',
    description: '',
    category_id: null,
});

const submitQuick = () => {
    quickForm.post(route('courses.quick'), {
        preserveScroll: true,
        onSuccess: (page) => {
            const response = page.props.flash || {};
            if (response.course) {
                toastMessage.value = 'Cours créé avec succès !';
                showToast.value = true;
                
                setTimeout(() => {
                    if (redirectToEdit.value) {
                        router.visit(response.edit_url);
                    } else {
                        router.reload();
                    }
                }, 800);
            }
        },
        onError: (errors) => {
            toastMessage.value = 'Erreur lors de la création';
            showToast.value = true;
        },
    });
};

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">{{ welcome }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Bienvenue sur votre espace personnel</p>
                </div>
                <div class="flex items-center gap-3">
                    <Link v-if="stats.is_admin" href="/admin/dashboard" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">👑 Admin</Link>
                    <button v-if="stats.is_instructor" @click="showQuickModal = true" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Ajouter un cours</button>
                    <Link v-if="stats.is_instructor" href="/courses/create" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Créer une formation</Link>
                    <Link href="/my-courses" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200">Mes formations</Link>
                    <Link href="/courses" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200">Découvrir</Link>
                </div>
            </div>
        </template>

        <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Mes cours créés</div>
                    <div class="mt-3 text-2xl font-bold text-gray-900">{{ stats.my_courses }}</div>
                    <p class="text-xs text-gray-400 mt-2">Formations que vous avez créées</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Cours suivis</div>
                    <div class="mt-3 text-2xl font-bold text-gray-900">{{ stats.enrolled_courses }}</div>
                    <p class="text-xs text-gray-400 mt-2">Formations où vous êtes inscrit</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Rôle(s)</div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span v-for="role in stats.role_names" :key="role" class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                            {{ role === 'instructor' ? '👨‍🏫 Instructeur' : role === 'admin' ? '👑 Admin' : '👤 Étudiant' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Accès et actions rapides</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Mes dernières formations</h3>
                            <Link href="/my-courses" class="text-sm text-indigo-600 hover:underline">Voir tout</Link>
                        </div>

                        <div v-if="myCourses.length === 0" class="text-sm text-gray-500">Vous n'avez pas encore de formations.</div>
                        <ul class="space-y-3">
                            <li v-for="course in myCourses" :key="course.id" class="p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <Link :href="`/course/${course.id}`" class="text-sm font-medium text-gray-900">{{ course.title }}</Link>
                                        <div class="text-xs text-gray-400">Créé le {{ new Date(course.created_at).toLocaleDateString() }}</div>
                                    </div>
                                    <div class="text-xs text-gray-500">Actions</div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Activités récentes</h3>
                        <p class="text-sm text-gray-500">Aucune activité récente disponible pour le moment.</p>
                    </div>
                </div>

                <aside>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
                        <h4 class="text-sm font-semibold text-gray-900">Raccourcis</h4>
                        <div class="mt-3 flex flex-col gap-2">
                            <Link href="/courses" class="px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-sm">Parcourir les formations</Link>
                            <Link v-if="stats.is_instructor" href="/courses/create" class="px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm">Créer une formation</Link>
                            <Link href="/profile" class="px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-sm">Mon profil</Link>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-900">Support</h4>
                        <p class="text-sm text-gray-500 mt-2">Besoin d'aide ? Contactez l'administrateur.</p>
                    </div>
                </aside>
            </div>
        </div>
    </AppLayout>
    <!-- Quick Create Modal -->
    <div v-if="showQuickModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="showQuickModal = false"></div>
        <div class="bg-white rounded-2xl shadow-lg z-50 w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Ajouter un cours rapide</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-600">Titre *</label>
                    <input v-model="quickForm.title" type="text" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Titre du cours">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Description (optionnelle)</label>
                    <textarea v-model="quickForm.description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 resize-none" placeholder="Brève description"></textarea>
                </div>
                <div v-if="props.categories && props.categories.length">
                    <label class="text-sm text-gray-600">Catégorie (optionnelle)</label>
                    <select v-model="quickForm.category_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                        <option :value="null">Aucune</option>
                        <option v-for="cat in props.categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 bg-indigo-50 p-3 rounded-lg">
                    <input v-model="redirectToEdit" type="checkbox" id="editAfter" class="rounded">
                    <label for="editAfter" class="text-sm text-gray-700">Aller directement ajouter les épisodes</label>
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button @click="showQuickModal = false" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Annuler</button>
                <button @click="submitQuick" :disabled="quickForm.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50">{{ quickForm.processing ? 'Création...' : 'Créer' }}</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <transition name="toast">
        <div v-if="showToast" class="fixed bottom-6 right-6 z-50 bg-green-50 border border-green-200 rounded-lg px-4 py-3 shadow-lg">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm text-green-800">{{ toastMessage }}</p>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.toast-enter-active, .toast-leave-active {
    transition: all 0.3s ease;
}
.toast-enter-from, .toast-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>


