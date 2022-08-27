<template>
    <div>
        <div class="row">
          <div class="col-sm-6">
               <h2 class="d-inline-block">Préchargement <template v-if="isDetail">:</template></h2>
               <template v-if="isDetail">
                   <span class="pl-2 h3 text-primary font-weight-bold"> N° Dossier {{ selected.id }}&nbsp;</span>
                </template>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active"><a href="#">Préchargement</a></li>
            </ol>
          </div>
        </div>
        <template v-if="!isDetail">
        <div class="row">
            <div class="col-sm-12">
                    <div class="table-responsive p-0">
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <ul class="legend mt-0 mb-0 pl-0 flex-1">
                                <li v-for="type in typeCmd" class="d-flex align-items-center">
                                    <span class="etat_T m-0 mr-1 border-0" :style="{'background': type.tcolor}"></span> 
                                    <label class="m-0 mr-2">{{type.typcmd}}</label>
                                    <label class="m-0 mr-2">({{ getNbreCmd(type.id) }})</label>
                                </li>
                            </ul>
                            <div>
                                <form @submit.prevent="save" enctype="multipart/form-data"  key=1 class="d-flex">
                                    <select class="form-control mr-2" v-model="typeCommande" :class="{ 'border-danger': submitted_add && !$v.typeCommande.required }">
                                        <option value="">Type commande</option>
                                        <option v-for="type in typeCmd"  :value="type.id">{{type.typcmd}}</option>
                                  
                                    </select>
                                    <select class="form-control mr-2" v-model="entrepot" :class="{ 'border-danger': submitted_add && !$v.entrepot.required }">
                                        <option value="">Choisir l'entrepôt</option>
                                        <option v-for="entrepot in listEntrepots"  :value="entrepot.id">{{entrepot.nomEntrepot}}</option>
                                  
                                    </select>
                                
                                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" :class="{'d-none': !submitted_add, 'd-inline-block': submitted_add && !$v.typeCommande.required}"></span>Créer</button>
                                </form>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-content-center mb-2">
                            <div class="d-flex align-items-end">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                                            >Nbre de ligne par Page</label> 
                                        <select
                                            v-model="paginate"
                                            class="form-control form-control-sm">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                </div>
                        
                            </div>
                            <div class="d-flex align-items-end">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <label class="text-nowrap mr-2 mb-0"
                                            >Type Commande</label> 
                                        <select
                                            v-model="selectedTypeCmd"
                                            class="form-control form-control-sm">
                                            <option value="">Afficher tout</option>
                                            <option :value="type.id" v-for="type in typeCmd">{{type.typcmd}}</option>
                                            
                                        </select>
                                    </div>
                                </div>
                        
                            </div>
                            <div class="d-flex align-items-end">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <label class="text-nowrap mr-2 mb-0"
                                            >Etat</label> 
                                        <select
                                            v-model="etatFiltre"
                                            class="form-control form-control-sm">
                                            <!-option value="">Afficher tout</option-->
                                            <option value="1">Validé</option>
                                            <option value="0">En cours</option>
                                            
                                        </select>
                                    </div>
                                </div>
                        
                            </div>
                            <div class="pr-0 col-md-4">
                                <input
                                    v-model.lazy="searchPre"
                                    type="search"
                                    class="form-control"
                                    placeholder="Rechercher par n°préchargement"
                                />
                            </div>
                        </div>
                        <div class="position-relative overflow-hidden">
                            <table class="table">
                                <thead class="thead-blue" :class="[isLoading ? '' : 'hasborder']">
                                     <tr>
                                        <th class="p-2 border-right border-white h6">ID</th>
                                        <th class="p-2 border-right border-white h6">Nbre commandes</th>
                                        <th class="p-2 border-right border-white h6">Nbre colis total</th>
                                        <th class="p-2 border-right border-white h6">Poids total</th>
                                        <th class="p-2 border-right border-white h6">Volume total</th>
                                        <th class="p-2 border-right border-white h6">Entrepôt</th>
                                        <th class="p-2 border-right border-white h6">Etat</th>
                                        <th class="text-nowrap p-2 border-right border-white h6">Date</th>
                                        <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                        
                                        <th class="text-nowrap p-2 border-right border-white h6 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bgStripe preChargeTable" :class="[isLoading ? 'loader-line' : '']"> 
                                    <template v-if="!prechargement.data || !prechargement.data.length">
                                        <tr><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="pre in prechargement.data" :key="prechargement.id" class="bg-white position-relative" :class="[{ 'rowActif': pre.id == selected.id, 'rowInactif':pre.id != selected.id }]">
                                            <td class="position-relative p-2 align-middle">
                                                <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': pre.typeCmd_color} : {'background': '#ccc'}]"></div>
                                            {{ pre.id }}</td>
                                            <td class="p-2 align-middle">{{ pre.nbrCmd }}</td>
                                            <td class="p-2 align-middle">{{ pre.total_colis }} {{pre.total_palette}}</td>
                                            <td class="p-2 align-middle">{{ pre.total_poids }}</td>
                                            <td class="p-2 align-middle">{{ pre.total_volume }}</td>
                                            <td class="p-2 align-middle">{{ pre.entrepot }}</td>
                                            <!--td>{{ pre.typecmd }}</td-->
                                            <td class="p-2 align-middle">
                                                <template v-if="pre.etat==1">
                                                    <span class="badge badge-success">Validé</span>
                                                </template>
                                                
                                                <span v-if="pre.etat==0" class="badge badge-warning">En cours</span>
                                            </td>
                                            <td class="p-2 align-middle">{{ pre.created_at }}</td>
                                            <td class="p-2 align-middle">{{ pre.user }}</td>
                                            <td class="p-2 align-middle">
                                                <div class="w-100 text-right">
                                                    <a v-if="pre.etat==1" href="#" title="Voir la facture" class="btn btn-circle border-0 btn-circle-sm m-1 position-relative bg-danger"  @click="showInvoice(pre)" data-toggle="modal" data-target="#openFacture">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    </a>
                                                    <button @click="showDossier(pre)" class="btn btn-info btn-sm" v-if="pre.etat==0">Ouvrir</button>
                                                     <a v-if="pre.etat==0" title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1 bg-white" v-on:click="deletePre(pre)">
                                                        <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                                    </a>
                                                
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div class="d-flex mt-4 justify-content-center">
                            <pagination
                                :data="prechargement"
                                @pagination-change-page="getPrechargement"
                            ></pagination>
                        </div>
                    </div>
                    </div>
              </div>
        </div>
       </template>
        
       <template v-else>
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-warning mb-3" @click="back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour
                </button>
            </div>
            
            <div class="mb-3 mb-4">
                <table class="table table-bordered bg-white"> 
                    <tr>
                        <th class="text-uppercase thead-blue py-1 w-60">Dossier selectionné</th>
                        <th class="text-uppercase thead-blue py-1">Etat Contenaire</th>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex justify-content-between detailPrecharge position-relative">
                                <ul class="w-100 legend m-0 p-0 position-absolute" style="top:-10px">
                                <li class="w-100 text-center font-weight-bold">
                                    <span class="float-none d-inline-block etat_T m-0 mr-1 border-0" :style="{'background': getColorTypeCmd(selected.typeCommandeID)}"></span> 
                                    {{ selected.typeCmd }}
                                </li>
                            </ul>
                                <table class="table m-0 mt-3 table-striped">
                                    <tbody> 
                                         <tr>
                                            <th>Nb CDE: {{ format_nbr(selected.nbrCmd) }}</th>
                                            <th>Nb colis: {{ format_nbr(selected.nbrColis) }}</th>
                                            <th>Poids: {{ format_nbr(selected.poids) }} KG</th>
                                            <th>Volume: {{ format_nbr(selected.volume) }} m<sup>3</sup></th>
                                            <th>Mnt fact: {{ format_nbr(selected.mntFact) }} &euro;</th>

                                        </tr>
                                  
                                    </tbody>

                                </table>
                            </div>
                         
                        </td>
                        <td class="pt-1 pb-4">
                            <div class="d-flex m-0 customRadio justify-content-center">


                                <p class="pr-3 m-0" v-for="contenaire in listContenaire" >

                                    <input type="radio" :value="contenaire.volume" v-model="capacite" @change="switchContenaire(contenaire.id, contenaire.volume)" :id="'cont_'+contenaire.volume" name="radio-group">
                                    <label :for="'cont_'+contenaire.volume"><span v-if="defaultContenaire.id==contenaire.id">{{ nbrContenaire }}</span> x {{contenaire.nom}}</label>
                                </p>
            
                            </div>
                            
                            <div class="conteneurState">
                               <div id="currentState" v-bind:style="{left: volumePercent+'%'}"></div> 
                                <div class="valuePercent" v-bind:style="{left: volumePercent+'%'}">{{volumePercent}}%</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="mb-2 w-100 d-flex justify-content-end align-items-end">
                <div class="mr-3">
                     <button class="btn btn-lg btn-primary" :disabled = "selected.etat == 1" v-on:click="valider()"><i class="fa fa-check"></i> Valider</button>
                </div>
            </div>
            
          
            <div class="d-flex justify-content-between align-content-center mb-2">
                <div class="d-flex align-items-end">
                    <div>
                        <div class="d-flex align-items-center">
                            <label for="paginate" class="text-nowrap mr-2 mb-0"
                                >Nbre de ligne par Page</label> 
                            <select
                                v-model="paginate"
                                class="form-control form-control-sm">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                            </select>
                        </div>
                    </div>
            
                </div>
                <div class="d-flex align-items-center">
                    <label class="text-nowrap mr-2 mb-0"
                            >Filtre par priorité</label> 
                    <select v-model="filtreRate" class="form-control form-control-sm">
                        <option value="">Afficher tout</option>
                        <option value="1">Pas urgente</option>
                        <option value="2">Normale</option>
                        <option value="3">Urgente</option>
                    </select>
                </div>
                <div class="d-flex px-0 align-items-end col-md-4">
                    <input
                        v-model.lazy="search"
                        type="search"
                        class="form-control"
                        placeholder="Rechercher par n°cmd, n°fe, n°ecv,n°fact, utilisateur, fournisseur..."
                    />
                </div>
               
            </div>        
                <div class="card-body table-responsive p-0">
                    <table class="table">
                        <thead class="thead-blue">
                             <tr>
                                <th class="p-2 border-right border-white h6">#</th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[0])">N°CDE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[1])">N°FE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[2])">N°ECV <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6">Fournisseur</th>
                                <th class="p-2 border-right border-white h6">Emballage</th>
                                <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[5])">Poids (KG) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[6])">Volume  (m<sup>3</sup>) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6  white-space-nowrap">Num Fact</th>
                                <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[4])">Date livraison <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                <th class="p-2 border-right text-center border-white h6">Priorité</th>
                                <th class="text-right p-2 border-right border-white h6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bgStripe">
                <template v-if="!reception.data || !reception.data.length">
                    <tr><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                </template>
                <template v-else>
                    <tr v-for="dry in reception.data" :key="dry.reidre" class="bg-white"  v-bind:style="[dry.dossier_id > 0 ? {'opacity': 0.3} : {'opacity': '1'}]">
                    
                    <td class="p-2 align-middle position-relative">
                        <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': dry.typeCmd_color} : {'background': '#ccc'}]"></div>
                        {{ dry.reidre }}
                    </td>
                    <td class="p-2 align-middle">
                         <!--label class="badge badge-primary mr-1 numCmdLab w-100">{{ dry.rencmd }}</label-->
                         {{ dry.rencmd }}
                    </td> 
                    <td class="p-2 align-middle">{{ dry.refere }}</td>
                    <td class="p-2 align-middle">{{ dry.reecvr }}</td>
                    <td class="p-2 align-middle text-uppercase">{{ dry.fournisseurs }}</td>
                    <td class="p-2 align-middle white-space-nowrap">
                            <template v-if="dry.renbcl > 0">
                                    <label class="badge badge-secondary mr-1">{{dry.renbcl}} Colis</label>
                              </template>
                              <template v-if="dry.renbpl > 0">
                                 <label class="badge badge-info mr-1">{{dry.renbpl}} Pal</label>
                              </template>
                    
                    </td>
                    <td class="p-2 align-middle text-right">{{ dry.repoid }}</td>
                    <td class="p-2 align-middle text-right">{{ dry.revolu }}</td>
                    
                    <td class="p-2 align-middle text-right">{{ dry.renufa }}</td>
                    <td class="p-2 align-middle"><i class="fa fa-calendar" aria-hidden="true"></i> {{ dry.redali }}</td>
                    <td class="p-2 align-middle text-nowrap"><i class="fa fa-user" aria-hidden="true"></i> {{ dry.reuser}}</td>
                    <td class="p-2 align-middle rateCenter position-relative" @click="setID(dry.reidre)">

                        <rate :id="'recep_'+dry.reidre" :length="3" :value="dry.priorite" :ratedesc="['Pas urgente', 'Normale', 'Urgente']" @after-rate="onAfterRate" :readonly="false" :disabled="false" />

                    </td>
                    <td class="p-2 text-right">

                        <div class="d-flex align-items-center justify-content-end">

                            <a title="Voir les détails" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white position-relative" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                 <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                            </a>

                            <button :disabled="dry.refasc === null || dry.refasc === ''" title="Voir la facture" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-white" v-on:click="showFacture(dry.refasc)">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i>
                            </button>
                            
                            <label class="switch mr-0"  v-bind:style="[dry.dossier_id > 0 || selected.etat == 1 ? {'opacity': 0.5} : {'opacity': '1'}]">
                                <input :disabled="dry.dossier_id > 0 || selected.etat == 1" class="switch-input inputCmd" :checked="selected.id == dry.idPre" type="checkbox" :value="dry.reidre" v-on:change="preselectionner($event,dry)" /> 
                                <span class="switch-label" data-on="OK" data-off="Choisir"></span> 
                                <span class="switch-handle"></span> 
                            </label>
                        </div>
                    </td>
                </tr>
                </template>

                </tbody>
                </table>
                </div>
            <div class="d-flex mt-4 justify-content-center">
                <pagination
                    :data="reception"
                    @pagination-change-page="getReception"
                ></pagination>
                
            </div>
              
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
                         <template v-if="pdfFileModal != null">
                            <embed :src="'/pdf/prechargementClient/'+pdfFileModal" frameborder="0" width="100%" height="450px">
                          </template>
                         <template  v-else> Auncun fichier </template>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalPdf()" class="btn btn-warning">Fermer</button>
                  </div>
             </div>
            
          </div>
        </div>
        <modalFacture></modalFacture>
        <modalDetailsCommande></modalDetailsCommande>

    </div>
