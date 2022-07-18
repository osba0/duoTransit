<template>
    <div>
        <PageLoader :is-loading = isLoading ></PageLoader> 
        <div class="row">
          <div class="col-sm-6">
               <h2>Préchargement</h2>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active"><a href="#">Préchargement</a></li>
            </ol>
          </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                    <div class="table-responsive p-0">
                         <div class="d-flex justify-content-end mb-4">
                            
                                <form @submit.prevent="save" enctype="multipart/form-data"  key=1 class="d-flex">
                                <select class="form-control mr-2" v-model="typeCommande" :class="{ 'border-danger': submitted_add && !$v.typeCommande.required }">
                                    <option value="">Choisir le type commande</option>
                                    <option v-for="type in typeCmd"  :value="type.id">{{type.typcmd}}</option>
                                  
                                </select>
                                
                                <button type="submit" class="btn btn-warning d-flex align-items-center">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" :class="{'d-none': !submitted_add, 'd-inline-block': submitted_add && !$v.typeCommande.required}"></span> <i class="fa fa-plus px-2" aria-hidden="true" :class="{'d-none': submitted_add, 'd-inline-block': !submitted_add}"></i> Ajouter un dossier</button>
                                </form>
                            
                        </div>
                        <table class="table">
                            <thead class="thead-light2 borderorange">
                                 <tr>
                                    <th class="p-2 border-right border-white h6">ID</th>
                                    <th class="p-2 border-right border-white h6">Nbre commandes</th>
                                    <th class="p-2 border-right border-white h6">Nbre colis total</th>
                                    <th class="p-2 border-right border-white h6">Poids total</th>
                                    <th class="p-2 border-right border-white h6">Volume total</th>
                                    <th class="p-2 border-right border-white h6">Type commande</th>
                                    <th class="p-2 border-right border-white h6">Etat</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Date</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgStripe preChargeTable"> 
                                <template v-if="!prechargement.data || !prechargement.data.length">
                                    <tr><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                                </template>
                                <template v-else>
                                    <tr v-for="pre in prechargement.data" :key="prechargement.id" class="bg-white position-relative" :class="[{ 'rowActif': pre.id == selected.id, 'rowInactif':pre.id != selected.id }]">
                                        <td>{{ pre.id }}</td>
                                        <td>{{ pre.nbrCmd }}</td>
                                        <td>{{ pre.total_colis }} {{pre.total_palette}}</td>
                                        <td>{{ pre.total_poids }}</td>
                                        <td>{{ pre.total_volume }}</td>
                                        <td>{{ pre.typecmd }}</td>
                                        <td>{{ pre.etat }}</td>
                                        <td>{{ pre.created_at }}</td>
                                        <td>{{ pre.user }}</td>
                                        <td><div class="">
                                                <label class="switch">
                                                    <input class="switch-input" v-model="choose" type="radio" :value="pre.id" v-on:change="selectedPre(pre)" /> 
                                                    <span class="switch-label" data-on="Oui" data-off="Non"></span> 
                                                    <span class="switch-handle"></span> 
                                                </label>
                                            </div></td>
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

        
       
        <div id="accordion">
          <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed"  data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Séléction des commandes
                </button>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" :class="{'show': selected.isSelected}"  aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body  position-relative" :class="{'off-body':!selected.isSelected}">
                 <div class="mb-3 mb-4">
            <table class="table table-bordered bg-white"> 
                <tr>
                    <th class="text-uppercase thead-blue py-1">Etat Contenaire</th>
                </tr>
                <tr>
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
                <div class="d-flex justify-content-between align-content-center mb-2">
                    <div class="d-flex align-items-end">
                        <div>
                            <div class="d-flex align-items-center">
                                <label for="paginate" class="text-nowrap mr-2 mb-0"
                                    >Nbre de ligne par Page</label> 
                                <select
                                    v-model="paginate"
                                    class="form-control form-control-sm">
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
                                    <option :value="type.id" v-for="type in typeCommandeUsed">{{type.type}}</option>
                                    
                                </select>
                            </div>
                        </div>
                
                    </div>
                </div>        
                    <div class="card-body table-responsive p-0">
                        <table class="table">
                            <thead class="thead-blue borderorange">
                                 <tr>
                                    <th class="p-2 border-right border-white h6">#</th>
                                    <th class="p-2 border-right border-white h6">N°CDE</th>
                                    <th class="p-2 border-right border-white h6">N°FE</th>
                                    <th class="p-2 border-right border-white h6">N°ECV</th>
                                    <th class="p-2 border-right border-white h6">Fournisseur</th>
                                    <th class="p-2 border-right border-white h6">Emballage</th>
                                    <th class="text-right p-2 border-right border-white h6">Poids</th>
                                    <th class="text-right p-2 border-right border-white h6">Volume</th>
                                    <th class="p-2 border-right border-white h6">Num Fact</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Date livraison</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                    <th class="text-right p-2 border-right border-white h6">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgStripe">
                    <template v-if="!reception.data || !reception.data.length">
                        <tr><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                    </template>
                    <template v-else>
                        <tr v-for="dry in reception.data" :key="dry.reidre" class="bg-white">
                        
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
                        
                        <td class="p-2 align-middle text-right">{{ dry.renufa }}</td>
                        <td class="p-2 align-middle"><i class="fa fa-calendar" aria-hidden="true"></i> {{ dry.redali }}</td>
                        <td class="p-2 align-middle text-nowrap"><i class="fa fa-user" aria-hidden="true"></i> {{ dry.reuser}}</td>
                        <td class="p-2 text-right">
                            <div class="d-flex justify-content-end">
                                <label class="switch">
                                    <input class="switch-input" :checked="selected.id == dry.idPre" type="checkbox" :value="dry.reidre" v-on:change="preselectionner($event,dry)" /> 
                                    <span class="switch-label" data-on="Choisie" data-off="Choisir"></span> 
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
              </div>
            </div>
          </div>

        </div>


        

    </div>
