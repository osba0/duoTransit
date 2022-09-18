<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">ID</th>
                        <th class="p-2 border-right border-white h6">Commandes</th>
                        <th class="p-2 border-right border-white h6" width="15%">Objet</th>
                        <th class="p-2 border-right border-white h6">Commentaire</th>
                        <th class="p-2 border-right border-white h6" width="15%">Photos</th>
                        <th class="text-right p-2 border-right border-white h6" width="15%">Action</th>
                    </tr>
                </thead>
             <tbody>
                      <tr v-for="incident in incidents.data" class="bg-white"> 
                        <td class="p-2 align-middle">
                            {{ incident.id }}
                        </td>
                         <td class="p-2 align-middle text-uppercase">
                            {{ incident.commandes }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ incident.objet }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ incident.commentaires }}
                        </td>
                        <td class="p-2 align-top" height="90">
                            <div class="position-relative h-100">             
                                <a v-for="(photo, index) in incident.photos" data-toggle="modal" data-target="#slidePhoto" class="border mr-1 mb-2 position-absolute shadow-sm"  v-on:click="setPhoto(incident.photos)" :style="{top:2*index+ 'px', left: 2*index+ 'px'}">
                                    <img :src="'/assets/incidents/'+photo" width="97" height="50"  >
                                </a>
                            </div>
                           
                           
                        </td>
                       
                         <td class="p-2 text-right">
                             <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editIncident(incident)" data-toggle="modal" data-target="#newIncident">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteIncident(incident)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                        </td>
                       
                    </tr>
                    <tr v-if="!incidents.data || !incidents.data.length"><td colspan="6" class="bg-white text-center">Aucun indicient déclaré</td></tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="incidents"
                @pagination-change-page="getIncident"
            ></pagination>
        </div>
        <!--Modal photos-->
        <div class="modal fade" id="slidePhoto" tabindex="-1" role="dialog" aria-labelledby="myModalPhoto"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Photos</h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoupPhoto">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                              <ol class="carousel-indicators">
                                <template v-for="(photo,index) in selectedPhotosModal">
                                    <li data-target="#carouselExampleIndicators" :data-slide-to="index" :class="(index==0?'active':'')"></li>
                                </template>
                              </ol>
                              <div class="text-center carousel-inner incidentPhoto">
                                <template v-for="(photo,index) in selectedPhotosModal">
                                     <div class="carousel-item" :class="(index==0?'active':'')">
                                      <img :src="'/assets/incidents/'+photo"/>
                                    </div>
                                </template>
                              </div>
                              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                              </a>
                              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                              </a>
                            </div>
                    </div>
                    
             </div>
            
          </div>
        </div>
         <!-- Modal incidents-->
        <div class="modal fade" id="newIncident" tabindex="-1" role="dialog" aria-labelledby="myModalIncident"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification incident</template>
                        <template v-else>Nouvel incident</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveIncident" enctype="multipart/form-data" key=1  autocomplete="off">
                           
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                        <label for="commande" class="d-block m-0 w-100 pr-2" >N° Commande</label>
                                       <input class="w-100 form-control" autocomplete="off" id="commande" aria-haspopup="true" aria-expanded="false" v-model="keyword" @focus="isFocus"  @blur="handleBlur"
                                        :class="{ 'border-danger': submitted && !$v.keyword.required }" />
                                         <ul class="dropdown-menu w-100 filterUl p-2" :class="{'d-block': showDropDown}" v-if="commandes.length > 0" style="left: 15px!important">
                                            <li v-for="cmd in commandes" :key="cmd.reidre" >
                                                <a class="p-2" v-text="cmd.rencmd" v-on:click="say(cmd.rencmd)"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 my-2 d-flex flex-column align-items-center">
                                <div class="w-100 d-flex align-items-center my-2">
                                    <div class="md-form w-100">
                                    <label for="Objet" class="d-block m-0 w-100 pr-2" >Objet</label>
                                    <input class="w-100 form-control"  v-model="incidentForm.objet" type="text" id="Objet" :class="{ 'border-danger': submitted && !$v.incidentForm.objet.required }"/>
                                </div>
                             </div>
                             </div>
                             </div>
                             <div class="row">
                                 <div class="col-12 my-2">
                                    <div class="w-100 my-2">
                                         <label for="commentaires"  class="d-block m-0 w-100 pr-2" style='white-space: nowrap;'>
                                        Commentaires
                                       </label>
                                       <textarea class="w-100 form-control" v-model="incidentForm.commentaires"></textarea>
                                       
                                    </div>
                                 </div>
                             
                            </div>
                            <div class="row">
                                 <div class="col-12 md-form w-100 my-2">
                                    <label for="photos" class="d-block m-0 w-100 pr-2" >Photos</label>
                                     <input type="file" id="file" name="file" multiple ref="file" v-on:change="handleFileUpload()"/>
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
    import { required, email, minLength, sameAs, between } from 'vuelidate/lib/validators';
    import Multiselect from 'vue-multiselect';
    export default {
        props: [
           'idClient',
           'idUser', 
        ],
         components: {
            Multiselect   
          },
        data() { 
            return {
                incidentForm :{
                    id: '',
                    commande:'',
                    objet: '',
                    commentaires:'',
                    file: [],

                },
                incidents:{},
                hasImage: false,
                submitted: false,
                paginate: 10,
                modeModify: false,
                commandes: [],
                showDropDown: false,
                keyword: null,
                hasImage: false,
                attachments: [],
                selectedPhotosModal: []
            }

        },
        validations: {
            incidentForm : {
                objet: { required },
                commentaires: { required },
            },
            keyword: { required },
        },
        watch: {
            keyword(after, before) {
                this.getResults();
            },
           paginate: function(){
                //this.getIncident();
           }
        },
         methods : { 
            setPhoto(photos){
                this.selectedPhotosModal = photos;
            },
            handleFileUpload(){
                  console.log("DED", this.attachments);
                for(var i=0; i<this.$refs.file.files.length;i++){
                    this.attachments.push(this.$refs.file.files[i])
                }
              
            },
            say: function (message) { 
                this.keyword = message;
                this.showDropDown=false;
            },
            isFocus(){
               this.showDropDown=true;
            },
            handleBlur(){
                var thiss=this;
                setTimeout(function(){
                    thiss.showDropDown=false;
                },500);
                
            },
            getResults() {
                axios.get('/livesearchCmd', { params: { keyword: this.keyword } })
                    .then(res => this.commandes = res.data)
                    .catch(error => {});
            },
            onProfil(){
                if(this.incidentForm.profil=='Client'){
                    this.noClientRole = false;
                }else{
                    this.noClientRole = true;

                }
            },
            removeImage(){
                this.hasImage = false;
                this.incidentForm.logo = "";
            },
            saveIncident(){

                this.submitted = true;

                // stop here if form is invalid
                this.$v.incidentForm.$touch();
                if (this.$v.incidentForm.$invalid) {
                    return;
                }
              
               const data = new FormData();
                data.append('objet', this.incidentForm.objet);
                data.append('commentaires', this.incidentForm.commentaires);
                data.append('commandes', this.keyword);
                data.append('idClient', this.idClient);
                data.append('user_id', this.idUser);
                data.append('file[]', this.attachments);

                for (let i = 0; i < this.attachments.length; i++) {
                data.append('files' + i, this.attachments[i]);
                }
                data.append('TotalFiles', this.attachments.length);

                let action = "createIncident";

                if(this.modeModify){
                    data.append('id', this.incidentForm.id);
                    action = "modifyIncident";
                }

                axios.post("/incidents/"+action, data,  {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        } 
                }).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getIncident();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Incident crée avec succés!',
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
                    this.closeModal();
                   
                });
            },
            flushData(){
                this.incidentForm.commande = "";
                this.incidentForm.commentaires = "";
                this.incidentForm.objet= "";
                this.attachments= []; 
                this.$refs.file.value = null;
                this.file = []; 
            },
            getIncident(page = 1){
                axios.get('/incidents/getIncident/'+this.idClient+'?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.incidents = response.data;
                });
            },
            deleteIncident(incident){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: user.firstname+' '+user.lastname,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteUser/'+user.id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Utilisateur supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getUser();


                        });
                  
                  }
                })
            },
            editIncident(incident){
                this.modeModify      = true;
                this.submitted       = false;
                this.incident.id     = incident.id;
                this.incident.objet  = incident.objet;
                this.incident.commentaires = user.commentaires;  
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {
         this.getIncident();
        }
    }
</script>