<template>
    <div>
        <button class="text-white bg-green-500 rounded-lg p-1" @click="toggleProgress()">{{this.estVu?'termine !':"n'est pas termine !"}}</button>
    </div>
</template>
<script>

    export default{
        props:['episodeId','vuEpisodes',],
        data(){
            return {
                vuEp:this.vuEpisodes,
                estVu:null,
            }
        },
        methods:{
            toggleProgress(){
                axios.post('/toggleProgress',{
                    episodeId:this.episodeId,
                })
                .then(response=>{
                    if(response.status==200){
                        this.estVu=!this.estVu;
                    }
                })
                .catch(error=>console.log(error));
            },
            episodeVu(){
                return this.vuEp.find(episode=>episode.id==this.episodeId)?true:false;
            }
        },
        mounted(){
           this.estVu=this.episodeVu();
        }
    }
</script>
