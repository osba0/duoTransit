<template>
    <div>
        <!-- Modal Motif-->
        <div class="modal fade" id="motifModal" tabindex="-1" role="dialog" aria-labelledby="mymotifModal" 
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Commande non préchargée n°{{ motifForm.idCmd }}</h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalIMotif()" aria-label="Close" ref="closePoupMotif">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveMotif" enctype="multipart/form-data" key=1  autocomplete="off">
                             <div class="row">
                                 <div class="col-12 my-2">
                                    <div class="w-100 my-2">
                                         <label for="commentaires"  class="d-block m-0 w-100 pr-2" style='white-space: nowrap;'>
                                        Veuillez renseigner un motif.
                                       </label>
                                       <textarea class="w-100 form-control" placeholder="Saisir un motif.." v-model="motifForm.commentaires"></textarea>
                                       
                                    </div>
                                 </div>
                             
                            </div>
                             <div class="modal-footer d-flex justify-content-center"> 
                                <button type="submit" class="btn btn-success"><template v-if="isRunMotif">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </template>Valider</button>
                                <button type="button" v-on:click="closeModalIMotif()" class="btn btn-warning">Annuler</button>
                            </div>
                         </form>
                    </div>
                    
             </div>
            
          </div>
        </div>
    </div>
</template>

<script>
  import { EventBus } from '../../event-bus';
export default {
    props: [],
     components: {
       
      },
      data() { 
        return {
            motifForm:{
                commentaires: '',
                idCmd: '',
                username: '',
                idreception: '',
                datecreation:''
            },
            isRunMotif: false
        }
      },
      methods: {
        selectCmdMotif(cmd){
            this.motifForm.idCmd = cmd.rencmd;
            this.motifForm.idreception = cmd.reidre;
            this.motifForm.username = cmd.user_created;
            this.motifForm.datecreation = cmd.recrea;
        },
        saveMotif(){

                if(this.motifForm.commentaires==""){
                     Vue.swal.fire(
                          'Warning!',
                          'Renseigner un motif avant de valider',
                          'warning'
                        )
                    return false;
                }
                axios.post('/gerer/saveMotif', {
                    'numcmd':      this.motifForm.idCmd,
                    'idreception': this.motifForm.idreception,
                    'motif' :      this.motifForm.commentaires, 
                    'username' :   this.motifForm.username,
                    'date' :       this.motifForm.datecreation
                }).then(response => {
                  
                    if(response.data.code==0){
                        this.closeModalIMotif();
                        //this.getReception();

                        Vue.swal.fire(
                          'succés!',
                          'Motif envoyé avec succés',
                          'success'
                        );

                        EventBus.$emit('LISTER_RECEPTION', {}); 
                        
                    }else{
                        
                         Vue.swal.fire(
                          'error!',
                          'Erreur',
                          'error'
                        )
                    }  

                });
            },
        closeModalIMotif(){
            this.$refs.closePoupMotif.click();
            this.motifForm.commentaires="";
        },

      },
         
      mounted() {

          EventBus.$on('VIEW_MOTIF_CREATION', (event) => {
              this.selectCmdMotif(event.dryMotif);
              
              $('#motifModal').modal('show');
          });
      }
  }
</script> 
