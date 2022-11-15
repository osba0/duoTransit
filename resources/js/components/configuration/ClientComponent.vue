<template>
    <div>
        <div class="d-flex justify-content-between mb-3">
            <h2>Liste des sociétés</h2>
            <a href="#" class="text-white h2 btn btn-primary font-weight-bold" data-toggle="modal" data-target="#newClient" >
                <i class="fa fa-plus" aria-hidden="true"></i> Nouvelle société
            </a>
        </div>
        <div class="card-body table-responsive p-0">
            <PageLoader :is-loading = isLoading ></PageLoader>  
            <table class="table table-bordered">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">#</th>
                        <th class="p-2 border-right border-white h6">Nom</th>
                        <th class="p-2 border-right border-white h6">Email</th>
                        <th class="p-2 border-right border-white h6">Telephone</th>
                        <th class="p-2 border-right border-white h6">Adresse</th>
                        <th class="p-2 border-right border-white h6">Pays</th>
                        <th class="p-2 border-right border-white h6">Transitaire</th>
                        <th class="p-2 border-right border-white h6" width="20%">Fournisseurs</th>
                        <th class="p-2 border-right border-white h6">Type Commmande</th>
                        <th class="p-2 border-right border-white h6 text-center">Logo</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody class="bgStripe">
                    <template v-if="!clients.data || !clients.data.length">    
                         <tr>
                           <td colspan="10" class="text-center">Aucune société trouvée!</td>
                        </tr>
                    </template>
                      <tr v-for="client in clients.data">  
                        <td class="p-2 align-middle">
                            {{ client.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ client.nom }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ client.email }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ client.telephone }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ client.adresse }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ client.pays }}
                        </td>
                        <td class="p-2 align-middle">   

                            <span class="badge bg-primary mr-2 p-2 mb-2" v-for="value in client.clenti">
                                <template v-for="transitaire in listTransitaire">
                                    <template v-if="value == transitaire.id">
                                        {{transitaire.nom}}
                                    </template>
                                </template>
                           
                            </span>            
                           
                        </td>
                        <td class="p-2 align-middle">   

                            <span class="badge bg-success mr-2 p-2 mb-2" v-for="value in client.fournisseurs">
                                <template v-for="fournisseur in listFournisseurs">
                                    <template v-if="value == fournisseur.id">
                                        {{fournisseur.fonmfo}}
                                    </template>
                                </template>
                           
                            </span>            
                           
                        </td>
                         <td class="p-2 align-middle">
                          <template v-for="value in client.typeCmd">
                                <template v-for="type in listTypeCmds">
                                    <template v-if="value == type.id">
                                        <span class="badge mr-2 p-2 text-white mb-2"  v-bind:style="{ background: type.tcolor }">
                                        {{type.typcmd}}
                                        </span> 
                                    </template>
                                </template>
                           
                            </template> 
                        </td>
                        <td class="p-2 align-middle text-center">
                          <template v-if="client.logo">
                              <img :src="'/images/logo/'+client.logo" style="height: 40px;">
                          </template>
                          <template v-else>
                             <img src="/images/logo/no-image.png" style="height: 40px;">
                          </template>
                          
                        </td>
                         <td class="p-2 align-middle">
                            <div class="d-flex justify-content-end align-items-center">
                                <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editClient(client)" data-toggle="modal" data-target="#newClient">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteClient(client)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                            </div>
                             
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="clients"
                @pagination-change-page="getClient"
            ></pagination>
        </div>
         <!-- Modal Clients-->
        <div class="modal fade" id="newClient" tabindex="-1" role="dialog" aria-labelledby="myModalClient"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Société</template>
                        <template v-else>Nouveau Société</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveClient" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <div v-if="this.submitted && $v.clientForm.value.$error" class="text-center">
                                      <span v-if="clientForm.value" class="badge bg-danger mr-2">Choisir minimum 1 fournisseur</span>
                                    </div> 
                                     <div v-if="this.submitted && $v.clientForm.valueTypeCmd.$error" class="text-center">
                                      <span v-if="clientForm.valueTypeCmd" class="badge bg-danger mr-2">Choisir minimum 1 type de commande</span>
                                    </div> 
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Nom
                                       </label>
                                        <input class="w-65 form-control" id="nom" v-model="clientForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.clientForm.nom.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Adresse
                                       </label>
                                        <input class="w-65 form-control" id="adresse" v-model="clientForm.adresse"/>
                                    </div>
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="tele" class="d-block m-0 text-right w-35 pr-2" >Telephone</label>
                                            <input class="w-65 form-control"  v-model="clientForm.telephone" type="text" id="tele"/>
                                        </div>
                                        </div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                                <label for="email" class="d-block m-0 text-right w-35 pr-2" >Email</label>
                                                <input class="w-65 form-control"  v-model="clientForm.email" type="text" id="email"/>
                                            </div>
                                    </div>
                                    
                                 </div>
                               
                             </div>
                             <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                   <div class="w-100 my-2 d-flex flex-column">
                                        <div class="w-100 d-flex justify-content-between align-items-center">
                                            <label class="typo__label pt-0 d-block m-0 text-right w-35  pr-2 nowrap">Pays</label>
                                            <div class="w-65 p-0">
                                                   <select id="pays" v-model="clientForm.pays" class="w-100 form-control" :class="{ 'border-danger': submitted && !$v.clientForm.pays.required }" >
                                                       <option value="">Choisir Pays</option>
                                                       <option value="Senegal">Senegal</option>
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 my-2 d-flex flex-column align-items-center"></div>  
                             </div>
                            <div class="row">
                                  <div class="col-6 my-2 d-flex flex-column">
                                       <div class="w-100 d-flex my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="logo" class="d-block m-0 text-right w-35 pr-2" >Logo</label>
                                            <div class="w-65 p-0">
                                            <input class="form-control"  :key="fileInputKey" ref="file" v-on:change="handleFileUpload()" type="file" id="logo" :disabled = "hasImage"/>
                                           
                                             </div>
                                            </div>

                                        </div>
                                        <div class="w-100 d-flex align-items-center my-2"></div>
                                    
                                 </div>
                                 <div class="col-6  d-flex justify-content-start">
                                       <template v-if="modeModify && hasImage">
                                            <img :src="'/images/logo/'+clientForm.logo" class="mt-1 rounded-lg" height="80" />
                                            <button class="ml-3 btn p-0 text-danger" v-on:click="removeImage()">
                                                <i aria-hidden="true" class="fa fa-close text-danger"></i> Supprimer le logo</button>
                                        </template>
                                        <template v-if="clientForm.urlPreview != ''">
                                        <img height="80" :src="clientForm.urlPreview" >
                                         <button class="ml-3 btn p-0 text-danger" v-on:click="removeImagePreview()">
                                                <i aria-hidden="true" class="fa fa-close text-danger"></i> Supprimer le logo</button>
                                        </template>
                                 </div>
                            </div>
                            <hr>
                             <div class="row">  
                                 <div class="col-12 my-2 d-flex flex-column">
                                    <div class="w-100 d-flex justify-content-between flex-column align-items-center">
                                        <label class="typo__label d-block m-0 w-100  pr-2 nowrap">Transitaire</label> 
                                        <div class="w-100 p-0">
                                            <multiselect v-model="clientForm.entite" :options="listTransitaire" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="nom" track-by="id" :preselect-first="false" :class="{ 'border-danger': submitted && !$v.clientForm.entite.required }">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} transitaire(s) selectionné(s)</span> 
                                                </template>
                                            </multiselect>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 my-2 d-flex flex-column">
                                    <div class="w-100 d-flex justify-content-between flex-column align-items-center">
                                        <label class="typo__label d-block m-0 w-100  pr-2 nowrap">Fournisseur</label>
                                        <div class="w-100 p-0">
                                            <multiselect v-model="clientForm.value" :options="listFournisseurs" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="fonmfo" track-by="id" :preselect-first="false"  :class="{ 'border-danger': submitted && !$v.clientForm.value.required }">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} fournisseur(s) selectionné(s)</span> 
                                                </template>
                                            </multiselect>
                                        </div>
                                    </div>
                                </div>
                             </div>
                             <div class="row">  
                                 <div class="col-12 my-2 d-flex flex-column">
                                    <div class="w-100 d-flex justify-content-between flex-column align-items-center">
                                        <label class="typo__label d-block m-0 w-100  pr-2 nowrap">Type Commande</label>
                                        <div class="w-100 p-0">
                                            <multiselect v-model="clientForm.valueTypeCmd" :options="listTypeCmds" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="typcmd" track-by="id" :preselect-first="false" :class="{ 'border-danger': submitted && !$v.clientForm.valueTypeCmd.required }">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Type Commande(s) selectionné(s)</span> 
                                                </template>
                                            </multiselect>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                             <div class="modal-footer d-flex justify-content-center mt-4"> 

                                <template v-if="modeModify">
                                        <button type="submit" class="btn btn-success">Modifier</button>
                                        <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler la modification</button>
                                </template>
                                <template v-else>
                                    <button type="submit" class="btn btn-success" :disabled="loading?true:false">
                                        <template v-if="loading">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </template>
                                        Enregister
                                    </button>
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
    import PageLoader from '../../components/PageLoader.vue';     

    export default {
        props: [
            "listTypeCmds",
            "listFournisseurs",
            "listTransitaire"
        ],
         components: {
            Multiselect,
            PageLoader
          },
        data() { 
            return {
                clientForm :{
                    id: '',
                    nom: '',
                    email: '',
                    adresse: '',
                    logo: '',
                    telephone: '',
                    idFournisseurs: [],
                    idTypeCmd: [],  
                    pays: '',
                    value: [],
                    valueTypeCmd: [],
                    entite: [],
                    idEntites: [],  
                    urlPreview:''
                },
                hasImage: false,
                submitted: false,
                clients: {},
                paginate: window.pagination,
                modeModify: false,
                isLoading: true,
                fileInputKey: 0,
                loading: false
            }

        },
        validations: {
            clientForm : {
                nom:  { required },
                pays: { required },
                value:{ required },
                valueTypeCmd: { required },
                entite: { required },
                pays: { required }
            },
            
        },
         watch: {
           paginate: function(){

                this.getClient();
           }
        },
         methods : { 
            removeImage(){
                this.hasImage = false;
                this.clientForm.logo = "";
            },
            removeImagePreview(){
                 this.clientForm.logo = "";
                 this.clientForm.urlPreview = "";
                 this.fileInputKey++;
            },
            handleFileUpload(){

                this.clientForm.logo = this.$refs.file.files[0];
                this.clientForm.urlPreview = URL.createObjectURL(this.$refs.file.files[0]);
               
            },
            saveClient(){
                this.submitted = true;
                // stop here if form is invalid
                this.$v.clientForm.$touch();
                if (this.$v.clientForm.$invalid) {
                    return;
                }
                
                const data = new FormData();
                data.append('file', this.clientForm.logo);
                data.append('nom', this.clientForm.nom);
                data.append('email', this.clientForm.email); 
                data.append('adresse', this.clientForm.adresse);
                data.append('telephone', this.clientForm.telephone);
                data.append('pays', this.clientForm.pays);
                
                let action = "createClient"; 

                if(this.modeModify){
                    data.append('id', this.clientForm.id);
                    if(this.hasImage){
                        data.append('imageSet', this.clientForm.logo);
                    }
                    action = "modifyClient";
                }

                this.clientForm.idFournisseurs = [];
                this.clientForm.idTypeCmd = [];
                this.clientForm.idEntites = [];

                // get fournisseurs
                for(var i=0; i<this.clientForm.value.length; i++){
                    var item = this.clientForm.value[i];
                    this.clientForm.idFournisseurs.push(item.id);  
                }
                // get type Cmd 
                for(var i=0; i<this.clientForm.valueTypeCmd.length; i++){
                    var item = this.clientForm.valueTypeCmd[i];
                    this.clientForm.idTypeCmd.push(item.id);  
                }

                // get transitaire
                for(var i=0; i<this.clientForm.entite.length; i++){
                    var item = this.clientForm.entite[i];
                    this.clientForm.idEntites.push(item.id);  
                }
             
                data.append('fournisseurs', JSON.stringify(this.clientForm.idFournisseurs));
                data.append('typecmd', JSON.stringify(this.clientForm.idTypeCmd));
                data.append('entite', JSON.stringify(this.clientForm.idEntites));

                this.loading=true;

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getClient();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Client enregistré avec succés!',
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
                    this.loading = false;
                    this.removeImagePreview();
                   
                });
            }, 
            flushData(){
                this.clientForm.id = '',
                this.clientForm.nom = '';
                this.clientForm.email = '';
                this.clientForm.adresse = '';
                this.clientForm.logo = '';
                this.clientForm.telephone = '';
                this.clientForm.idFournisseurs = [];
                this.clientForm.pays = '',
                this.clientForm.value = [];
                this.clientForm.valueTypeCmd = []; 
            },
            getClient(page = 1){
                this.isLoading=true;
                axios.get('/configuration/getClient?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.clients = response.data;
                    this.closeLoader();
                });
            },
            deleteClient(client){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: client.nom,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteClient/'+client.id+"?logo="+client.logo).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Client supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getClient();


                        });
                  
                  }
                })
            },
            editClient(client){

                this.modeModify = true;
                this.submitted = false;
                this.clientForm.id = client.id;
                this.clientForm.nom = client.nom;
                this.clientForm.adresse = client.adresse;
                this.clientForm.telephone= client.telephone;
                this.clientForm.logo= client.logo;
                this.clientForm.email= client.email;
                this.clientForm.pays= client.pays;
                if(client.logo){
                    this.hasImage = true;
                }else{
                    this.hasImage = false;
                }
                // value selected fournisseur
                this.clientForm.value=[];
                var selected = [];
                for(var i=0; i<client.fournisseurs.length;i++){
                    selected.push(this.listFournisseurs.find(option => option.id === client.fournisseurs[i]));
                }
                this.clientForm.value = selected;

                // value selected fournisseur

                this.clientForm.valueTypeCmd=[];
                var selected2 = [];
                for(var i=0; i<client.typeCmd.length;i++){
                    selected2.push(this.listTypeCmds.find(option => option.id === client.typeCmd[i]));
                }
                this.clientForm.valueTypeCmd = selected2;

                // transitaire
                this.clientForm.entite=[];
                var selected3 = [];
                for(var i=0; i<client.clenti.length;i++){
                    selected3.push(this.listTransitaire.find(option => option.id === client.clenti[i]));
                }
                this.clientForm.entite = selected3;
               
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;
                this.hasImage = false;
                this.flushData();
               

            },
            closeLoader(){
                var thiss = this;
                setTimeout(function(){
                  thiss.isLoading=false;
                },500);
            }
        },
        mounted() {
            this.getClient();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>