<template>
    <div>
        <div class="card-body table-responsive p-0">

            <form @submit.prevent="importFile" enctype="multipart/form-data" key=1>
                <div class="d-flex align-items-center  w-100 mb-3">
                    <div class="d-flex align-items-center p-2 rounded">
                        <label class="mb-0 badge-primary p-2 mr-2 rounded-circle badge">1.</label>
                         <select
                            v-model="typeCommande"
                            class="form-control form-control-sm" @change="selectTypeCmd()" :class="{ 'border-danger': submitted && !$v.reception.typeCmd.required }" >
                            <option value="">Choisir type Commande</option>
                            <option :value="type.id" v-for="type in typeCmd">{{type.typcmd}}</option>
                            
                        </select>
                    </div>
                    <div class="d-flex align-items-center mx-2">
                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </div>
                    <div class="d-flex align-items-center p-2 rounded" :class="[step==0 ? 'disbaled-1':'']">
                        <label class="mb-0 badge-primary p-2 mr-2 rounded-circle badge">2.</label>
                        <span class="text-nowrap pr-2">Choisir le fichier</span>
                        <input type="file" :disabled="step==0" id="file" name="file" ref="file" v-on:change="handleFileUpload()"  class="form-control mr-3">
                    </div>
                     <div class="d-flex align-items-center mx-2">
                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </div>
                    <div class="d-flex align-items-center"  :class="[step <= 1 ? 'disbaled-1':'']">
                         <label class="mb-0 badge-primary p-2 mr-2 rounded-circle badge">3.</label>
                        <button :disabled="step<=1"  class="btn btn-success">Importer</button>
                    </div>

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
                <div class="mx-5">
                    <div class="d-flex align-items-center">
                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                            >Etat</label> 
                        <select
                            class="form-control form-control-sm" v-model="etatCmd">
                            <option value="">Afficher tout</option>
                            <option value="0">En Attente</option>
                            <option value="1">Réceptionnée</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="d-flex align-items-center">
                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                            >Type commande</label> 
                       <select class="form-control form-control-sm"  v-model="filtreCmd">
                            <option value="">Afficher tout</option>
                            <option :value="type.id" v-for="type in typeCmd">{{type.typcmd}}</option>
                            
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
               <tbody class="bgStripe" :class="[isLoading ? 'loader-line' : '']">
                <template v-if="!commandes.data || !commandes.data.length">
                        <tr><td colspan="10" class="bg-white text-center" v-if="checking">Aucune commande trouvée!</td></tr>
                    </template>
                      <tr v-for="cmd in commandes.data" class="bg-white" :class="[checkFournisseurExist(cmd.fournisseur) ? '':'bg-light-danger']">
                        <td class="p-2 align-middle">
                            {{ cmd.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ cmd.type_commande }}
                        </td>
                        <td class="p-2 align-middle">
                            <div v-if="!checkFournisseurExist(cmd.fournisseur)" class="d-flex align-items-center">
                                <div class="circlePulse pulse redPulse"></div> 
                                <span class="mx-2">{{ cmd.fournisseur }}</span>
                                <button @click="setDefaultVal(cmd.fournisseur)" :title="'Ajouter le fournisseur: '+cmd.fournisseur" data-toggle="modal" data-target="#newFournisseur" class="btn btn-light rounded-circle text-primary"><i class="fa fa-plus"></i></button>
                            </div>
                            <div v-else  class="d-flex align-items-center">
                                 <div class="circlePulse greenPulse"></div> 
                                 <span class="mx-2">{{ cmd.fournisseur }}</span>
                            </div>
                             
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
                            <label class="badge badge-success" v-if="cmd.etat_cmd==1">Réceptionnée</label>
                        </td>
                         <td class="p-2 align-middle">
                            {{ cmd.created_at }}
                        </td>

                        <td class="text-right">
                            <!--button v-if="!checkFournisseurExist(cmd.fournisseur)"  @click="setDefaultVal(cmd.fournisseur)" :title="'Ajouter le fournisseur: '+cmd.fournisseur" data-toggle="modal" data-target="#newFournisseur" class="btn btn-light rounded-circle text-primary"><i class="fa fa-plus"></i></button-->
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


        <!-- Modal Fournisseurs-->
        <div class="modal fade" id="newFournisseur" tabindex="-1" role="dialog" aria-labelledby="myModalFournisseur"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                         Ajout Fournisseur: <span class="h3 text-primary">{{ fournisseurForm.nom }}</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalFour()" aria-label="Close" ref="closePoupFour">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveFournisseur" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Fournisseur
                                       </label>
                                        <input  class="w-65 form-control" autocomplete="false"  id="nom" v-model="fournisseurForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.fournisseurForm.nom.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column">
                                        <div class="w-100 d-flex align-items-center my-2">
                                         <label for="inter"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Interlocuteur
                                       </label>
                                        <input class="w-65 form-control" autocomplete="false" id="inter" v-model="fournisseurForm.gestionnaire"/>
                                    </div>
                                    
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                         <label for="email"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Email
                                       </label>
                                        <input class="w-65 form-control" autocomplete="false"  id="email" v-model="fournisseurForm.email"/>
                                    </div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column">
                                        <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Adresse
                                       </label>
                                        <input class="w-65 form-control" autocomplete="false"  id="adresse" v-model="fournisseurForm.adresse"/>
                                    </div>
                                    
                                 </div>
                             </div>

                              <div class="row">
                                 <div class="col-6 my-2 d-flex flex-column">
                                       <div class="w-100 d-flex my-2">
                                            <div class="md-form w-100 d-flex justify-content-between">
                                            <label for="logo" class="d-block m-0 text-right w-35 pt-3 pr-2" >Logo</label>
                                            <div class="w-65 p-2">
                                            <input class=" form-control"  ref="file2" v-on:change="handleFileUpload2()" type="file" id="logo"/>
                                            
                                             </div>
                                            </div>

                                        </div>
                                        <div class="w-100 d-flex align-items-center my-2"></div>
                                    
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
                            
                             <div class="modal-footer d-flex justify-content-center"> 
                                <button type="submit" class="btn btn-success">Enregister</button>
                                <button type="button" v-on:click="closeModalFour()" class="btn btn-warning">Annuler</button>
                               
                               
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
          'typeCmd',
          'fournisseurList'
        ],
        data() { 
            return {
                submitted: false,
                commandes: {},
                paginate: 20,
                modeModify: false,
                fileExcel: null,
                typeCommande: '',
                step: 0,
                 isLoading: true,
                 checking: false,
                fournisseurForm :{
                    id: '',
                    nom: '',
                    adresse: '',
                    logo: '',
                    telephone: '',
                    client: '',
                    idClients:'',
                    email: '',
                    gestionnaire: ''

                },
                page: 1,
                etatCmd: '',
                filtreCmd: ''
            }

        },
        validations: {
            fournisseurForm : {
                nom: { required }
            },
        },
        watch: {
           paginate: function(){

                this.getCommande();
           },
           etatCmd: function(){
                this.getCommande();
           },
           filtreCmd: function(){
                this.getCommande();
           },
        },
         methods : { 
            handleFileUpload(){

                this.fileExcel = this.$refs.file.files[0];  
                if(this.fileExcel){
                    this.step=2;
                }else{
                    this.step=1;
                }

            },
            handleFileUpload2(){

                this.fournisseurForm.logo = this.$refs.file2.files[0];  
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
                data.append('typeCommande', this.typeCommande);

                axios.post("/import", data).then(response => {
                  
                    if(response.data.code==0){
                        /*this.$refs.closePoupFour.click();
                         this.getCommande();
                        this.flushData();*/
                        Vue.swal.fire(
                          'succés!',
                          'Importé avec succés!',
                          'success'
                        );
                        Vue.swal.fire(
                         'succés!',
                          'Importé avec succés!',
                          'success'
                        ).then((result) => {
                             location.reload();
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
                this.contenaireForm.nom = "";
                this.contenaireForm.capacite = "";
                this.contenaireForm.isdefault= 0;
                
            },
            getCommande(page = 1){
                 this.isLoading=true;
                axios.get('/listcmdimported?page=' + page + "&paginate=" + this.paginate+"&etat="+this.etatCmd+"&typeCmd="+this.filtreCmd).then(response => {
                    this.commandes = response.data;
                    this.checking=true;
                    var self=this;
                    setTimeout(function(){
                         self.isLoading=false;
                    },800);
                   
                });
                this.page = page;
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
                            ).then((result) => {
                                localStorage.setItem('current_page_import', this.page);  
                                location.reload();
                            });


                        });
                  
                  }
                })
            },
            closeModal(){
                this.$refs.closePoupFour.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            },
            selectTypeCmd(){
                if(this.typeCommande!=''){
                    this.step=1;
                }else{
                    this.step=0;
                }
            },
            checkFournisseurExist(name){
              console.log(this.fournisseurList);
                for(var i=0; i<this.fournisseurList.length; i++){
                    var obj=this.fournisseurList[i];
                    if((name.trim() == obj.fonmfo.trim() ||  obj.fonmfo.toLowerCase().trim() === name.toLowerCase().trim() || obj.fonmfo.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim() === name.toLowerCase().trim()) && (obj.fogefo!='' && obj.fogefo!=null) && (obj.foemail!='' && obj.foemail!=null)){
                       return true
                    }
                }

                return false;
            },
            setDefaultVal(name){
                this.fournisseurForm.nom=name;

                for(var i=0; i<this.fournisseurList.length; i++){
                    var obj=this.fournisseurList[i];
                    if((name.trim() == obj.fonmfo.trim() ||  obj.fonmfo.toLowerCase().trim() == name.toLowerCase().trim() || obj.fonmfo.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim() == name.toLowerCase().trim())){
                        this.fournisseurForm.id = obj.id;
                    } 
                }
              
            },
            saveFournisseur(){

                this.submittedFour = true;

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
                data.append('gestionnaire', this.fournisseurForm.gestionnaire);
                data.append('id', this.fournisseurForm.id);
                data.append('slug', this.slugClient);  

                let action = "modifyFournisseur"; //"createFournisseur";

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoupFour.click();
                        //this.fournisseurList.push(JSON.parse(response.data.lastedAdded));
                        this.flushDataFour();
                        Vue.swal.fire(
                          'succés!',
                          'Fournisseur enregistré avec succés!',
                          'success'
                        ).then((result) => {
                            localStorage.setItem('current_page_import', this.page);  
                            location.reload();
                        });
                        
                        
                        
                    }else{
                         Vue.swal.fire(
                          'error!',
                          response.data.message,
                          'error'
                        )
                    }
                    this.submittedFour = false;
                   
                });
            },
            flushDataFour(){
                this.fournisseurForm.nom = "";
                this.fournisseurForm.adresse = "";
                this.fournisseurForm.email = "";
                this.fournisseurForm.telephone= "";
                this.fournisseurForm.logo= "";
                this.fournisseurForm.client= "";
                this.fournisseurForm.idClients= "";
                this.fournisseurForm.id= "";
            },
            closeModalFour(){
                 this.$refs.closePoupFour.click();
            }

        },
        mounted() {
            this.getCommande(localStorage.getItem('current_page_import')=== null? this.page : localStorage.getItem('current_page_import'));
        }
    }
</script>
