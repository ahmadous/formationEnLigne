<template>
    <app-layout :title="course.title">
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        {{ course.title }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">{{ course.episodes ? course.episodes.length : 0 }} épisodes</p>
                </div>
                <div v-if="course.category" class="text-sm">
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full">
                        {{ course.category.name }}
                    </span>
                </div>
            </div>
        </template>

        <div class="py-6 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content (Left) -->
                    <div class="lg:col-span-2">
                        <!-- Video Player Section -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                            <div class="bg-black rounded-lg" style="aspect-ratio: 16/9;">
                                <div v-if="course.episodes && course.episodes.length > 0" class="w-full h-full">
                                    <video-player 
                                        :video-url="currentEpisode.video_url"
                                        :episode-title="currentEpisode.title"
                                    />
                                </div>
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Episode Info -->
                            <div v-if="course.episodes && course.episodes.length > 0" class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ currentEpisode.title }}</h3>
                                        <p class="text-gray-500 text-sm mt-1">Épisode {{ currentEpisodeIndex + 1 }} sur {{ course.episodes.length }}</p>
                                    </div>
                                    <progress-button :episode-id="currentEpisode.id" :vu-episodes="vu"/>
                                </div>
                                <p class="text-gray-700 leading-relaxed">{{ currentEpisode.description }}</p>
                                
                                <!-- External Link -->
                                <div v-if="getVideoSource(currentEpisode.video_url).external" class="mt-4">
                                    <a 
                                        :href="getVideoSource(currentEpisode.video_url).watchUrl" 
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M10 6a4 4 0 11-8 0 4 4 0 018 0zM15.228 12c.149-.365.852-.365 1.154-.365h.02c.114 0 .227-.006.228-.019v.019c.4 0 .795.008 1.084.298.152.15.685.703.852 1.112.039.1.1.199.145.299.105.244.185.464.258.713a5.212 5.212 0 01.393 2.832c0 .41-.024.81-.07 1.202a4.248 4.248 0 01-.424 1.424 4.526 4.526 0 01-2.582 2.582 4.26 4.26 0 01-1.424.423c-.393.046-.792.07-1.202.07-.41 0-.81-.024-1.202-.07a4.26 4.26 0 01-1.424-.423 4.526 4.526 0 01-2.582-2.582 4.248 4.248 0 01-.423-1.424 8.054 8.054 0 01-.07-1.202c0-.41.024-.81.07-1.202a4.248 4.248 0 01.424-1.424 4.526 4.526 0 012.582-2.582 4.26 4.26 0 011.424-.423c.393-.046.792-.07 1.202-.07.41 0 .81.024 1.202.07a4.26 4.26 0 011.424.423c.566.286 1.084.714 1.502 1.123.149.15.298.3.437.463" />
                                        </svg>
                                        Regarder en ligne
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Discussions Section -->
                        <div class="mt-6">
                            <discussion-forum 
                                v-if="course.episodes && course.episodes.length > 0 && currentEpisode.id"
                                :episode="currentEpisode" 
                                :course="course" 
                                :discussions="discussions"
                            />
                        </div>
                    </div>

                    <!-- Sidebar (Right) -->
                    <div class="lg:col-span-1">
                        <!-- Episodes List -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg sticky top-6">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">
                                    Programme ({{ course.episodes ? course.episodes.length : 0 }})
                                </h3>
                            </div>
                            <div v-if="course.episodes && course.episodes.length > 0" class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                                <button 
                                    v-for="(episode, index) in course.episodes" 
                                    :key="episode.id"
                                    @click="switchEpisode(index)"
                                    :class="[
                                        'w-full p-4 text-left hover:bg-indigo-50 transition-colors',
                                        index === currentEpisodeIndex ? 'bg-indigo-100 border-l-4 border-indigo-600' : ''
                                    ]"
                                >
                                    <div class="flex items-start space-x-3">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                            {{ index + 1 }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ episode.title }}
                                            </p>
                                            <p class="text-xs text-gray-500 line-clamp-2">
                                                {{ episode.description }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <div v-else class="p-4 text-center text-gray-500">
                                Aucun épisode disponible
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout.vue';
import progressButton from './progressButton.vue';
import { Link } from '@inertiajs/vue3';
import DiscussionForum from './DiscussionForum.vue';
import VideoPlayer from './VideoPlayer.vue';

export default {
    components: {
        AppLayout,
        progressButton,
        Link,
        DiscussionForum,
        VideoPlayer,
    },
    props: ['course', 'vu'],
    data() {
        return {
            courseShow: this.course,
            currentEpisodeIndex: 0,
            discussions: [],
        };
    },
    computed: {
        currentEpisode() {
            if (this.courseShow.episodes && this.courseShow.episodes[this.currentEpisodeIndex]) {
                return this.courseShow.episodes[this.currentEpisodeIndex];
            }
            return { id: null, title: '', description: '', video_url: '' };
        }
    },
    methods: {
        async switchEpisode(index) {
            this.currentEpisodeIndex = index;
            await this.loadDiscussions(this.currentEpisode.id);
            window.scroll({ top: 0, left: 0, behavior: 'smooth' });
        },
        async loadDiscussions(episodeId) {
            if (!episodeId) {
                this.discussions = [];
                return;
            }
            try {
                const res = await fetch(`/episodes/${episodeId}/discussions`, {
                    headers: { 'Accept': 'application/json' },
                });
                if (res.ok) {
                    this.discussions = await res.json();
                }
            } catch (e) {
                console.error('Impossible de charger les discussions', e);
            }
        },
        getVideoSource(url) {
            if (!url) return { type: 'unknown', external: false };
            
            if (url.includes('youtube.com/watch?v=') || url.includes('youtu.be/')) {
                const videoId = url.includes('watch?v=') 
                    ? url.split('v=')[1].split('&')[0]
                    : url.split('youtu.be/')[1].split('?')[0];
                return {
                    type: 'youtube',
                    embedUrl: 'https://www.youtube.com/embed/' + videoId,
                    watchUrl: 'https://www.youtube.com/watch?v=' + videoId,
                    external: true
                };
            }
            
            if (url.includes('vimeo.com/')) {
                const videoId = url.split('vimeo.com/')[1].split('?')[0];
                return {
                    type: 'vimeo',
                    embedUrl: 'https://player.vimeo.com/video/' + videoId,
                    watchUrl: url,
                    external: true
                };
            }
            
            if (url.includes('dailymotion.com/') || url.includes('dai.ly/')) {
                return {
                    type: 'dailymotion',
                    embedUrl: url,
                    watchUrl: url,
                    external: true
                };
            }
            
            // Local file or direct URL
            return {
                type: 'file',
                embedUrl: url,
                watchUrl: url,
                external: false
            };
        },
    },
    async mounted() {
        if (!this.courseShow.episodes || this.courseShow.episodes.length === 0) {
            this.currentEpisodeIndex = 0;
        }
        await this.loadDiscussions(this.currentEpisode.id);
    },
};
</script>