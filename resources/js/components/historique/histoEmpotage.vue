 <template>
    <div>
        <div class="row justify-content-center histoform">
            <div class="col-md-12">
                <div class="d-inline-block">
                    <div class="border p-3 bg-white justify-content-center rounded">
                        <div class="d-inline-flex filtreTireur align-items-end">
                            <div class="mr-3 text-left" style="width: 230px">

                               <label class="mr-3  text-left w-100 mb-0">Date Début</label>
                                <date-picker v-model="filtre.dateDebut" required valueType="YYYY-MM-DD"  :disabled-date="disabledFutureDate" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                            </div>
                            <div class="mr-3 text-left" style="width: 230px">
                                  <label class="text-left w-100 mb-0">Date Fin</label>
                                  <date-picker v-model="filtre.dateFin" required valueType="YYYY-MM-DD" input-class="form-control w-100" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                            </div>
                            <div class="mr-3 text-left" style="width: 230px">
                                <label class="text-left w-100 mb-0">Type Commande</label>
                                <select class="form-control" v-model="filtre.typeCmd">
                                    <option value="">Tout</option>
                                    <option v-for="type in typeCmd"  :value="type.id">{{type.typcmd}}</option>
                                </select>
                            </div>
                            <div class="mr-3 text-left" style="width: 230px">
                                <label class="text-left w-100 mb-0">Fournisseur</label>
                                <select class="form-control" v-model="filtre.fournisseur">
                                    <option value="">Tout</option>
                                    <option :value="four.id" v-for="four in listFournisseurs">{{four.fonmfo}}</option>
                                    
                                </select>
                            </div>
                            <div class="mr-3 text-left" style="width: 230px">
                                <label class="text-left w-100 mb-0">N°Dossier</label>
                                <input type="text" class="form-control"  v-model="filtre.dossier">
                            </div>
                             <div class="mr-3 text-left" style="width: 230px">
                                <label class="text-left w-100 mb-0">N°Commande</label>
                                <input type="text" class="form-control"  v-model="filtre.commande">
                            </div>
                            <div>
                               <button @click="search()" class="btn btn-success ml-3"> <span v-if="isloading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Rechercher</button>
                            </div>
                        </div>
                         
                    </div>  
                </div>
            </div>
          
        </div>
        

        <template v-if="showResult">

            <template v-if="!isDetail">      
                <div class="row mt-3">
                    <div class="col-sm-12 ">
                        <div class="d-flex justify-content-between align-content-center mb-2">
                                <div class="d-flex align-items-end">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <label class="text-nowrap mr-2 mb-0">Nbre de ligne par Page</label> 
                                        <select
                                            v-model="paginateHisto"
                                            class="form-control form-control-sm">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                </div>
                        
                            </div>
                           
                        </div> 
                        <div class="position-relative overflow-hidden">
                            <table class="table">
                                <thead class="thead-blue" :class="[isloading ? '' : 'hasborder']">
                                     <tr>
                                        <th class="p-2 border-right border-white h6">N° Dossier</th>
                                        <!--th class="p-2 border-right border-white h6">Date Début</th>
                                        <th class="p-2 border-right border-white h6">Date Fin</th-->

                                        <th class="p-2 border-right border-white h6">Type commande</th>
                                        <th class="p-2 border-right border-white h6">N°TC</th>
                                        <th class="p-2 border-right border-white h6">Type TC</th>
                                        <th class="p-2 border-right border-white h6">N°Plomb</th>
                                        <th class="p-2 border-right border-white h6">Nbre colis total</th>
                                        <th class="p-2 border-right border-white h6">Poids total</th>
                                        <th class="p-2 border-right border-white h6">Volume total</th-->
                                        <th class="p-2 border-right border-white h6">Etat</th>
                                        <th class="text-nowrap p-2 border-right border-white h6">Date</th>
                                        <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                        <th class="p-2 border-right border-white h6 text-right">Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="bgStripe preChargeTable" :class="[isloading ? 'loader-line' : '']"> 
                                    <template v-if="!result.data || !result.data.length">
                                        <tr><td colspan="14" class="bg-white text-center">Aucun résultat!</td></tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="res in result.data" :key="res.id" class="bg-white position-relative">
                                            <td>{{ res.reference }}</td>
                                            <!--td>{{ res.dateDebut }}</td>
                                            <td>{{ res.dateCloture }}</td-->
                                          
                                            <td class="p-2 align-middle">
                                                {{ res.typeCommande }}
                                            </td>
                                            <td class="p-2 align-middle">
                                                {{ res.numContenaire }}
                                            </td>
                                            <td class="p-2 align-middle">
                                               {{ res.typeContenaire }}
                                            </td>
                                            <td class="p-2 align-middle">
                                               {{ res.plomb }}
                                            </td>
                                            <td>{{ parseInt(res.total_colis) + parseInt(res.total_pallette) }}</td>
                                            <td>{{ res.total_poids }}</td>
                                            <td>{{ res.total_volume }}</td>
                                           
                                            <td>
                                                <span v-if="res.etat==1" class="badge badge-success">Validé</span>
                                                <span v-if="res.is_close==1" class="badge badge-secondary">Cloturé</span>
                                                <span v-if="res.etat==0" class="badge badge-warning">En cours</span>
                                            </td>
                                             <!--td>
                                                <div v-if="res.rapport_pdf!=null" class="d-flex align-items-center w-100 justify-content-center cursor-pointer"  @click="getpdf(res.rapport_pdf, res.id)">
                                                    <i class="fa fa-download mr-2" aria-hidden="true"></i>
                                                    <span class="badge badge-danger">PDF</span>
                                                </div>
                                                
                                            </td-->
                                            <td>{{ res.date }}</td>
                                            <td>{{ res.user }}</td>
                                             <td class="p-2 align-middle">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <div @click="detailsCommande(res)" class="d-flex cursor-pointer bg-primary position-relative rounded-circle boxAction justify-content-center align-items-center mr-2" title="Liste des commandes">
                                                    <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre">{{ res.nbrCmd }}</span> <i class="fa fa-list-ul" aria-hidden="true"></i>
                                                    </div>
                                                    <a v-if="res.etat==1" href="#" title="Rapport Empotage" class="boxAction btn btn-circle border-0 btn-circle-sm m-1 position-relative bg-danger"  @click="showInvoice(res)" data-toggle="modal" data-target="#openFacture">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div class="d-flex mt-4 justify-content-center paginationDossier">
                                <pagination
                                    :data="result"
                                    @pagination-change-page="search"
                                ></pagination>
                            
                            </div>
                        </div>
                    </div>
                    
                </div>
                </template>
            <template v-else> 
               <div class="d-flex justify-content-between align-items-center mt-3">
                    <button class="btn btn-warning mb-3" @click="reinit()">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour
                    </button>
                    <div class="d-flex">
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border">N°TC: <b>{{ selected.numtc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border mx-3">Type TC: <b>{{ selected.typetc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border">Plomb: <b>{{ selected.plomb }}</b></div>
                    </div>
                </div>
                                <table class="table">
                    <thead class="thead-blue borderorange">
                         <tr>
                            <th class="p-2 border-right border-white h6">N°CDE</th>
                            <th class="p-2 border-right border-white h6">N°FE</th>
                            <th class="p-2 border-right border-white h6">N°ECV</th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6">Poids</th>
                            <th class="text-right p-2 border-right border-white h6">Volume</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Date livraison</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Crée par</th>
                            <th class="p-2 border-right border-white text-left h6">Préchargé par le client?</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Douane</th>
                            <th class="text-right p-2 border-right border-white h6">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bgStripe">
                    <template v-if="!reception.data || !reception.data.length">
                        <tr><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                    </template>
                    <template v-else>
                        <tr v-for="dry in reception.data" :key="dry.reidre" class="bg-white">
                        <td class="p-2 align-middle">{{ dry.rencmd }}</td>
                        <td class="p-2 align-middle">{{ dry.refere }}</td>
                        <td class="p-2 align-middle">{{ dry.reecvr }}</td>
                        <td class="p-2 align-middle text-uppercase">{{ dry.fournisseurs }}</td>
                        <td class="p-2 align-middle">
                                <template v-if="dry.renbcl > 0">
                                        <label class="badge badge-secondary mr-1">{{dry.renbcl}} Colis</label>
                                  </template>
                                  <template v-if="dry.renbpl > 0">
                                     <label class="badge badge-info mr-1">{{dry.renbpl}} Pal</label>
                                  </template>
                        
                        </td>
                        <td class="p-2 align-middle text-right">{{ dry.repoid }}</td>
                        <td class="p-2 align-middle text-right">{{ dry.revolu }}</td>
                        
                        
                        <td class="p-2 align-middle"><i class="fa fa-calendar" aria-hidden="true"></i> {{ dry.redali }}</td>
                        <td class="p-2 align-middle text-nowrap"><i class="fa fa-user" aria-hidden="true"></i> {{ dry.user_created}}</td>
                        <td class="p-2 align-middle">
                            
                            <template v-if="dry.idPre > 0">
                                 <span class="badge badge-success">oui</span> {{dry.prechargeur}}
                            </template>
                            <template v-else>
                                  <span class="badge badge-warning">non</span>
                            </template>
                           
                        </td>
                         <td class="p-2 align-middle">
                             {{dry.douane}}
                         </td>
                        <td class="p-2 text-right">
                             <a title="Voir les détails" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <a href="#" title="Voir la facture" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-white" v-on:click="showFacture(dry.refasc)" data-toggle="modal" data-target="#openFacture">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i>
                            </a>
                            <!--div class="d-flex justify-content-end align-items-center">
                                <span v-if="dry.dossier_empotage_id > 0"><i class="fa fa-check-circle"></i></span>
                                <label class="switch">
                                    <template v-if="selected.etat==0">
                                        <input class="switch-input inputCmd" :checked="(selected.dossier == dry.dossier_id || dry.dossier_empotage_id > 0)" type="checkbox" :value="dry.reidre" v-on:change="empoter($event,dry)" /> 
                                        <span class="switch-label" data-on="Choisie" data-off="Choisir"></span> 
                                        <span class="switch-handle"></span> 
                                    </template>
                                    <template v-else>
                                        <input class="switch-input inputCmd" :checked="(selected.identifiant == dry.dossier_empotage_id)" type="checkbox" :value="dry.reidre" v-on:change="empoter($event,dry)" /> 
                                        <span class="switch-label" data-on="Choisie" data-off="Choisir"></span> 
                                        <span class="switch-handle"></span> 
                                    </template>
                                    
                                </label>
                            </div-->
                        </td>
                    </tr>
                    </template>

                    </tbody>
                </table>
                <div class="d-flex mt-4 justify-content-center">
                    <pagination
                        :data="reception"
                        @pagination-change-page="gerReception"
                    ></pagination>
                    
                </div>
                <hr>
            </template>
        </template>
        <!-- Modal Facture-->
        <div class="modal fade" id="openFacture" tabindex="-1" role="dialog" aria-labelledby="myModalFacture"
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
                         <template v-if="pdfFileModal != null">
                            <embed :src="'/pdf/empotage/'+pdfFileModal" frameborder="0" width="100%" height="450px">
                          </template>
                         <template v-if="pdfFileModal != null && pdfFile != null"> Auncun fichier </template>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalPdf()" class="btn btn-warning">Fermer</button>
                  </div>
             </div>
            
          </div>
        </div>
        <modalDetailsCommande></modalDetailsCommande>
     
    </div>
</template>

<script>

import { EventBus } from '../../event-bus';

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import VueTimepicker from 'vue2-timepicker';
import 'vue2-timepicker/dist/VueTimepicker.css'; 
import { PdfMakeWrapper, Table } from 'pdfmake-wrapper';

import modalDetailsCommande from '../../components/modal/detailsCommande.vue';

export default { 
    props: [
            'listFournisseurs',
            'typeCmd',
            'clientCurrent',
            'listEntrepots'
    ],
     components: {
        modalDetailsCommande
    },
    data() {
        return {

            filtre: {
                dateDebut: new Date(new Date().getTime() - 168*60*60*1000).toISOString().slice(0,10), // 1 semaine 24 * 7 = 148
                dateFin: new Date().toISOString().slice(0,10),
                typeCmd: '',
                fournisseur: '',
                dossier: '',
                commande: ''
            },
            isloading: false,
            paginate: 5,
            paginateRecep: 5,
            paginateHisto: 10,
            result: {},
            reception: {},
            showResult: false,
            selected: {
                numtc: '',
                typetc: '',
                plomb: '',
                identifiant: '',
                typeCmd: '',
                dossier: ''
            },
            isDetail: false,
            pdfFileModal: null,
            pdfFile: null
        };
    },
    watch: {
        paginateHisto: function() {
            this.search();
        }
    },
    methods: {
        disabledFutureDate(date) {
          const today = new Date();
          today.setHours(0, 0, 0, 0);

          return date > today;
        },
        search(page=1){
            this.showResult = true;
            this.isloading = true;
            this.reinit();
            axios.post('/search/histoEmpotage/'+this.clientCurrent.id,{
                page: page,
                paginate: this.paginate,
                filtre: this.filtre
            }).then(response => {
                this.result = response.data;
                var self = this;
                setTimeout(function(){
                    self.isloading = false;
                },500);
                
            });

        },
        getpdf(pdf, namefile){
           
            const linkSource = `data:application/pdf;base64,${pdf}`;
            const downloadLink = document.createElement("a");
            const fileName = "rapport_"+namefile+".pdf";
            downloadLink.href = linkSource;
            downloadLink.download = fileName;
            downloadLink.click();
        },
        detailsCommande(empotage){
            this.isDetail=true;
            this.selected.numtc=empotage.numContenaire;
            this.selected.typetc=empotage.typeContenaire;
            this.selected.plomb=empotage.plomb;
            this.selected.identifiant = empotage.id;
            this.selected.typeCmd    = empotage.typeCommandeID;
            this.selected.dossier = empotage.reference;

            this.gerReception();
            
        },
        gerReception(page = 1){
            // get commandes
          
            axios.get('/histoEmpotage/reception/'+this.clientCurrent.id+"/"+this.selected.typeCmd+'?page=' + page + "&paginate=" + this.paginateRecep+"&ref="+this.selected.dossier+"&id_empotage="+this.selected.identifiant+"&filtre_four="+this.filtre.fournisseur).then(response => {
                this.reception = response.data;
                console.log(this.reception, "reception");
            });
        },
        reinit(){
            this.isDetail=false;
            this.selected.numtc='';
            this.selected.typetc='';
            this.selected.plomb='';
        },
        showInvoice(pre){
            this.pdfFileModal = null;
            this.pdfFile = null;

            var labelCmd = pre.typeCommande.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
            this.pdfFileModal = 'dossier-'+pre.reference+'_'+labelCmd+'_numtc-'+pre.numContenaire+'_plomb-'+pre.plomb+".pdf";
       },
       closeModalPdf(){
            this.$refs.closePoupPdf.click();
        },
        showFacture(file){
            this.pdfFileModal = null;
            this.pdfFile = null;    
            if(file!=''){
                this.pdfFile = file;
            }
        },
        showModal(dry){ 
    
            EventBus.$emit('VIEW_CMD', {
                openView: true,
                dry: dry,
                fournisseur: this.listFournisseurs,
                typeCommande: this.typeCmd,
                entrepot: this.listEntrepots,
                idClient: this.clientCurrent.id
            });

        }
    },
    mounted() {
      this.search();
    }
   
};
</script>
