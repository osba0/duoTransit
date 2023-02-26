<template>
    <ul class="legend mt-0 mb-2 pl-3 flex-1">
        <li class="align-items-center d-flex" v-for="prd in produitActif">
            <span class="d-flex type2 etat_T m-0 mr-1 border-0 deae" :class="getTypeProduit(prd)"></span> 
            <label class="m-0 mr-2">{{ prd }}</label>
        </li>
        <!--li class="align-items-center d-none" :class="[exist.includes('Précurseur de drogue')?'d-flex':'d-none']">
            <span class="type2 etat_T m-0 mr-1 border-0 precurseur_drogue"></span> 
            <label class="m-0 mr-2">Précurseur de drogue</label>
        </li>
        <li class="align-items-center d-none" :class="[exist.includes('Psychotrope')?'d-flex':'d-none']">
            <span class="type2 etat_T m-0 mr-1 border-0 psychotrope"></span> 
            <label class="m-0 mr-2">Psychotrope</label>
        </li>
        <li class="align-items-center d-none" :class="[exist.includes('Dangereux')?'d-flex':'d-none']">
            <span class="type2 etat_T m-0 mr-1 border-0 dangereux"></span> 
            <label class="m-0 mr-2">Dangereux</label>
        </li>
        <li class="d-flex align-items-center">
            <span class="type2 etat_T m-0 mr-1 border-0 autre"></span> 
            <label class="m-0 mr-2">Autre</label>
        </li-->
    </ul>
</template>

<script>
  import { EventBus } from '../../event-bus';
  export default {
      props: [],
       components: {
         
        },
        data() { 
          return {
              produitActif: [],
              dries: []
          }
        },
        methods: {

           getTypeProduit(produit){
              var result='';
                 switch(produit){
                  case 'DEAE': result = 'deae text-white'; break;
                  case 'Précurseur de drogue': result = 'precurseur_drogue text-white'; break;
                  case 'Psychotrope':  result = 'psychotrope text-white'; break;
                  case 'Dangereux': result = 'dangereux text-white'; break;
                  case 'Autre': result = 'autre text-white'; break;
                  default: result = 'badge-primary'; 
              }

              return result;
          },
          formatage(){
            for(var i=0; i<this.dries.length; i++){
               if(!this.produitActif.includes(this.dries[i].typeproduit) && (this.dries[i].typeproduit!="" && this.dries[i].typeproduit!=null)){
                this.produitActif.push(this.dries[i].typeproduit);
              }
            }
          }
          
        },
           
        mounted() {

            EventBus.$on('SET_PRODUIT_SPECIFIK', (event) => {
                this.dries =  event.prd;
                this.produitActif=[];
                this.formatage();
              
            });
        }
    }
</script> 
