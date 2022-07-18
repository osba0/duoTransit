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
                        <th class="p-2 border-right border-white h6">Entrepôts</th>
                        <th class="p-2 border-right border-white h6">Contenaires</th>
                        <th class="p-2 border-right border-white h6">Logo</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody>
                <template v-if="!entites.data || !entites.data.length">
                        <tr><td colspan="6" class="bg-white text-center">Aucun transitaire trouvé!</td></tr>
                    </template>
                      <tr v-for="entite in entites.data" class="bg-white">
                        <td class="p-2 align-middle">
                            {{ entite.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ entite.nom }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ entite.telephone }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ entite.adresse }}
                        </td>
                        <td class="p-2 align-middle">
                           <template v-for="entrepot in listEntrepots">
                               <template v-for="item in entite.entrepots">
                                   <span v-if="entrepot.id==item" class="badge badge-info mr-2">{{ entrepot.nom }}</span>
                               </template>
                           </template>
                        </td>
                         <td class="p-2 align-middle">
                           <template v-for="contenaire in listContenaires">
                               <template v-for="item in entite.contenaires">
                                   <span v-if="contenaire.id==item" class="badge badge-info mr-2">{{ contenaire.nom }}</span>
                               </template>
                           </template>
                        </td>
                        <td class="p-2 align-middle">
                          <template v-if="entite.logo">
                              <img :src="'/images/logo/'+entite.logo" style="height: 40px;">
                          </template>
                          <template v-else>
                             <img src="/images/logo/no-image.png" style="height: 40px;">
                          </template>
                          
                        </td>
                         <td class="p-2 text-right">
                             <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editEntite(entite)" data-toggle="modal" data-target="#newEntite">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteEntite(entite)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="entites"
                @pagination-change-page="getEntite"
            ></pagination>
        </div>
         <!-- Modal Entites-->
        <div class="modal fade" id="newEntite" tabindex="-1" role="dialog" aria-labelledby="myModalEntite"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification transitaire</template>
                        <template v-else>Nouvel transitaire</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveEntite" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Nom
                                       </label>
                                        <input class="w-65 form-control" id="nom" v-model="entiteForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.entiteForm.nom.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Adresse
                                       </label>
                                        <input class="w-65 form-control" id="adresse" v-model="entiteForm.adresse"/>
                                    </div>
                                 </div>
                                
                             </div>
                             
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="tele" class="d-block m-0 text-right w-35 pr-2" >Telephone</label>
                                            <input class="w-65 form-control"  v-model="entiteForm.telephone" type="text" id="tele"/>
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
                                                <img :src="'/images/logo/'+entiteForm.logo" class="mt-1" height="30" />
                                                <button class="btn p-0" v-on:click="removeImage()"><i aria-hidden="true" class="fa fa-close text-danger"></i></button>
                                            </template>
                                             </div>
                                            </div>

                                        </div>
                                        <div class="w-100 d-flex align-items-center my-2"></div>
                                    
                                 </div>
                             </div>
                             <div class="row">
                                <div class="col-12 my-3 d-flex flex-column align-items-center">
                                    <div class="w-100" :class="{ 'border-danger': submitted && !$v.entiteForm.entrepots.required }" >
                                         <label>Entrepôt</label>
                                         <multiselect v-model="entiteForm.entrepots" :options="listEntrepots" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="nom" track-by="id" :preselect-first="false">
                                            <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Entrepôt(s) selectionné(s)</span> 
                                            </template> 
                                        </multiselect>
                                    </div>
                                </div>
                             </div> <div class="row">
                                <div class="col-12 my-3 d-flex flex-column align-items-center">
                                    <div class="w-100" :class="{ 'border-danger': submitted && !$v.entiteForm.contenaires.required }" >
                                            <label>Contenaire</label>
                                            <multiselect v-model="entiteForm.contenaires" :options="listContenaires" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="nom" track-by="id" :preselect-first="false">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Contenaire(s) selectionné(s)</span> 
                                                </template> 
                                            </multiselect>
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
          'listContenaires',
          'listEntrepots' 
        ],
         components: {
            Multiselect   
          },
        data() { 
            return {
                entiteForm :{
                    id: '',
                    nom: '',
                    adresse: '',
                    logo: '',
                    telephone: '',
                    contenaires: [],
                    entrepotsValues: [],
                    entrepots: [],
                    contenairesValues: []
                },
                hasImage: false,
                submitted: false,
                entites: {},
                paginate: 5,
                modeModify: false,
                value: [],
                options: []
            }

        },
        validations: {
            entiteForm : {
                nom: { required },
                contenaires: { required },
                entrepots: { required }
            },
        },
        watch: {
           paginate: function(){

                this.getEntite();
           }
        },
         methods : { 
            removeImage(){
                this.hasImage = false;
                this.entiteForm.logo = "";
            },
            handleFileUpload(){

                this.entiteForm.logo = this.$refs.file.files[0];  
            },
            saveEntite(){

                this.submitted = true;

                // stop here if form is invalid
                this.$v.entiteForm.$touch();
                if (this.$v.entiteForm.$invalid) {
                    return;
                }
                var nom = this.entiteForm.nom;
               const data = new FormData();
                data.append('file', this.entiteForm.logo);
                data.append('nom', this.entiteForm.nom);
                data.append('adresse', this.entiteForm.adresse);
                data.append('telephone', this.entiteForm.telephone);
                data.append('entrepots', JSON.stringify(this.entiteForm.entrepots));
               
                // get entrepot
                for(var i=0; i<this.entiteForm.entrepots.length; i++){
                    var item = this.entiteForm.entrepots[i];
                    this.entiteForm.entrepotsValues.push(item.id);  
                }
                data.append('entrepots', JSON.stringify(this.entiteForm.entrepotsValues));

                // get contenaire
                for(var i=0; i<this.entiteForm.contenaires.length; i++){
                    var item = this.entiteForm.contenaires[i];
                    this.entiteForm.contenairesValues.push(item.id);  
                }

                data.append('contenaires', JSON.stringify(this.entiteForm.contenairesValues)); 

                let action = "createEntite";

                if(this.modeModify){
                    data.append('id', this.entiteForm.id);
                    if(this.hasImage){
                        data.append('imageSet', this.entiteForm.logo);
                    }
                    action = "modifyEntite";
                }

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getEntite();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          nom+' est enregistré avec succés!',
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
                this.entiteForm.nom = "";
                this.entiteForm.adresse = "";
                this.entiteForm.telephone= "";
                this.entiteForm.logo= "";
                this.entiteForm.entrepots= [];
                this.entiteForm.contenaires= [];
            },
            getEntite(page = 1){
                axios.get('/configuration/getEntite?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.entites = response.data;
                });
            },
            deleteEntite(entite){
                var name=entite.nom;
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: entite.nom,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteEntite/'+entite.id+"?logo="+entite.logo).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              name+' supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getEntite();


                        });
                  
                  }
                })
            },
            editEntite(entite){

                this.modeModify = true;
                this.submitted = false;
                this.entiteForm.id = entite.id;
                this.entiteForm.nom = entite.nom;
                this.entiteForm.adresse = entite.adresse;
                this.entiteForm.telephone= entite.telephone;
                this.entiteForm.logo= entite.logo;
                this.entiteForm.entrepots= this.getIdSelected(this.listEntrepots, entite.entrepots);
                this.entiteForm.contenaires = this.getIdSelected(this.listContenaires, entite.contenaires);
        
                if(entite.logo){
                    this.hasImage = true;
                }else{
                    this.hasImage = false;
                }
            },
            getIdSelected(listeInitial, currentItem){
                var selected = [];

                for(var i=0; i<currentItem.length; i++){
                    for(var j=0; j<listeInitial.length; j++){
                        
                        if(listeInitial[j].id==currentItem[i]){
                            selected.push(listeInitial[j]);
                        }
                    }
                }

                return selected;
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {
         this.getEntite();
         console.log("log", this.listEntrepots);
        }
    }
</script>