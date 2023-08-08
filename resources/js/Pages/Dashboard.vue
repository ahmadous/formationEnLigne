<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3';

const form = reactive({
  title: null,
  description: null,
  episodes:[
    {
        title:null,
        description:null,
        video_url:null,
    }
  ]
})

function submit() {
  router.post('/courses', form)
}
function add(){
    this.form.episodes.push( {
        title:null,
        description:null,
        video_url:null,
    });
}
function remove(){
    this.form.episodes.pop();
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form class="bg-white shadow-md rounded px-8 lg:px-8" @submit.prevent="submit">
                        <div class="mx-4 py-4">
                            <label class="block text-sm font-bold text-gray-700" for="title">
                             Titre de la formation
                              <!-- Using form state modifiers, the classes can be identical for every input -->
                              <!-- ... -->
                            </label>
                            <input type="text" v-model="form.title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3
                            leading-tight text-gray-700 focus:shadow-outline focus:outline-none
                            "/>
                        </div>
                        <div class="mx-4 py-4">
                            <label class="block text-sm font-bold text-gray-700" for="description">
                                Description de la formation
                            </label>
                            <textarea name="description" v-model="form.description" class="shadow appearance-none border rounded w-full py-2 px-3
                            text-gray-700 focus:shadow-outline focus:outline-none" id="description" cols="20" rows="5"></textarea>
                        </div>
                        <div class="mx-4 py4">
                            <h2 class="text-2">
                                Episodes de la Formation
                            </h2>
                            <div v-for="(episode, index) in form.episodes" :key="index">

                                <label class="block text-sm font-bold text-gray-700" :for="'title-'+index">
                                    Titre de l'episode numero {{ index +1}}
                                     <!-- Using form stat modifiers, the classes can be identical for every input -->
                                     <!-- ... -->
                                   </label>
                                   <input type="text" v-model="form.episodes[index].title" :id="'title-'+index" class="shadow appearance-none border rounded w-full py-2 px-3
                                   leading-tight text-gray-700 focus:shadow-outline focus:outline-none
                                   "/>

                                <label class="block text-sm font-bold text-gray-700" :for="'description-'+index">
                                    Description de l'episode numero {{ index +1}}
                                     <!-- Using form stat modifiers, the classes can be identical for every input -->
                                     <!-- ... -->
                                </label>
                                <textarea type="text" v-model="form.episodes[index].description" :id="'description-'+index" class="shadow appearance-none border rounded w-full py-2 px-3
                                   leading-tight text-gray-700 focus:shadow-outline focus:outline-none
                                " cols="20" rows="5"></textarea>

                                <label class="block text-sm font-bold text-gray-700" :for="'video_url-'+index">
                                    Url de la video de l'episode numero {{ index +1}}
                                     <!-- Using form stat modifiers, the classes can be identical for every input -->
                                     <!-- ... -->
                                </label>
                                <input type="text" v-model="form.episodes[index].video_url" :id="'video_url-'+index" class="shadow appearance-none border rounded w-full py-2 px-3
                                   leading-tight text-gray-700 focus:shadow-outline focus:outline-none
                                "/>

                            </div>
                        </div>
                        <button class="px-4 py-2 rounded bg-green-600 my-2 mx-4 text-white block" @click.prevent="add()" v-if="form.episodes.length<15">+</button>
                        <button class="px-4 py-2 rounded bg-red-600 my-2 mx-4 text-white block" @click.prevent="remove()" v-if="form.episodes.length>1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                          </svg>
                          </button>
                        <button class=" my-2 bg-blue-500 hover:bg-blue-700  text-white font-bold py-2 mx-4 px-4 rounded
                        focus:outline-none focus:shadow-outline" type="submit">
                        creer ma formation</button>
                      </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
