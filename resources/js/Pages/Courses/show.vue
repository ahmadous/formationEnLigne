<template>
    <app-layout title="course">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ course.title }}
            </h2>
        </template>

        <div class="py-3 mx-8 ">
            <div class="text-2xl">{{this.courseShow.episodes[this.currentKey].title}}</div>
            <iframe class="w-full h-screen my-2" :src="this.courseShow.episodes[this.currentKey].video_url" title="Application full stack Laravel et InertiaJS - Afficher les épisodes" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            <div class="text-sm text-gray-700">{{this.courseShow.episodes[this.currentKey].description}}</div>
        </div>
        <div class="py-6 mx-8">
            <!-- <progress-bar :vu-episodes="vu" :episodes="course.episode"/> -->
        </div>
        <div class="mx-8 p-3 bg-white">
            <ul v-for="(episode, index) in this.courseShow.episodes" :key="episode.id">
                <li class="mt-3 flex justify-between items-center">
                  <div>
                    Episode numero :{{index+1}} -  {{episode.title}}
                    <button class="text-gray-500 focus:text-indigo-500" @click="switchEpisode(index)">voir l'episode</button>
                </div>
                <progress-button :episode-id="episode.id" :vu-episodes="vu"/>
            </li>
        </ul>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from './../../Layouts/AppLayout.vue';
    import progressButton from './progressButton.vue';
    import progressBar from './progressBar.vue';
    export default{
        components:{
            AppLayout,
            progressButton,
            progressBar,
        },
        props:['course','vu',],
        data(){
            return{
                courseShow:this.course,
                currentKey:0,
            }
        },
        methods:{
            switchEpisode(index){
                this.currentKey=index;
                window.scroll({
                    top:0,
                    left:0,
                    behavior:'smooth',

                })
            },
        },
        mounted(){
            // console.log(this.courseList);
        }
    }
</script>
