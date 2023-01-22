<template>
<div>
  <template v-if="!viewReception.data || !viewReception.data.length">
    <div class="modal fade" id="detailReception" tabindex="-1" role="dialog" ref="detailRep" aria-labelledby="detailReception"
      aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl" role="document">
         <div class="modal-content">
            
                <div class="modal-header text-left">
                    <h4 class="modal-title font-weight-bold">Commande N°{{ viewReception.rencmd }}</h4>
                    <div class="d-flex align-items-center ml-5">
                        <div class="d-flex align-items-center" title="Date création">
                            <a class="btn btnAction mx-1 btn-circle border btn-circle-sm bg-white"><i class="fa fa-calendar-o"></i></a>
                            <span>{{ viewReception.recrea}}</span>
                        </div>
                        <div class="ml-3 pl-3 border-left  d-flex align-items-center"  title="Date modification" v-if="viewReception.recrea!=viewReception.reupda">
                            <a class="btn btnAction mx-1 btn-circle border btn-circle-sm bg-white"><i class="fa fa-history"></i></a>
                            <span>{{ viewReception.reupda}}</span>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalDetail()" aria-label="Close" ref="closePoupDetail">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0 mx-3">
                    <template v-if="!ispreviewModal">
                     <div class="row">
                         <div class="col-6 my-2 d-flex flex-column align-items-center">
                                <div class="w-100 d-flex align-items-center my-2">
                                    <label class="d-block m-0 text-right w-35 pr-2">
                                        Entrepot
                                    </label>
                                    <div class="w-65 pl-2 bg-light" >
                                       <span>{{ getEntrepot(viewReception.entrepots_id) }}</span>
                                      </div>
                                </div>
                                 <div class="w-100 d-flex align-items-center my-2">
                                    <label class="d-block m-0 text-right w-35 pr-2">
                                        Type Commande <span class="red">*</span>
                                    </label>
                                    <div class="w-65 pl-2 bg-light" >
                                        <span>{{ getTypeCmd(viewReception.id_commandes) }}</span>
                                    </div>
                                </div>
                                 <div class="w-100 d-flex align-items-center my-2">
                                    <label class="d-block m-0 text-right w-35 pr-2">
                                        Document spécifique
                                    </label>
                                    <div class="w-65 pl-2 bg-light" >
                                        <span>{{ viewReception.typeproduit==""? "N/A": viewReception.typeproduit  }}</span>
                                    </div>
                                </div>
                                <div class="w-100 my-2 d-flex justify-content-between align-items-center">
                                    <label class="d-block m-0 text-right w-35 pr-2" for="numfe">N°FE</label>
                                    <div class="w-65 pl-2 bg-light" >
                                        <span>{{ viewReception.refere }}</span>
                                    </div>
                                </div>
                                 <div class="w-100 my-2 d-flex justify-content-between align-items-center">
                                    <label class="d-block m-0 text-right w-35 pr-2" for="numecv">N°ECV / BBE</label>
                                     <div class="w-65 pl-2 bg-light" >
                                        <span>{{ viewReception.reecvr }}</span>
                                    </div>
                                </div>
                                <div class="w-100 d-flex align-items-center my-2">
                                   <label for="formname"  class="d-block m-0 text-right w-35 pr-2" >
                                    Fournisseur
                                   </label>
                                   <div class="w-65 pl-2 bg-light" >                                     
                                       <span>{{ getFournis(viewReception.id_fournisseurs) }}</span>
                                   </div>
                                </div>
                                 <div class="w-100 my-2 d-flex justify-content-between align-items-center">
                                    <label class="d-block m-0 text-right w-35 pr-2" for="numCommande">N°Commande</label>
                                    <div class="w-65 bg-light pl-2">
                                        <span v-if="!Array.isArray(viewReception.listgroup) || viewReception.listgroup.length==0">{{ viewReception.rencmd }}</span>
                                        <div v-else class="w-100 bg-light rounded-lg pt-1">
                                            <label v-for="cmd in viewReception.listgroup" class="badge badge-primary mr-2">
                                                {{ cmd }} 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label data-error="wrong" data-success="right" for="formname" class="d-block m-0 text-right w-35 pr-2">Date de livraison</label>
                                    <div class="w-65 pl-2 bg-light">
                                        <span>{{ viewReception.redali }}</span>
                                    </div>
                                </div>
                               

                               
                                  
                         </div>
                          <div class="col-6 my-2 d-flex flex-column align-items-center">
                                <div class="w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label class="d-block m-0 text-right w-35 pr-2" for="nbrpalette">
                                    Nbr Palette
                                     </label>
                                     <div class="w-65 pl-2 bg-light">
                                         <span>{{ viewReception.renbpl }}</span>
                                     </div>
                                </div>
                                <div class="w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label class="d-block m-0 text-right w-35 pr-2" for="nbrcolis">
                                    Nbr Colis
                                     </label>
                                     <div class="w-65 pl-2 bg-light">
                                         <span>{{ viewReception.renbcl }}</span>
                                     </div>
                                </div>
                              
                                <div class="w-100 my-2 d-flex justify-content-between align-items-center">
                                    <label  for="poidstotal" class="d-block m-0 text-right w-35 pr-2">Poids (KG)</label>
                                     <div class="w-65 pl-2 bg-light">
                                         <span>{{ viewReception.repoid }}</span>
                                     </div>
                                </div>
                                 <div class="w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label for="volumetotal" class="d-block m-0 text-right w-35 pr-2" >
                                        Volume (m<sup>3</sup>)
                                    </label>
                                     <div class="w-65 pl-2 bg-light">
                                         <span>{{ viewReception.revolu }}</span>
                                     </div>
                                </div>
                                 <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label for="numfact" class="d-block m-0 text-right w-35 pr-2" >N° Facture</label>
                                     <div class="w-65 pl-2 bg-light">
                                         <span>{{ viewReception.renufa }}</span>
                                     </div>
                                </div>
                                 <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label for="montfact" class="d-block m-0 text-right w-35 pr-2" >Montant Facture (&euro;)</label>
                                    <div class="w-65 pl-2 bg-light">
                                        <span>{{ viewReception.revafa }}</span>
                                    </div>
                                </div>
                                 <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label data-error="wrong" data-success="right" for="formname" class="d-block m-0 text-right w-35 pr-2">Utilisateur</label>
                                    <div class="w-65 pl-2 bg-light">
                                        <span>{{ viewReception.user_created }}</span>
                                    </div>
                                </div>
                                
                         </div>
                    </div>
                    <div class="row" v-if="!(viewReception.recomt == null || viewReception.recomt == '' || viewReception.recomt == ' ') ">
                        <div class="col-12 md-form w-100 d-flex my-2 justify-content-between flex-column">
                            <label for="commentaire" class="d-block m-0 text-left  pr-2" >Commentaires</label>
                            <div class="w-100 pl-2 bg-light">
                                <span>{{ viewReception.recomt }}</span>
                            </div>
                        </div>
                    </div>
                    </template>
                    <template v-else>
                        <div class="bg-dark p-3 content-preview">
                            <div class="text-center">
                                <button v-on:click="closePreview()" class="border-0 btn btn-warning">Fermer l'incident</button>
                            </div>
                            <h5 class="font-weight-bold">Commentaires de l'incident</h5>
                            <div>{{ commentairesCurrent }}</div>
                            <hr class="text-white">
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
                    </template>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h4>Incident(s) déclaré(s)</h4>
                        </div>
                        <div class="col-12" v-if="!incidents.data || !incidents.data.length">Aucun indicient déclaré</div>
                        <table v-else class="table">
                            <thead class="thead-blue">
                                 <tr>
                                    <th class="p-2 border-right border-white h6">ID</th>
                                    <th class="p-2 border-right border-white h6">Commandes</th>
                                    <th class="p-2 border-right border-white h6">Objet</th>
                                    <th class="p-2 border-right border-white h6">Commentaire</th>
                                    <th class="p-2 border-right border-white h6">Photos</th>
                                    <th v-if="canDeleteIncident==true" class="text-right p-2 border-right border-white h6">Action</th>
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
                                        <template v-if="incident.commentaires.length > 0">
                                            {{ incident.commentaires.substring(0,20)+".." }} <a class="text-primary cursor-pointer" v-on:click="setPhoto(incident)">Voir plus</a>
                                        </template>
                                        
                                    </td>
                                    <td class="p-2" v-on:click="setPhoto(incident)">
                                        <div class="position-relative">
                                            <!--data-toggle="modal" data-target="#slidePhoto"-->
                                            <template v-if="incident.photos.length > 0">
                                                <a v-for="(photo, index) in incident.photos" class="border mr-1 mb-2 position-absolute shadow-sm"  v-on:click="setPhoto(incident)" :style="{top:2*index+ 'px', left: 2*index+ 'px'}">
                                                       
                                                          <img :src="'/assets/incidents/'+photo" width="97" height="50" v-on:click="setPhoto(incident)" class="cursor-pointer">  
                                                </a>
                                            </template>
                                             <template v-else> 
                                               <img src="/images/logo/no-image.png" style="height: 40px;"> 
                                            </template>
                                        </div>
                                       
                                    </td>
                                    <td class="p-2 text-right align-middle">

                                        <a v-if="canDeleteIncident==true" title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteIncident(incident)">
                                            <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                   
                                   
                                </tr>
                                
                            </tbody>
                        </table>

                    </div>
                   
                </div>

                <div class="modal-footer d-flex justify-content-center">
                <button type="button" v-on:click="closeModalDetail()" class="btn btn-warning">Fermer</button>
              </div>
         </div>
        
      </div>
    </div>
  </template>
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
                viewReception: {}, 
                incidents: {},
                ispreviewModal: false,
                listEntrepots: [],
                typeCmd: [],
                listFournisseurs: [],
                paginate: 5,
                idClient:'',
                selectedPhotosModal: [],
                ispreviewModal: false,
                commentairesCurrent:  '',
                canDeleteIncident: false
            }

      },
      methods: {
        getEntrepot(id){
            var entrepotList = this.listEntrepots;

             for(var i=0; i<entrepotList.length; i++){
    
                if(entrepotList[i].id==id){
                    return entrepotList[i].nomEntrepot; 
                }
            }
            return id;
        },
        getTypeCmd(id){
            for(var i=0; i<this.typeCmd.length; i++){
                if(this.typeCmd[i].id== parseInt(id)){
                  return this.typeCmd[i].typcmd; 
                }
            }
            return id;
        },
        getFournis(id){
            for(var i=0; i<this.listFournisseurs.length; i++){
                if(this.listFournisseurs[i].id==id){
                    return this.listFournisseurs[i].fonmfo; 
                }
            }
            return id;
        },
        closeModalDetail(){
             this.$refs.closePoupDetail.click();
             this.closePreview();
        },
        closePreview(){
            this.ispreviewModal=false;
        },
        getIncident(page = 1, cmd){
            this.incidents = {};
            axios.get('/incidents/getIncident/'+this.idClient+'?page=' + page + "&paginate=" + this.paginate+"&cmd="+cmd).then(response => {
                this.incidents = response.data;
            });
        },
        setPhoto(incident){
            this.selectedPhotosModal = incident.photos;
            this.commentairesCurrent = incident.commentaires;
            this.ispreviewModal = true;
        },
         deleteIncident(incident){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: incident.objet,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/incidents/deleteIncident/'+incident.id+"?logos="+ JSON.stringify(incident.photos)+"&commande="+incident.commandes).then(response => {
                             Vue.swal.fire(
                              'Supprimé!',
                              'Incident supprimé avec succés.',
                              'success'
                            );
                             this.getIncident(1, incident.commandes);


                        });
                  
                  }
                })
            }

      },
         
      mounted() {


          EventBus.$on('VIEW_CMD', (event) => {
              this.viewReception = event.dry;
              this.listFournisseurs = event.fournisseur;
              this.typeCmd = event.typeCommande;
              this.listEntrepots = event.entrepot;
              this.idClient = event.idClient;
              this.canDeleteIncident = event.canDeleteIncident;

              this.getIncident(1, event.dry.rencmd);
              
              $('#detailReception').modal('show');
          });
      }
  }
</script> 
