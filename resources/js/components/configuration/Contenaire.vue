<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">#</th>
                        <th class="p-2 border-right border-white h6">Nom</th>
                        <th class="p-2 border-right border-white h6">Capacité</th>
                        <th class="p-2 border-right border-white h6">Affichage par défaut</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody class="bgStripe">
                <template v-if="!contenaires.data || !contenaires.data.length">
                        <tr><td colspan="6" class="bg-white text-center">Aucun contenaire défini!</td></tr>
                    </template>
                      <tr v-for="contenaire in contenaires.data" class="bg-white">
                        <td class="p-2 align-middle">
                            {{ contenaire.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ contenaire.nom }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ contenaire.volume }} m<sup>3</sup>
                        </td>
                        <td class="p-2 align-middle">
                            <span v-if="contenaire.isdefault==1" class="badge badge-success">Oui</span>
                            <span v-if="contenaire.isdefault==0" class="badge badge-danger">Non</span>
                        </td>

                         <td class="p-2 text-right">
                             <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editContenaire(contenaire)" data-toggle="modal" data-target="#newContenaire">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteContenaire(contenaire)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="contenaires"
                @pagination-change-page="getContenaire"
            ></pagination>
        </div>
         <!-- Modal Conteneur-->
        <div class="modal fade" id="newContenaire" tabindex="-1" role="dialog" aria-labelledby="myModalContenaire"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Conteneur</template>
                        <template v-else>Nouveau Conteneur</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveContenaire" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Nom
                                       </label>
                                        <input class="w-65 form-control" id="nom" v-model="contenaireForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.contenaireForm.nom.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Capacité
                                       </label>
                                        <input class="w-65 form-control" id="capacite" v-model="contenaireForm.capacite"/>
                                    </div>
                                 </div>
                                
                             </div>
                              <div class="row">
                                    <div class="col-12 my-2 d-flex flex-column align-items-center">
                                        <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex align-items-center">
                                                <span for="tele" class="d-block m-0 text-right w-35 pr-2" >Choisir par défaut</span>
                                                <div>
                                                    <input v-model="contenaireForm.isdefault" type="radio"  name="isdefault" value="1"  id="oui"/>
                                                    <label class="m-0 mr-3"  for="oui">Oui</label>
                                                    <input v-model="contenaireForm.isdefault" type="radio" name="isdefault" checked value="0" id="non"/>
                                                    <label class="m-0" for="non">Non</label>
                                                </div>
                                            
                                            </div>
                                        </div>
                                 </div>
                              
                             </div>
                             <div class="modal-footer d-flex justify-content-center"> 

                                <template v-if="modeModify">
                                        <button type="submit" class="btn btn-success">Modifier</button>
                                        <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler la modification</button>
                                </template>
                                <template v-else>
                                    <button type="submit" class="btn btn-success">Enregister</button>
                                    <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler</button>
                                </template>
                               
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
        data() { 
            return {
                contenaireForm :{
                    id: '',
                    nom: '',
                    capacite: '',
                    isdefault: 0

                },
                hasImage: false,
                submitted: false,
                contenaires: {},
                paginate: 5,
                modeModify: false
            }

        },
        validations: {
            contenaireForm : {
                nom: { required }
            },
        },
        watch: {
           paginate: function(){

                this.getContenaire();
           }
        },
         methods : { 
            removeImage(){
                this.hasImage = false;
                this.contenaireForm.logo = "";
            },
            handleFileUpload(){

                this.contenaireForm.logo = this.$refs.file.files[0];  
            },
            saveContenaire(){

                this.submitted = true;

                // stop here if form is invalid
                this.$v.contenaireForm.$touch();
                if (this.$v.contenaireForm.$invalid) {
                    return;
                }
                
               const data = new FormData();
                data.append('nom', this.contenaireForm.nom);
                data.append('capacite', this.contenaireForm.capacite);
                data.append('isdefault', this.contenaireForm.isdefault);

                let action = "createContenaire";

                if(this.modeModify){
                    data.append('id', this.contenaireForm.id);
                    action = "modifyContenaire";
                }

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getContenaire();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Conteneur enregistré avec succés!',
                          'success'
                        )
                        

                    }else{
                         Vue.swal.fire(
                          'error!',
                          response.data.message,
                          'error'
                        )
                    }
                    this.submitted = false;
                   
                });
            },
            flushData(){
                this.contenaireForm.nom = "";
                this.contenaireForm.capacite = "";
                this.contenaireForm.isdefault= 0;
                
            },
            getContenaire(page = 1){
                axios.get('/configuration/getContenaires?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.contenaires = response.data;
                });
            },
            deleteContenaire(contenaire){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: contenaire.nom,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteContenaire/'+contenaire.id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Conteneur supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getContenaire();


                        });
                  
                  }
                })
            },
            editContenaire(contenaire){

                this.modeModify = true;
                this.submitted = false;
                this.contenaireForm.id = contenaire.id;
                this.contenaireForm.nom = contenaire.nom;
                this.contenaireForm.capacite = contenaire.volume;
                this.contenaireForm.isdefault= contenaire.isdefault;
    
               
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {
         this.getContenaire();
        }
    }
</script>
