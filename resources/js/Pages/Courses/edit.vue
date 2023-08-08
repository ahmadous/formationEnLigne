<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3';

export default{
    props:['course'],
    data() {
        return {
            courseData : this.course
        }
    },
    methods:{
        submit() {
                    router.patch('/courses/'+this.courseData.id, this.courseData)
                },
        add(){
            this.courseData.episodes.push( {
                title:null,
                description:null,
                video_url:null,
            });

        },
        remove(){
            this.courseData.episodes.pop();
        }
    }
}
</script>

<template>
    <AppLayout title="edit">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               Modiification de {{ courseData.title }}
            </h2>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form class=" bg-white shadow-md rounded px-8 lg:px-8" @submit.prevent="submit">
                        <div class="mx-4 py-4">
                            <label class="block text-sm font-bold text-gray-700" for="title">
                             Titre de la formation
                              <!-- Using form state modifiers, the classes can be identical for every input -->
                              <!-- ... -->
                            </label>
                            <input type="text" v-model="courseData.title" id="title" class="shadow appearance-none border-blue-500 rounded w-full py-2 px-3
                            leading-tight text-gray-500 focus:shadow-outline focus:outline-none
                            "/>
                        </div>
                        <div class="mx-4 py-4">
                            <label class="block text-sm font-bold text-gray-700" for="description">
                                Description de la formation
                            </label>
                            <textarea name="description" v-model="courseData.description" class="shadow appearance-none border-blue-500 rounded w-full py-2 px-3
                            text-gray-500 focus:shadow-outline focus:outline-none" id="description" cols="20" rows="5"></textarea>
                        </div>
                        <div class="mx-4 py4">
                            <h2 class="text-2">
                                Episodes de la Formation
                            </h2>
                            <div v-for="(episode, index) in this.courseData.episodes" :key="index">

                                <label class="block text-sm font-bold text-gray-700" :for="'title-'+index">
                                    Titre de l'episode numero {{ index +1}}
                                     <!-- Using form stat modifiers, the classes can be identical for every input -->
                                     <!-- ... -->
                                   </label>
                                   <input type="text" v-model="courseData.episodes[index].title" :id="'title-'+index" class="shadow appearance-none border-blue-500 rounded w-full py-2 px-3
                                   leading-tight text-gray-500 focus:shadow-outline focus:outline-none
                                   "/>

                                <label class="block text-sm font-bold text-gray-700" :for="'description-'+index">
                                    Description de l'episode numero {{ index +1}}
                                     <!-- Using form stat modifiers, the classes can be identical for every input -->
                                     <!-- ... -->
                                </label>
                                <textarea type="text" v-model="courseData.episodes[index].description" :id="'description-'+index" class="shadow appearance-none border-blue-500 rounded w-full py-2 px-3
                                   leading-tight text-gray-500 focus:shadow-outline focus:outline-none
                                " cols="20" rows="5"></textarea>

                                <label class="block text-sm font-bold text-gray-700" :for="'video_url-'+index">
                                    Url de la video de l'episode numero {{ index +1}}
                                     <!-- Using form stat modifiers, the classes can be identical for every input -->
                                     <!-- ... -->
                                </label>
                                <input type="text" v-model="courseData.episodes[index].video_url" :id="'video_url-'+index" class="shadow appearance-none border-blue-500 rounded w-full py-2 px-3
                                   leading-tight text-gray-500 focus:shadow-outline focus:outline-none
                                "/>

                            </div>
                        </div>
                        <button class="px-4 py-2 rounded bg-green-600 my-2 mx-4 text-white" @click.prevent="add()" v-if="courseData.episodes.length<15">+</button>
                        <button class="px-4 py-2 rounded bg-red-600 my-2 mx-4 text-white" @click.prevent="remove()" v-if="courseData.episodes.length>=1">-
                          </button>
                        <button class="block my-2 bg-blue-500 hover:bg-blue-700  text-white font-bold py-2 mx-4 px-4 rounded
                        focus:outline-none focus:shadow-outline" type="submit">
                        Modifier ma formation</button>
                      </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