</template>
<script type="text/ecmascript-6">
    import { EventBus } from '../../event-bus';

    import modalFacture from '../../components/modal/facture.vue';
    import modalDetailsCommande from '../../components/modal/detailsCommande.vue';


    import { PdfMakeWrapper, Table, Img, QR } from 'pdfmake-wrapper';
    import { required, minLength, between } from 'vuelidate/lib/validators';
    import PageLoader from '../../components/PageLoader.vue';  
    import VueQRCodeComponent from 'vue-qrcode-component';
    import htmlToPdfmake from "html-to-pdfmake";
    import pdfFonts from "pdfmake/build/vfs_fonts";

    Vue.component('qr-code', VueQRCodeComponent);

    export default {
        props: [ 
            'idClient',
            'listFournisseurs',
            'typeCmd',
            'defaultContenaire',
            'listContenaire',
            'currentClient',
            'currentEntite',
            'listEntrepots',
            'cmdAPrecharger'
        ],  
        components: {
            /*PageLoader*/
            modalDetailsCommande,
            modalFacture
          },
        data() { 
            return {
                isLoading: true,
                submitted_add: false,
                paginate: 10,
                selectedTypeCmd: "",
                typeCommandeUsed: {},
                prechargement:{},
                reception: {},
                paginateRecep: 200,  // bug lors de la selection de la 2 page les stats sont renitialisé
                typeCommande: "",
                entrepot:'',
                choose:'',
                selected: {
                    typeCmd: '',
                    id: '',
                    nbrCmd: '',
                    nbrColis: '',
                    poids: '',
                    volume: '',
                    isSelected: false,
                    etat: '',
                    mntFact: 0,
                    entrepotID: ''
                },
                capacite: this.defaultContenaire.volume,
                nbrContenaire: 0,
                volumePercent:0,
                isDetail: false,
                commandeSelected: [],
                commandeNoSelected: [],
                checkedCommandes: [],
                pdfFileModal: null,
                idRate: '',
                search: '',
                filtreRate: '',
                etatFiltre: 0,
                searchPre: '',
                // Sort column
                columns: ['rencmd', 'refere', 'reecvr', 'renufa', 'redali', 'repoid', 'revolu', 'totalColis'],
                sortedColumn: '',
                order: 'asc'
            }

        },
        validations: {
            typeCommande: { required },
            entrepot: { required },
            keyword: { required },
             
        },
        watch: {
            keyword(after, before) {
                this.getResults();
            },
            paginate: function(){
               this.getPrechargement();
            },
            selectedTypeCmd: function(value) {
                this.getPrechargement();
            },
            searchPre: function(value) {
                this.getPrechargement();
            },
            etatFiltre: function(value) {
                this.getPrechargement();
            },
            search: function(value) {
                this.getReception();
            },
            filtreRate: function(value) {
                this.getReception();
            },
            order: function(value) {
                this.getReception();
           }
        },
        methods: { 
            /**
             * Sort the data by column.
             * */
            sortByColumn(column) {
              this.sortedColumn = column;
              this.order = (this.order === 'asc') ? 'desc' : 'asc';
            },
            getNbreCmd(id){
                for(var i=0; i<this.cmdAPrecharger.length; i++){
                    if(this.cmdAPrecharger[i].type_commandes_id == id){
                        return this.cmdAPrecharger[i].total;
                    }
                }
                return 0;
            },
        switchContenaire(id, volume){
            this.capacite = volume;
            this.defaultContenaire.id = id;
            this.setProgressCont(this.selected.volume);
        },
        getColorTypeCmd(id){
             for(var i=0; i<this.typeCmd.length;i++){
                if(this.typeCmd[i].id === id){
                    return this.typeCmd[i].tcolor;
                }
            }
            return "#aaa";
        },
        showDossier(pre){
            this.commandeSelected = [];
            this.commandeNoSelected = [];

            this.isDetail = true;
            this.selected.id         = pre.id;
            this.selected.nbrCmd     = pre.nbrCmd;
            this.selected.nbrColis   = pre.total_colis;
            this.selected.poids      = pre.total_poids;
            this.selected.volume     = pre.total_volume;
            this.selected.mntFact    = pre.total_mnt;
            this.selected.typeCmd    = pre.typecmd;
            this.selected.isSelected = true;
            this.selected.etat           = pre.etat;
            this.selected.typeCommandeID = pre.typecmdID; 
            this.selected.entrepotID     = pre.entrepotID;
            this.setProgressCont(pre.total_volume);
            this.getReception();
           },
        preselectionner(event, cmd){

            var ischecked=0;
            if (event.target.checked) {
                ischecked=1;
            }
            const data = new FormData();
            data.append('idPrehargement', this.selected.id);
            data.append('idreception', cmd.reidre);
            data.append('ischecked', ischecked);

            axios.post("/setPrechargement", data).then(response => {
                let res = response.data.result;
                 
                this.selected.nbrCmd   = res[0].total_cmd;
                this.selected.nbrColis = parseInt(res[0].total_colis==null ? 0 : res[0].total_colis) + parseInt(res[0].total_palette==null ? 0 : res[0].total_palette);
                this.selected.poids    = res[0].total_poids==null ? 0 : res[0].total_poids;
                this.selected.volume   = res[0].total_volume==null ? 0 : res[0].total_volume;
                this.selected.mntFact   = res[0].total_mnt==null ? 0 : res[0].total_mnt;

                this.setProgressCont(res[0].total_volume);
                this.getPrechargement(); // refresh tableau prechargement
           
              
            });
        },
        setProgressCont(volume){
            var percent = ((volume/this.capacite)*100).toFixed(0);
                
            if(percent > 100){
                 var surplus =  Math.floor(percent/100);
                 this.volumePercent = (percent%100);
                 this.nbrContenaire = surplus+1;
            }else{
               
                if(this.selected.volume==0 || this.selected.volume==null || this.selected.volume==''){
                    this.nbrContenaire = 0;
                }else{
                    this.nbrContenaire = 1;
                }
                
                this.volumePercent = percent;
            }
        },
        getPrechargement(page = 1){
            this.isLoading=true;
            axios.get('/prechargement/list/'+this.idClient+'?page=' + page + "&paginate=" + this.paginate + "&typeCmd=" + this.selectedTypeCmd+"&keysearch="+this.searchPre+"&etatFiltre="+this.etatFiltre).then(response => {
                this.prechargement = response.data;
                 var that = this;
                setTimeout(function(){
                    that.isLoading = false;
                },500);
            });
        },
        getReception(page = 1){
            this.isLoading=true;
            axios.get('/prechargement/getreception/'+this.idClient+'?page=' + page + "&paginate=" + this.paginateRecep+"&idPre="+this.selected.id+"&typecmd="+this.selected.typeCommandeID+"&entrepotID="+this.selected.entrepotID+"&keysearch="+this.search+"&rate="+this.filtreRate+"&column="+this.sortedColumn+"&order="+this.order).then(response => {
                this.reception = response.data;
                this.isLoading = false;
            });
        },
        save(){
            this.submitted_add = true;
             // stop here if form is invalid
            this.$v.typeCommande.$touch();
            if (this.$v.typeCommande.$invalid || this.$v.entrepot.$invalid) {  
                return;
            }

            const data = new FormData();
            data.append('typeCmd', this.typeCommande);
            data.append('entrepot', this.entrepot);
            data.append('clientID', this.idClient);

            axios.post("/createDossierPre", data).then(response => {
              
                if(response.data.code==0){
                    Vue.swal.fire(
                      'succés!',
                      'Crée avec succés!',
                      'success'
                    );
                    this.submitted_add = false;
                    this.getPrechargement();
                    
                }else{
                    this.submitted_add = false;
                     Vue.swal.fire(
                      'error!',
                      response.data.message,
                      'error'
                    )
                }  
            });
        },
        back(){
            this.getPrechargement(this.selected.currentPage);
            this.isDetail = false;
           },
        valider(){
        
            this.commandeSelected = [];
            this.commandeNoSelected = [];

            var self = this;
            $(".inputCmd").each(function(){
                if($(this).is(':checked')){
                    self.commandeSelected.push($(this).val());
                }else{
                    self.commandeNoSelected.push($(this).val());
                }
                
            });

            if(!this.commandeSelected.length>0){
                Vue.swal.fire(
                      'warning!',
                      'Choisir au moins une commande avant de valider!',
                      'warning'
                    );
                return false;
            }


            Vue.swal.fire({
                  title: 'Confirmez la validation',
                  text: "Dossier de préchargement n° "+this.selected.id,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '',
                  confirmButtonText: 'Oui, Valider!'
                }).then((result) => {
                  if (result.isConfirmed) {

                        Vue.swal({
                            title: 'Validation',
                            html: '<b>En cours...</b>',
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Vue.swal.showLoading()
                            },
                            willClose: () => {

                            }
                        }).then((result) => {});


                        axios.post("/prechargementClient/valider/"+this.currentClient['id'], {
                            'idsCmd': this.commandeSelected,
                            'ignored': this.commandeNoSelected,
                            'id_prechargement' : this.selected.id,
                            'typeCmd' : this.selected.typeCommandeID,
                            'nbrContenaire': this.nbrContenaire,
                            'typeContenaire': this.defaultContenaire.id,

                        }).then(response => {
                          
                            if(response.data.code==0){

                                setTimeout(function(){
                                    Vue.swal.close();
                                     Vue.swal.fire(
                                      'succés!',
                                      'validé avec succés!',
                                      'success'
                                    ).then((result) => {
                                        // redirection   
                                        location.reload();
                                    });   
                                  
                                }, 5000);


                                // Envoi notification avec le fichier PDF

                                this.getCmdSelected(this.selected.id, true);
                                
                                /*Vue.swal.fire(
                                  'succés!',
                                  'validé avec succés!',
                                  'success'
                                );*/


                               

                                
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

            
        },
        getCmdSelected(idPre){
            axios.get('/prechargementClient/getCmdChoisi/'+idPre).then(response => {
                this.checkedCommandes = response.data.result;
                this.generatePdf(true);
                
            });
        },
        currentDateTime() {
            const current = new Date();
            const date = current.getDate()+'/'+(current.getMonth()+1)+'/'+current.getFullYear();
            const time = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
            const dateTime = date;

            return dateTime;
        },
        convertImgToBase64(url, callback, outputFormat){
            var canvas = document.createElement('CANVAS');
            var ctx = canvas.getContext('2d');
            var img = new Image;
            img.crossOrigin = 'Anonymous';
            img.onload = function(){
                canvas.height = img.height;
                canvas.width = img.width;
                ctx.drawImage(img,0,0);
                var dataURL = canvas.toDataURL(outputFormat || 'image/png');
                callback.call(this, dataURL);
                // Clean up
                canvas = null; 
            };
            img.src = url;
        },
        generatePdf(isnotification=false){

            PdfMakeWrapper.setFonts(pdfFonts);

            const pdf = new PdfMakeWrapper();
            pdf.pageOrientation('landscape');
            pdf.defaultStyle({
                fontSize: 10
            });

             var that  = this;

            this.convertImgToBase64('/images/logo/'+that.currentEntite['logo'], function(base64Img){
                //var code = new QR('my code').end;

                var entete=[];
          
                entete.push([
                       { image: base64Img, width: 100}, {text: ''}, 
                       {text: 'Date: '+ that.currentDateTime(), fontSize: 8, alignment: 'right', lineHeight: 1}
                    ]); 

                entete.push([
                    {text:that.currentEntite['nom']+"\nTél: "+ that.currentEntite['telephone']+"/ Fax: "+that.currentEntite['fax']+"\nEmail: "+that.currentEntite['email']+"\nAdresse: "+that.currentEntite['adresse']},

                    {text: 'N°Préchargement '+that.selected.id+'\n'+that.selected.typeCmd, fontSize: 14, bold: true, alignment: 'center', color: '#3490dc'}, 

                    {text: ['Client: ', {text: that.currentClient['clnmcl']+"\n"+ that.currentClient['cltele']+"\n"+that.currentClient['clmail']+"\n"+that.currentClient['cladcl']+", "+that.currentClient['pays']+"\n\n", fontSize: 11}],  alignment: 'right'}]);

               
                /*entete.push([
                    {text: 'Préchargement N° '+that.selected.id, fontSize: 15, alignment: 'center', lineHeight: 2, colSpan: 3}
                    ]);*/
                
               

                var header = new Table(entete).widths('*').layout('noBorders').margin([0, 0, 0, 7]).end;

                const data = [];

                const headerTab = ['N°FE', 'N°ECV', 'N°CDE', 'Emballage', 'Fournisseurs', 'Poids(kg)', 'Volume(m3)', 'Factures'];
                
                data.push(headerTab); 
                

                var legend1 = "";
                var legend2 = "";
               
                for(var i=0; i< that.checkedCommandes.length; i++){
                    var obj = that.checkedCommandes[i];
                    var nbr = [];
                    var emballage = [];
                    var cmdCell=[];
                    var prio = "";


                    if(obj.renbcl > 0){
                        nbr.push(obj.renbcl);
                        emballage.push((obj.renbcl).toString() + ' Colis');
                    }

                    if(obj.renbpl > 0){
                        nbr.push(obj.renbpl);
                        emballage.push((obj.renbpl).toString() + ' Pal.');
                    }

                    if(obj.priorite==1){
                        prio = '*';
                        legend1 = '(*) Pas urgente';

                    }

                    if(obj.priorite==3){
                        prio = '***';
                        legend2 = '(***) Urgente';
                    }


                    cmdCell.push(obj.rencmd+" "+prio);
                    
                    const item = [obj.refere,obj.reecvr, cmdCell, emballage ,obj.fournisseurs, obj.repoid, obj.revolu, obj.renufa];
                    
                    data.push(item);
                }
                if(that.checkedCommandes.length==0){
                    data.push([{text: 'Aucune commande selectionné', fontSize: 12, alignment: 'center', colSpan: 8}])
                }
               
                var table = new Table(data).widths([70,70,70,70,'*',60,60,90]).layout({
                    color(columnIndex){
                    return columnIndex=== 0 ? "#cccccc": '';  
                    },
                    fillColor (columnIndex){
                        if(columnIndex===0){
                            return columnIndex === 0 ? "#bbb": '';  
                        }else{
                            return columnIndex%2 === 0 ? "white": '#eee';  
                        }
                        
                    }
                }).end;

                // totaux
                var totaux = [[{text: 'Total commande', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Nb Colis total', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Poids total', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Volume total', fontSize: 10, bold: true, alignment: 'center'} ]];
                totaux.push([{text: that.checkedCommandes.length, fontSize: 10, bold: true, alignment: 'center'}, {text: that.selected.nbrColis, fontSize: 10, bold: true, alignment: 'center'}, {text: that.selected.poids, fontSize: 10, bold: true, alignment: 'center'}, {text: that.selected.volume, fontSize: 10, bold: true, alignment: 'center'} ]);

                var tabtotaux= new Table(totaux).widths(['20%', '20%', '20%', '20%']).layout({
                    color(columnIndex){
                    return columnIndex=== 0 ? "#cccccc": '';  
                    },
                    fillColor (columnIndex){
                        if(columnIndex===0){
                            return columnIndex === 0 ? "#ccc": '';  
                        }
                        
                    }
                }).margin([0, 15, 8, 7]).end;

                pdf.header = {
                     exampleLayout: {
                        hLineWidth: function (i, node) {
                          if (i === 0 || i === node.table.body.length) {
                            return 0;
                          }
                          return (i === node.table.headerRows) ? 2 : 1;
                        },
                        vLineWidth: function (i) {
                          return 0;
                        },
                        hLineColor: function (i) {
                          return i === 1 ? 'black' : '#aaa';
                        },
                        paddingLeft: function (i) {
                          return i === 0 ? 0 : 8;
                        },
                        paddingRight: function (i, node) {
                          return (i === node.table.widths.length - 1) ? 0 : 8;
                        }
                      }
                }

                pdf.footer(function(currentPage, pageCount) { return  { margin: [20, 0, 20, 0], height: 30, columns: [{alignment: "left",
                text: 'DuoTransit'}, {text: currentPage.toString() + ' / ' + pageCount, alignment: "right"}]}; });

                pdf.add(header);

                pdf.add(table);

                pdf.add(
                    pdf.ln(2)
                );

                var labelCmd1 = that.selected.typeCmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
            
                var nameFile = 'prechargement-'+that.selected.id+'_'+labelCmd1+".pdf";


                var qrTotaux = [];

                var legendTotaux = [];

                legendTotaux.push([tabtotaux, {text: legend1+' '+legend2, fontSize: 10, bold: true, alignment: 'left'}]);

                qrTotaux.push([legendTotaux, new QR(location.origin+"/pdf/prechargementClient/"+nameFile).fit(80).alignment('right').end]); 

                var tableQR = new Table(qrTotaux).widths(['*', 80]).layout('noBorders').end;

                pdf.add(tableQR);

                if(isnotification){
                   var self = that; 
                   pdf.create().getDataUrl(function(url) { 
                    
                        axios.post("/prechargementClient/notification/"+self.currentClient['id'], {
                            'idsCmd': self.commandeSelected,
                            'id_prechargement' : self.selected.id,
                            'typeCmd': self.selected.typeCmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase(), 
                            'base64_file_pdf': url

                        });/*.then(response => {

                            Vue.swal.close();

                            if(response.data.code==0){
                                Vue.swal.fire(
                                  'succés!',
                                  'validé avec succés!',
                                  'success'
                                ).then((result) => {
                                    // redirection   
                                    self.back();
                                });
                            }else{
                                 Vue.swal.fire(
                                  'Erreur!',
                                  '',
                                  'error'
                                )
                            } 
                        });.catch(err => {
                            Vue.swal.close();
                            console.log(err.code);
                            console.log(err.message);

                            Vue.swal.close();
                                Vue.swal.fire(
                              'Warning!',
                              'Echec envoi de mail!',
                              'warning'
                            ).then((result) => {
                                // redirection   
                                location.reload();
                            });
                            // console.log(err.stack);
                        });*/

                   }); // download() or open() // getDataUrl
                }else{
                    pdf.create().download(); 
                }

            });

            
           
    
            },
            showInvoice(pre){
                var labelCmd = pre.typecmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
            
                this.pdfFileModal = 'prechargement-'+pre.id+'_'+labelCmd+".pdf";
                console.log(this.pdfFileModal);
            },
            closeModalPdf(){
                 this.$refs.closePoupPdf.click();
            },
            onAfterRate(rate) {
                var that = this;
                setTimeout(function(){
                    axios.post("/setRate/"+that.currentClient['id'], {
                        'rate': rate,
                        'idReception': that.idRate

                    }).then(response => {

                    });
                },1000);
                
            },
            setID(id){
                this.idRate = id;
            },
            format_nbr(mnt){
                return mnt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            },
            showModal(dry){

                 EventBus.$emit('VIEW_CMD', {
                    openView: true,
                    dry: dry,
                    fournisseur: this.listFournisseurs,
                    typeCommande: this.typeCmd,
                    entrepot: this.listEntrepots,
                    idClient: this.idClient,
                    canDeleteIncident: false
                });

            },
            deletePre(pre){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: "Préchargement n° "+pre.id,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/prechargementClient/delete/'+pre.id+'?clientID='+this.idClient+"&typeCmd="+pre.typecmdID).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Dossier préchargement supprimé.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getPrechargement();


                        });
                  
                  }
                })
            },
            showFacture(fact){
                 EventBus.$emit('VIEW_FACT', {
                    pathFile: fact
                }); 
            }

        }, 
        mounted() {
          this.getPrechargement();
        }
    }
</script>