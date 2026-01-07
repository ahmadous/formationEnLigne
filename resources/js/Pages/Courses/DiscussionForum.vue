<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    episode: {
        type: Object,
        required: true,
    },
    course: {
        type: Object,
        required: true,
    },
    discussions: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const user = page.props.auth.user;

const newDiscussionTitle = ref('');
const newDiscussionContent = ref('');
const showNewForm = ref(false);
const selectedDiscussion = ref(null);
const replyContent = ref('');
const isLoading = ref(false);

const isInstructor = computed(() => {
    return user?.roles?.some(r => r.slug === 'instructor') || user?.roles?.some(r => r.slug === 'admin');
});

const isAuthor = computed(() => {
    return user?.id === course?.user_id;
});

const createDiscussion = async () => {
    if (!newDiscussionTitle.value.trim() || !newDiscussionContent.value.trim()) {
        alert('Veuillez remplir le titre et le contenu');
        return;
    }

    isLoading.value = true;
    try {
        const response = await fetch(route('discussions.store'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                episode_id: props.episode.id,
                title: newDiscussionTitle.value,
                content: newDiscussionContent.value,
            }),
        });

        if (response.ok) {
            const data = await response.json();
            props.discussions.unshift(data);
            newDiscussionTitle.value = '';
            newDiscussionContent.value = '';
            showNewForm.value = false;
        }
    } catch (error) {
        console.error('Error creating discussion:', error);
        alert('Erreur lors de la création de la discussion');
    } finally {
        isLoading.value = false;
    }
};

const addReply = async (discussion) => {
    if (!replyContent.value.trim()) {
        alert('Veuillez écrire une réponse');
        return;
    }

    isLoading.value = true;
    try {
        const response = await fetch(route('discussions.reply', discussion.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                content: replyContent.value,
            }),
        });

        if (response.ok) {
            const data = await response.json();
            if (!discussion.replies) {
                discussion.replies = [];
            }
            discussion.replies.push(data);
            replyContent.value = '';
            selectedDiscussion.value = null;
        }
    } catch (error) {
        console.error('Error adding reply:', error);
        alert('Erreur lors de l\'ajout de la réponse');
    } finally {
        isLoading.value = false;
    }
};

const toggleSolved = async (discussion) => {
    if (!isAuthor) return;

    try {
        const response = await fetch(route('discussions.toggleSolved', discussion.id), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (response.ok) {
            discussion.is_solved = !discussion.is_solved;
        }
    } catch (error) {
        console.error('Error toggling solved:', error);
    }
};

const deleteDiscussion = async (discussion) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette discussion ?')) return;

    try {
        const response = await fetch(route('discussions.destroy', discussion.id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (response.ok) {
            const index = props.discussions.indexOf(discussion);
            if (index > -1) {
                props.discussions.splice(index, 1);
            }
        }
    } catch (error) {
        console.error('Error deleting discussion:', error);
    }
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">💬 Discussions & Questions</h3>
                <p class="text-sm text-gray-500 mt-1">{{ discussions.length }} discussion{{ discussions.length !== 1 ? 's' : '' }}</p>
            </div>
            <button
                v-if="user"
                @click="showNewForm = !showNewForm"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors"
            >
                + Nouvelle discussion
            </button>
        </div>

        <!-- New Discussion Form -->
        <div v-if="showNewForm && user" class="p-6 bg-gray-50 border-b border-gray-100">
            <input
                v-model="newDiscussionTitle"
                type="text"
                placeholder="Titre de votre question..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:outline-none focus:border-indigo-500"
            />
            <textarea
                v-model="newDiscussionContent"
                placeholder="Décrivez votre question en détail..."
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:outline-none focus:border-indigo-500 resize-none"
            />
            <div class="flex gap-3">
                <button
                    @click="createDiscussion"
                    :disabled="isLoading"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium disabled:opacity-50"
                >
                    {{ isLoading ? 'En cours...' : 'Publier' }}
                </button>
                <button
                    @click="showNewForm = false"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium"
                >
                    Annuler
                </button>
            </div>
        </div>

        <!-- Discussions List -->
        <div class="divide-y divide-gray-100">
            <div v-if="discussions.length === 0" class="p-6 text-center text-gray-500">
                <p>Aucune discussion pour le moment. Soyez le premier à poser une question ! 🚀</p>
            </div>

            <div v-for="discussion in discussions" :key="discussion.id" class="p-6 hover:bg-gray-50 transition-colors">
                <!-- Discussion Header -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="text-base font-semibold text-gray-900">{{ discussion.title }}</h4>
                            <span
                                v-if="discussion.is_solved"
                                class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full"
                            >
                                ✓ Résolue
                            </span>
                            <span
                                v-if="isInstructor && discussion.is_instructor_answer"
                                class="inline-flex px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full"
                            >
                                Réponse du formateur
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">
                            par <strong>{{ discussion.user?.name }}</strong>
                            <span class="text-xs text-gray-400">{{ new Date(discussion.created_at).toLocaleDateString('fr-FR') }}</span>
                        </p>
                    </div>
                    <div v-if="isAuthor || user?.id === discussion.user_id" class="flex gap-2">
                        <button
                            v-if="isAuthor"
                            @click="toggleSolved(discussion)"
                            :title="discussion.is_solved ? 'Marquer comme non résolue' : 'Marquer comme résolue'"
                            class="p-2 text-gray-400 hover:text-green-600"
                        >
                            ✓
                        </button>
                        <button
                            v-if="user?.id === discussion.user_id || isAuthor"
                            @click="deleteDiscussion(discussion)"
                            title="Supprimer"
                            class="p-2 text-gray-400 hover:text-red-600"
                        >
                            🗑️
                        </button>
                    </div>
                </div>

                <!-- Discussion Content -->
                <p class="text-gray-700 mb-4">{{ discussion.content }}</p>

                <!-- Replies -->
                <div v-if="discussion.replies && discussion.replies.length > 0" class="bg-gray-50 rounded-lg p-4 mb-4 space-y-3">
                    <p class="text-sm font-medium text-gray-600">{{ discussion.replies.length }} réponse{{ discussion.replies.length !== 1 ? 's' : '' }}:</p>
                    <div v-for="reply in discussion.replies" :key="reply.id" class="text-sm border-l-2 border-indigo-300 pl-3">
                        <p class="font-medium text-gray-900">
                            {{ reply.user?.name }}
                            <span v-if="reply.is_instructor_answer" class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                Formateur
                            </span>
                        </p>
                        <p class="text-gray-600 mt-1">{{ reply.content }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ new Date(reply.created_at).toLocaleDateString('fr-FR') }}</p>
                    </div>
                </div>

                <!-- Reply Form -->
                <button
                    v-if="user && selectedDiscussion?.id !== discussion.id"
                    @click="selectedDiscussion = discussion"
                    class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"
                >
                    Répondre
                </button>

                <div v-if="selectedDiscussion?.id === discussion.id" class="mt-4 pt-4 border-t border-gray-200">
                    <textarea
                        v-model="replyContent"
                        placeholder="Votre réponse..."
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:outline-none focus:border-indigo-500 resize-none text-sm"
                    />
                    <div class="flex gap-2">
                        <button
                            @click="addReply(discussion)"
                            :disabled="isLoading"
                            class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium disabled:opacity-50"
                        >
                            {{ isLoading ? 'En cours...' : 'Envoyer' }}
                        </button>
                        <button
                            @click="selectedDiscussion = null"
                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium"
                        >
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
