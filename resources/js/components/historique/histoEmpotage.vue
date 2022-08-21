 <template>
    <div>
        <template v-if="!isDetail"> 
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
        </template>
        

        <template v-if="showResult">

            <template v-if="!isDetail">     
                <div class="row d-flex align-items-center justify-content-between mb-3">
                    <ul class="legend mt-4 mb-2 pl-3 flex-1">
                        <li v-for="type in typeCmd" class="d-flex align-items-center">
                            <span class="etat_T m-0 mr-1 border-0" :style="{'background': type.tcolor}"></span> 
                            <label class="m-0 mr-2">{{type.typcmd}}</label>
                        </li>
                    </ul>  
                </div> 
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
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[0])">
                                            
                                            N° Dossier <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                        <th class="p-2 border-right border-white h6">N°TC</th>
                                        <th class="p-2 border-right border-white h6">Type TC</th>
                                        <th class="p-2 border-right border-white h6">N°Plomb</th>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[3])">Nbre colis total <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[1])">Poids total <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[2])">Volume total <i class="fa fa-sort" aria-hidden="true" ></i></th>
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
                                            <td class="position-relative"> <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': res.typeCmd_color} : {'background': '#ccc'}]"></div> {{ res.reference }}</td>
                                            <td class="p-2 align-middle">
                                                {{ res.numContenaire }}
                                            </td>
                                            <td class="p-2 align-middle">
                                               {{ res.typeContenaire }}
                                            </td>
                                            <td class="p-2 align-middle">
                                               {{ res.plomb }}
                                            </td>
                                            <td>{{ res.colis_total }}</td>
                                            <td>{{ res.total_poids }}</td>
                                            <td>{{ res.total_volume }}</td>
                                           
                                            <td>
                                                <!--span v-if="res.etat==1" class="badge badge-success">Validé</span-->
                                                <span v-if="res.is_close==1" class="badge badge-primary">Cloturé</span>
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
                        <!--div class="h5 mb-0 rounded bg-white py-2 px-3 border">N°TC: <b>{{ selected.numtc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border mx-3">Type TC: <b>{{ selected.typetc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border">Plomb: <b>{{ selected.plomb }}</b></div-->
                    </div>
                </div>


                <div class="mb-3 mb-3">
                
                    <table class="table table-bordered bg-white"> 
                        <tr>
                            <th class="text-uppercase thead-blue py-1 w-60">Dossier selectionné 
                                <span class="py-0 px-2 rounded text-lowercase bg-warning" v-if="selected.etat==0">En cours</span>
                            <span class="py-0 px-2 rounded text-lowercase bg-success" v-if="selected.etat==1">Validé</span></th>
                        </tr>
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex justify-content-between detailPrecharge position-relative">
                                    <ul class="w-100 legend m-0 p-0 position-absolute" style="top:-10px">
                                        <li class="w-100 text-center font-weight-bold">
                                            <span class="float-none d-inline-block etat_T m-0 mr-1 border-0" :style="{'background': getColorTypeCmd(selected.typeCmd)}"></span> 
                                            {{ selected.typeCommande }}
                                        </li>
                                    </ul>
                                    <table class="table m-0 mt-3 table-striped">
                                        <tbody> 
                                             <tr>
                                                <th>N°Dossier: <b>{{ selected.dossier }}</b></th>
                                                <th>Nombre de commande: <b>{{ selected.nbrcmd }}</b></th> 
                                                <th>N°TC: <b>{{ selected.numtc }}</b></th>
                                                <th>Type TC: <b>{{ selected.typetc }}</b></th>
                                                <th>Plomb: <b>{{ selected.plomb }}</b></th>
                                            </tr>
                                      
                                        </tbody>

                                    </table>
                                </div>
                             
                            </td>
                        </tr>
                    </table>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-content-center mb-2">
                    <div class="d-flex align-items-end">
                        <div>
                            <div class="d-flex align-items-center">
                                <label for="paginateRecep" class="text-nowrap mr-2 mb-0"
                                    >Nbre de ligne par Page</label> 
                                <select
                                    v-model="paginateRecep"
                                    class="form-control form-control-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
                        </div>
                
                    </div>
                    
                    <div class="d-flex px-0 align-items-end col-md-4">
                        <input
                            v-model.lazy="searchRecep"
                            type="search"
                            class="form-control"
                            placeholder="Rechercher par n°cmd, n°fe, n°ecv,n°fact, utilisateur, fournisseur..."
                        />
                    </div>
                   
                </div> 
                <table class="table">
                    <thead class="thead-blue hasborder">
                         <tr>
                            <th class="p-2 border-right border-white h6" v-on:click="sortByColumn(columns[0])">N°CDE 
                                <i class="fa fa-sort" aria-hidden="true" ></i>
                            </th>
                            <th class="p-2 border-right border-white h6">N°FE</th>
                            <th class="p-2 border-right border-white h6">N°ECV</th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6">Poids</th>
                            <th class="text-right p-2 border-right border-white h6">Volume</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Date livraison</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Crée par?</th>
                            <th class="p-2 border-right border-white text-left h6">Validé par?</th>
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
                            <i class="fa fa-user" aria-hidden="true"></i>
                            {{dry.prechargeur}}
                           
                        </td>
                         <td class="p-2 align-middle">
                             {{dry.douane}}
                         </td>
                        <td class="p-2 text-right">
                             <a title="Voir les détails" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white position-relative" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                 <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                            </a>
                            <a href="#" title="Voir la facture" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-white" v-on:click="showFacture(dry.refasc)" data-toggle="modal" data-target="#openFacture">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    </template>

                    </tbody>
                </table>
                <div class="d-flex mt-4 justify-content-center">
                    <pagination
                        :data="reception"
                        @pagination-change-page="getReception"
                    ></pagination>
                    
                </div>
                <hr>
            </template>
        </template>
        <!-- Modal Facture-->
        <div class="modal fade fullscreenModal" id="openFacture" tabindex="-1" role="dialog" aria-labelledby="myModalFacture"
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
            paginate: 10,
            paginateRecep: 10,
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
                dossier: '',
                nbrcmd: '',
                typeCommande: ''
            },
            isDetail: false,
            pdfFileModal: null,
            pdfFile: null,
            searchRecep: '',
            // Sort column
            columnsSearch: ['reference', 'total_poids', 'total_volume', 'colis_total'],
            sortedColumnSearch: '',
            orderSearch: 'asc',
            // Sort column Reception
            columns: ['rencmd', 'refere', 'reecvr', 'renufa', 'redali', 'repoid', 'revolu'],
            sortedColumn: '',
            order: 'asc'
        };
    },
    watch: {
        paginateHisto: function() {
            this.search();
        },
        orderSearch: function(value) {
            this.search();
        },
        paginateRecep: function(){
             this.getReception();
        },
        searchRecep: function(value) {
            this.getReception();
        },
        order: function(value) {
            this.getReception();
       }
        
    },
    methods: {
        sortByColumn(column) {
            this.sortedColumn = column;
            this.order = (this.order === 'asc') ? 'desc' : 'asc';
        },
        sortByColumnSearch(column) {
            this.sortedColumnSearch = column;
            this.orderSearch = (this.orderSearch === 'asc') ? 'desc' : 'asc';
        },
        disabledFutureDate(date) {
          const today = new Date();
          today.setHours(0, 0, 0, 0);

          return date > today;
        },
        search(page=1){
            this.showResult = true;
            this.isloading = true;
            this.reinit();
            axios.post('/search/histoEmpotage/'+this.clientCurrent.id+"?column="+this.sortedColumnSearch+"&order="+this.orderSearch,{
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
        getColorTypeCmd(id){
             for(var i=0; i<this.typeCmd.length;i++){
                if(this.typeCmd[i].id === id){
                    return this.typeCmd[i].tcolor;
                }
            }
            return "#aaa";
        },
        detailsCommande(empotage){
            this.isDetail=true;
            this.selected.numtc=empotage.numContenaire;
            this.selected.typetc=empotage.typeContenaire;
            this.selected.plomb=empotage.plomb;
            this.selected.identifiant = empotage.id;
            this.selected.typeCmd    = empotage.typeCommandeID;
            this.selected.dossier = empotage.reference;
            this.selected.nbrcmd = empotage.nbrCmd;
            this.selected.typeCommande = empotage.typeCommande;

            this.getReception();
            
        },
        getReception(page = 1){
            // get commandes
          
            axios.get('/histoEmpotage/reception/'+this.clientCurrent.id+"/"+this.selected.typeCmd+'?page=' + page + "&paginate=" + this.paginateRecep+"&ref="+this.selected.dossier+"&id_empotage="+this.selected.identifiant+"&filtre_four="+this.filtre.fournisseur+"&keysearch="+this.searchRecep+"&column="+this.sortedColumn+"&order="+this.order).then(response => {
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
