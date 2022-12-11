<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue" :class="[isLoading ? '' : 'hasborder']">
                     <tr>
                        <th class="p-2 border-right border-white h6">#</th>
                        <th class="p-2 border-right border-white h6">Nom</th>
                        <th class="p-2 border-right border-white h6">Telephone</th>
                        <th class="p-2 border-right border-white h6">Email</th>
                        <th class="p-2 border-right border-white h6">Adresse</th>
                        <th class="p-2 border-right border-white h6">Logo</th>
                        <th class="p-2 border-right border-white h6">Société</th>
                        <th class="p-2 border-right border-white h6">Etat</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody  class="bgStripe" :class="[isLoading ? 'loader-line' : '']">
                <template v-if="!fournisseurs.data || !fournisseurs.data.length">
                        <tr><td colspan="8" class="bg-white text-center" v-if="checking">Aucun fournisseur défini!</td></tr>
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
                            {{ fournisseur.email }}
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
                        <td> {{slugClient}}--{{listClient}}
                            <template v-if="slugClient!=''">
                                <template v-for="client in listClient">
                                     <template v-if="client.clfocl.includes(fournisseur.id) && slugClient==client.slug">
                                        <a class="badge badge-success text-white position-relative mr-2 p-2 mb-2 cursor-pointer">
                                        {{client.clnmcl}}
                                        </a>  
                                    </template>
                                </template>
                            </template>
                            <template v-else>
                                <template v-for="client in listClient">
                                    <template v-if="client.clfocl.includes(fournisseur.id)">
                                        <a title="Retirer la société" v-on:click="retirerSocieteFour(client, fournisseur)" class="badge badge-success text-white position-relative mr-2 p-2 mb-2 cursor-pointer">
                                        {{client.clnmcl}}
                                        <span class="badge badge-danger position-icone position-absolute"><i class="fa fa-minus"></i></span>
                                       
                                        </a>  
                                    </template>
                                    <template v-else>
                                        <a title="Ajouter la société" class="badge badge-secondary text-white position-relative mr-2 p-2 mb-2 cursor-pointer" v-on:click="addSocieteFour(client, fournisseur)">
                                        {{client.clnmcl}}
                                         <span class="badge badge-success position-icone position-absolute"><i class="fa fa-plus"></i></span>
                                        </a>
                                    </template>
                                </template>
                            </template>          
                        </td>
                         <td>  
                            <label class="switch">
                                <input class="switch-input inputCmd" :checked="fournisseur.etat==1" type="checkbox" :value="fournisseur.id" v-on:change="etat($event,fournisseur)" /> 
                                <span class="switch-label" data-on="Actif" data-off="Inactif"></span> 
                                <span class="switch-handle"></span> 
                            </label>
                        </td>
                         <td class="p-2 text-right white-space-nowrap">
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
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="tele" class="d-block m-0 text-right w-35 pr-2" >Telephone</label>
                                            <input class="w-65 form-control"  v-model="fournisseurForm.telephone" type="text" id="tele"/>
                                        </div>
                                        </div>
                                    
                                    <div class="w-100 d-flex align-items-center my-2"></div>
                                    
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                         <label for="email"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Email
                                       </label>
                                        <input class="w-65 form-control" id="email" v-model="fournisseurForm.email"/>
                                    </div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column">
                                        <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Adresse
                                       </label>
                                        <input class="w-65 form-control" id="adresse" v-model="fournisseurForm.adresse"/>
                                    </div>
                                    
                                 </div>
                             </div>

                              <div class="row">
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
                            
                            <div class="row d-none">
                                <div class="col-12 my-2 d-flex flex-column">
                                    <div class="w-100 d-flex justify-content-between flex-column align-items-center">
                                        <label class="typo__label d-block m-0 w-100  pr-2 nowrap">Société</label>
                                        <div class="w-100 p-0">
                                            <multiselect v-model="fournisseurForm.client" :options="listClient" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="clnmcl" track-by="id" :preselect-first="false">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} société(s) selectionné(s)</span> 
                                                </template>
                                            </multiselect> 
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
    import { required, minLength, between } from 'vuelidate/lib/validators';
    import Multiselect from 'vue-multiselect';
    export default {
         props: [
            'listClient',
            'idEntite',
            'slugClient'
        ],
         components: {
            Multiselect
          },
        data() { 
           
            return {
                fournisseurForm :{
                    id: '',
                    nom: '',
                    adresse: '',
                    logo: '',
                    telephone: '',
                    client: '',
                    idClients:'',
                    email: ''

                },
                hasImage: false,
                submitted: false,
                fournisseurs: {},
                paginate: 10,
                modeModify: false,
                isLoading: true,
                checking: false,
            }

        },
        validations: {
            fournisseurForm : {
                nom: { required }
               // email: { email }
            },
        },
        watch: {
           paginate: function(){

                this.getFournisseur();
           }
        },
         methods : { 
             etat(event, four){

                var ischecked=0;
                if (event.target.checked) {
                    ischecked=1;
                }

                const data = new FormData();
                data.append('id', four.id);
                data.append('etat', ischecked);

                axios.post("/configuration/etatFournisseur", data).then(response => {
                let res = response.data.result;
                this.getFournisseur();
                });
            },
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
                data.append('email', this.fournisseurForm.email);


                this.fournisseurForm.idClients = [];

                // get clients
                for(var i=0; i<this.fournisseurForm.client.length; i++){
                    var item = this.fournisseurForm.client[i];
                    this.fournisseurForm.idClients.push(item.id);  
                }

                data.append('listClientAjouter', JSON.stringify(this.fournisseurForm.idClients));

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
                        this.flushData();
                        this.getFournisseur();
                        Vue.swal.fire(
                          'succés!',
                          'Fournisseur enregistré avec succés!',
                          'success'
                        ).then((result) => {
                            this.getFournisseur();
                           //window.location.reload();
                        });
                        
                        
                        
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
                this.fournisseurForm.email = "";
                this.fournisseurForm.telephone= "";
                this.fournisseurForm.logo= "";
                this.fournisseurForm.client= "";
                this.fournisseurForm.idClients= "";
            },
            getFournisseur(page = 1){
                 this.isLoading=true;
                axios.get('/configuration/getFournisseur?slug='+this.slugClient+'&page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.fournisseurs = response.data;
                     if(this.fournisseurs.length > 0){
                           
                        }else{
                             this.checking=true;
                        }
                     this.isLoading=false;
                });
            },
            addSocieteFour(client, fournisseur){
                Vue.swal.fire({
                  title: fournisseur.nom,
                  text: "Confirmer l'ajout de "+client.clnmcl,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#38c172',
                  cancelButtonColor: '#545b62',
                  confirmButtonText: 'Oui, ajouter!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.post('/configuration/clientFournisseur/'+fournisseur.id+"/"+client.id).then(response => {
                            if(response.data.code==0){
                                this.modeModify = false;
                                Vue.swal.fire(
                                  'Ajouté!',
                                  'Société ajouté avec succés', 
                                  'success'
                                ).then((result) => {
                                    window.location.reload();
                                });
                            }else{
                                 Vue.swal.fire(
                                  'Warning!',
                                  'Erreur',
                                  'warning'
                                ).then((result) => {
                                   
                                });
                            }
                            
                        });
                  
                  }
                });
            },
            retirerSocieteFour(client, fournisseur){
                Vue.swal.fire({
                  title: fournisseur.nom,
                  text: "Confirmer le retrait de "+client.clnmcl,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#38c172',
                  cancelButtonColor: '#545b62',
                  confirmButtonText: 'Oui, retirer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.post('/configuration/retirerclientFournisseur/'+fournisseur.id+"/"+client.id).then(response => {
                            if(response.data.code==0){
                                this.modeModify = false;
                                Vue.swal.fire(
                                  'Retiré!',
                                  'Société retiré avec succés', 
                                  'success'
                                ).then((result) => {
                                    window.location.reload();
                                });
                            }else{
                                 Vue.swal.fire(
                                  'Warning!',
                                  'Erreur',
                                  'warning'
                                ).then((result) => {
                                   
                                });
                            }
                            
                        });
                  
                  }
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
                            if(response.data.code==0){
                                this.modeModify = false;
                                this.getFournisseur();
                                Vue.swal.fire(
                                  'Supprimé!',
                                  'Fournisseur supprimé avec succés', 
                                  'success'
                                ).then((result) => {
                                     window.location.reload();
                                });
                            }else{
                                 Vue.swal.fire(
                                  'Warning!',
                                  'Impossible de supprimer. Ce fournisseur a des commandes enregistrées.',
                                  'warning'
                                ).then((result) => {
                                   
                                });
                            }
                            
                             
                           


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
                this.fournisseurForm.email= fournisseur.email;

                if(fournisseur.logo){
                    this.hasImage = true;
                }else{
                    this.hasImage = false;
                }


                // value selected client
                this.fournisseurForm.client=[];
                var selected = [];
                for(var i=0; i<this.listClient.length;i++){
                    var foauth = this.listClient[i].clfocl;
                    if(foauth.includes(fournisseur.id)){
                        selected.push(this.listClient[i]);
                    }
                }
                this.fournisseurForm.client = selected;
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>