</template>
<script>
    import { required, minLength, between } from 'vuelidate/lib/validators';
    import PageLoader from '../../components/PageLoader.vue';  
    export default {
        props: [ 
            'idClient',
            'listFournisseurs',
            'typeCmd',
            'defaultContenaire',
            'listContenaire'
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
                typeCommandeUsed: {},
                prechargement:{},
                reception: {},
                paginateRecep: 10,
                typeCommande: "",
                choose:'',
                selected: {
                    typeCmd: '',
                    id: '',
                    nbrCmd: '',
                    nbrColis: '',
                    poids: '',
                    volume: '',
                    isSelected: false
                },
                capacite: this.defaultContenaire.volume,
                nbrContenaire: 0,
                volumePercent:0
            }

        },
        validations: {
            typeCommande: { required },
            keyword: { required },
             
        },
        watch: {
            keyword(after, before) {
                this.getResults();
            },
           paginate: function(){
              
           }
        },
        methods: { 
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
            const data = new FormData();
            data.append('idPrehargement', this.selected.id);
            data.append('idreception', cmd.reidre);
            data.append('ischecked', ischecked);

            axios.post("/setPrechargement", data).then(response => {
                let res = response.data.result;
                 
                this.selected.nbrCmd   = res[0].total_cmd;
                this.selected.nbrColis = res[0].total_colis;
                this.selected.poids    = res[0].total_poids;
                this.selected.volume   = res[0].total_volume;

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
        selectedPre(pre){
            this.selected.id         = pre.id;
            this.selected.nbrCmd     = pre.nbrCmd;
            this.selected.nbrColis   = pre.total_colis;
            this.selected.poids      = pre.total_poids;
            this.selected.volume     = pre.total_volume;
            this.selected.typeCmd    = pre.typecmd;
            this.selected.isSelected = true;
            
            this.setProgressCont(pre.total_volume);
            this.getReception();


        },
        getPrechargement(page = 1){
            this.isLoading=true;
            axios.get('/prechargement/list/'+this.idClient+'?page=' + page + "&paginate=" + this.paginate).then(response => {
                this.prechargement = response.data;
                this.isLoading = false;
                //this.getReception();
            });
        },
        getReception(page = 1){
            this.isLoading=true;
            axios.get('/prechargement/reception/'+this.idClient+'?page=' + page + "&paginate=" + this.paginateRecep+"&idPre="+this.selected.id).then(response => {
                this.reception = response.data;
                this.isLoading = false;
            });
        },
        save(){
            this.submitted_add = true;
             // stop here if form is invalid
            this.$v.typeCommande.$touch();
            if (this.$v.typeCommande.$invalid) {  
                return;
            }

            const data = new FormData();
            data.append('typeCmd', this.typeCommande);
            
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
        }
        },
        mounted() {
          this.getPrechargement();
        }
    }
</script>