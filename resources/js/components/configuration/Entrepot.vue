<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">#</th>
                        <th class="p-2 border-right border-white h6">Nom</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody>
                <template v-if="!entrepots.data || !entrepots.data.length">
                        <tr><td colspan="6" class="bg-white text-center">Aucun entreprot défini!</td></tr>
                    </template>
                      <tr v-for="entrepot in entrepots.data" class="bg-white">
                        <td class="p-2 align-middle">
                            {{ entrepot.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ entrepot.nom }}
                        </td>
                         <td class="p-2 text-right">
                             <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editEntrepot(entrepot)" data-toggle="modal" data-target="#newEntrepot">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteEntrepot(entrepot)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="entrepots"
                @pagination-change-page="getEntrepots"
            ></pagination>
        </div>
         <!-- Modal Entrepots-->
        <div class="modal fade" id="newEntrepot" tabindex="-1" role="dialog" aria-labelledby="myModalEntrepot"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Entrepôt</template>
                        <template v-else>Nouveau Entrepot</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveEntrepot" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-12 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Nom
                                       </label>
                                        <input class="w-65 form-control" id="nom" v-model="entrepotForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.entrepotForm.nom.required }" />
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
                entrepotForm :{
                    id: '',
                    nom: '',
                    adresse: '',
                    logo: '',
                    telephone: ''

                },
                hasImage: false,
                submitted: false,
                entrepots: {},
                paginate: 5,
                modeModify: false
            }

        },
        validations: {
           entrepotForm : {
                nom: { required }
            },
        },
        watch: {
           paginate: function(){

                this.getEntrepots();
           }
        },
         methods : { 
            saveEntrepot(){

                this.submitted = true;

                // stop here if form is invalid
                this.$v.entrepotForm.$touch();
                if (this.$v.entrepotForm.$invalid) {
                    return;
                }
                
               const data = new FormData();
                data.append('nom', this.entrepotForm.nom);
               

                let action = "createEntrepot";

                if(this.modeModify){
                    data.append('id', this.entrepotForm.id);
                    if(this.hasImage){
                        data.append('imageSet', this.entrepotForm.logo);
                    }
                    action = "modifyEntrepot";
                }

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getEntrepots();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Entrepôt enregistré avec succés!',
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
                this.entrepotForm.nom = "";
            },
            getEntrepots(page = 1){
                axios.get('/configuration/getEntrepots?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.entrepots = response.data;
                });
            },
            deleteEntrepot(entrepot){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: entrepot.nom,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteEntrepot/'+entrepot.id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Entrepôt supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getEntrepots();


                        });
                  
                  }
                })
            },
            editEntrepot(entrepot){

                this.modeModify = true;
                this.submitted = false;
                this.entrepotForm.id = entrepot.id;
                this.entrepotForm.nom = entrepot.nom;
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {
         this.getEntrepots();
        }
    }
</script>