<template>
    <div>
        <!-- Modal Facture-->
        <div class="modal fade" id="openFacturePop" tabindex="-1" role="dialog" aria-labelledby="myModalFacturePops"
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
        }
      },
      methods: {
        closeModalPdf(){
             this.$refs.closePoupPdf.click();
             this.pdfFile=null;
        },

      },
         
      mounted() {

          EventBus.$on('VIEW_FACT', (event) => {
              this.pdfFile = event.pathFile;

              
              
              $('#openFacturePop').modal('show');
          });
      }
  }
</script> 