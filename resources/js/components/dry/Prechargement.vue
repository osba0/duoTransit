<template>
	<div>
		<div class="card-body table-responsive p-0">
            <table class="table table-striped table-bordered">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">N° Dossier</th>  
                        <th class="p-2 border-right border-white h6">Type Commande</th>
                        <th class="p-2 border-right border-white h6">Colis Empoté</th>
                        <th class="p-2 border-right border-white h6">Poids Empoté</th>
                        <th class="p-2 border-right border-white h6">Volume Empoté</th>
                        <th class="p-2 border-right border-white h6">Date debut</th>
                        <th class="p-2 border-right border-white h6">Date cloture</th>
                        <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody>
                 <template v-if="!chargement.data || !chargement.data.length">    
                     <tr class="bg-white">
                       <td colspan="9" class="text-center">Aucune donnée!</td>
                    </tr>
                    </template>
                    <tr v-for="char in chargement.data" :key="chargement.numDossier">
                        <td class="p-2 align-middle">
                            {{ char.numDossier }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ char.typeCommande }}
                        </td>
                         <td class="p-2 align-middle">
                         {{ char.colisEmpote }}
                        </td>
                        <td class="p-2 align-middle">
                         {{ char.volumeEmpote }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ char.poidEmpote }}
                        </td> 
                          
                        <td class="p-2 align-middle">
                            <i class="fa fa-calendar" aria-hidden="true"></i> {{ char.dateDebut }}
                        </td>
                        <td class="p-2 align-middle">
                            <i class="fa fa-calendar" aria-hidden="true"></i> {{ char.dateCloture }}
                        </td> 
                         <td class="p-2 align-middle">
                            <i class="fa fa-user" aria-hidden="true"></i> {{ char.user }}
                        </td>
                         <td class="p-2 text-right">
                           
                            <a title="Editer" class="btn m-1 btn-circle border btn-circle-sm" :href="'/prechargement/'+idClient+'/'+char.numDossier+'/'+char.typeCommandeID">
                               <i class="fa fa-pencil" aria-hidden="true"></i> 
                            </a>
                          
                          
                            <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteChargement(char)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                            </a>
                        </td>
                       
                    </tr> 
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="chargement"
                @pagination-change-page="getChargement"
            ></pagination>
        </div>
         <!-- Modal Chargement-->
        <div class="modal fade" id="newChargement" tabindex="-1" role="dialog" aria-labelledby="myModalChargement"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Chargement | Nouveau Dossier</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePopupDoss">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="createDossier" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-12 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="numDoss"  class="d-block m-0 text-right  pr-2" style='white-space: nowrap;'>
                                        N° dossier
                                       </label>
                                        <input class="form-control" id="numDoss" v-model="initChargement.numDossier" 
                                        :class="{ 'border-danger': submitted && !$v.initChargement.numDossier.required }" />
                                    </div>
                                    
                                    <div class="w-100 d-flex align-items-center my-2"></div>
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                    <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                        <label for="dateDeb" class="d-block m-0 text-right w-35 pr-2" >Date début</label>
                                        <input class="w-65 form-control"  v-model="initChargement.dateDebut" type="date" id="dateDeb" 
                                        :class="{ 'border-danger': submitted && !$v.initChargement.dateDebut.required }"/>
                                    </div>
                                </div>
                                    
                                    <div class="w-100 d-flex align-items-center my-2"></div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column align-items-center">
                                       <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="dateClo" class="d-block m-0 text-right w-35 pr-2" >Date cloture</label>
                                            <input class="w-65 form-control"  v-model="initChargement.dateCloture" type="date" id="dateClo" 
                                            :class="{ 'border-danger': submitted && !$v.initChargement.dateCloture.required }"/>
                                        </div>
                                        </div>
                                        <div class="w-100 d-flex align-items-center my-2"></div>
                                    
                                 </div>
                             </div>
                             <div class="modal-footer d-flex justify-content-center"> 
                                <button type="submit" class="btn btn-success">Enregister</button>
                                <button type="button" v-on:click="closeModalDossier()" class="btn btn-warning">Annuler</button>
                            </div>
                         </form>
                    </div>
                    
             </div>
            
          </div>
        </div>
	</div>
</template>
<script>
    import { required, minLength, between } from 'vuelidate/lib/validators'
	export default {
        props: [
          'idClient',
          'listFournisseurs' 
        ],
        data() { 
            return {
                initChargement :{
                    numDossier: '',
                    dateDebut: null,
                    dateCloture: null

                },
                submitted: false,
                chargement: {},
                paginate: 5,
            }

    	},
        validations: {

            initChargement: {
                numDossier: { required },
                dateDebut:  { required },
                dateCloture:{ required }
            },
        },
         watch: {

           paginate: function(){

                this.getChargement();
           }
        },
        methods : { 
            getChargement(page = 1){
                axios.get('/getPreChargmement?page=' + page + "&paginate=" + this.paginate).then(response => {
                    console.log(response);
                    this.chargement = response.data;
                });
            },

            closeModalDossier(){
                this.$refs.closePopupDoss.click();
            },

            createDossier(){
                this.submitted = true;
                // stop here if form is invalid
                this.$v.initChargement.$touch();
                if (this.$v.initChargement.$invalid) {
                    return;
                }
                axios.post('/chargement/save', {
                    'numdossier': this.initChargement.numDossier,
                    'datedebut' : this.initChargement.dateDebut,
                    'datecloture' : this.initChargement.dateCloture, 
                    'user'         : 1,
                    'clientID'     : this.idClient
                    }).then(response => {
                        console.log('Rep', response.data.code);
                        if(response.data.code==0){

                            this.$refs.closePopupDoss.click();
                            Vue.swal.fire(
                              'Success',
                              'Dossier crée avec succés',
                              'success'
                            );
                            this.getChargement();

                        }else{
                            this.show = true;
                            this.$toast.warning(response.data.message);
                        }
                        this.submitted = false;
                       
                    });

            }
        },
        mounted() {
            this.getChargement();
        }
    }
</script>