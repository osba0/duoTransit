<template>
    <div>
        <!--PageLoader :is-loading = isLoading ></PageLoader--> 
        <div class="row">
          <div class="col-sm-8 d-flex align-items-center">
               <h3>Empotage<template v-if="isDetail">:</template></h3>
               <template v-if="isDetail">
                   <span class="pl-2 h4 text-primary font-weight-bold"> N° Dossier {{ selected.dossier }}&nbsp;</span>
                </template>
          </div>
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item" :class="!isDetail ? 'active': ''"><a href="#">Empotage</a></li>
              <li class="breadcrumb-item active"  v-if="isDetail"><a href="#">Dossier n° {{ selected.dossier }}</a></li>
            </ol>
          </div>
        </div>

        <template v-if="!isDetail">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <ul class="legend mt-4 mb-2 pl-0 flex-1">
                            <li v-for="type in typeCmd" class="d-flex align-items-center">
                                <span class="etat_T m-0 mr-1 border-0" :style="{'background': type.tcolor}"></span> 
                                <label class="m-0 mr-2">{{type.typcmd}}</label>
                            </li>
                        </ul>
                        <a href="#" class="text-white h2 btn btn-primary font-weight-bold" data-toggle="modal" data-target="#newEmpotage">
                             <i class="fa fa-plus" aria-hidden="true"></i> Créer un nouveau rapport
                        </a>
                        
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
                            placeholder="Rechercher par n°dossier, n°plomb, n°tc"
                        />
                    </div>
                </div>
                    <div class="position-relative overflow-hidden">
                        <table class="table">
                            <thead class="thead-blue" :class="[isLoading ? '' : 'hasborder']">
                                 <tr>
                                    <th class="p-2 border-right border-white h6">N° Dossier</th>
                                    <!--th class="p-2 border-right border-white h6">N°TC</th>
                                    <th class="p-2 border-right border-white h6">Type TC</th>
                                    <th class="p-2 border-right border-white h6">N°Plomb</th-->
                                    <th class="p-2 border-right border-white h6">Entrepôt</th>
                                    <th class="p-2 border-right border-white h6" title="Date départ du bâteau">Date Départ</th>
                                    <th class="p-2 border-right border-white h6"  title="Date arrivée du bâteau">Date Arrivée</th>
                                    <th class="p-2 border-right border-white h6">Etat</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Date de création</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                    <th class="text-nowrap p-2 border-right border-white h6 text-right pr-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgStripe preChargeTable" :class="[isLoading ? 'loader-line' : '']"> 
                                <template v-if="!empotage.data || !empotage.data.length">
                                    <tr v-if="checking"><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                                </template>
                                <template v-else>
                                    <tr v-for="empo in empotage.data" :key="empotage.id" class="bg-white position-relative" :class="[{ 'rowActif': empo.id == selected.identifiant, 'rowInactif':empo.id != selected.identifiant }]">
                                        <td class="position-relative align-middle"> 
                                            <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': empo.typeCmd_color} : {'background': '#ccc'}]"></div> {{ empo.reference }}
                                        </td>
                                       
                                        <!--td class="p-2 align-middle">
                                            {{ empo.numContenaire }}
                                        </td>
                                        <td class="p-2 align-middle">
                                           {{ empo.typeContenaire }}
                                        </td>
                                        <td class="p-2 align-middle">
                                           {{ empo.plomb }}
                                        </td-->
                                        <td class="p-2 align-middle">
                                           {{ empo.entrepot }}
                                        </td>
                                        <td class="p-2 align-middle">
                                           {{ empo.dateDepart }}
                                        </td>
                                        <td class="p-2 align-middle">
                                           {{ empo.dateArrivee }}
                                        </td>
                                       
                                        <td class="align-middle">
                                            <span v-if="empo.etat==1" class="badge badge-success">Validé</span>
                                            <span v-if="empo.is_close==1" class="badge badge-secondary">Cloturé</span>
                                            <span v-if="empo.etat==0" class="badge badge-warning">En cours</span>
                                        </td>
                                        <td class="align-middle">{{ empo.date }}</td>
                                        <td class="align-middle">{{ empo.user }}</td>
                                        <td class="align-middle text-right">  
                                             <!--a v-if="pre.etat==1" href="#" title="Voir la facture" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-danger"  @click="showInvoice(empo)" data-toggle="modal" data-target="#openFacture">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a-->
                                            <a v-if="empo.etat==1" href="#" title="Voir la facture" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-danger"  @click="showRapport(empo)" data-toggle="modal" data-target="#openFacture">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a>
                                            <!--button v-if="empo.etat==0"  @click="showDossier(empo)" class="btn btn-info btn-sm">Ouvrir</button-->
                                            
                                            <a href="#" title="Liste contenaire" class="btn p-0 m-1 ml-2  position-relative"  @click="showContenaire(empo)">
                                                <img src="/images/contenaire.png" alt="Contenaire" height="30">
                                                <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white" :class="[empo.nbreContenaireNonValide > 0? 'bg-danger':'']">{{empo.totalContenaire}}</span></a>
                                             <a v-if="empo.etat==0" href="#" title="Complément de document" class="btn p-0 m-1 ml-2  position-relative"  @click="showDocument(empo)" data-toggle="modal" data-target="#openDocument">
                                                        <img src="/images/document_compl.png" alt="Documents" height="30">
                                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white" :class="[getCountDoc(empo.document) == 0? 'bg-danger':'']">{{ getCountDoc(empo.document) > 9 ? '+9' : getCountDoc(empo.document) }}</span>
                                                     
                                                    </a>
                                            <a v-if="empo.etat==0"  title="Editer" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white"  data-toggle="modal" data-target="#newEmpotage" v-on:click="editEmpotage(empo)">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                             <a v-if="empo.etat==0"  title="Cloturer" class="btn bg-success text-white border-success ml-3 m-1 btn-circle border btn-circle-sm m-1 bg-white" v-on:click="cloturer(empo)">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            </a>
                                            <a v-if="empo.etat==0"  title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1 bg-white" v-on:click="deleteEmpotage(empo)">
                                                <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div class="d-flex mt-4 justify-content-center paginationDossier">
                            <pagination
                                :data="empotage"
                                @pagination-change-page="getEmpotage"
                            ></pagination>
                        
                        </div>
                    </div>
                </div>
                
            </div>

        </template>
        <template v-else>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary mb-2 mr-3" @click="back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour
                </button>
                <div class="w-100 overflow-auto">
                    <div class="d-flex flex-row align-items-center justify-content-end outlineBtn">
                        <template v-for="contenaire, index in contenaires.data">
                            <div class="position-relative ml-3">
                                <button v-on:click="contenaireSelectionner(index)" class="btn btn-default d-flex flex-column mb-0 p-0">
                                    <img src="/images/contenaire.png" alt="Contenaire" height="45"  :class="index==currentIndex ? 'activeContent':'imageGrey'">
                                    <span class="badge w-100 mt-1" :class="index==currentIndex ? 'badge-primary':'badge-secondary opacity-7'">{{ contenaire.numContenaire}}</span>
                                </button>
                                <button v-if="contenaire.etat == 1" style="top: -0px; right: -3px;"  v-on:click="reactiver(contenaire, index)" class="badge badge-success position-absolute rounded-circle  border-0" title="Réactiver le contenaire"><i class="fa fa-refresh"></i></button>
                              
                                <button v-if="contenaire.etat == 0" title="Supprimer le contenaire" style="top: 0px; right: 0px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="supprimerContenaire(contenaire)"><i class="fa fa-times"></i></button>
                            </div>
                        </template>
                        <template v-if="!(!contenaires.data || !contenaires.data.length)">
                            <div class="position-relative ml-3" style="width:60px">
                                <button class="btn  btn-transparent rounded-circle mb-0" data-toggle="modal" data-target="#creerContenaire"><span class="h1"><i class="fa fa-plus"></i></span>
                                </button>
                            </div>
                        </template>
                       
                        <template v-else>
                            <div class="position-relative ml-3">
                                <button class="text-white h2 btn btn-primary font-weight-bold" data-toggle="modal" data-target="#creerContenaire">  <span class="mb-0 align-middle"><i class="fa fa-plus"></i></span><span class="mb-0 pl-1">Ajouter un contenaire</span>
                                </button>
                            </div>
                        </template>
                       
                    </div>
                </div>
            </div>
            <div :class="[isLoadingContenaire ? 'loader-line' : '']"></div>
                <template v-if="!contenaires.data || !contenaires.data.length">
                    <hr>
                    <div class="text-center mt-5">
                        <div class="d-inline-block position-relative cursor-pointer" data-toggle="modal" data-target="#creerContenaire">
                            <img src="/images/contenaire.png" alt="Contenaire" class="text-center">
                            <label class="badge badge-success position-absolute rounded-circle p-2 border-0 text-white" style="top: -5px;right: -20px;"><i class="fa fa-plus" style="font-size: 20px;"></i></label> 
                        </div>
                        
                        <br>Créer un contenaire 
                    </div>
                </template>
                <template v-else>
                    <div class="d-flex mb-2">
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border">N°TC: <b>{{ selected.numtc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border mx-3">Type TC: <b>{{ selected.typetc }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border">Plomb: <b>{{ selected.plomb }}</b></div>
                        <div class="h5 mb-0 rounded bg-white py-2 px-3 border mx-3 position-relative">
                            <a @click="showPhoto( selected.photos )" data-toggle="modal" data-target="#openPhotoChargment" class="cursor-pointer ">
                                <span>Photos chargement: </span>
                                
                                     <template v-if="selected.photos.length > 0">
                                        <p class="position-relative d-inline-block pr-4">
                                        <span v-for="(photo, index) in selected.photos" class="border mr-1 mb-2 position-absolute shadow-sm " :style="{top:0.5*index-5+ 'px', left: 2*index+ 'px'}">  
                                            <a class="badge badge-primary position-absolute rounded border-0 text-white" style="top: -5px;right: -13px;"><i class="fa fa-eye"></i></a> 
                                            <img :src="'/assets/photos_chargement/'+photo" width="30" height="30" class="">  
                                        </span></p>
                                      
                                    </template>
                                     <template v-else> 
                                       <span class="position-absolute d-inline-block m-0" style="top: 2px; right: -40px;">
                                        <img src="/images/icone-photo.png" height="30" >
                                       </span>
                                       <a class="badge badge-success position-absolute rounded-circle border-0 text-white" style="top: -5px;right: -50px;"><i class="fa fa-plus"></i></a> 
                                    </template>
                                
                            </a>
                        </div>
                    </div>
                    <div class="mb-3 mb-3 d-block">
                        <VueScrollFixedNavbar>
                        <table class="table table-bordered bg-white"> 
                        <tr>
                            <th class="text-uppercase thead-blue py-1 w-60">Total
                            <span class="ml-2 py-0 px-2 rounded text-lowercase bg-warning" v-if="selected.etat==0">En cours</span>
                            <span class="ml-2 py-0 px-2 rounded text-lowercase bg-success" v-if="selected.etat==1">Validé</span>
                            </th>
                            <th class="text-uppercase thead-blue py-1">Etat contenaire</th>
                        </tr>
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex justify-content-between detailPrecharge position-relative">
                                    <ul class="w-100 legend m-0 p-0 position-absolute" style="top:-10px">
                                        <li class="w-100 text-center font-weight-bold">
                                            <span class="float-none d-inline-block etat_T m-0 mr-1 border-0" :style="{'background': getColorTypeCmd(selected.idCmd)}"></span> 
                                            {{ selected.typeCmd}}
                                        </li>
                                    </ul>
                                    <table class="table m-0 mt-3 table-striped">
                                        <tbody> 
                                             <tr>
                                                <th>Nbre Commande: {{ format_nbr(selected.nbrCmd) }}</th>
                                                <th>Nbre de colis empoté: {{ format_nbr(selected.nbrColis) }}</th>
                                                <th>Poids empoté: {{ format_nbr(selected.poids) }} KG</th>
                                                <th>Volume empoté: {{ format_dec(selected.volume) }} m<sup>3</sup></th>
                                            </tr>
                                      
                                        </tbody>

                                    </table>
                                </div>
                             
                            </td>
                            <td class="pt-1 pb-4">
                                <div class="d-flex m-0 customRadio justify-content-center">


                                    <p class="pr-3 m-0" v-for="contenaire in listContenaire" >

                                        <input type="radio" disabled :value="contenaire.volume" v-model="capacite" @change="switchContenaire(contenaire.id, contenaire.volume)" :id="'cont_'+contenaire.volume" name="radio-group">
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
                    </VueScrollFixedNavbar>
                    </div>
                    <div v-if="selected.etat==0" class="d-flex justify-content-end align-items-center mr-3  mb-3 sucesss"> 
                        <!--button class="btn btn-lg btn-danger" :disabled = "selected.dossier == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button-->
            
                        <div>
                             <!--button class="btn btn-lg btn-secondary text-white mr-3  mx-2" :disabled = "checkedCommandes == '' || selected.isClosed==1 || selected.etat == 0" v-on:click="cloturer()">Cloturer</button-->
                            <button class="btn btn-lg btn-primary" :disabled = "(selected.dossier == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()"><i class="fa fa-check"></i> Valider</button>
                        </div>
                       
                    </div>
                    <hr>

                <!--div class="d-flex justify-content-between align-content-center mb-2">
                    <div class="d-flex align-items-end">
                        <div>
                            <div class="d-flex align-items-center">
                                <label for="paginateRecep" class="text-nowrap mr-2 mb-0"
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
                    
                    <div class="d-flex px-0 align-items-end col-md-4">
                        <input
                            v-model.lazy="searchRecep"
                            type="search"
                            class="form-control"
                            placeholder="Rechercher par n°cmd, n°fe, n°ecv,n°fact, utilisateur, fournisseur..."
                        />
                    </div>
                   
                </div-->  
                <div :class="[selected.etat==1? 'position-relative bgValid':'']" >
                    <table class="table">
                        <thead class="thead-blue position-relative" :class="[run? 'disabled-row':'']">
                             <tr>
                                <!--th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[0])">N°CDE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">N°FE <i class="fa fa-sort" aria-hidden="true" ></i></th-->
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[2])">N°ECV / BBE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6">Fournisseur</th>
                                <th class="p-2 border-right border-white h6">Emballage</th>
                                <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[5])">Poids (KG) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[6])">Volume (m<sup>3</sup>) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">N°Facture</th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">Mnt Facture</th>
                                <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[4])"><i class="fa fa-calendar" aria-hidden="true"></i> Date livraison <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <!--th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap">Crée par</th-->
                                <!--th class="p-2 border-right border-white text-left h6">Préchargé par le client?</th-->
                                <th class="text-nowrap p-2 border-right border-white h6">Dépalettisation</th>
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
                            <!--td class="p-2 align-middle">{{ dry.rencmd }}</td>
                            <td class="p-2 align-middle">{{ dry.refere }}</td-->
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
                            
                            <td class="p-2 align-middle">{{ dry.renufa }}</td>
                            <td class="p-2 align-middle">{{ format_nbr(dry.revafa) }}</td>
                            <td class="p-2 align-middle white-space-nowrape">{{ dry.redali }}</td>
                            <!--td class="p-2 align-middle text-nowrap white-space-nowrap"><i class="fa fa-user" aria-hidden="true"></i> {{ dry.user_created}}</td-->
                            <!--td class="p-2 align-middle">
                                
                                <template v-if="dry.idPre > 0">
                                     <span class="badge badge-success">oui</span> {{dry.prechargeur}}
                                </template>
                                <template v-else>
                                      <span class="badge badge-warning">non</span>
                                </template>
                               
                            </td-->
                             <td class="p-2 align-middle">
                                <img :class="'loaderDepal_'+dry.reidre" style="display:none" src="/images/in-progress.gif"/>
                                <div v-if="dry.depalettisation!='' && dry.depalettisation != null" :id="'labelDepal_'+dry.reidre">
                                    <span>{{ dry.depalettisation }}</span>
                                    <i class="fa fa-edit" v-on:click="editDepaletissation(dry)"></i>
                                </div>
                                <form v-on:submit.prevent="saveDepalettisation(dry)"><input :data-id="dry.reidre" :class="[dry.depalettisation!='' && dry.depalettisation != null? 'd-none':'d-block']" type="text" :id="'depal_'+dry.reidre" v-model="depaletissation[dry.reidre]" style="width:90px" @blur="saveDepalettisation(dry)" @onfocusout="saveDepalettisation(dry)"  class="text-center val-douane"/><button type="submit" class="d-none"></button></form>  
                                
                               
                             </td>
                             <td class="p-2 align-middle">
                                <img :class="'loader_'+dry.reidre" style="display:none" src="/images/in-progress.gif"/>
                                <div v-if="dry.douane!='' && dry.douane != null" :id="'label_'+dry.reidre">
                                    <span>{{dry.douane}}</span>
                                    <i class="fa fa-edit" v-on:click="editDouane(dry)"></i>
                                </div>
                                <form v-on:submit.prevent="saveDouane(dry)"><input :data-id="dry.reidre" :class="[dry.douane!='' && dry.douane != null? 'd-none':'d-block']" type="text" :id="dry.reidre" v-model="douane[dry.reidre]" style="width:90px" @blur="saveDouane(dry)" @onfocusout="saveDouane(dry)"  class="text-center val-douane"/><button type="submit" class="d-none"></button></form>  
                                
                                <!--input v-else class="text-center val-douane" style="width:80px" :disabled="(dry.douane!='' && dry.douane != null)" type="text" :data-id="dry.reidre" v-model="douane[dry.reidre]" @focus="focusDoune(dry)" @blur="saveDouane(dry)" :placeholder="dry.douane"-->   
                             </td>
                            <td class="p-2 text-right">
                                <div class="d-flex justify-content-end align-items-center">
                                    <a title="Voir les détails" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white mr-3 position-relative" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                                    </a>
                                    <span v-if="dry.dossier_empotage_id > 0" class="d-none"><i class="fa fa-check-circle"></i></span>
                                    <label class="switch d-none">
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
                                </div>
                            </td>
                        </tr>
                        </template>

                        </tbody>
                        <tfoot class="thead-blue position-relative" :class="[run? 'disabled-row':'']">
                             <tr>
                                <!--th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[0])">N°CDE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">N°FE <i class="fa fa-sort" aria-hidden="true" ></i></th-->
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[2])">N°ECV / BBE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6">Fournisseur</th>
                                <th class="p-2 border-right border-white h6">Emballage</th>
                                <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[5])">Poids (KG) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[6])">Volume (m<sup>3</sup>) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">N°Facture</th>
                                <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">Mnt Facture</th>
                                <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[4])"><i class="fa fa-calendar" aria-hidden="true"></i> Date livraison <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                <!--th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap">Crée par</th-->
                                <!--th class="p-2 border-right border-white text-left h6">Préchargé par le client?</th-->
                                <th class="text-nowrap p-2 border-right border-white h6">Dépalettisation</th>
                                <th class="text-nowrap p-2 border-right border-white h6">Douane</th>
                                <th class="text-right p-2 border-right border-white h6">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="d-flex mt-4 justify-content-center">
                        <pagination
                            :data="reception"
                            @pagination-change-page="getReception"
                        ></pagination>
                        
                    </div>
                    </div>
                    <hr>
                    <div v-if="selected.etat==0" class="d-flex justify-content-end mr-3  mb-5 sucesss"> 
                        <!--button class="btn btn-lg btn-danger" :disabled = "selected.dossier == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button-->
                        <button class="btn btn-lg btn-primary" :disabled = "(selected.dossier == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()"><i class="fa fa-check"></i> Valider</button>
                    </div>
                </template>
            
        </template>
        <!-- Modal Empotage-->
        <div class="modal fade" id="newEmpotage" tabindex="-1" role="dialog" aria-labelledby="myModalEmpotage"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modifier</template>
                        <template v-else>Nouveau rapport d'empotage</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveEmpotage" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <label for="numDossier"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                            N°Reference
                                        </label>
                                        <template v-if="modeModify">
                                            <input  class="w-65 form-control" type="text" readonly disabled v-model="numDossierEdit">
                                        </template>
                                        <template v-else>
                                            <select  class="form-control w-65" @change="setData($event)">
                                                <option value="">Choisir</option>
                                                <option v-for="dossier in listeDossier" :value="dossier.idpre">{{dossier.numDossier}} {{ getTypeCommande(dossier.type_commandes) }}</option>
                                            </select>
                                        </template>
                                        <!--input class="w-65 form-control" autocomplete="off" id="numDossier" aria-haspopup="true" aria-expanded="false" v-model="keyword" @focus="isFocus"  @blur="handleBlur"
                                        :class="{ 'border-danger': submitted && !$v.keyword.required }" />
                                         <ul class="dropdown-menu filterUl p-2" :class="{'d-block': showDropDown}" v-if="dossiers.length > 0">
                                            <li v-for="dossier in dossiers" :key="dossier.id" >
                                                <a class="p-2" v-text="dossier.numDossier+' '+getTypeCommande(dossier.type_commandes_id)" v-on:click="say(dossier.numDossier, dossier.type_commandes_id,  dossier.entrepots_id)"></a>
                                            </li>
                                        </ul-->
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2 justify-content-between position-relative">
                                        <!--label for="plomb"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                          Type Commande
                                        </label-->
                                        <div class="position-absolute w-100 h-100"></div>
                                        <select readonly class="form-control mr-2" v-model="empotageForm.typeCmd">
                                            <option value="">Type commande</option>
                                            <option v-for="type in typeCmd"  :value="type.id">{{type.typcmd}}</option>
                                        </select>
                                        <select readonly class="form-control ml-2" v-model="empotageForm.idEntrepot">
                                            <option value="">Entrepot</option>
                                            <option v-for="entrepot in listEntrepots" :value="entrepot.id">{{entrepot.nomEntrepot}}</option>
                                        </select>
                                    </div>
                                    
                                    
                                    
                                 </div>
                                
                             </div>
                             <div class="row">
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2 dateW65">
                                        <label class="d-block m-0 text-right  w-35 pr-2" title="Date départ du bâteau">Date Départ</label>
                                        <date-picker v-model="empotageForm.dateDepart" required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                                    </div>
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2 dateW65">
                                        <label class="d-block m-0 text-left pr-2" title="Date arrivée du bâteau">Date Arrivée</label>
                                        <date-picker v-model="empotageForm.dateArrivee" required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                                    </div>
                                 </div>
                             </div>
                             <div class="modal-footer d-flex justify-content-center mt-2"> 

                                <template v-if="modeModify">
                                        <button type="submit" class="btn btn-success">Modifier</button>
                                        <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler la modification</button>
                                </template>
                                <template v-else>
                                    <button type="submit" class="btn btn-success">Enregister</button>
                                    <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler</button>
                                </template>
                               
                            </div>
                         </form>
                    </div>
                    
             </div>
            
          </div>
        </div>
          <!-- Modal Photos Chargement-->
        <div class="modal fade fullscreenModal" id="openPhotoChargment" tabindex="-1" role="dialog" aria-labelledby="myModalPhoto"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="photo">
             <div class="modal-content">
                
                    <div class="modal-header text-left align-items-center">
                        <h4 class="modal-title font-weight-bold">Photos Chargement</h4>
                       
                            <div class="flex-1 text-center">
                                <label class="mb-0">Ajout un fichier</label>
                                <input type="file" id="photoChargement" name="photoChargement" multiple ref="filePhoto" v-on:change="handleFileUploadPhoto()"/>
                                <button class="btn btn-success" v-on:click="savePhoto()">Enregister</button>
                            </div>
                      
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupPhoto" v-on:click="getEmpotage()">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        
                    </div>
                    <div class="modal-body mx-3 overflow-hidden">
                        <div class="d-flex justify-content-between h-100">
                            <div>
                                <div class="d-flex flex-column">
                                    <template v-for="photo, index in tabPhoto">
                                        <div class="position-relative">
                                            <button v-on:click="getPhoto(index)" class="btn rounded-circle  btn-default mb-2" :class="index==currentIndexPhoto ? 'bg-light':''">
                                                <span class="h1"><i class="fa fa-file-image-o"></i></span>
                                            </button>
                                            
                                            <button style="top: -3px; right: -3px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="removePhoto(photo)"><i class="fa fa-times"></i></button>
                                            
                                        </div>
                                    </template>
                                   
                                </div>
                            </div>
                             <template v-if="tabPhoto.length > 0">
                                <embed :src="'/assets/photos_chargement/'+tabPhoto[currentIndexPhoto]" frameborder="0" width="95%" height="450px">
                              </template>
                             <template v-else>
                                <div class="w-100 text-center">Aucune Photo </div> 
                              </template>
                            
                         </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalPhoto()" class="btn btn-warning">Fermer</button>
                  </div>
             </div>
            
          </div>
        </div>
        <!-- Modal Contenire-->
        <div class="modal fade" id="creerContenaire" tabindex="-1" role="dialog" aria-labelledby="myModalContenaire"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModifyContenaire">Modifier</template>
                        <template v-else>Nouveau contenaire</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoupContenaire">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveContenaire" enctype="multipart/form-data" key=1 >
                            
                              <div class="row">
                               
                                  <div class="col-4 my-2 d-flex flex-row justify-content-between align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="tc"  class="d-block m-0 text-left pr-2" style='white-space: nowrap;'>
                                        N° TC
                                       </label>
                                        <input class="w-65 form-control" id="tc" autocomplete="off" v-model="empotageFormContenaire.tc" />
                                    </div>
                                 </div>
                                  <div class="col-4 my-2 d-flex flex-row justify-content-between align-items-center">                                   
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="typetc"  class="d-block m-0 text-left  w-35 pr-2" style='white-space: nowrap;'>
                                       Type TC
                                       </label>
                                        <select class="form-control ml-2" v-model="empotageFormContenaire.typetc">
                                            <option value=''>Choisir le contenaire</option>
                                            <option v-for="contenaire in listContenaire"  :value="contenaire.id">{{contenaire.nom}}</option>
                                        </select>
                                       
                                    </div>
                                 </div>
                                  <div class="col-4 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="plomb"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                       Plomb
                                       </label>
                                        <input class="w-65 form-control" id="plomb" autocomplete="off" v-model="empotageFormContenaire.plomb"/>
                                    </div>
                                    
                                 </div>
                                
                             </div>
                             <div class="modal-footer d-flex justify-content-center mt-2"> 

                                <template v-if="modeModifyContenaire">
                                        <button type="submit" class="btn btn-success">Modifier</button>
                                        <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler la modification</button>
                                </template>
                                <template v-else>
                                    <button type="submit" class="btn btn-success">Créer</button>
                                    <button type="button" v-on:click="closeModalContenaire()" class="btn btn-warning">Annuler</button>
                                </template>
                               
                            </div>
                         </form>
                    </div>
                    
             </div>
            
          </div>
        </div>
         <!-- Modal Facture-->
        <div class="modal fade" id="openFacture" tabindex="-1" role="dialog" aria-labelledby="myModalFacture"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Rapport d'empotage</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupPdf">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <template v-if="pdfFileModal != null">
                            <embed :src="'/pdf/empotage/'+pdfFileModal" frameborder="0" width="100%" height="450px">
                          </template>
                         <template  v-else> Auncun fichier </template>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalPdf()" class="btn btn-warning">Fermer</button>
                  </div>
             </div>
            
          </div>
        </div>
        <modalDetailsCommande></modalDetailsCommande>
        <!-- Modal Document-->
        <div class="modal fade fullscreenModal" id="openDocument" tabindex="-1" role="dialog" aria-labelledby="myModalFacture"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left align-items-center">
                        <h4 class="modal-title font-weight-bold">Documents</h4>
                       
                            <div class="flex-1 text-center">
                                <label class="mb-0">Ajout un fichier</label>
                                <input type="file" id="file" name="file" multiple ref="fileDoc" v-on:change="handleFileUploadDoc()"/>
                                <button class="btn btn-success" v-on:click="saveDocs()">Enregister</button>
                            </div>
                      
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupDoc" v-on:click="getEmpotage()">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        
                    </div>
                    <div class="modal-body mx-3 overflow-hidden">
                        <div class="d-flex justify-content-between h-100">
                            <div>
                                <div class="d-flex flex-column">
                                    <template v-for="doc, index in tabDoc">
                                        <div class="position-relative">
                                            <button v-on:click="getDoc(index)" class="btn rounded-circle  btn-default mb-2" :class="index==currentIndexDoc ? 'bg-light':''">
                                                <span class="h1"><i class="fa fa-file"></i></span>
                                            </button>
                                            
                                            <button style="top: -3px; right: -3px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="removeDoc(doc)"><i class="fa fa-times"></i></button>
                                            
                                        </div>
                                    </template>
                                   
                                </div>
                            </div>
                             <template v-if="tabDoc.length > 0">
                                <embed :src="'/assets/documents/'+tabDoc[currentIndexDoc]" frameborder="0" width="95%" height="450px">
                              </template>
                             <template v-else>
                                <div class="w-100 text-center">Aucun document </div> 
                              </template>
                            
                         </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalDoc()" class="btn btn-warning">Fermer</button>
                  </div>
             </div>
            
          </div>
        </div>

    </div>
