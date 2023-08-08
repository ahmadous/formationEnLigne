<template>
    <app-layout title="course">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Listes des Formations
            </h2>
        </template>

        <div class="py-6" v-for="course in this.courseList" v-bind:key="course.id">
            <div class="mx-4 bg-white rounded shadow p-4">
                <div class="text-sm text-gray-500">Mise en ligne par :{{course.user.name}} - <span class="text-gray-500 text-sm">{{course.participants}}participant<span v-if="parseInt(course.participants)>1">s</span></span></div>
                <div class="flex justify-between items-center">
                    <div class="text-2xl">{{course.title}}</div>
                    <div class="text-sm text-gray-500">{{course.episodes_count}} episodes</div>
                </div>
                <div class="text-sm text-gray-500">{{course.description}}</div>
                <div class="flex items-center justify-between">
                    <a :href="'course/'+course.id"  class="bg-blue-700 text-white text-sm px-2 py-1 mt-3 inline-block rounded-lg shadow-lg hover:bg-blue-500">voir la formation</a>
                    <a :href="'courses/edit/'+course.id" v-if="course.permission" class="bg-blue-700 text-white text-sm px-2 py-1 mt-3 inline-block rounded-lg shadow-lg hover:bg-blue-500">modifier la formation</a>
                </div>
            </div>
        </div>

    </app-layout>
</template>
<script>
    import AppLayout from './../../Layouts/AppLayout.vue';
    export default{
        components:{
            AppLayout
        },
        data(){
            return{
                courseList:this.courses
            }
        },
        props:['courses'],
        mounted(){
            console.log(this.courseList);
        }
    }
</script>
