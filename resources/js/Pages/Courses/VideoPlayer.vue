<template>
    <div class="w-full h-full bg-black rounded-lg overflow-hidden">
        <!-- YouTube Embed -->
        <iframe 
            v-if="source.type === 'youtube'"
            class="w-full h-full"
            :src="source.embedUrl"
            :title="episodeTitle"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
        ></iframe>

        <!-- Vimeo Embed -->
        <iframe 
            v-if="source.type === 'vimeo'"
            class="w-full h-full"
            :src="source.embedUrl"
            :title="episodeTitle"
            frameborder="0"
            allow="autoplay; fullscreen; picture-in-picture"
            allowfullscreen
        ></iframe>

        <!-- Dailymotion Embed -->
        <iframe 
            v-if="source.type === 'dailymotion'"
            class="w-full h-full"
            :src="source.embedUrl"
            :title="episodeTitle"
            frameborder="0"
            allow="autoplay; fullscreen; picture-in-picture"
            allowfullscreen
        ></iframe>

        <!-- HTML5 Video Player (Local files) -->
        <video 
            v-if="source.type === 'file'"
            class="w-full h-full"
            controls
            :title="episodeTitle"
        >
            <source :src="source.embedUrl" type="video/mp4">
            <p>Votre navigateur ne supporte pas la balise vidéo HTML5.</p>
        </video>

        <!-- Fallback -->
        <div v-if="source.type === 'unknown'" class="w-full h-full flex items-center justify-center text-white">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="opacity-50">Format vidéo non supporté</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        videoUrl: {
            type: String,
            required: true,
        },
        episodeTitle: {
            type: String,
            default: 'Lecture vidéo',
        },
    },
    computed: {
        source() {
            return this.parseVideoSource(this.videoUrl);
        },
    },
    methods: {
        parseVideoSource(url) {
            if (!url) return { type: 'unknown', embedUrl: '' };
            
            // YouTube
            if (url.includes('youtube.com/watch?v=') || url.includes('youtu.be/')) {
                const videoId = url.includes('watch?v=')
                    ? url.split('v=')[1].split('&')[0]
                    : url.split('youtu.be/')[1].split('?')[0];
                return {
                    type: 'youtube',
                    embedUrl: `https://www.youtube.com/embed/${videoId}`,
                };
            }
            
            // Vimeo
            if (url.includes('vimeo.com/')) {
                const videoId = url.split('vimeo.com/')[1].split('?')[0];
                return {
                    type: 'vimeo',
                    embedUrl: `https://player.vimeo.com/video/${videoId}`,
                };
            }
            
            // Dailymotion
            if (url.includes('dailymotion.com/') || url.includes('dai.ly/')) {
                return {
                    type: 'dailymotion',
                    embedUrl: url.replace('watch/', 'embed/video/'),
                };
            }
            
            // Local file or direct URL
            return {
                type: 'file',
                embedUrl: url,
            };
        },
    },
};
</script>
