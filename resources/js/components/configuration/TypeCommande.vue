<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">ID</th>
                        <th class="p-2 border-right border-white h6">Type commande</th>
                        <th class="p-2 border-right border-white h6">Couleur</th>
                        <th class="p-2 border-right border-white h6" v-if="slugClient==''">Société</th>
                        <th class="p-2 border-right border-white h6">Etat</th>
                        <th class="text-right p-2 border-right border-white h6" v-if="isRoot">Action</th>
                    </tr>
                </thead>
             <tbody class="bgStripe">
                <template v-if="!typecommandes.data || !typecommandes.data.length">
                        <tr><td colspan="6" class="bg-white text-center">Aucun type commande défini!</td></tr>
                    </template>
                      <tr v-for="typecommande in typecommandes.data" class="bg-white">
                        <td class="p-2 align-middle">
                            {{ typecommande.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ typecommande.type }}
                        </td>
                        <td class="p-2 align-middle">
                            <span class="badge badge-default px-2" :style="{background: typecommande.color}">&nbsp;</span>
                            
                        </td>
                        <td v-if="slugClient==''">
                            <template v-if="slugClient!=''">
                                <template v-for="client in listClient">
                                    <template v-if="client.cltyco.includes(typecommande.id)">
                                        <a title="Retirer la société" v-on:click="retirerSocieteTypeCmd(client, typecommande)" class="badge badge-success text-white position-relative mr-2 p-2 mb-2 cursor-pointer">
                                        {{client.clnmcl}}
                                        <span class="badge badge-danger position-icone position-absolute"><i class="fa fa-minus"></i></span>
                                       
                                        </a>  
                                    </template>
                                    <template v-else>
                                        <a title="Ajouter la société" class="badge badge-secondary text-white position-relative mr-2 p-2 mb-2 cursor-pointer" v-on:click="addSocieteTypeCmd(client, typecommande)">
                                        {{client.clnmcl}}
                                         <span class="badge badge-success position-icone position-absolute"><i class="fa fa-plus"></i></span>
                                        </a>
                                    </template>
                                </template>
                            </template>
                            <template v-else>
                                <template v-for="client in listClient">
                                    <template v-if="client.cltyco.includes(typecommande.id)">
                                        <a title="Retirer la société" v-on:click="retirerSocieteTypeCmd(client, typecommande)" class="badge badge-success text-white position-relative mr-2 p-2 mb-2 cursor-pointer">
                                        {{client.clnmcl}}
                                        <span class="badge badge-danger position-icone position-absolute"><i class="fa fa-minus"></i></span>
                                       
                                        </a>  
                                    </template>
                                    <template v-else>
                                        <a title="Ajouter la société" class="badge badge-secondary text-white position-relative mr-2 p-2 mb-2 cursor-pointer" v-on:click="addSocieteTypeCmd(client, typecommande)">
                                        {{client.clnmcl}}
                                         <span class="badge badge-success position-icone position-absolute"><i class="fa fa-plus"></i></span>
                                        </a>
                                    </template>
                                </template>
                            </template>

                             
                        </td>
                        <td>  
                            <label class="switch">
                                <input class="switch-input inputCmd" :checked="(typecommande.etat)" type="checkbox" :value="typecommande.id" v-on:change="preselectionner($event,typecommande)" /> 
                                <span class="switch-label" data-on="Actif" data-off="Inactif"></span> 
                                <span class="switch-handle"></span> 
                            </label>
                        </td>
                         <td class="p-2 text-right" v-if="isRoot">
                             <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editTypeCommande(typecommande)" data-toggle="modal" data-target="#newTŷpeCommande">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteTypeCommande(typecommande)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="typecommandes"
                @pagination-change-page="getTypeCommande"
            ></pagination>
        </div>
         <!-- Modal Typecommandes-->
        <div class="modal fade" id="newTŷpeCommande" tabindex="-1" role="dialog" aria-labelledby="myModalTypCommandes"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Type Commande</template>
                        <template v-else>Nouvel Type de commande</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveTypeCommande" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="type"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Type Commande
                                       </label>
                                        <input class="w-65 form-control" id="type" v-model="typecommandesForm.type" 
                                        :class="{ 'border-danger': submitted && !$v.typecommandesForm.type.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Color
                                       </label>
                                        <input class="w-65 form-control" id="color" type="color" v-model="typecommandesForm.color"/>
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
            'isRoot',
            'idEntite',
            'listClient',
            'slugClient'
        ],
        data() { 
            return {
                typecommandesForm :{
                    id: '',
                    type: '',
                    color: '',
                    etat: ''
                },
                hasImage: false,
                submitted: false,
                typecommandes: {},
                paginate: 5,
                modeModify: false
            }

        },
        validations: {
            typecommandesForm : {
                type: { required }
            },
        },
        watch: {
           paginate: function(){

                this.getTypeCommande();
           }
        },
         methods : { 
            saveTypeCommande(){

                this.submitted = true;

                // stop here if form is invalid
                this.$v.typecommandesForm.$touch();
                if (this.$v.typecommandesForm.$invalid) {
                    return;
                }
                
               const data = new FormData();

                data.append('type', this.typecommandesForm.type);
                data.append('color', this.typecommandesForm.color);
                data.append('etat', this.typecommandesForm.etat);
                data.append('slug', this.slugClient); 

                let action = "createTypeCommande";

                if(this.modeModify){
                    data.append('id', this.typecommandesForm.id);
                    action = "modifyTypeCommande";
                }

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getTypeCommande();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Type Commande enregistré avec succés!',
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
                this.typecommandesForm.type = "";
                this.typecommandesForm.color = "";
            },
            getTypeCommande(page = 1){
                axios.get('/configuration/getTypeCommande?slug='+this.slugClient+'&page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.typecommandes = response.data;
                });
            },
            deleteTypeCommande(typecommande){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: typecommande.nom,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteTypeCommande/'+typecommande.id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              typecommande.type+' supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getTypeCommande();


                        });
                  
                  }
                })
            },
            editTypeCommande(typecommande){

                this.modeModify = true;
                this.submitted = false;
                this.typecommandesForm.id = typecommande.id;
                this.typecommandesForm.type = typecommande.type;
                this.typecommandesForm.color = typecommande.color;
                this.typecommandesForm.etat = parseInteger(typecommande.etat);
               
            
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            },
            addSocieteTypeCmd(client, typecommande){
                Vue.swal.fire({
                  title: typecommande.type,
                  text: "Confirmer l'ajout de "+client.clnmcl,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#38c172',
                  cancelButtonColor: '#545b62',
                  confirmButtonText: 'Oui, ajouter!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.post('/configuration/clientTypeCmd/'+typecommande.id+"/"+client.id).then(response => {
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
            retirerSocieteTypeCmd(client, typecommande){
                Vue.swal.fire({
                  title: typecommande.type,
                  text: "Confirmer le retrait de "+client.clnmcl,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#38c172',
                  cancelButtonColor: '#545b62',
                  confirmButtonText: 'Oui, retirer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.post('/configuration/retirerclientTypeCmd/'+typecommande.id+"/"+client.id).then(response => {
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
            preselectionner(event, type){

                var ischecked=0;
                if (event.target.checked) {
                    ischecked=1;
                }

                const data = new FormData();
                data.append('id', type.id);
                data.append('etat', ischecked);

                axios.post("/configuration/etatTypeCommande", data).then(response => {
                let res = response.data.result;
                this.getTypeCommande();

           
              
            });
            }
        },
        mounted() {
         this.getTypeCommande();
        }
    }
</script>
