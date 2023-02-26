<template>
    <div>
         <!-- Modal Motif-->
        <div class="modal fade " :class="[can_edit?'fullscreenModal':'']" id="listMotif" tabindex="-1" role="dialog" aria-labelledby="mylistMotif" 
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" :class="[!can_edit?'h-100':'']" role="document">
             <div class="modal-content" :class="[!can_edit?'max_height_content':'']">
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Commande non chargée n°{{motifForm.idCmd}}</h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalMotif()" aria-label="Close" ref="closePopupMotif">
                          <span aria-hidden="true">&times;</span> 
                        </button>
                    </div>
                    <div class="modal-body mx-3 overflow-hidden">
                          <div class="appWhasapp">
                                <div class="w-100 app-one">
                                   <div class="headerMotif px-3 py-2 bg-white font-weight-bold  h3">Motif du rejet</div>
                                  <!-- Conversation Start -->
                                  <div class="col-sm-12 conversation">

                                    <!-- Message Box -->
                                    <div class="w-100 message" id="conversation">

                                        <div v-for="motif, index in motifs" :key="motif.id" class="w-100 message-body">
                                            <div class="col-sm-12 message-main-receiver" v-if="index==0">
                                              <div class="receiver mt-2">
                                                <div class="message-text">
                                                 {{ motif.motif }}
                                                </div>
                                                <span class="message-time pull-right">
                                                  {{ motif.user }} {{ motif.datemotif }}
                                                </span>
                                              </div>
                                            </div>
                                            <template v-else>
                                                <div class="col-sm-12" :class="[motifs[index-1].user==motif.user?'message-main-receiver':'message-main-sender']">
                                                  <div class="mt-2" :class="[motifs[index-1].user==motif.user?'receiver':'sender']">
                                                    <div class="message-text">
                                                      {{ motif.motif }}
                                                    </div>
                                                    <span class="message-time pull-right">
                                                      {{ motif.user }} {{ motif.datemotif }}
                                                    </span>
                                                  </div>
                                                </div>
                                            </template>

                                        </div>
                                    </div>
                                    <!-- Message Box End -->

                                    <!-- Reply Box -->
                                    <div class="row reply" v-if="can_edit">
                                      <div class="col-sm-11 col-xs-9 reply-main">
                                        <textarea v-model="motifForm.commentaires" class="form-control" rows="1" id="comment" placeholder="Editer un nouveau commentaire"></textarea>
                                      </div>
                                      <div class="col-sm-1 col-xs-1 reply-send d-flex flex-column mt-2">
                                        <i class="fa fa-send fa-2x" aria-hidden="true" v-on:click="saveMotif()"></i>
                                        <button class="btn btn-warning" v-on:click="closeModalMotif()">Fermer</button>
                                      </div>
                                    </div>
                                    <!-- Reply Box End -->
                                  </div>
                                  <!-- Conversation End -->
                                </div>
                                <!-- App One End -->
                              </div>

                              <!-- App End -->
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
            motifs:{},
            motifForm:{
                commentaires: '',
                idCmd: '',
                username: '',
                idreception: '',
                datecreation:''
            },
            currentDry: {},
            isRunMotif: false,
            can_edit: false
        }
      },
      methods: {
        getMotif(id, cmd){
                this.currentDry = cmd;
                this.motifForm.idreception = cmd.reidre;
                this.motifForm.idCmd = cmd.rencmd;
                this.motifForm.datecreation = cmd.recrea;
                this.motifForm.username  = cmd.reuser;
                axios.get('/getmotif/'+id).then(response => {
                    var resp = response.data;
                    console.log("Reponse", resp);
                    if(resp.code==0){
                      this.motifs = resp.data;
                    }else{
                         Vue.swal.fire(
                              '',
                              'Erreur',
                              'warning'
                            );
                    }
                    
                });
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
                this.isRunMotif = true;
                axios.post('/gerer/saveMotif', {
                    'numcmd':      this.motifForm.idCmd,
                    'idreception': this.motifForm.idreception,
                    'motif' :      this.motifForm.commentaires, 
                    'username' :   this.motifForm.username,
                    'date' :       this.motifForm.datecreation 
                }).then(response => {

                    this.isRunMotif = false;
                  
                    if(response.data.code==0){
                        this.flustDataMotif();
                       this.getMotif(this.currentDry.reidre, this.currentDry);
                        
                    }else{
                        
                         Vue.swal.fire(
                          'error!',
                          'Erreur',
                          'error'
                        )
                    }  

                });
            },
            flustDataMotif(){
                this.motifForm.commentaires = ""; 
            },
            closeModalMotif(){
                this.$refs.closePopupMotif.click();
                
            }

      },
         
      mounted() {

          EventBus.$on('VIEW_MOTIF', (event) => {
              this.currentDry = event.dryCmd;
              this.can_edit = (event.can_edit!=null? event.can_edit:false)
              this.getMotif(this.currentDry.reidre, this.currentDry);

              
              
              $('#listMotif').modal('show');
          });
      }
  }
</script> 
