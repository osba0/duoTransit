<template>
    <div>
              
       <div class="row">
            <div class="col-md-12 pt-5">
                <div class="text-left pb-2 border-bottom  h4 mb-2">
                    {{ clientsAuth.length > 0 ? 'Société(s) supervisée(s) ' : 'Choisir une société' }} 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <template v-if="!clients.data || !clients.data.length">    
                    <span class="badge badge-warning p-2 " v-if="checking">Aucune société disponible!</span>
                </template>
                <ul class="d-flex list-unstyled  flex-wrap">
                    <li class="d-flex align-items-center col-3 box mt-5 mb-5" v-for = "client in clients.data" :key = "client.id">
                        <div class="d-flex flex-column justify-content-center align-items-center h-100 w-100">
                            <div class="logoCl text-center p-1 position-relative" style="background: transparent;">
                                <template v-if = "client.logo">
                                   <img :src="'/images/logo/'+client.logo" style="height: 80px" class="elevation-2 border bg-white inline-block" :alt="client.nom">
                                </template> 
                                <template v-else>
                                  No image
                                </template>
                                
                            </div>
                            <h4 class="text-center w-100">{{ client.nom }}</h4>
                            <template v-if="clientsAuth.length > 0">
                                <template v-if="roleUser == 'client'">
                                    <a :href="'/'+slugEntite+'/precharger/'+client.slug" class="btn text-white">
                                        <i class="fa fa-folder-open-o" aria-hidden="true"></i> Ourvir
                                    </a>
                                </template>
                                <template v-if="roleUser == 'consultation'">
                                     <a :href="'/'+slugEntite+'/consultation/'+client.slug" class="btn text-white">
                                        <i class="fa fa-folder-open-o" aria-hidden="true"></i> Ourvir
                                    </a>
                                </template>
                                <template v-if="roleUser == 'auxiliaire'">
                                     <a :href="'/'+slugEntite+'/numdocim/'+client.slug" class="btn text-white">
                                        <i class="fa fa-folder-open-o" aria-hidden="true"></i> Ourvir
                                    </a>
                                </template>
                            </template>
                            <template v-else>
                                <a :href="'/'+slugEntite+'/reception/'+client.slug" class="btn text-white">
                                    <i class="fa fa-folder-open-o" aria-hidden="true"></i> Ouvrir le dossier
                                </a>
                            </template>
                          
                        </div>
                        
                    </li>
                </ul>
            </div>
            
        </div>
    </div>
</template>
<script>
    export default { 
        props: [ 
          'clientSup',
          'roleUser',
          'slugEntite'
        ],
        data() { 
            return {
                clientsAuth : JSON.parse(this.clientSup),
                clients: {},
                checking: false,
            }
        },
        mounted() {
            this.isLoading=true;
            axios.get('/configuration/clientsHome/'+this.clientSup).then(response => {
                console.log(response);
                this.clients = response.data;
                if(!this.clients.length > 0){
                    this.checking=true;    
                }
                this.isLoading=false;
            });
        }
    }
</script>
