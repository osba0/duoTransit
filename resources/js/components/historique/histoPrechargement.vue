 <template>
    <div>
        <div class="row justify-content-center histoform">
            <div class="col-md-12">
                <form v-on:submit.prevent="search">
                <div class="d-inline-block">
                    <div class="border p-3 bg-white justify-content-center rounded  text-center">
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
                                <label class="text-left w-100 mb-0">N°Commande</label>
                                <input type="text" class="form-control"  v-model="filtre.commande">
                            </div>
                            <div>
                               <button type="submit" class="btn btn-success ml-3"> <span v-if="isloading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Rechercher</button>
                            </div>
                        </div>
                         
                    </div> 
                </div> 
            </form>
            </div>
          
        </div>
        

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
                                        <th class="p-2 border-right border-white h6">ID</th>
                                        <th class="p-2 border-right border-white h6">Nbre colis total</th>
                                        <th class="p-2 border-right border-white h6">Poids total (KG)</th>
                                        <th class="p-2 border-right border-white h6">Volume total (m<sup>3</sup>)</th>
                                        <th class="p-2 border-right border-white h6">Contenaire</th>
                                        <th class="text-nowrap p-2 border-right border-white h6">Date</th>
                                        <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                        <th class="p-2 border-right border-white h6">Etat</th>
                                        <th class="p-2 border-right border-white h6 text-right">Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="bgStripe preChargeTable" :class="[isloading ? 'loader-line' : '']"> 
                                    <template v-if="!result.data || !result.data.length">
                                        <tr><td colspan="14" class="bg-white text-center">Aucun résultat!</td></tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="res in result.data" :key="res.id" class="bg-white position-relative">
                                            <td class="p-2 align-middle position-relative">
                                                <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': res.typeCmd_color} : {'background': '#ccc'}]"></div>
                                                {{ res.id }}
                                            </td>                    
                                            <td class="p-2 align-middle">{{ parseInt(res.total_colis) + parseInt(res.total_pallette) }}</td>
                                            <td class="p-2 align-middle">{{ res.total_poids }}</td>
                                            <td class="p-2 align-middle">{{ res.total_volume }}</td>
                                            <td class="p-2 align-middle">
                                                {{ res.nbre_contenaire }} ({{ res.contenaire }})
                                            </td>
                                            <td class="p-2 align-middle">{{ res.updated_at }}</td>
                                            <td class="p-2 align-middle">{{ res.user }}</td>
                                            <td>
                                                <span v-if="res.totalEmpote == res.nbrCmd " class="badge badge-success">Cloturé</span>
                                               <span v-if="res.totalEmpote < res.nbrCmd " class="badge badge-warning">En cours</span>
                                            </td>
                                            <td class="p-2 align-middle">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <div @click="detailsCommande(res)" class="d-flex cursor-pointer bg-primary position-relative rounded-circle boxAction justify-content-center align-items-center mr-2" title="Liste des commandes">
                                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre">{{ res.nbrCmd > 9 ? '+9' : res.nbrCmd }}</span> 
                                                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                                                    </div>
                                                    <a v-if="res.etat==1" href="#" title="Rapport Préchargement" class="boxAction btn btn-circle border-0 btn-circle-sm m-1 position-relative bg-danger"  @click="showInvoice(res)" data-toggle="modal" data-target="#openFacture">
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
                    <button class="btn btn-primary mb-3" @click="reinit()">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour
                    </button>
                    <!--div class="d-flex">
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border">N°TC: <b>{{ selected.numtc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border mx-3">Type TC: <b>{{ selected.typetc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border">Plomb: <b>{{ selected.plomb }}</b></div>
                    </div-->
                </div>
                   
                 <div class="mb-2 float-left w-100 mt-3"><typeproduit></typeproduit></div>
                    <table class="table">
                    <thead class="thead-blue borderorange">
                         <tr>
                            <th class="p-2 border-right border-white h6">N°CDE</th>
                            <th class="p-2 border-right border-white h6">N°FE</th>
                            <th class="p-2 border-right border-white h6">N°ECV</th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6">Poids(KG)</th>
                            <th class="text-right p-2 border-right border-white h6">Volume (m<sup>3</sup>)</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Date livraison</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Crée par</th>
                            <th class="p-2 border-right border-white text-left h6">Utilisateur</th>
                            <th class="p-2 border-right border-white text-left h6">Etat</th>
                            <th class="text-nowrap p-2 border-right border-white h6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bgStripe">
                    <template v-if="!reception.data || !reception.data.length"> 
                        <tr><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                    </template>
                    <template v-else>
                        <tr v-for="dry in reception.data" :key="dry.reidre" class="bg-white">
                        <td class="p-2 align-middle position-relative"> <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': dry.typeCmd_color} : {'background': '#ccc'}]"></div> 
                            <label class="numCmd badge w-100" :class="getTypeProduit(dry.typeproduit)">
                                {{ dry.rencmd }}
                            </label></td>
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
                            {{dry.prechargeur}}
                        </td>
                        <td>
                            <div  v-if="dry.dossier_id > 0">
                                <template v-if="dry.douane != '' && dry.douane != null">
                                    <span class="badge badge-success">Empotée</span>
                                </template>
                                <template v-else> <span class="badge badge-primary">Préchargée</span></template>
                            
                            </div>
                            <span  class="badge badge-warning" v-else>En attente</span>
                        </td>
                         <td class="p-2 align-middle text-right">
                            <button title="Voir les détails" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white position-relative" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                   <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                            </button>
                            <!--button title="Voir la facture" :disabled="dry.refasc === null || dry.refasc === ''" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-white" v-on:click="showFacture(dry.refasc)" data-toggle="modal" data-target="#openFacture">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i>
                            </button-->
                            <button :disabled="dry.refasc === null || dry.refasc === ''" title="Voir la facture" class="btn btn-circle btnAction border btn-circle-sm mx-1 position-relative bg-white" v-on:click="showFacture(dry)" data-toggle="modal" data-target="#openFacture">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <!--i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i-->
                                <span :class="{ 'bg-light2': getCountFacture(dry.refasc) == 0, 'bg-green2' : getCountFacture(dry.refasc) > 0}" class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{ getCountFacture(dry.refasc) > 9 ? '+9' : getCountFacture(dry.refasc) }}</span>
                            </button>
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
     <modalFacture></modalFacture>   
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
import modalFacture from '../../components/modal/facture.vue';
import typeproduit from '../../components/modal/typeproduit.vue';

export default { 
    props: [
            'listFournisseurs',
            'typeCmd',
            'clientCurrent',
            'listEntrepots',
            'idEntite'
    ],
    components: {
        modalDetailsCommande,
        modalFacture,
        typeproduit
    },
    data() {
        return {

            filtre: {
                dateDebut: new Date(new Date().getTime() - 168*60*60*1000).toISOString().slice(0,10), // 1 semaine 24 * 7 = 148
                dateFin: new Date().toISOString().slice(0,10),
                typeCmd: '',
                fournisseur: '',
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
                identifiant: '',
                typeCmd: '',
                dossier: ''
            },
            isDetail: false,
            pdfFileModal: null,
            pdfFile: null,
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
            axios.post('/search/histoPrechargement/'+this.clientCurrent.id+'/'+this.idEntite,{
                page: page,
                paginate: this.paginateHisto,
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
            this.selected.identifiant = empotage.id;
            this.selected.typeCmd    = empotage.typecmdID;
           
            this.getReception();
            
        },
        getReception(page = 1){
            // get commandes
          
            axios.get('/histoPrechargement/reception/'+this.clientCurrent.id+"/"+this.selected.typeCmd+'?page=' + page + "&paginate=" + this.paginateRecep+"&id_pre="+this.selected.identifiant+"&filtre_four="+this.filtre.fournisseur+"&filtre_cmd="+this.filtre.commande).then(response => {
                this.reception = response.data;
                 // get Ŝpecification

                EventBus.$emit('SET_PRODUIT_SPECIFIK', { 
                    prd: this.reception.data
                });
                console.log(this.reception, "reception");
            });
        },
        reinit(){
            this.isDetail=false;
        },
        showInvoice(pre){
            this.pdfFileModal = null;
            this.pdfFile = null;
            var labelCmd = pre.typecmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
        
            this.pdfFileModal = 'prechargement-'+pre.id+'_'+labelCmd+".pdf";
            console.log(this.pdfFileModal);
       },
       closeModalPdf(){
             this.$refs.closePoupPdf.click();
        },
       showFacture(fact){
                 EventBus.$emit('VIEW_FACT', { 
                    listeFacture: fact.refasc,
                    idReception: fact.reidre,
                    can_modify: false
                }); 
            },
        showModal(dry){ 

            EventBus.$emit('VIEW_CMD', {
                openView: true,
                dry: dry,
                fournisseur: this.listFournisseurs,
                typeCommande: this.typeCmd,
                entrepot: this.listEntrepots,
                idClient: this.clientCurrent.id,
                canDeleteIncident: false
            });

        },
        getCountFacture(doc){
            if(Array.isArray(doc)){
                return doc.length;
            }
            return 0;
        },
        getTypeProduit(produit){
                   switch(produit){
                    case 'DEAE': return 'deae text-white'; break;
                    case 'Précurseur de drogue': return 'precurseur_drogue text-white'; break;
                    case 'Psychotrope': return 'psychotrope text-white'; break;
                    case 'Dangereux': return 'dangereux text-white'; break;
                    case 'Autre': return 'autre text-white'; break;
                    default: return 'border border-width-2 border-primary'; 
                }
            },
    },
    mounted() {
      this.search();
    }
   
};
</script>
