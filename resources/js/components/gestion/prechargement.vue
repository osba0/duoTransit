<template>
    <div>
        <!--PageLoader :is-loading = isLoading ></PageLoader--> 
        <div class="row">
            <!--div class="qr-code-image">
                <qr-code text='Text to encode'></qr-code>
            </div-->
            
          <div class="col-sm-9 d-flex align-items-center">
               <h2>Validation Préchargement  <template v-if="isDetail">:</template></h2>
               <template v-if="isDetail">
                   <span class="pl-2 h3 text-primary font-weight-bold"> N° Dossier {{ selected.id }}&nbsp;</span>
                   <span class="h3 text-primary font-weight-bold"> du {{ selected.dateDebut }} au {{ selected.dateCloture }}</span>
                </template>
          </div>
          <div class="col-sm-3">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item" :class="!isDetail ? 'active': ''"><a href="#">Préchargement</a></li>
              <li class="breadcrumb-item active"  v-if="isDetail"><a href="#">Dossier n° {{ selected.id }}</a></li>
            </ol>
          </div>
        </div>


        <template v-if="!isDetail">
            <div class="row d-flex align-items-center justify-content-between mb-3">
                <ul class="legend mt-4 mb-2 pl-3 flex-1">
                    <li v-for="type in typeCmd" class="d-flex align-items-center">
                        <span class="etat_T m-0 mr-1" :style="{'background': type.tcolor}"></span> 
                        <label class="m-0 mr-2">{{type.typcmd}}</label>
                    </li>
                </ul>
                <div class="mt-2 mr-3">
                    <a href="#" class="text-white btn btn-success font-weight-bold" data-toggle="modal" data-target="#newDossier">
                        <i class="fa fa-plus" aria-hidden="true"></i> Nouvel Dossier
                    </a> 
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
                                <option value="">Afficher tout</option>
                                <option value="1">Validé</option>
                                <option value="0">En cours</option>
                                
                            </select>
                        </div>
                    </div>
            
                </div>
                <div class="pr-0 col-md-4">
                    <input
                        v-model.lazy="search"
                        type="search"
                        class="form-control"
                        placeholder="Rechercher par n°dossier"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="position-relative overflow-hidden">
                        <table class="table">
                            <thead class="thead-blue" :class="[isLoading ? '' : 'hasborder']">
                                 <tr>
                                    <th class="p-2 border-right border-white h6">N° Dossier</th>
                                    <th class="p-2 border-right border-white h6">Date Début</th>
                                    <th class="p-2 border-right border-white h6">Date Fin</th>
                                    <th class="p-2 border-right border-white h6">Nbre commandes</th>
                                    <th class="p-2 border-right border-white h6">Nbre colis total</th>
                                    <th class="p-2 border-right border-white h6">Poids total</th>
                                    <th class="p-2 border-right border-white h6">Volume total</th>
                                    <!--th class="p-2 border-right border-white h6">Type commande</th-->
                                    <th class="p-2 border-right border-white h6">Etat</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Date</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                    <th class="text-nowrap p-2 border-right text-right border-white h6">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgStripe preChargeTable"  :class="[isLoading ? 'loader-line' : '']"> 
                                <template v-if="!prechargement.data || !prechargement.data.length">
                                    <tr><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                                </template>
                                <template v-else>
                                    <tr v-for="pre in prechargement.data" :key="prechargement.id" class="bg-white position-relative" :class="[{ 'rowActif': pre.id == selected.id, 'rowInactif':pre.id != selected.id }]">
                                        <td class="position-relative align-middle">
                                            <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': pre.typeCmd_color} : {'background': '#ccc'}]"></div>
                                            {{ pre.id }}
                                        </td>
                                        <td class="align-middle">{{ pre.dateDebut }}</td>
                                        <td class="align-middle">{{ pre.dateCloture }}</td>
                                        <td class="align-middle">{{ pre.nbrCmd }}</td>
                                        <td class="align-middle">{{ parseInt(pre.total_colis) + parseInt(pre.total_pallette) }}</td>
                                        <td class="align-middle">{{ pre.total_poids }}</td>
                                        <td class="align-middle">{{ pre.total_volume }}</td>
                                        <!--td>{{ pre.typecmd }}</td-->
                                       
                                        <td class="align-middle">
                                            <span v-if="pre.etat==1" class="badge badge-success">Validé</span>
                                            <span v-if="pre.etat==0" class="badge badge-warning">En cours</span>
                                        </td>
                                        <td class="align-middle">{{ pre.dateCrea }}</td>
                                        <td class="align-middle">{{ pre.user }}</td>
                                        <td class="align-middle">  
                                            <div class="w-100 text-right">
                                                <button @click="showDossier(pre)" class="btn btn-info btn-sm">Ouvrir</button>
                                                  <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1 bg-white" v-on:click="deletePre(pre)">
                                                        <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                                    </a>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div class="d-flex mt-4 justify-content-center paginationDossier">
                            <pagination
                                :data="prechargement"
                                @pagination-change-page="getPrechargement"
                            ></pagination>
                        
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
           
            <div class="mb-3 mb-3">
                
                <table class="table table-bordered bg-white"> 
                <tr>
                    <th class="text-uppercase thead-blue py-1 w-50">Dossier selectionné</th>
                    <th class="text-uppercase thead-blue py-1">Etat contenaire</th>
                </tr>
                <tr>
                    <td class="align-middle">
                        <div class="d-flex justify-content-between detailPrecharge position-relative">
                            <table class="table m-0 mt-3 table-striped">
                                <tbody> 
                                     <tr>
                                        <th>Nbre Commande: {{ selected.nbrCmd }}</th>
                                        <th>Nbre de colis: {{ selected.nbrColis }}</th>
                                        <th>Poids: {{ selected.poids }} KG</th>
                                        <th>Volume: {{ selected.volume }} m<sup>3</sup></th>

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
            <div class="d-flex justify-content-between align-items-center mb-3 sucesss"> 
                <button class="btn btn-lg btn-danger" :disabled = "selected.id == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button>
                <div class="h5 mb-0 rounded bg-white py-2 px-3 border">{{ selected.typeCmd }}</div>
                <!--div class="h5 mb-0 rounded bg-white py-2 px-3 border">Nbre Commande: {{ selected.nbrCmd }}</div-->
                <div class="h5 mb-0 rounded bg-white py-2 px-3 border">
                    <span class="py-2 px-3 badge badge-warning" v-if="selected.etat==0">En cours</span>
                    <span class="py-2 px-3 badge badge-success" v-if="selected.etat==1">Validé</span>
                </div>
                <button class="btn btn-lg btn-primary" :disabled = "(selected.id == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()">Valider</button>
            </div>
            <hr>
                
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
                        <td class="p-2 text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <span v-if="dry.dossier_id > 0"><i class="fa fa-check-circle"></i></span>
                                <label class="switch">
                                    <template v-if="selected.etat==0">
                                        <input class="switch-input inputCmd" :checked="(selected.id == dry.dossier_id || dry.idPre > 0)" type="checkbox" :value="dry.reidre" v-on:change="preselectionner($event,dry)" /> 
                                        <span class="switch-label" data-on="Choisie" data-off="Choisir"></span> 
                                        <span class="switch-handle"></span> 
                                    </template>
                                    <template v-else>
                                        <input class="switch-input inputCmd" :checked="(selected.id == dry.dossier_id)" type="checkbox" :value="dry.reidre" v-on:change="preselectionner($event,dry)" /> 
                                        <span class="switch-label" data-on="Choisie" data-off="Choisir"></span> 
                                        <span class="switch-handle"></span> 
                                    </template>
                                    
                                </label>
                            </div>
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
                <div class="d-flex justify-content-between mb-3 sucesss"> 
                    <button class="btn btn-lg btn-danger" :disabled = "selected.id == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button>
                    <button class="btn btn-lg btn-primary" :disabled = "(selected.id == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()">Valider</button>
                </div>
        </template>
        
        <!-- Modal creation dossier-->
        <div class="modal fade" id="newDossier" tabindex="-1" role="dialog" aria-labelledby="myModalDossier"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
           <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Nouvel Dossier</h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalDossier()" aria-label="Close" ref="closePoupDossier">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">

                        <form @submit.prevent="save" enctype="multipart/form-data"  key=1>

                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label for="numDoss"  class="d-block m-0 text-left pr-2 white-space-nowrap">N° dossier</label>
                                           <input autocomplete="off" class="form-control mr-2" id="numDoss" v-model="initChargement.numDossier" 
                                            :class="{ 'border-danger': submitted_add && !$v.initChargement.numDossier.required}" />
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label  class="d-block m-0 text-left pr-2 white-space-nowrap">Choix type commande</label>
                                            <select class="form-control" v-model="initChargement.typeCommande" :class="{ 'border-danger': submitted_add && !$v.initChargement.typeCommande.required }">
                                                <option value="">Choisir le type commande</option>
                                                <option v-for="type in typeCmd"  :value="type.id">{{type.typcmd}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                             </div>
                              <div class="row mb-4">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                            <label for="dateDeb" class="m-0 text-left w-35 pr-2 white-space-nowrap" >Date début</label>

                                             <date-picker v-model="initChargement.dateDebut" class="w-100" :disabled-date="disabledFutureDate"  required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY" :class="{ 'border-danger': submitted_add && !$v.initChargement.dateDebut.required }"></date-picker>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label for="dateClo" class="d-block m-0 text-left w-35 pr-2 text-nowrap" >Date cloture</label>
                                            <date-picker v-model="initChargement.dateCloture" class="w-100" :disabled-date="disabledFutureDate"  required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY" :class="{ 'border-danger': submitted_add  && !$v.initChargement.dateCloture.required}"></date-picker>
                                        </div>
                                    </div>
                                </div>
                             </div>
                             <div class="modal-footer d-flex justify-content-center"> 
                                <button type="submit" class="btn btn-success d-flex align-items-center">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" :class="{'d-none': !submitted_add, 'd-inline-block': submitted_add && !$v.typeCommande.required}"></span>Créer</button>
                                <button type="button" v-on:click="closeModalDossier()" class="btn btn-warning">Annuler</button>
                            </div>
                        </form>   
                    </div>  
             </div>
          </div>
        </div>
        

    </div>
</template>
<script>
    import { PdfMakeWrapper, Table, Img, QR } from 'pdfmake-wrapper';

    import { ITable } from 'pdfmake-wrapper/lib/interfaces'; 

    import pdfFonts from "pdfmake/build/vfs_fonts";
    import { required, minLength, between } from 'vuelidate/lib/validators';
    import PageLoader from '../../components/PageLoader.vue';  
    import VueQRCodeComponent from 'vue-qrcode-component';
    import htmlToPdfmake from "html-to-pdfmake";

    Vue.component('qr-code', VueQRCodeComponent);

    export default {
        props: [ 
            'idClient',
            'listFournisseurs',
            'typeCmd',
            'defaultContenaire',
            'listContenaire',
            'currentClient',
            'currentEntite'
            
        ],  
        components: {
            PageLoader
          },
        data() { 
            return {
                isLoading: true,
                submitted_add: false,
                paginate: 5,
                selectedTypeCmd: "",
                prechargement:{},
                prechargementDossier: {},
                reception: {},
                paginateRecep: 10,
                choose:'',
                selected: {
                    typeCmd: '',
                    id: '',
                    nbrCmd: '',
                    nbrColis: '',
                    poids: '',
                    volume: '',
                    dateDebut: '',
                    dateCloture: '',
                    isSelected: false,
                    typeCommande: '',
                    etat: 0,
                    currentPage: 1
                },
                capacite: this.defaultContenaire.volume,
                nbrContenaire: 0,
                volumePercent:0,
                initChargement :{
                    numDossier: '',
                    dateDebut:   null,
                    dateCloture: null,
                    typeCommande: "",

                },
                checkedCommandes: [],
                isDetail: false,
                commandeSelected: [],
                commandeNoSelected: [],
                eventCmdSelected: {
                    ischecked: -1,
                    idcmd: ''

                },
                search: "",
                etatFiltre: ""
            }

        },
        validations: {
            typeCommande: { required },
            keyword: { required },
             initChargement: {
                numDossier:   { required },
                dateDebut:    { required },
                dateCloture:  { required },
                typeCommande: { required },
            }
             
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
            search: function(value) {
                this.getPrechargement();
            },
            etatFiltre: function(value) {
                this.getPrechargement();
            }
        },
        methods: { 
             disabledFutureDate(date) {
              const today = new Date();
              today.setHours(0, 0, 0, 0);

              return date > today;
            },
            switchContenaire(id, volume){
                this.capacite = volume; 
                this.defaultContenaire.id = id;
                this.setProgressCont(this.selected.volume);
            },
            preselectionner(event, cmd){
                var ischecked=0;
               
                if (event.target.checked) {
                    ischecked=1;
                }
                this.eventCmdSelected.ischecked=ischecked;
                this.eventCmdSelected.idcmd = cmd.reidre;
                
                
                const data = new FormData();
                data.append('idPrehargement', this.selected.id);
                data.append('idreception', cmd.reidre);
                data.append('ischecked', ischecked);

                axios.post("/gerer/dossier/setPrechargement", data).then(response => {
                let res = response.data.result;
    
                this.selected.nbrCmd   = res[0].total_cmd;
                this.selected.nbrColis = parseInt(res[0].total_colis==null ? 0 : res[0].total_colis) + parseInt(res[0].total_palette==null ? 0 : res[0].total_palette);
                this.selected.poids    = res[0].total_poids==null ? 0 : res[0].total_poids;
                this.selected.volume   = res[0].total_volume==null ? 0 : res[0].total_volume;



                this.setProgressCont(res[0].total_volume);
                this.getPrechargement(); // refresh tableau prechargement
                
                this.getCmdSelected(this.selected.id);
                this.getReception();

           
              
            });
        },
        closeModalDossier(){
            this.$refs.closePoupDossier.click();
            this.flushFormular();
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
            this.selected.currentPage=page;
            axios.get('/gerer/dossier/list/'+this.idClient+'?page=' + page + "&paginate=" + this.paginate+ "&typeCmd=" + this.selectedTypeCmd+"&keysearch="+this.search+"&etatFiltre="+this.etatFiltre).then(response => {
                this.prechargement = response.data;
                var that = this;
                setTimeout(function(){
                    that.isLoading = false;
                },500);
                
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
                        axios.delete('/gerer/deletePre/'+pre.id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Deleted!',
                              'Your file has been deleted.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getPrechargement();


                        });
                  
                  }
                })
            },
        getReception(page = 1){
            this.isLoading=true;
            axios.get('/gerer/dossier/pre/reception/'+this.idClient+"/"+this.selected.typeCommande+'?page=' + page + "&paginate=" + this.paginateRecep+"&idPre="+this.selected.id).then(response => {
                this.reception = response.data;
                if(this.selected.etat==0){
                    this.selected.nbrCmd   = 0;
                    this.selected.nbrColis  = 0;
                    this.selected.poids    = 0;
                    this.selected.volume   = 0;

                    var nbColis=0;
                    var nbCommande=0;

                    for(var i=0; i<this.reception.data.length; i++){
                        var obj = this.reception.data[i];
                        if(obj.idPre > 0 || obj.dossier_id > 0){
                            if(this.eventCmdSelected.idcmd!=obj.reidre || (this.eventCmdSelected.ischecked > 0 && this.eventCmdSelected.idcmd==obj.reidre)){
                                nbCommande++;
                                nbColis +=  (obj.renbcl + obj.renbpl);
                    
                                this.selected.poids    += obj.repoid;
                                this.selected.volume   += obj.revolu;
                            }else if(this.eventCmdSelected.ischecked == 0 && this.eventCmdSelected.idcmd==obj.reidre){
                                nbCommande--;
                                nbColis -=  (obj.renbcl + obj.renbpl);
                    
                                this.selected.poids    -= obj.repoid;
                                this.selected.volume   -= obj.revolu;

                                if(nbCommande < 0){
                                    nbCommande=0;
                                    this.selected.poids    = 0;
                                    this.selected.volume   = 0;
                                    nbColis           = 0;
                                }
                            }
                            
                            
                        }
                    }

                    this.selected.nbrColis = nbColis;
                    this.selected.nbrCmd = nbCommande;
                    this.setProgressCont(this.selected.volume);
                }
               
                this.isLoading = false;
            });
        },
        save(){
            this.submitted_add = true;
             // stop here if form is invalid
            this.$v.initChargement.$touch();
            if (this.$v.initChargement.$invalid) {
                return;
            }
            
            axios.post("/gerer/createDossier", {
                'numdossier': this.initChargement.numDossier,
                'datedebut' : this.initChargement.dateDebut,
                'datecloture' : this.initChargement.dateCloture, 
                'typeCmd' : this.initChargement.typeCommande,
                'clientID' : this.idClient

            }).then(response => {
              
                if(response.data.code==0){
                    Vue.swal.fire(
                      'succés!',
                      'Crée avec succés!',
                      'success'
                    );
                    this.submitted_add = false;

                    this.closeModalDossier();
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
        currentDateTime() {
            const current = new Date();
            const date = current.getDate()+'/'+(current.getMonth()+1)+'/'+current.getFullYear();
            const time = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
            const dateTime = date;

            return dateTime;
        },
        getCmdSelected(idDossier){
            axios.get('/gerer/getCmd/'+idDossier).then(response => {
                this.checkedCommandes = response.data.result;
                
            });
        },
        valider(){
            console.log(this.commandeSelected);
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
            axios.post("/gerer/createDossier/valider/"+this.currentClient['id'], {
                'idsCmd': this.commandeSelected,
                'ignored': this.commandeNoSelected,
                'id_dossier' : this.selected.id

            }).then(response => {
              
                if(response.data.code==0){
                    Vue.swal.fire(
                      'succés!',
                      'validé avec succés!',
                      'success'
                    );
                    this.selected.etat = 1;
                    //this.getPrechargement();
                    this.getReception();

                    
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
        flushFormular(){

            this.initChargement.numDossier= '';
            this.initChargement.dateDebut=   null;
            this.initChargement.dateCloture= null;
            this.initChargement.typeCommande= "";

        },
        generatePdf(){

            PdfMakeWrapper.setFonts(pdfFonts);

            const pdf = new PdfMakeWrapper();
            pdf.defaultStyle({
                fontSize: 10
            });

            //var code = new QR('my code').end;

            var entete=[];
      
            entete.push([{text: this.currentEntite['nom'], fontSize: 22, bold: true, alignment: 'left'},  {text: 'Date: '+ this.currentDateTime(), fontSize: 8, alignment: 'right', lineHeight: 1}]); 
            entete.push([{text:['Client: ', {text: this.currentClient['clnmcl'], fontSize: 14}]}, {text: ['Entrepôt: ', {text: 'CNM', fontSize: 14}],  alignment: 'right'}]);
           
            entete.push([{text: 'N°Dossier '+this.selected.id, fontSize: 15, alignment: 'center', lineHeight: 2, colSpan: 2}]);
            entete.push([{text: 'Prévision de chargement pour le '+this.selected.dateDebut+' ET '+this.selected.dateCloture, fontSize: 11, alignment: 'center', colSpan: 2}]);
           

            var header = new Table(entete).widths('*').layout('noBorders').margin([0, 0, 0, 7]).end;

            const data = [];

            const headerTab = ['N°FE', 'N°ECV', 'N°CDE', 'Emballage', 'Fournisseurs', 'Poids', 'Volume', 'Factures'];
            
            data.push(headerTab); 
            //data.push(code); 
           
            for(var i=0; i< this.checkedCommandes.length; i++){
                var obj = this.checkedCommandes[i];
                var nbr = [];
                var emballage = [];


                if(obj.renbcl > 0){
                    nbr.push(obj.renbcl);
                    emballage.push((obj.renbcl).toString() + ' Colis');
                }

                if(obj.renbpl > 0){
                    nbr.push(obj.renbpl);
                    emballage.push((obj.renbpl).toString() + ' Palette(s)');
                }

                

                
                const item = [obj.refere,obj.reecvr, obj.rencmd, emballage ,obj.fournisseurs, obj.repoid, obj.revolu, obj.renufa];
                
                data.push(item);
            }
            if(this.checkedCommandes.length==0){
                data.push([{text: 'Aucune commande selectionné', fontSize: 12, alignment: 'center', colSpan: 8}])
            }
           
            var table = new Table(data).widths('*').layout({
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

            pdf.add(new QR(this.selected.id).fit(80).alignment('right').end);
           

            pdf.create().open(); // download() or open()
    
    
           },
           showDossier(dossier){
            this.commandeSelected = [];
            this.commandeNoSelected = [];
            this.isDetail = true;
            this.selected.id         = dossier.id;
            this.selected.nbrCmd     = dossier.nbrCmd;
            this.selected.nbrColis   = parseInt(dossier.total_colis)+parseInt(dossier.total_pallette);
            this.selected.poids      = dossier.total_poids;
            this.selected.volume     = dossier.total_volume;
            this.selected.typeCmd    = dossier.typecmd;
            this.selected.dateDebut  = dossier.dateDebut;
            this.selected.dateCloture  = dossier.dateCloture;
            this.selected.isSelected = true;
            this.selected.typeCommande = dossier.typecmdId;
            this.selected.etat = dossier.etat;
            this.setProgressCont(dossier.total_volume);
            this.getReception();
            this.getCmdSelected(this.selected.id);




           },
           back(){
            this.getPrechargement(this.selected.currentPage);
            this.isDetail = false;
            this.commandeSelected = [];
            this.commandeNoSelected = [];
            this.eventCmdSelected.ischecked = -1;
            this.eventCmdSelected.idcmd = '';

           }
        },
        mounted() {
          this.getPrechargement();
        }
    }
</script>