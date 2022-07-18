<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">#</th>
                        <th class="p-2 border-right border-white h6">Nom</th>
                        <th class="p-2 border-right border-white h6">Telephone</th>
                        <th class="p-2 border-right border-white h6">Adresse</th>
                        <th class="p-2 border-right border-white h6">Logo</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody>
                <template v-if="!fournisseurs.data || !fournisseurs.data.length">
                        <tr><td colspan="6" class="bg-white text-center">Aucun fournisseur défini!</td></tr>
                    </template>
                      <tr v-for="fournisseur in fournisseurs.data" class="bg-white">
                        <td class="p-2 align-middle">
                            {{ fournisseur.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ fournisseur.nom }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ fournisseur.telephone }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ fournisseur.adresse }}
                        </td>
                        <td class="p-2 align-middle">
                          <template v-if="fournisseur.logo">
                              <img :src="'/images/logo/'+fournisseur.logo" style="height: 40px;">
                          </template>
                          <template v-else>
                             <img src="/images/logo/no-image.png" style="height: 40px;">
                          </template>
                          
                        </td>
                         <td class="p-2 text-right">
                             <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editFournisseur(fournisseur)" data-toggle="modal" data-target="#newFournisseur">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteFournisseur(fournisseur)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="fournisseurs"
                @pagination-change-page="getFournisseur"
            ></pagination>
        </div>
         <!-- Modal Fournisseurs-->
        <div class="modal fade" id="newFournisseur" tabindex="-1" role="dialog" aria-labelledby="myModalFournisseur"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Fournisseur</template>
                        <template v-else>Nouveau Fournisseur</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveFournisseur" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Nom
                                       </label>
                                        <input class="w-65 form-control" id="nom" v-model="fournisseurForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.fournisseurForm.nom.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Adresse
                                       </label>
                                        <input class="w-65 form-control" id="adresse" v-model="fournisseurForm.adresse"/>
                                    </div>
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="tele" class="d-block m-0 text-right w-35 pr-2" >Telephone</label>
                                            <input class="w-65 form-control"  v-model="fournisseurForm.telephone" type="text" id="tele"/>
                                        </div>
                                        </div>
                                    
                                    <div class="w-100 d-flex align-items-center my-2"></div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column">
                                       <div class="w-100 d-flex my-2">
                                            <div class="md-form w-100 d-flex justify-content-between">
                                            <label for="logo" class="d-block m-0 text-right w-35 pt-3 pr-2" >Logo</label>
                                            <div class="w-65 p-2">
                                            <input class=" form-control"  ref="file" v-on:change="handleFileUpload()" type="file" id="logo" :disabled = "hasImage"/>
                                             <template v-if="modeModify && hasImage">
                                                <img :src="'/images/logo/'+fournisseurForm.logo" class="mt-1" height="30" />
                                                <button class="btn p-0" v-on:click="removeImage()"><i aria-hidden="true" class="fa fa-close text-danger"></i></button>
                                            </template>
                                             </div>
                                            </div>

                                        </div>
                                        <div class="w-100 d-flex align-items-center my-2"></div>
                                    
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
                fournisseurForm :{
                    id: '',
                    nom: '',
                    adresse: '',
                    logo: '',
                    telephone: ''

                },
                hasImage: false,
                submitted: false,
                fournisseurs: {},
                paginate: 5,
                modeModify: false
            }

        },
        validations: {
            fournisseurForm : {
                nom: { required }
            },
        },
        watch: {
           paginate: function(){

                this.getFournisseur();
           }
        },
         methods : { 
            removeImage(){
                this.hasImage = false;
                this.fournisseurForm.logo = "";
            },
            handleFileUpload(){

                this.fournisseurForm.logo = this.$refs.file.files[0];  
            },
            saveFournisseur(){

                this.submitted = true;

                // stop here if form is invalid
                this.$v.fournisseurForm.$touch();
                if (this.$v.fournisseurForm.$invalid) {
                    return;
                }
                
               const data = new FormData();
                data.append('file', this.fournisseurForm.logo);
                data.append('nom', this.fournisseurForm.nom);
                data.append('adresse', this.fournisseurForm.adresse);
                data.append('telephone', this.fournisseurForm.telephone);

                let action = "createFournisseur";

                if(this.modeModify){
                    data.append('id', this.fournisseurForm.id);
                    if(this.hasImage){
                        data.append('imageSet', this.fournisseurForm.logo);
                    }
                    action = "modifyFournisseur";
                }

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getFournisseur();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Fournisseur enregistré avec succés!',
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
                this.fournisseurForm.nom = "";
                this.fournisseurForm.adresse = "";
                this.fournisseurForm.telephone= "";
                this.fournisseurForm.logo= "";
            },
            getFournisseur(page = 1){
                axios.get('/configuration/getFournisseur?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.fournisseurs = response.data;
                });
            },
            deleteFournisseur(fournisseur){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: fournisseur.nom,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteFournisseur/'+fournisseur.id+"?logo="+fournisseur.logo).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Fournisseur supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getFournisseur();


                        });
                  
                  }
                })
            },
            editFournisseur(fournisseur){

                this.modeModify = true;
                this.submitted = false;
                this.fournisseurForm.id = fournisseur.id;
                this.fournisseurForm.nom = fournisseur.nom;
                this.fournisseurForm.adresse = fournisseur.adresse;
                this.fournisseurForm.telephone= fournisseur.telephone;
                this.fournisseurForm.logo= fournisseur.logo;
                if(fournisseur.logo){
                    this.hasImage = true;
                }else{
                    this.hasImage = false;
                }
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {
         this.getFournisseur();
        }
    }
</script>