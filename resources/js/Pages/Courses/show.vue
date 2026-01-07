<template>
    <app-layout :title="course.title">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ course.title }}
                </h2>
                <div v-if="course.category" class="text-sm">
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full">
                        {{ course.category.name }}
                    </span>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Video Player Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-4">
                        <div v-if="course.episodes && course.episodes.length > 0">
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe 
                                    class="w-full h-[500px] rounded-lg"
                                    :src="getEmbedUrl(currentEpisode.video_url)" 
                                    :title="currentEpisode.title"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen
                                ></iframe>
                            </div>
                            
                            <!-- Watch on YouTube Link -->
                            <div class="mt-3 flex justify-end">
                                <a 
                                    :href="getYouTubeWatchUrl(currentEpisode.video_url)" 
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center text-red-600 hover:text-red-800 font-medium"
                                >
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                    </svg>
                                    Regarder sur YouTube
                                </a>
                            </div>
                            
                            <div class="mt-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ currentEpisode.title }}</h3>
                                <p class="mt-2 text-gray-600">{{ currentEpisode.description }}</p>
                            </div>
                        </div>
                        <div v-else class="text-center py-12 text-gray-500">
                            Aucun épisode disponible pour le moment.
                        </div>
                    </div>
                </div>

                <!-- Episodes List -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Épisodes ({{ course.episodes ? course.episodes.length : 0 }})
                        </h3>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        <li v-for="(episode, index) in course.episodes" :key="episode.id" 
                            class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4">
                                    <button 
                                        @click="switchEpisode(index)"
                                        class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center hover:bg-indigo-700 transition-colors"
                                    >
                                        ▶
                                    </button>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            Épisode {{ index + 1 }}: {{ episode.title }}
                                        </p>
                                        <p class="text-sm text-gray-500 line-clamp-1">
                                            {{ episode.description }}
                                        </p>
                                    </div>
                                </div>
                                <progress-button :episode-id="episode.id" :vu-episodes="vu"/>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout.vue';
import progressButton from './progressButton.vue';
import progressBar from './progressBar.vue';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        AppLayout,
        progressButton,
        progressBar,
        Link,
    },
    props: ['course', 'vu'],
    data() {
        return {
            courseShow: this.course,
            currentKey: 0,
        };
    },
    computed: {
        currentEpisode() {
            if (this.courseShow.episodes && this.courseShow.episodes[this.currentKey]) {
                return this.courseShow.episodes[this.currentKey];
            }
            return { title: '', description: '', video_url: '' };
        }
    },
    methods: {
        switchEpisode(index) {
            this.currentKey = index;
            window.scroll({
                top: 0,
                left: 0,
                behavior: 'smooth',
            });
        },
        getEmbedUrl(url) {
            if (!url) return '';
            
            // YouTube watch URL
            if (url.includes('youtube.com/watch?v=')) {
                const videoId = url.split('v=')[1].split('&')[0];
                return 'https://www.youtube.com/embed/' + videoId;
            }
            
            // YouTube short URL
            if (url.includes('youtu.be/')) {
                const videoId = url.split('youtu.be/')[1].split('?')[0];
                return 'https://www.youtube.com/embed/' + videoId;
            }
            
            // Vimeo
            if (url.includes('vimeo.com/')) {
                const videoId = url.split('vimeo.com/')[1].split('?')[0];
                return 'https://player.vimeo.com/video/' + videoId;
            }
            
            // Return original URL for other sources
            return url;
        },
        getYouTubeWatchUrl(url) {
            if (!url) return '#';
            
            // YouTube watch URL
            if (url.includes('youtube.com/watch?v=')) {
                return url;
            }
            
            // YouTube short URL
            if (url.includes('youtu.be/')) {
                const videoId = url.split('youtu.be/')[1].split('?')[0];
                return 'https://www.youtube.com/watch?v=' + videoId;
            }
            
            // Return original URL
            return url;
        },
    },
    mounted() {
        // Reset currentKey if course has no episodes
        if (!this.courseShow.episodes || this.courseShow.episodes.length === 0) {
            this.currentKey = 0;
        }
    },
};
</script>