</template>
<script>
    import { EventBus } from '../../event-bus';
    import {VueScrollFixedNavbar} from "vue-scroll-fixed-navbar";
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import VueTimepicker from 'vue2-timepicker';
    import 'vue2-timepicker/dist/VueTimepicker.css'; 

    import modalDetailsCommande from '../../components/modal/detailsCommande.vue';
    import { PdfMakeWrapper, Table, QR, Img} from 'pdfmake-wrapper';

    import { ITable } from 'pdfmake-wrapper/lib/interfaces'; 

    import pdfFonts from "pdfmake/build/vfs_fonts";
    import { required, minLength, between } from 'vuelidate/lib/validators';
    import PageLoader from '../../components/PageLoader.vue';  
    export default {
        props: [ 
            'idClient',
            'listFournisseurs',
            'typeCmd',
            'defaultContenaire',
            'listContenaire',
            'clientCurrent',
            'currentEntite',
            'listEntrepots',
            'listeDossier',
            'idEntite'
        ],  
        components: {
            PageLoader,
            VueScrollFixedNavbar
          },
        data() { 
            return {
                isLoading: true,
                submitted_add: false,
                submitted: false,
                paginate: 10,
                selectedTypeCmd: "",
                typeCommandeUsed: {},
                empotage:{},
                prechargementDossier: {},
                reception: {},
                paginateRecep: 200, // bug lors de la selection de la 2 page les stats sont renitialisé
                choose:'',
                dossiers: [],
                selected: {
                    identifiant:'',
                    typeCmd: '',
                    dossier: '',
                    nbrCmd: 0,
                    nbrColis: 0,
                    poids: 0,
                    volume: 0,
                    dateDebut: '',
                    dateCloture: '',
                    isSelected: false,
                    idCmd: '',
                    etat: 0,
                    currentPage: 1,
                    plomb:'',
                    typetc:'',
                    numtc:'',
                    IDContenaire: '',
                    capacite: '',
                    isClosed: false,
                    entrepot: '',
                    idEntrepot:'',
                    photos:''
                    
                },
                modeModify: false,
                capacite: this.defaultContenaire.volume,
                nbrContenaire: 0,
                volumePercent:0,
                empotageForm :{
                    id: '',
                    reference: '',
                    tc: '',
                    typetc: '',
                    plomb: '',
                    typeCmd: '',
                    idEntrepot:'',
                    dateDepart: '',
                    dateArrivee: ''

                },
                empotageFormContenaire: {
                    reference: '',
                    tc: '',
                    typetc: '',
                    plomb: ''
                },
                checkedCommandes: [],
                isDetail: false,
                commandeSelected: [],
                commandeNoSelected: [],
                keyword: null,
                typeCommande: '',
                showDropDown: false,
                douane:[],
                loader:[],
                eventCmdSelected: {
                    ischecked: -1,
                    idcmd: ''

                },
                search: "",
                etatFiltre: "",
                checking: false,
                pdfFileModal: null,
                numDossier: "",
                searchRecep: '',
                numDossierEdit: '',
                // Sort column
                columns: ['rencmd', 'refere', 'reecvr', 'renufa', 'redali', 'repoid', 'revolu', 'totalColis'],
                sortedColumn: '',
                order: 'asc',
                run: false,
                modeModifyContenaire: false,
                contenaires: {},
                currentIndex: 0,
                currentIndexDoc: 0,
                attachments: [],
                tabDoc: [],
                currentIndexPhoto: 0,
                attachmentsPhoto: [],
                tabPhoto: [],
                depaletissation: [],
                isLoadingContenaire: false 
            }

        },
        validations: {
            typeCommande: { required },
            keyword: { required }
             
        },
        watch: {
            keyword(after, before) {
                this.getResults();
            },
            paginate: function(){
               this.getEmpotage();
            },
            selectedTypeCmd: function(value) {
                this.getEmpotage();
            },
            search: function(value) {
                this.getEmpotage();
            },
            searchRecep: function(value) {
                this.getReception();
            },
            etatFiltre: function(value) {
                this.getEmpotage();
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
            switchContenaire(id, volume){
                this.capacite = volume; 
                this.defaultContenaire.id = id;
                this.setProgressCont(this.selected.volume);
            },
            getTypeCommande(id){
                for(var i=0; i<this.typeCmd.length;i++){
                    if(this.typeCmd[i].id === id){
                        return this.typeCmd[i].typcmd;
                    }
                }
                return id;
            },
            getColorTypeCmd(id){
                 for(var i=0; i<this.typeCmd.length;i++){
                    if(this.typeCmd[i].id === id){
                        return this.typeCmd[i].tcolor;
                    }
                }
                return "#aaa";
            },
            empoter(event, cmd){
                var ischecked=0;
               
                if (event.target.checked) {
                    ischecked=1;
                }

                this.eventCmdSelected.ischecked=ischecked;
                this.eventCmdSelected.idcmd = cmd.reidre;
                
                
                const data = new FormData();
                data.append('idEmpotage', this.selected.identifiant);
                data.append('idreception', cmd.reidre);
                data.append('ischecked', ischecked);


                axios.post("/gerer/dossier/setEmpotage", data).then(response => {
                    let res = response.data.result;
        
                    this.selected.nbrCmd   = res[0].total_cmd;
                    this.selected.nbrColis = parseInt(res[0].total_colis==null ? 0 : res[0].total_colis) + parseInt(res[0].total_palette==null ? 0 : res[0].total_palette);
                    this.selected.poids    = res[0].total_poids==null ? 0 : res[0].total_poids;
                    this.selected.volume   = res[0].total_volume==null ? 0 : res[0].total_volume;



                    this.setProgressCont(res[0].total_volume);
                    this.getEmpotage(); // refresh tableau prechargement
                    
                    this.getCmdSelected(this.selected.identifiant,this.selected.idCmd, false);
                    this.getReception();

               
                  
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
            getEmpotage(page = 1){
                this.isLoading=true;    
                axios.get('/empotage/list/'+this.idClient+"/"+this.idEntite+'?page=' + page + "&paginate=" + this.paginate+ "&typeCmd=" + this.selectedTypeCmd+"&keysearch="+this.search+"&etatFiltre="+this.etatFiltre).then(response => {
                    this.empotage = response.data;
                    var self = this;
                    setTimeout(function(){
                        self.isLoading = false;
                    },500);
                    this.checking=true;
                });
            }, 
            getReception(page = 1){
                this.isLoading=true;
               
                axios.get('/gerer/dossier/empotage/reception/'+this.idClient+"/"+this.selected.idCmd+'?page=' + page + "&paginate=" + this.paginateRecep+"&idEntrepot="+this.selected.idEntrepot+"&ref="+this.selected.dossier+"&id_empotage="+this.selected.identifiant+"&keysearch="+this.searchRecep+"&column="+this.sortedColumn+"&order="+this.order+"&contenaireSelected="+this.contenaires.data[this.currentIndex].id+"&contenaireEtat="+this.contenaires.data[this.currentIndex].etat).then(response => {

                    this.reception = response.data;

                    //if(this.selected.etat==0){
                        this.selected.nbrCmd   = 0;
                        this.selected.nbrColis  = 0;
                        this.selected.poids    = 0;
                        this.selected.volume   = 0;

                        var nbColis=0;
                        var nbCommande=0;
                        for(var i=0; i<this.reception.data.length; i++){
                            var obj = this.reception.data[i];


                            if((obj.dossier_empotage_id > 0 && this.selected.identifiant == obj.dossier_empotage_id) ){


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
                   // }
                   
                    this.isLoading = false;
                });
            },
            currentDateTime() {
                const current = new Date();
                const date = current.getDate()+'/'+(current.getMonth()+1)+'/'+current.getFullYear();
                const time = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
                const dateTime = date;

                return dateTime;
            },
            getCmdSelected(id, typeCommande, genererPDF){
                axios.get('/gerer/getCmd/empoter/'+id+'/'+typeCommande+"?IDContenaire="+this.contenaires.data[this.currentIndex].id).then(response => {
                    this.checkedCommandes = response.data.result;
                     if(genererPDF){
                        this.generatePdf(true);
                    }
                });
            },
            valider(){
                this.commandeSelected = [];
                this.commandeNoSelected = [];
                var self = this;

                $(".val-douane").each(function(){
                    if($(this).val()!='' || $('#label_'+$(this).attr("data-id")+' span').text()!=''){
                        self.commandeSelected.push($(this).attr("data-id"));
                    }else{
                        self.commandeNoSelected.push($(this).attr("data-id"));
                    }
                    
                });


                if(!this.commandeSelected.length>0){
                    Vue.swal.fire(
                          'warning!',
                          'Renseigner au moins un n° douane avant de valider!',
                          'warning'
                        );
                    return false;
                }

                Vue.swal.fire({
                      title: 'Confirmez la validation',
                      text: "N°TC: "+this.selected.numtc+" Plomb:"+this.selected.plomb,
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

                           axios.post("/gerer/validationEmpotage/valider/"+this.idClient+"/"+this.idEntite, {
                                'idsCmd': this.commandeSelected,
                                'ignored': this.commandeNoSelected,
                                'idEmpotage' : this.selected.identifiant,
                                'typeCmd' : this.selected.idCmd,
                                'id_dossier': this.selected.dossier,
                                'IDContenaire': this.contenaires.data[this.currentIndex].id

                            }).then(response => {


                                // Envoi notification avec le fichier PDF

                                this.getCmdSelected(this.selected.identifiant,this.selected.idCmd, true);
                              
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
                        }
                    });



                
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

                    var entete=[];
                    entete.push([{ image: base64Img, width: 100}, {text: ''}, {text: 'Date: '+ that.currentDateTime(), fontSize: 8, alignment: 'right', lineHeight: 1}]); 
                    entete.push([
                        {text:that.currentEntite['nom']+"\nTél: "+ that.currentEntite['telephone']+"/ Fax: "+that.currentEntite['fax']+"\nEmail: "+that.currentEntite['email']+"\nAdresse: "+that.currentEntite['adresse']},

                        {text: 'N°Dossier '+that.selected.dossier+'\n'+that.selected.typeCmd, fontSize: 20, bold: true, alignment: 'center', color: '#3490dc'}, 

                        {text: ['Entrepôt: ', {text: that.selected.entrepot, fontSize: 14}],  alignment: 'right'}]);
                    
                    //entete.push([{text: 'N°Dossier '+that.selected.dossier, fontSize: 15, alignment: 'center', lineHeight: 2, colSpan: 3}]);
                    entete.push([{text: "\n\nDestination: "+that.clientCurrent.pays, fontSize: 13, alignment: 'left', colSpan: 3}]);
                    entete.push([{text: "Rapport d'empotage pour le compte de: "+" "+that.clientCurrent.clnmcl+"\n", fontSize: 16, alignment: 'center', colSpan: 3}]);
                    
                  

                    var header = new Table(entete).widths('*').layout('noBorders').margin([0, 0, 0, 7]).end;

                    var infos_contenaire = [];
                    infos_contenaire.push([
                    {text:['N° TC: ', {text: that.selected.numtc, fontSize:12, bold: true}]},
                    {text:['TYPE TC: ', {text: that.selected.typetc+' DRY', fontSize:12, bold: true}]},{text:['PLOMB: ', {text: that.selected.plomb, fontSize:12, bold: true}]}]);

                    var contenaire = new Table(infos_contenaire).widths(['28%', '28%', '28%', '28%']).margin([0, 0, 0, 7]).end;

                    var totaux = [[{text: 'Nb de commande', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Nb Colis empotés', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Poids(KG) empoté', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Volume m3 empoté', fontSize: 10, bold: true, alignment: 'center'} ]];
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

                    const data = [];

                    const headerTab = ['Référence', 'Emballage', 'Designation', 'Poids(KG)', 'Volume(m3)', 'Factures','Dépalettisation', 'Douanes'];

                    data.push(headerTab); 

                    var legend1 = "";
                    var legend2 = "";

                    console.log("RES", that.checkedCommandes);

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

                        cmdCell.push(obj.refere+" "+prio);

                        const item = [cmdCell, emballage ,obj.fournisseurs, obj.repoid, obj.revolu, obj.renufa, obj.depalettisation, obj.douane];
                        data.push(item);
                    }

                    var table = new Table(data).widths([70,70,'*',60,60,60,80,80]).layout({
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

                    pdf.add(contenaire);

                    pdf.add(table);

                    
                    pdf.add(
                        pdf.ln(2)
                    );


                    // formater le nom du fichier

                    var labelCmd1 =  that.selected.typeCmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
                
                    var nameFile = 'dossier-'+that.formatage(that.selected.dossier)+'_'+labelCmd1+"_numtc-"+that.formatage(that.selected.numtc)+"_plomb-"+that.formatage(that.selected.plomb)+".pdf";

                    console.log("Name file=", nameFile); 


                    var qrTotaux = [];

                    var legendTotaux = [];

                    legendTotaux.push([tabtotaux, {text: legend1+' '+legend2, fontSize: 10, bold: true, alignment: 'left'}]);

                    qrTotaux.push([legendTotaux, new QR(location.origin+"/pdf/empotage/"+nameFile).fit(80).alignment('right').end]); 

                    var tableQR = new Table(qrTotaux).widths(['*', 80]).layout('noBorders').end;

                    pdf.add(tableQR);

                    
                    if(isnotification){

                    var self = that; 
                    pdf.create().getDataUrl(function(url) { 

                        console.log(url, "File PDF"); 


                        axios.post("/gerer/empotage/notification/"+self.clientCurrent['id']+"/"+self.idEntite, {
                            'idsCmd': self.commandeSelected,
                            'id_dossier' : self.selected.dossier,
                            'numtc': self.selected.numtc,
                            'typetc': self.selected.typetc,
                            'plomb':  self.selected.plomb,
                            'typeCommande': self.selected.typeCmd,
                            'typeCmd': self.selected.typeCmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase(), 
                            'base64_file_pdf': url,
                            'IDclient': self.idClient,
                            'namefile': nameFile

                        }); /*.then(response => {
                            Vue.swal.close();
                                Vue.swal.fire(
                              'succés!',
                              'validé avec succés!',
                              'success'
                            ).then((result) => {
                                // redirection   
                                location.reload();
                            });
                        }).catch(err => {
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
               

                /* pdf.create().open(); // download() or open()


               


                pdf.create().getBase64((data) => {
                    axios.post("/gerer/empotage/savepdf", {
                    'dataPdf': data,
                    'id_rapport': this.selected.identifiant

                     }).then(response => {
                  
                    });
                });

                */
        
               },
               showContenaire(empo){
                    this.isDetail = true;
                    this.isLoadingContenaire = true;
                    this.selected.identifiant = empo.id;
                    this.selected.dossier     = empo.reference;
                    this.selected.typeCmd     = empo.typeCommande;
                    this.selected.idCmd       = empo.typeCommandeID;
                    this.selected.entrepot    =  empo.entrepot;
                    this.selected.idEntrepot  =  empo.entrepotID;
                    // get all contener
                      axios.get('/gerer/empotage/getContenaire/'+empo.id)
                        .then(response => {
                            this.contenaires = response.data;
                            if(this.contenaires.data.length > 0){
                              this.contenaireSelectionner(0);

                            }
                            this.isLoadingContenaire = false;
                    }).catch(error => {});
               },
               contenaireSelectionner(index){

                    this.currentIndex = index;

                    this.selected.etat = this.contenaires.data[index].etat;


                    this.selected.plomb = this.contenaires.data[index].plomb;
                    this.selected.numtc = this.contenaires.data[index].numContenaire;
                    this.selected.typetc = this.contenaires.data[index].typeContenaire;

                    this.selected.IDContenaire = this.contenaires.data[index].id;


                    this.selected.capacite = this.contenaires.data[index].capaciteContenaire;
                    this.capacite = this.contenaires.data[index].capaciteContenaire;

                    this.selected.poids      = this.contenaires.data[index].total_poids;
                    this.selected.volume     = this.contenaires.data[index].total_volume;
                    this.selected.nbrColis   = this.contenaires.data[index].colis_total;

                    this.selected.photos = this.contenaires.data[index].photos_chargement;

                    this.setProgressCont(this.contenaires.data[index].total_volume);
                    this.getReception();

               },
               showDossier(dossier){

                    this.commandeSelected = [];
                    this.commandeNoSelected = [];
                    
                    this.selected.identifiant = dossier.id;
                    this.selected.dossier         = dossier.reference;
                    this.selected.nbrCmd     = dossier.nbrCmd;
                    this.selected.nbrColis   = parseInt(dossier.total_colis)+parseInt(dossier.total_pallette);
                    this.selected.poids      = dossier.total_poids;
                    this.selected.volume     = dossier.total_volume;
                    this.selected.typeCmd    = dossier.typeCommande;
                    this.selected.dateDebut  = dossier.dateDebut;
                    this.selected.dateCloture  = dossier.dateCloture;
                    this.selected.isSelected = true;
                    this.selected.idCmd      = dossier.typeCommandeID;
                    this.selected.etat       = dossier.etat;
                    this.selected.numtc      = dossier.numContenaire;
                    this.selected.typetc     = dossier.typeContenaire;
                    this.selected.plomb      = dossier.plomb;
                    this.selected.capacite   = dossier.capaciteContenaire;
                    this.selected.isClosed   = dossier.is_close;
                    this.capacite = dossier.capaciteContenaire;
                    this.selected.entrepot   = dossier.entrepot;
                    this.selected.idEntrepot = dossier.entrepotID;

                    this.setProgressCont(dossier.total_volume);
                    this.getReception();
                    this.getCmdSelected(this.selected.identifiant, this.selected.idCmd, false);

               },
               back(){
                this.getEmpotage(this.selected.currentPage);
                this.isDetail = false;
                this.isLoadingContenaire = false;
                this.commandeSelected = [];
                this.commandeNoSelected = [];
                this.eventCmdSelected.ischecked = -1;
                this.eventCmdSelected.idcmd = '';
                this.run = false;
                this.reception = {};

               },
                getResults() {
                    axios.get('/gerer/livesearch', { params: { keyword: this.keyword } })
                        .then(res => this.dossiers = res.data)
                        .catch(error => {});
                },
                say: function (message, typecmd, entrepot) { 
                    this.keyword = message;
                    this.showDropDown=false;
                    this.empotageForm.typeCmd = typecmd;
                    this.empotageForm.idEntrepot = entrepot;
                },
                isFocus(){
                   this.showDropDown=true;
                },
                handleBlur(){
                    var thiss=this;
                    setTimeout(function(){
                        thiss.showDropDown=false;
                    },500);
                    
                },
                 closeModal(){
                    this.$refs.closePoup.click();
                    this.flushData();
                    this.submitted = false;
                    this.modeModify = false;

                },
                closeModalContenaire(){
                    this.$refs.closePoupContenaire.click();
                    this.flushDataContenaire();
                    this.modeModifyContenaire = false;

                },
                reactiver(contenaire, index){
                     Vue.swal.fire({
                      title: 'Confirmez la réactivation',
                      text: "Contenaire n° "+contenaire.numContenaire,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '',
                      confirmButtonText: 'Oui, Valider!'
                    }).then((result) => {
                      if (result.isConfirmed) {

                             Vue.swal({
                                title: 'Réactivation',
                                html: '<b>En cours...</b>',
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Vue.swal.showLoading()
                                },
                                willClose: () => {

                                }
                            }).then((result) => {});

                           axios.post("/gerer/empotage/reactiver/"+contenaire.id, {
                                
                            }).then(response => {
                                Vue.swal.fire(
                                  'succés!',
                                  'Réactivé avec succés!',
                                  'success'
                                ).then((result) => {


                                    // rafraichir all contenaire 
                                      axios.get('/gerer/empotage/getContenaire/'+this.selected.identifiant)
                                        .then(response => {
                                            this.contenaires = response.data;
                                            if(this.contenaires.data.length > 0){
                                                this.contenaireSelectionner(index);

                                            }
                                    }).catch(error => {});
                                   
                                });   
                            });
                        }
                    });
                },
                saveContenaire(){
                    const data = new FormData();
                    data.append('idEmpo', this.selected.identifiant);
                    data.append('tc', this.empotageFormContenaire.tc);
                    data.append('typetc', this.empotageFormContenaire.typetc);
                    data.append('plomb', this.empotageFormContenaire.plomb);

                    let action = "createContenaireEmpotage";

                    if(this.modeModify){
                        data.append('id', this.empotageForm.id);    
                        action = "modifyContenaireEmpotage";
                    }

                    axios.post("/gerer/empotage/"+action, data).then(response => {
                      
                        if(response.data.code==0){
                            this.$refs.closePoupContenaire.click();
                            this.flushData();
                            Vue.swal.fire(
                                  'succés!',
                                  'Contenaire crée avec succés!',
                                  'success'
                                ).then((result) => {

                                    this.flushContenaire();
                                    // rafraichir all contenaire 
                                      axios.get('/gerer/empotage/getContenaire/'+this.selected.identifiant)
                                        .then(response => {
                                            this.contenaires = response.data;
                                            if(this.contenaires.data.length > 0){
                                                this.contenaireSelectionner(this.contenaires.data.length-1);

                                            }
                                    }).catch(error => {});
                                   
                                });   
                            
                        }else{
                             Vue.swal.fire(
                              'error!',
                              response.data.message,
                              'error'
                            )
                        }
                        this.modeModifyContenaire = false;
                       
                    });
                },
                flushContenaire(){
                    this.empotageFormContenaire.tc = '';
                    this.empotageFormContenaire.typetc = '';
                    this.empotageFormContenaire.plomb = '';
                },
                saveEmpotage(){
                    this.submitted = true;
                    
                    var date1 = new Date(this.empotageForm.dateDepart);
                    var date2 = new Date(this.empotageForm.dateArrivee);

                     if(date1.getTime() > date2.getTime()){
                        Vue.swal.fire(
                              'warning!',
                              'Date départ incorrecte!',
                              'warning'
                            );
                        this.submitted_circle=false;

                        return false;
                    }
                    
                    
                    const data = new FormData();
                    data.append('reference', this.empotageForm.reference);
                    data.append('typeCmd', this.empotageForm.typeCmd);
                    data.append('idClient', this.idClient);
                    data.append('idEntite', this.idEntite);
                    data.append('idEntrepot', this.empotageForm.idEntrepot);
                    data.append('date_depart', this.empotageForm.dateDepart);
                    data.append('date_arrivee', this.empotageForm.dateArrivee);

                    let action = "createEmpotage";

                    if(this.modeModify){
                        data.append('id', this.empotageForm.id);    
                        action = "modifyEmpotage";
                    }

                    axios.post("/gerer/empotage/"+action, data).then(response => {
                      
                        if(response.data.code==0){
                            this.$refs.closePoup.click();
                            this.flushData();
                            Vue.swal.fire(
                              'succés!',
                              'Empotage enregistré avec succés!',
                              'success'
                            );
                            this.getEmpotage();
                            
                        }else{
                             Vue.swal.fire(
                              'error!',
                              response.data.message,
                              'error'
                            )
                        }
                        this.submitted = false;
                        this.modeModify = false;
                       
                    });
                },
                flushData(){
                    this.keyword = "";
                    this.empotageForm.tc = "";
                    this.empotageForm.typetc= "";
                    this.empotageForm.plomb= "";
                    this.empotageForm.typeCmd = "";
                    this.empotageForm.idEntrepot = "";
                },
                flushDataContenaire(){
                    this.empotageFormContenaire.tc = "";
                    this.empotageFormContenaire.typetc = "";
                    this.empotageFormContenaire.plomb = "";
                },
                setData(event) { 
                    for(var i=0; i< this.listeDossier.length; i++){
                        if(this.listeDossier[i].idpre==event.target.value){
                            this.empotageForm.reference = this.listeDossier[i].numDossier;
                            this.empotageForm.typeCmd = this.listeDossier[i].type_commandes;
                            this.empotageForm.idEntrepot = this.listeDossier[i].entrepots;
                        }
                    }
                   
                  
                },
                saveDepalettisation(cmd){
                    this.run = true;
                    $(".loaderDepal_"+cmd.reidre).show();

                    var value = $("#depal_"+cmd.reidre).val();  

                   
                    const data = new FormData();
                    data.append('idEmpotage', this.selected.identifiant);
                    data.append('contenaireSelected', this.contenaires.data[this.currentIndex].id);
                    data.append('idreception', cmd.reidre);
                    data.append('depalettisation', value);



                    axios.post("/gerer/updateDepalettisation", data).then(response => {
                        let res = response.data.result;
                        this.getReception();
                        $(".loaderDepal_"+cmd.reidre).hide(); 

                        if(value!=''){
                            $("#labelDepal_"+cmd.reidre).show();
                            $("#depal_"+cmd.reidre).hide();
                        }

                   
                      
                    });
                },
                saveDouane(cmd){ 
                    this.run = true;
                    $(".loader_"+cmd.reidre).show();
                    /*const data = new FormData();
                    data.append('id', id);
                    data.append('douane', this.douane[id]);
                    axios.post("/gerer/updateDouane", data).then(response => {
                        $(".loader_"+id).hide(); 
                    });*/

                    var douane = $("#"+cmd.reidre).val(); //this.douane[cmd.reidre];   

                   
                    const data = new FormData();
                    data.append('idEmpotage', this.selected.identifiant);
                    data.append('contenaireSelected', this.contenaires.data[this.currentIndex].id);
                    data.append('idreception', cmd.reidre);
                    data.append('douane', douane);



                    axios.post("/gerer/updateDouane", data).then(response => {
                        let res = response.data.result;
            
                        this.selected.nbrCmd   = res[0].total_cmd;
                        this.selected.nbrColis = parseInt(res[0].total_colis==null ? 0 : res[0].total_colis) + parseInt(res[0].total_palette==null ? 0 : res[0].total_palette);
                        this.selected.poids    = res[0].total_poids==null ? 0 : res[0].total_poids;
                        this.selected.volume   = res[0].total_volume==null ? 0 : res[0].total_volume;



                        this.setProgressCont(res[0].total_volume);
                        this.getEmpotage(); // refresh tableau prechargement
                        
                        this.getCmdSelected(this.selected.identifiant,this.selected.idCmd, false);
                        this.getReception();
                        $(".loader_"+cmd.reidre).hide(); 

                        if(douane!=''){
                            $("#label_"+cmd.reidre).show();
                            $("#"+cmd.reidre).hide();
                        }

                   
                      
                    });

                },
                focusDoune(cmd){
                },
                cloturer(empotage){
                     Vue.swal.fire({
                      title: 'Confirmez la cloture',
                      text:   'N° Dossier: '+empotage.reference,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#38c172',
                      //cancelButtonColor: '#f0c867',
                      confirmButtonText: 'Oui, cloturer!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                            axios.post('/gerer/empotage/cloturer/'+empotage.id+"?id_dossier="+empotage.reference+"&typeCmd="+empotage.typeCommandeID).then(response => {
                                console.log(response);
                                if(response.data.code == 0){
                                    Vue.swal.fire(
                                      'Cloturé!',
                                      'Dossier cloturé.',
                                      'success'
                                    ).then((result) => {
                                        // redirection   
                                        location.reload();
                                    }); 
                                 }else{
                                    Vue.swal.fire(
                                      'Warning!',
                                      response.data.message,
                                      'warning'
                                    );
                                 }

                            });
                      
                      }
                    })
               },
               supprimerContenaire(contenaire){
                     Vue.swal.fire({
                      title: 'Confirmez la suppression',
                      text:   'N° Contenaire: '+contenaire.numContenaire,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#e3342f',
                      //cancelButtonColor: '#f0c867',
                      confirmButtonText: 'Oui, supprimer!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                            axios.post('/gerer/empotage/contenaire/suppression/'+contenaire.id).then(response => {
                                
                                if(response.data.code == 0){


                                Vue.swal.fire(
                                  'Supprimé!',
                                  'Contenaire supprimé',
                                  'success'
                                ).then((result) => {
                                    // rafraichir all contenaire 
                                      axios.get('/gerer/empotage/getContenaire/'+this.selected.identifiant)
                                        .then(response => {
                                            this.contenaires = response.data;
                                            if(this.contenaires.data.length > 0){
                                                this.contenaireSelectionner(0);

                                            }
                                    }).catch(error => {});
                                   
                                });   
                                 }else{
                                    Vue.swal.fire(
                                      'Error!',
                                      '',
                                      'error'
                                    );
                                 }

                            });
                      
                      }
                    })
               },
               editEmpotage(empotage){
                    this.modeModify=true;
                    this.empotageForm.id = empotage.id;
                    //this.keyword=empotage.reference;
                    this.empotageForm.reference =empotage.reference;
                    this.empotageForm.tc       =empotage.numContenaire;
                    this.empotageForm.typetc   =empotage.IDContenaire;
                    this.empotageForm.plomb    =empotage.plomb;
                    this.empotageForm.typeCmd  =empotage.typeCommandeID;
                    this.empotageForm.idEntrepot = empotage.entrepotID;
                    this.empotageForm.dateDepart= empotage.dateDepartEng;
                    this.empotageForm.dateArrivee= empotage.dateArriveeEng;
                    this.numDossierEdit = empotage.reference;
               },
               deleteEmpotage(empotage){
                    Vue.swal.fire({
                      title: 'Confirmez la suppression',
                      text: "Dossier n° "+empotage.reference,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Oui, supprimer!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                            axios.delete('/gerer/deleteEmpotage/'+empotage.id+'/'+this.idEntite+"?idClient="+this.idClient).then(response => {
                                Vue.swal.fire(
                                    'Supprimé!',
                                    'Dossier supprimé avec succés.',
                                    'success'
                                ).then((result) => {
                                    // redirection   
                                    location.reload();
                                });   
                            });
                      
                      }
                    })
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
                format_nbr(mnt){
                    if(mnt != '' && mnt != null){
                        return mnt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                    }
                    return mnt;
                },
                showRapport(empo){
                    var labelCmd = empo.typeCommande.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
                
                    this.pdfFileModal = 'dossier-'+this.formatage(empo.reference)+'_'+labelCmd+"_numtc-"+this.formatage(empo.numContenaire)+"_plomb-"+this.formatage(empo.plomb)+".pdf";
                },
                closeModalPdf(){
                     this.$refs.closePoupPdf.click();
                },
                getCountDoc(doc){
                    if(Array.isArray(doc)){
                        return doc.length;
                    }
                    return 0;
                },
                handleFileUploadDoc(){
                    this.attachments = [];
                    for(var i=0; i<this.$refs.fileDoc.files.length;i++){
                        this.attachments.push(this.$refs.fileDoc.files[i]);
                    }
                },
                formatage(str){
                    return str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
                   },
                saveDocs(){
                const data = new FormData();

                if(!this.attachments.length > 0){
                     Vue.swal.fire(
                              '',
                              'Ajouter un document avant de valider!',
                              'warning'
                            )   
                    return false;
                }
                
                data.append('file[]', this.attachments);

                for (let i = 0; i < this.attachments.length; i++) {
                    data.append('files' + i, this.attachments[i]);
                }


                data.append('TotalFiles', this.attachments.length);

                data.append('Document[]', this.tabDoc); 

                axios.post("/saveDocs/"+this.currentEmpotage, data,  {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            } 
                    }).then(response => {
                       
                        this.$refs.fileDoc.value = null;
                        if(response.data.code==0){
                             this.tabDoc = response.data.file;
                             this.currentIndexDoc = 0;
                            Vue.swal.fire(
                              'succés!',
                              'Document(s) ajouté(s) avec succés!',
                              'success'
                            )         

                        }else{
                             Vue.swal.fire(
                              'error!',
                              response.data.message,
                              'error'
                            )
                        }
                       
                    });
                },
                removeDoc(nameDoc){

                Vue.swal.fire({
                  title: 'Confirmez la suppression du document',
                  text: nameDoc,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                       // remove Doc
                       const data = new FormData();

                        data.append('Document[]', this.tabDoc); 
                        data.append('nameFile', nameDoc); 

                        axios.post("/removeDocs/"+this.currentEmpotage, data,  {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    } 
                            }).then(response => {
                               
                                if(response.data.code==0){
                                     this.tabDoc = response.data.file;
                                     this.currentIndexDoc = 0;
                                    Vue.swal.fire(
                                      'succés!',
                                      'Document(s) supprimé(s) avec succés!',
                                      'success'
                                    )         

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
             closeModalDoc(){
                this.$refs.closePoupDoc.click();
                this.getEmpotage();
            },
            showDocument(empo){
                this.tabDoc = [];
                this.currentEmpotage = empo.id;
                if(Array.isArray(empo.document)){
                    this.tabDoc = empo.document;
                }
            },
             getDoc(index){
                this.currentIndexDoc = index;
            },
            format_dec(mnt){
                return mnt.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            },
            editDepaletissation(dry){
                $("#labelDepal_"+dry.reidre).hide();
                $("#depal_"+dry.reidre).val(dry.depalettisation);
                $("#depal_"+dry.reidre).show();
                $("#depal_"+dry.reidre).removeClass("d-none");
                $("#depal_"+dry.reidre).focus();
            },
            editDouane(dry){
                $("#label_"+dry.reidre).hide();
                $("#"+dry.reidre).val(dry.douane);
                $("#"+dry.reidre).show();
                $("#"+dry.reidre).removeClass("d-none");
                $("#"+dry.reidre).focus();
            },
            showPhoto(photos){
                
                this.tabPhoto = [];

                this.currentIndexPhoto = 0;

                if(Array.isArray(photos)){
                    console.log(photos, "Liste photo");
                    this.tabPhoto = photos;
                }
            },
            closeModalPhoto(){
                this.$refs.closePoupPhoto.click();
                this.getEmpotage();
            },
            savePhoto(){
                const data = new FormData();

                if(!this.attachmentsPhoto.length > 0){
                     Vue.swal.fire(
                              '',
                              'Ajouter une photo avant de valider!',
                              'warning'
                            )   
                    return false;
                }
                
                data.append('file[]', this.attachmentsPhoto);

                for (let i = 0; i < this.attachmentsPhoto.length; i++) {
                    data.append('files' + i, this.attachmentsPhoto[i]);
                }


                data.append('TotalFiles', this.attachmentsPhoto.length);

                data.append('Photos[]', this.tabPhoto); 

                axios.post("/savePhotos/"+this.selected.IDContenaire, data,  {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            } 
                    }).then(response => {
                       
                        this.$refs.filePhoto.value = null;
                        if(response.data.code==0){
                             this.tabPhoto = response.data.file;
                             this.currentIndexPhoto = 0;

                             // refresh 
                             // get all contener
                            axios.get('/gerer/empotage/getContenaire/'+this.selected.identifiant)
                                .then(response => {
                                    this.contenaires = response.data;
                                    if(this.contenaires.data.length > 0){
                                      this.contenaireSelectionner(this.currentIndex); 

                                    }
                            }).catch(error => {});

                            Vue.swal.fire(
                              'succés!',
                              'Photo(s) ajouté(s) avec succés!',
                              'success'
                            )         

                        }else{
                             Vue.swal.fire(
                              'error!',
                              response.data.message,
                              'error'
                            )
                        }
                       
                    });
                },
                removePhoto(name){

                Vue.swal.fire({
                  title: 'Confirmez la suppression de la photo',
                  text: name,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                       // remove Doc
                       const data = new FormData();

                        data.append('Photos[]', this.tabPhoto); 
                        data.append('nameFile', name); 
                        

                        axios.post("/removePhotos/"+this.selected.IDContenaire, data,  {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    } 
                        }).then(response => {
                           
                            if(response.data.code==0){
                                 this.tabPhoto = response.data.file;
                                 this.currentIndexPhoto = 0;

                                  // refresh 
                                 // get all contener
                                axios.get('/gerer/empotage/getContenaire/'+this.selected.identifiant)
                                    .then(response => {
                                        this.contenaires = response.data;
                                        if(this.contenaires.data.length > 0){
                                          this.contenaireSelectionner(this.currentIndex); 

                                        }
                                }).catch(error => {});

                                Vue.swal.fire(
                                  'succés!',
                                  'Photo supprimé avec succés!',
                                  'success'
                                )         

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
             handleFileUploadPhoto(){
                this.attachmentsPhoto = [];
                for(var i=0; i<this.$refs.filePhoto.files.length;i++){
                    this.attachmentsPhoto.push(this.$refs.filePhoto.files[i]);
                }
            },
             getPhoto(index){
                this.currentIndexPhoto = index;
            }
        },
        mounted() {
          this.getEmpotage();
        }
    }
</script>