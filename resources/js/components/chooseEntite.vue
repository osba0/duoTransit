<template>
    <div>
        <template v-if="configMode=='select'">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <span v-for="entite in listEntite" :title="entite" :class="[slugEntite==entite.slug? '':'d-none']">
                    <img :src="'/images/logo/'+entite.logo" :alt="entite.nom" width="100" class="bg-white inline-block" 
                           >
                    </span>
              </button>
              <div class="dropdown-menu px-2" aria-labelledby="dropdownMenu2">
                 <a v-for="entite in listEntite" :title="entite" :class="[slugEntite==entite.slug? 'd-none':'d-block']">
                    <img :src="'/images/logo/'+entite.logo" :alt="entite.nom" width="100" class="bg-white inline-block" 
                           >
                    </a>
              </div>
            </div>
        </template>
        <div class="row d-select">
            <div v-for="entite in listEntite" class="col cursor-pointer" @click="goLink(entite.slug)" :class="[slugEntite==entite.slug || slugEntite==''? '':'d-none']">
                <div class="mb-4 rounded-lg p-2 bg-light-blue" :class="[slugEntite==entite.slug || slugEntite==''? 'actionBox1':'inactifBox']">
                    <div role="button" class="rounded-lg bg-white text-center" :class="[configMode=='small'? 'p-1':'p-3']">
                        <div class="h1 text-primary font-weight-bold text-center">
                            <img :src="'/images/logo/'+entite.logo" :alt="entite.nom" class="bg-white inline-block" 
                            v-bind:style="size">
                        </div> 
                        <span class="text-primary d-block text-uppercase font-weight-bold h6 m-0 white-space-nowrap ">{{ entite.nom }}</span>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
          'listEntite',
          'configMode',
          'slugEntite'
        ],
        data() { 
            return {
              currentEntite: '',
              size: {
                height: '60px'
              }
            }

        },
        methods : { 
            setEntite(){
                window.location.href ='/transitaire/'+this.currentEntite.slug;
            },
            goLink(slug){
                window.location.href ='/transitaire/'+slug;
            }
        },
        mounted() {
           if(this.configMode == 'normal'){
              
            }
            if(this.configMode == 'small'){
                this.size.height = '30px'
            }
        }
    }
</script>
