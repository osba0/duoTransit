<template>
    <div>
        <div class="card-body table-responsive p-0">
            <form @submit.prevent="importFile" enctype="multipart/form-data" key=1>
                <div class="d-flex align-items-center w-50 mb-3">
                    <input type="file" id="file" name="file" ref="file" v-on:change="handleFileUpload()"  class="form-control mr-3">
                    <button class="btn btn-success">Importer</button>

                </div>
                
            </form>

            <div class="d-flex justify-content-between align-content-center mb-2">
            <div class="d-flex align-items-end">
                <div>
                    <div class="d-flex align-items-center">
                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                            >Nbre de ligne par Page</label> 
                        <select
                            v-model="paginate"
                            class="form-control form-control-sm">
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="150">150</option>
                        </select>
                    </div>
                </div>
        
            </div>
            <div class="d-flex align-items-end">
                <div>
                    <div class="d-flex align-items-center">
                       
                    </div>
                </div>
        
            </div>
            <div class="pr-0 col-md-4">
               
            </div>
        </div>

            <table class="table">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">#</th>
                        <th class="p-2 border-right border-white h6">Type Commande</th>
                        <th class="p-2 border-right border-white h6">Fournisseur</th>
                        <th class="p-2 border-right border-white h6">Commandes</th>
                        <th class="p-2 border-right border-white h6">Date Transmission</th>
                        <th class="p-2 border-right border-white h6">Client</th>
                        <th class="p-2 border-right border-white h6">Agent</th>
                        <th class="p-2 border-right border-white h6">Etat</th>
                        <th class="p-2 border-right border-white h6">Date ajout</th>
                        
                        <th class="text-right  p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody class="bgStripe">
                <template v-if="!commandes.data || !commandes.data.length">
                        <tr><td colspan="9" class="bg-white text-center">Aucune commande importée!</td></tr>
                    </template>
                      <tr v-for="cmd in commandes.data" class="bg-white">
                        <td class="p-2 align-middle">
                            {{ cmd.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ cmd.type_commande }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ cmd.fournisseur }}
                        </td>
                       <td class="p-2 align-middle">
                            {{ cmd.commandes }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ cmd.date_transmission }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ cmd.client }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ cmd.user_import }}
                        </td>
                        <td class="p-2 align-middle">
                            <label class="badge badge-warning" v-if="cmd.etat_cmd==0">En cours</label>
                            <label class="badge badge-success" v-if="cmd.etat_cmd==1">Réceptionné</label>
                        </td>
                         <td class="p-2 align-middle">
                            {{ cmd.created_at }}
                        </td>

                        <td class="text-right">
                            <button @click="deleteImportCmd(cmd)" class="btn btn-light rounded-circle text-danger"><i class="fa fa-trash" style="font-size:22px"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="commandes"
                @pagination-change-page="getCommande"
            ></pagination>
        </div>
         <!-- Modal Contenaire-->
        <div class="modal fade" id="newContenaire" tabindex="-1" role="dialog" aria-labelledby="myModalContenaire"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Contenaire</template>
                        <template v-else>Nouveau Contenaire</template>
                        
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
        props: [
          'slugClient',
        ],
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
                commandes: {},
                paginate: 20,
                modeModify: false,
                fileExcel: null
            }

        },
        validations: {
            contenaireForm : {
                nom: { required }
            },
        },
        watch: {
           paginate: function(){

                this.getCommande();
           }
        },
         methods : { 
            removeImage(){
                this.hasImage = false;
                this.contenaireForm.logo = "";
            },
            handleFileUpload(){

                this.fileExcel = this.$refs.file.files[0];  
            },
            importFile(){

                /*this.submitted = true;

                // stop here if form is invalid
                this.$v.contenaireForm.$touch();
                if (this.$v.contenaireForm.$invalid) {
                    return;
                }*/
                
               const data = new FormData();
                data.append('file', this.fileExcel);
                data.append('slug', this.slugClient);

                axios.post("/import", data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                         this.getCommande();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Importé avec succés!',
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
            getCommande(page = 1){
                axios.get('/listcmdimported?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.commandes = response.data;
                });
            },
            deleteImportCmd(cmd){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: cmd.commandes,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/import/delete/'+cmd.id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Commande supprimé avec succés.',
                              'success'
                            );
                             this.getCommande();


                        });
                  
                  }
                })
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {
         this.getCommande();
        }
    }
</script>
