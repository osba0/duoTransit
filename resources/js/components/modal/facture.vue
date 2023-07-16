<template>
    <div>
        <!-- Modal Facture-->
        <!--div class="modal fade fullscreenModal" id="openFacturePop" tabindex="-1" role="dialog" aria-labelledby="myModalFacturePops"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
          	 <div class="modal-content">
          	 	
          	 		<div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Facture</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupPdf">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                  	     <template v-if="pdfFile != null">
                         	<embed :src="'/assets/factures/'+pdfFile" frameborder="0" width="100%" height="450px">
                     	  </template>
                 	     <template  v-else> Auncun fichier </template>
                    </div>
                    <div class="modal-footer d-flex justify-content-center"> 
                    <button type="button" v-on:click="closeModalPdf()" class="btn btn-warning">Fermer</button>
                  </div>
          	 </div>
            
          </div>
        </div-->
        <!-- Modal Facture New-->
        <div class="modal fade fullscreenModal" id="openFacturePop" tabindex="-1" role="dialog" aria-labelledby="myModalFacturePops"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left align-items-center">
                        <h4 class="modal-title font-weight-bold">Facture(s)</h4>
                        <template v-if="can_modify || true">
                            <div class="flex-1 text-center">
                                <label class="mb-0">Ajout une facture</label>
                                <input type="file" id="fileFact" name="fileFact" multiple ref="fileFactInput" v-on:change="handleFileUploadFactureUp()"/>
                                <button class="btn btn-success" v-on:click="updateFacture()">
                                 <template v-if="showSpin">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </template>Enregister</button>
                            </div>
                        </template>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupPdf">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        
                    </div>
                    <div class="modal-body mx-3 overflow-hidden">
                        <div class="d-flex justify-content-between h-100">
                            <div>
                                <div class="d-flex flex-column">
                                    <template v-for="doc, index in tabFacture">
                                        <div class="position-relative">
                                            <button v-on:click="getFacture(index)" class="btn rounded-circle  btn-default mb-2" :class="index==currentIndex ? 'bg-light':''">
                                                <span class="h1"><i class="fa fa-file"></i></span>
                                            </button>
                                            <template v-if="true">
                                                <button style="top: -3px; right: -3px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="removeFacture(doc)"><i class="fa fa-times"></i></button>
                                            </template>
                                        </div>
                                    </template>
                                   
                                </div>
                            </div>
                             <template v-if="tabFacture.length > 0">
                                <embed :src="'/assets/factures/'+tabFacture[currentIndex]" frameborder="0" width="95%" height="450px">
                              </template>
                             <template v-else>
                                <div class="w-100 text-center">Aucune facture ajoutée</div> 
                              </template>
                            
                         </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalPdf()" class="btn btn-warning">Fermer</button>
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
           pdfFile: null,
           currentIndex: 0,
           tabFacture: [],
           attachmentsFacture: [],
           currentReception: '',
           can_modify: false,
           showSpin: false
        }
      },
      methods: {
        closeModalPdf(){
             this.$refs.closePoupPdf.click();
             this.pdfFile=null;
        },
        getFacture(index){
            this.currentIndex = index;
        },
        handleFileUploadFactureUp(){
            this.attachmentsFacture = [];
            for(var i=0; i<this.$refs.fileFactInput.files.length;i++){
                this.attachmentsFacture.push(this.$refs.fileFactInput.files[i]);
            }
        },
        updateFacture(){
            this.showSpin = true;
            const data = new FormData();

            if(!this.attachmentsFacture.length > 0){
                this.showSpin = false;
                Vue.swal.fire(
                      '',
                      'Ajouter une facture avant de valider!',
                      'warning'
                    )   
                return false;
            }

            console.log("TEST", this.attachmentsFacture); 
            
            data.append('file[]', this.attachmentsFacture);

            for (let i = 0; i < this.attachmentsFacture.length; i++) {
                data.append('files' + i, this.attachmentsFacture[i]);
            }


            data.append('TotalFiles', this.attachmentsFacture.length);

            data.append('Document[]', this.tabFacture); 

            axios.post("/updateFacture/"+this.currentReception, data,  {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        } 
                }).then(response => {
                   
                    this.$refs.fileFactInput.value = null;
                    if(response.data.code==0){
                         this.tabFacture = response.data.file;
                         this.currentIndex = 0;
                         this.showSpin = false;
                        Vue.swal.fire(
                          'succés!',
                          'Facture(s) ajouté(s) avec succés!',
                          'success'
                        )     
                        EventBus.$emit('REFRESH_RECEPTION', {});         

                    }else{
                        this.showSpin = false;
                         Vue.swal.fire(
                          'error!',
                          response.data.message,
                          'error'
                        )
                    }
                   
                });
        },
        removeFacture(fact){

                Vue.swal.fire({
                  title: 'Confirmez la suppression du fichier',
                  text: fact,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                       // remove Facture
                       const data = new FormData();

                        data.append('Document[]', this.tabFacture); 
                        data.append('nameFile', fact); 

                        axios.post("/removeFacture/"+this.currentReception, data,  {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    } 
                            }).then(response => {
                               
                                if(response.data.code==0){
                                     this.tabFacture = response.data.file;
                                     this.currentIndex = 0;
                                    Vue.swal.fire(
                                      'succés!',
                                      'Facture(s) supprimée(s) avec succés!',
                                      'success'
                                    )  

                                    EventBus.$emit('REFRESH_RECEPTION', {});       

                                }else{
                                     Vue.swal.fire(
                                      'error!',
                                      response.data.message,
                                      'error'
                                    )
                                }
                               
                            });
                  
                  }
                });
            }

      },
         
      mounted() {

          EventBus.$on('VIEW_FACT', (event) => {
              //this.pdfFile = event.pathFile;
            
              this.tabFacture = event.listeFacture;
              this.currentReception = event.idReception;
              this.can_modify = event.can_modify

              $('#openFacturePop').modal('show');
          });
      }
  }
</script> 
