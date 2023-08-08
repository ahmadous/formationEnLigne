<template>

    <div>
        {{pourcentage}}
    </div>

</template>
<script>
export default{
    props:[
        'vuEpisodes',
        'episodes'
    ],
    computed:{
        pourcentage(){
            let filterEpisode=this.episodes.filter(courseEp=>{
                return this.vuEpisodes.find(vuEp=>{
                    return vuEp.id==courseEp.id;
                });
            });
            return  filterEpisode.length;
        }
    },

    mounted(){
        console.log(this.vuEpisodes,this.episodes);
    }
}
</script>
