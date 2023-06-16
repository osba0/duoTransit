<template>
    <div>
        <!--PageLoader :is-loading = isLoading ></PageLoader--> 
        <div class="row">
            <!--div class="qr-code-image">
                <qr-code text='Text to encode'></qr-code>
            </div-->
            
          <div class="col-sm-8 d-flex align-items-center" v-if="!showCurrentOrder">
               <h3>Validation Préchargement  <template v-if="isDetail">:</template></h3>
               <template v-if="isDetail">
                   <span class="pl-2 h4 text-primary font-weight-bold"> N° Dossier {{ selected.id }}&nbsp;</span>
                   <!--span class="h4 text-primary font-weight-bold"> {{ selected.dateDebut }} au {{ selected.dateCloture }}</span-->
                </template>
          </div>

          <div class="col-sm-8 d-flex align-items-center" v-if="showCurrentOrder">
               <h3>Liste des commandes <strong><u>{{ current_type_commande }}</u></strong> à précharger</h3>
          </div>
          
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <template v-if="!showCurrentOrder">
                    <li class="breadcrumb-item" :class="!isDetail ? 'active': ''"><a href="#">Préchargement</a></li>
                    <li class="breadcrumb-item active"  v-if="isDetail"><a href="#">Dossier n° {{ selected.id }}</a></li>
              </template>
               <template v-if="showCurrentOrder">
                    <li class="breadcrumb-item" :class="!isDetail ? 'active': ''"><a href="#">Liste des commandes</a></li>
              </template>
              
            </ol>
          </div>
        </div>

        <template v-if="!isDetail">
            <div class="row d-flex align-items-center justify-content-between mb-3">
                <ul class="legend mt-4 mb-2 pl-3 flex-1">
                    <li v-for="type in typeCmd" class="d-flex cursor-pointer align-items-center" title="Afficher" @click="showOrders(type)"> 
                        <span class="etat_T m-0 cursor-pointer mr-1 border-0" :style="{'background': type.tcolor}"></span> 
                        <label class="m-0 mr-2 cursor-pointer">{{type.typcmd}}</label>
                        <label class="m-0 mr-2 cursor-pointer badge badge-primary">{{ getNbreCmd(type.id) }}</label>
                    </li>
                </ul>
                <div class="mt-2 mr-3">
                    <a href="#" class="text-white btn btn-primary font-weight-bold" data-toggle="modal" data-target="#newDossier">
                        <i class="fa fa-plus" aria-hidden="true"></i> Nouveau Dossier
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
                                    <th class="p-2 border-right border-white h6">Entrepôt</th>
                                    <th class="p-2 border-right border-white h6">Etat</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Date de création</th>
                                    <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                                    
                                    <th class="text-nowrap p-2 border-right text-right border-white h6">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bgStripe preChargeTable"  :class="[isLoading ? 'loader-line' : '']"> 
                                <template v-if="!prechargement.data || !prechargement.data.length" >
                                    <tr v-if="checking"><td colspan="14" class="bg-white text-center">Aucune donnée!</td></tr>
                                </template>
                                <template v-else>
                                    <tr v-for="pre in prechargement.data" :key="prechargement.id" class="bg-white position-relative" :class="[{ 'rowActif': pre.identifiant == selected.identifiant, 'rowInactif':pre.identifiant != selected.identifiant }]">
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
                                        
                                        <td class="align-middle">{{ pre.entrepot }}</td>
                                        <td class="align-middle">
                                            <template v-if="pre.etat==1">
                                                <span class="badge badge-success">Validé</span>
                                                <button class="btn btn-default border-0" v-if="pre.on_empote==0" @click="reactiver(pre)">
                                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                                </button>
                                            </template>
                                            
                                            <span v-if="pre.etat==0" class="badge badge-warning">En cours</span>
                                        </td>

                                        <td class="align-middle">{{ pre.dateCrea }}</td>
                                        <td class="align-middle">{{ pre.user }}</td>
                                        
                                        <td class="align-middle">  
                                            <div class="w-100 text-right" :class="[pre.etat==1 ? 'text-center' : 'text-right']">
                                                <a v-if="pre.etat==1" href="#" title="Voir la facture" class="btn btn-circle border-0 btn-circle-sm m-1 position-relative bg-danger"  @click="showInvoice(pre)" data-toggle="modal" data-target="#openFacture">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                </a>

                                                <!--button v-if="pre.etat!=1" @click="showDossier(pre)" class="btn btn-info btn-sm">Ouvrir</button-->

                                                
                                                 <a v-if="pre.etat!=1"@click="showDossier(pre)" title="Ouvrir le dossier" href="#" class="btn m-1 border-primary btn-circle border btn-circle-sm m-1 bg-white">
                                                    <i class="fa fa-folder-open"></i>
                                                </a>
                                                <a v-if="pre.etat!=1" title="Editer" href="#" class="btn m-1 border-primary btn-circle border btn-circle-sm m-1 bg-white" v-on:click="editDossier(pre)" data-toggle="modal" data-target="#newDossier">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                                <a v-if="pre.etat==0" title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1 bg-white" v-on:click="deletePre(pre)">
                                                        <i class="fa fa-trash text-danger" aria-hidden="true"></i>
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
                <button class="btn btn-primary  mb-3" @click="back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour
                </button>
            </div>
            <template v-if="isDetail && !showCurrentOrder">
                <div class="d-flex bg-white rounded justify-content-between d-flex pt-2 justify-content-between border mb-2 px-2 fontSpan">
                   
                    <div class="ml-2">
                        <label>Booking:</label>
                        <span class="badge badge-primary">{{ selected.booking }}</span>
                    </div>
                     <div class="ml-2">
                        <label>Nom navire:</label>
                        <span class="badge badge-primary">{{ selected.nomNavire }}</span>
                    </div>
                    <div class="ml-2">
                        <label>Terminal retour:</label>
                        <span class="badge badge-primary">{{ selected.termRetour }}</span>
                    </div>
                </div>
                <div class="d-flex bg-white rounded justify-content-between d-flex pt-2 justify-content-between border mb-2 px-2 fontSpan">
                    <div>
                        <label>Date empotage:</label>
                        <span class="badge badge-primary">{{ selected.dateDebut }}</span>
                    </div>
                    <div class="ml-2">
                        <label>Date cloture navire:</label>
                        <span class="badge badge-primary mb-2">{{ selected.dateCloture }}</span>
                    </div>
                     <div class="ml-2">
                        <label>Date départ:</label>
                        <span class="badge badge-primary">{{ selected.dateDepart }}</span>
                    </div>
                     <div class="ml-2">
                        <label>Date arrivée:</label>
                        <span class="badge badge-primary">{{ selected.dateArrivee }}</span>
                    </div>
                </div>
          </template>
           
            <div class="mb-3" v-if="!showCurrentOrder">
                <VueScrollFixedNavbar>
                <table class="table table-bordered bg-white"> 
                <tr>
                    <th class="text-uppercase thead-blue py-1 w-60">Dossier selectionné  
                        <span class="py-0 px-2 rounded text-lowercase bg-warning" v-if="selected.etat==0">En cours</span>
                    <span class="py-0 px-2 rounded text-lowercase bg-success" v-if="selected.etat==1">Validé</span></th>
                    <th class="text-uppercase thead-blue py-1">Etat conteneur</th>
                </tr>
                <tr>
                    <td class="align-middle">
                        <div class="d-flex justify-content-between detailPrecharge position-relative">
                            <ul class="w-100 legend m-0 p-0 position-absolute" style="top:-10px">
                                <li class="w-100 text-center font-weight-bold">
                                    <span class="float-none d-inline-block etat_T m-0 mr-1 border-0" :style="{'background': getColorTypeCmd(selected.typeCommande)}"></span> 
                                    {{ selected.typeCmd }}
                                </li>
                            </ul>
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
        </VueScrollFixedNavbar>
            </div>
            <div class="d-flex justify-content-between align-items-center mr-3 mb-3 sucesss"> 
                <!--button class="btn btn-lg btn-danger" :disabled = "selected.id == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button-->
                <!--div class="h5 m-0">
                    <span class="py-0 px-0 text-uppercase font-weight-bold">{{ selected.typeCmd }} </span> 
                </div-->
                <div><typeproduit></typeproduit></div>
                    
                
                <button v-if="!showCurrentOrder" class="btn btn-lg btn-primary" :disabled = "(selected.id == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()"><i class="fa fa-check"></i> Valider</button>
            </div>
            <hr>
            <!--div class="d-flex justify-content-between align-content-center mb-2">
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
                        v-model.lazy="searchRecep"
                        type="search"
                        class="form-control"
                        placeholder="Rechercher par n°cmd, n°fe, n°ecv,n°fact, utilisateur, fournisseur..."
                    />
                </div>
               
            </div-->  
                
                <table class="table">
                    <thead class="thead-blue position-relative" :class="[run? 'disabled-row':'']">
                         <tr>
                            <th class="p-2 border-right border-white cursor-pointer h6 white-space-nowrap" v-on:click="sortByColumn(columns[1])">N°FE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[5])">Poids(KG) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[6])">Volume(m<sup>3</sup>) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                             <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">N°Facture</th>
                            <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">Mnt Facture</th>
                            <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[4])">Date livraison <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-nowrap p-2 border-right border-white h6 white-space-nowrap">Crée par</th>
                            <th class="p-2 border-right border-white text-left h6 white-space-nowrap">Préchargé?</th>
                            <th class="p-2 border-right text-center border-white h6 white-space-nowrap" title="N° Préchargement">N° Pré.</th>
                            <th class="p-2 border-right text-center border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[8])">Priorité  <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-right p-2 border-right border-white h6">Actions</th>
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
                                {{ dry.refere }}
                            </label></td>
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
                        <td class="p-2 align-middle">{{ format_nbr(dry.revafa)  }}</td>
                        
                        <td class="p-2 align-middle white-space-nowrap">{{ dry.redali }}</td>
                        <td class="p-2 align-middle text-nowrap"><span class="badge badge-info">{{ dry.user_created}}</span></td>
                        <td class="p-2 align-middle white-space-nowrap">
                            
                            <template v-if="dry.idPre > 0">
                                 <span class="badge badge-success">oui</span> {{dry.prechargeur}}
                            </template>
                            <template v-else>
                                  <span class="badge badge-warning">non</span>
                            </template>
                           
                        </td>
                        <td class="align-middle"><span class="border rounded bg-light text-center d-inline-block w-100">{{ dry.idPre }}</span></td>
                        <td class="p-2 align-middle rateCenter position-relative">

                            <rate :length="3" :value="dry.priorite" :ratedesc="['Pas urgente', 'Normale', 'Urgente']" :readonly="true"  />

                        </td>
                        <td class="p-2 text-right align-middle">
                            <div class="d-flex justify-content-end align-items-center">
                                 <template v-if="dry.motifID != ''">
                                        <a title="Commande non chargé" href="#" class="btn btnAction mx-1 btn-circle border btn-circle-sm bg-white" v-on:click="showMotifModal(dry)">
                                            <i class="fa fa-info" aria-hidden="true"></i>
                                        </a>
                                        
                                    </template>
                                <a title="Voir les détails" href="#" class="btn mx-1 btn-circle border btn-circle-sm btnAction bg-white mr-1 position-relative" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                                </a>
                                 <template  v-if="!showCurrentOrder">
                                    <span v-if="dry.dossier_id > 0"><i class="fa fa-check-circle"></i></span>
                                    <label class="switch mr-0" :style= "[selected.etat==1 ? {opacity: 0.5} : {opacity: 1}]"> 
                                        <template v-if="selected.etat==0">
                                            <input class="switch-input inputCmd" :disabled="selected.etat==1" :checked="(selected.id == dry.dossier_id || dry.idPre > 0)" type="checkbox" :value="dry.reidre" v-on:change="preselectionner($event,dry)" /> 
                                            <span class="switch-label" data-on="Choisie" data-off="Choisir"></span> 
                                            <span class="switch-handle"></span> 
                                        </template>
                                        <template v-else>
                                            <input class="switch-input inputCmd" :disabled="selected.etat==1" :checked="(selected.id == dry.dossier_id)" type="checkbox" :value="dry.reidre" v-on:change="preselectionner($event,dry)" /> 
                                            <span class="switch-label" data-on="OK" data-off="Choisir"></span> 
                                            <span class="switch-handle"></span> 
                                        </template>
                                        
                                    </label>
                                </template>
                               <button :disabled="dry.refasc === null || dry.refasc === ''" title="Voir la facture" class="btn btn-circle btnAction border btn-circle-sm mx-1 position-relative bg-white" v-on:click="showFacture(dry)">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <!--i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i-->
                                    <span :class="{ 'bg-light2': getCountFacture(dry.refasc) == 0, 'bg-green2' : getCountFacture(dry.refasc) > 0}" class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{ getCountFacture(
                                        dry.refasc) > 9 ? '+9' : getCountFacture(dry.refasc) }}</span>
                                </button>
                                
                                <button title="Rejet de la commande" @click="showMotifCreationModal(dry)" class="btn mx-1 btn-circle border-0 btn-circle-sm btnAction bg-transparent mr-1 position-relative"><i class="mt-1 fa fa-ban text-danger" style="font-size:26px" aria-hidden="true"></i></button>
                                
                            </div>
                        </td>
                    </tr>
                    </template>

                    </tbody>
                    <tfoot class="thead-blue position-relative" :class="[run? 'disabled-row':'']">
                         <tr>
                           
                            <th class="p-2 border-right border-white cursor-pointer h6 white-space-nowrap" v-on:click="sortByColumn(columns[1])">N°FE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[5])">Poids(KG) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[6])">Volume(m<sup>3</sup>) <i class="fa fa-sort" aria-hidden="true" ></i></th>
                             <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">N°Facture</th>
                            <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">Mnt Facture</th>
                            <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[4])">Date livraison <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-nowrap p-2 border-right border-white h6 white-space-nowrap">Crée par</th>
                            <th class="p-2 border-right border-white text-left h6 white-space-nowrap">Préchargé?</th>
                            <th class="p-2 border-right text-center border-white h6 white-space-nowrap" title="N° Préchargement">N° Pré.</th>
                            <th class="p-2 border-right text-center border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[8])">Priorité  <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-right p-2 border-right border-white h6">Actions</th>
                        </tr>
                    </tfoot>
                </table>
                <div class="d-flex mt-4 justify-content-center">
                    <pagination
                        :data="reception"
                        @pagination-change-page="getReception"
                    ></pagination>
                    
                </div>
                <hr>
                <div class="d-flex justify-content-end mr-3 mb-5 sucesss" v-if="!showCurrentOrder"> 
                    <!--button class="btn btn-lg btn-danger" :disabled = "selected.id == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button-->
                    <button class="btn btn-lg btn-primary" :disabled = "(selected.id == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()"><i class="fa fa-check"></i> Valider</button>
                </div>
        </template>


        <creationMotif></creationMotif>
        
        
        <!-- Modal creation dossier-->
        <div class="modal fade" id="newDossier" tabindex="-1" role="dialog" aria-labelledby="myModalDossier"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
           <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="!isEdited">Nouvel Dossier</template>
                            <template v-else>Modification Dossier</template>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalDossier()" aria-label="Close" ref="closePoupDossier">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">

                        <form @submit.prevent="save" enctype="multipart/form-data"  key=1>
                            <h5 class="text-primary mb-0 font-weight-bold">Détail Dossier</h5>
                             <hr class="mt-1">
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label for="numDoss"  class="d-block m-0 text-left pr-2 white-space-nowrap">N° dossier</label>
                                           <input autocomplete="off" :disabled="isEdited" class="form-control mr-2" id="numDoss" v-model="initChargement.numDossier" 
                                            :class="{ 'border-danger': submitted_add && !$v.initChargement.numDossier.required}" />
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-6 my-2 d-flex flex-row align-items-center justify-content-between">
                                    <div class="w-49 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label  class="d-block m-0 text-left pr-2 white-space-nowrap">Type commande</label>
                                            <select :disabled="isEdited" class="form-control" v-model="initChargement.typeCommande" :class="{ 'border-danger': submitted_add && !$v.initChargement.typeCommande.required }">
                                                <option value="">Choisir le type commande</option>
                                                <option v-for="type in typeCmd"  :value="type.id">{{type.typcmd}}</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="w-49 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label  class="d-block m-0 text-left pr-2 white-space-nowrap">Entrepôt</label>
                                            <select class="form-control" v-model="initChargement.entrepot" :class="{ 'border-danger': submitted_add && !$v.initChargement.entrepot.required }">
                                                <template v-if="!listEntrepots.length==1">
                                                    <option value="">Choisir l'entrepôt</option>
                                                </template>
                                                
                                                <option v-for="entrepot in listEntrepots"  :value="entrepot.id">{{entrepot.nomEntrepot}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                             </div>
                              <div class="row mb-4">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100" :class="{ 'border-danger-date': submitted_add  && !$v.initChargement.dateDebut.required}">
                                            <label for="dateDeb" class="m-0 text-left w-35 pr-2 white-space-nowrap" >Date empotage</label>

                                             <date-picker v-model="initChargement.dateDebut" class="w-100" required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100" :class="{ 'border-danger-date': submitted_add  && !$v.initChargement.dateCloture.required}">
                                           <label for="dateClo" class="d-block m-0 text-left w-35 pr-2 text-nowrap" >Date cloture navire</label>
                                            <date-picker v-model="initChargement.dateCloture" class="w-100"  required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY" :class="{ 'border-danger': submitted_add  && !$v.initChargement.dateCloture.required}"></date-picker>
                                        </div>
                                    </div>
                                </div>
                             </div>
                             <h5 class="text-primary mb-0 font-weight-bold">Infos Navire</h5>
                             <hr class="mt-1">
                             <div class="row">
                                <div class="col-4 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label for="numBooking"  class="d-block  m-0 text-left pr-2 white-space-nowrap">N° Booking</label>
                                           <input autocomplete="off" class="form-control text-uppercase mr-2" id="numBooking" v-model="initChargement.numBooking" 
                                            :class="{ 'border-danger': submitted_add && !$v.initChargement.numBooking.required}" />
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-4 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label for="nomNavire"  class="d-block m-0 text-left pr-2 white-space-nowrap">Nom navire</label>
                                           <input autocomplete="off" class="form-control text-uppercase mr-2" id="nomNavire" v-model="initChargement.nomNavire" 
                                            :class="{ 'border-danger': submitted_add && !$v.initChargement.nomNavire.required}" />
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-4 my-2 d-flex flex-row align-items-center justify-content-between">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                           <label for="termRetour"  class="d-block m-0 text-left pr-2 white-space-nowrap">Terminal retour</label>
                                           <input autocomplete="off" class="form-control text-uppercase mr-2" id="termRetour" v-model="initChargement.termRetour" 
                                            :class="{ 'border-danger': submitted_add && !$v.initChargement.termRetour.required}" />
                                        </div>
                                    </div>
                                </div>
                             </div>
                              <div class="row mb-4">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100" :class="{ 'border-danger-date': submitted_add  && !$v.initChargement.dateDepart.required}">
                                            <label for="dateDeb" class="m-0 text-left w-35 pr-2 white-space-nowrap" >Date départ</label>

                                             <date-picker v-model="initChargement.dateDepart" class="w-100" required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY" :class="{ 'border-danger': submitted_add && !$v.initChargement.dateDepart.required }"></date-picker>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100" :class="{ 'border-danger-date': submitted_add  && !$v.initChargement.dateArrivee.required}">
                                           <label for="dateClo" class="d-block m-0 text-left w-35 pr-2 text-nowrap" >Date arrivée</label>
                                            <date-picker v-model="initChargement.dateArrivee" class="w-100"  required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY" ></date-picker>
                                        </div>
                                    </div>
                                </div>
                             </div>

                             <div class="modal-footer d-flex justify-content-center"> 
                                <button type="submit" class="btn btn-success d-flex align-items-center">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" :class="{'d-none': !submitted_circle, 'd-inline-block': submitted_circle && !$v.typeCommande.required}"></span><template v-if="!isEdited">Créer</template>
                            <template v-else>Enregister</template></button>  
                                <button type="button" v-on:click="closeModalDossier()" class="btn btn-warning">Annuler</button>
                            </div>
                        </form>   
                    </div>  
             </div>
          </div>
        </div>


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
                         <template v-if="pdfFileModal != null">
                            <embed :src="'/pdf/prechargement/'+pdfFileModal" frameborder="0" width="100%" height="450px">
                          </template>
                         <template  v-else> Auncun fichier </template>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalPdf()" class="btn btn-warning">Fermer</button>
                  </div>
             </div>
            
          </div>
        </div>
         <!-- Modal Facture-->    
        <modalFacture></modalFacture>   
        <modalMotif></modalMotif>
        <modalDetailsCommande></modalDetailsCommande>

    </div>
</template>
<script>
    import { EventBus } from '../../event-bus';

    import {VueScrollFixedNavbar} from "vue-scroll-fixed-navbar";
    import modalDetailsCommande from '../../components/modal/detailsCommande.vue';
    import { PdfMakeWrapper, Table, Img, QR } from 'pdfmake-wrapper';
    import modalFacture from '../../components/modal/facture.vue';

    import typeproduit from '../../components/modal/typeproduit.vue';
    import modalMotif from '../../components/modal/motif.vue';

    import creationMotif from '../../components/modal/creationMotif.vue';


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
            'currentEntite',
            'listEntrepots',
            'cmdAPrecharger',
            'idEntite'
            
        ],  
        components: {
            PageLoader,
            VueScrollFixedNavbar,
            typeproduit,
            modalMotif,
            creationMotif,
            modalFacture
          },
        data() { 
            return {
                isLoading: true,
                submitted_add: false,
                paginate: 10,
                selectedTypeCmd: "",
                prechargement:{},
                prechargementDossier: {},
                reception: {},
                paginateRecep: 200, // bug lors de la selection de la 2 page les stats sont renitialisé
                choose:'',
                selected: {
                    identifiant: '',
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
                    entrepot: '',
                    idEntrepot: '',
                    currentPage: 1,
                    booking: '',
                    dateDepart: '',
                    dateArrivee: '',
                    termRetour: ''
                },
                capacite: this.defaultContenaire.volume,
                nbrContenaire: 0,
                volumePercent:0,
                initChargement :{
                    numDossier: '',
                    dateDebut:   null,
                    dateCloture: null,
                    numBooking: null,
                    nomNavire: null,
                    termRetour: null,
                    dateDepart: null,
                    dateArrivee: null,
                    typeCommande: "",
                    entrepot: this.listEntrepots.length==1? this.listEntrepots[0].id:'', // si il y'a un seul 

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
                etatFiltre: "",
                submitted_circle: false,
                checking: false,
                pdfFileModal: null,
                searchRecep: '',
                filtreRate: '',
                // Sort column
                columns: ['rencmd', 'refere', 'reecvr', 'renufa', 'redali', 'repoid', 'revolu', 'totalColis', 'priorite'],
                sortedColumn: '',
                order: 'asc',
                run: false,
                isEdited: false,
                showCurrentOrder: false,
                current_type_commande: '',
                page: 1
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
                numBooking:   { required },
                nomNavire:    { required },
                termRetour:   { required },
                dateDepart:   { required },
                dateArrivee:  { required },
                entrepot: { required },
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
            },
            searchRecep: function(value) {
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
            getColorTypeCmd(id){
                 for(var i=0; i<this.typeCmd.length;i++){
                    if(this.typeCmd[i].id === parseInt(id)){
                        return this.typeCmd[i].tcolor;
                    }
                }
                console.log("tcolor", id, 'array', this.typeCmd);
                return "#aaa";
            },
            format_nbr(mnt){
                    if(mnt != '' && mnt != null){
                        return mnt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                    }
                    return mnt;
                },
          
            preselectionner(event, cmd){
                this.run = true;
                var ischecked=0;
               
                if (event.target.checked) {
                    ischecked=1;
                }
                this.eventCmdSelected.ischecked=ischecked;
                this.eventCmdSelected.idcmd = cmd.reidre;



                this.commandeSelected = [];
                this.commandeNoSelected = [];
                
                var self = this;
                $(".inputCmd").each(function(){
                    if($(this).is(':checked')){
                        console.log("OK", $(this).val());
                        self.commandeSelected.push(parseInt($(this).val()));
                    }else{
                        self.commandeNoSelected.push($(this).val());
                    }
                    
                });
                
                
                 /*const data = new FormData();
                data.append('idPrehargement', this.selected.id);
                data.append('idreception', cmd.reidre);
                data.append('ischecked', ischecked);
                data.append('listCmd',  JSON.stringify(this.commandeSelected))*/

                axios.post("/gerer/dossier/setPrechargement/"+this.idEntite, {
                            'idPrehargement' :  this.selected.id,
                            'idreception' :  cmd.reidre,
                            'ischecked' :  ischecked,
                            'listCmd' :  this.commandeSelected,
                            'listCmdNoSelect' :  this.commandeNoSelected

                        }).then(response => {
                let res = response.data.result;
    
                this.selected.nbrCmd   =  this.commandeSelected.length; //res[0].total_cmd;
                this.selected.nbrColis = parseInt(res[0].total_colis==null ? 0 : res[0].total_colis) + parseInt(res[0].total_palette==null ? 0 : res[0].total_palette);
                this.selected.poids    = res[0].total_poids==null ? 0 : res[0].total_poids;
                this.selected.volume   = res[0].total_volume==null ? 0 : res[0].total_volume;



                this.setProgressCont(res[0].total_volume);
                this.getPrechargement(); // refresh tableau prechargement
                
                this.getCmdSelected(this.selected.id, this.selected.typeCommande, false);
               // this.getReception(); 
              
                });
        },
        closeModalDossier(){
            this.submitted_add = false;
            this.isEdited = false;
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
            this.page = page;
            axios.get('/gerer/dossier/list/'+this.idClient+"/"+this.idEntite+'?page=' + page + "&paginate=" + this.paginate+ "&typeCmd=" + this.selectedTypeCmd+"&keysearch="+this.search+"&etatFiltre="+this.etatFiltre).then(response => {
                this.prechargement = response.data;
                var that = this;
                setTimeout(function(){
                    that.isLoading = false;
                },500);
                this.checking=true;
                
            });
        },
        deletePre(pre){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: "Dossier de préchargement n° "+pre.id,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/gerer/deletePre/'+pre.id+"/"+this.idEntite+'?clientID='+this.idClient+"&typeCmd="+pre.typecmdId).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Dossier préchargement supprimé.',
                              'success'
                            ).then((result) => {
                                            // reload   
                                            localStorage.setItem('current_page_preclient', this.page);  
                                            location.reload();
                                        });
                             /*this.modeModify = false;
                             this.getPrechargement();*/


                        });
                  
                  }
                })
            },
        getReception(page = 1){
            this.isLoading=true;
            axios.get('/gerer/dossier/pre/reception/'+this.idClient+"/"+this.idEntite+"/"+this.selected.typeCommande+'?page=' + page + "&paginate=" + this.paginateRecep+"&idEntrepot="+this.selected.idEntrepot+"&idPre="+this.selected.id+"&etat="+this.selected.etat+"&filtreRate="+this.filtreRate+"&keysearch="+this.searchRecep+"&column="+this.sortedColumn+"&order="+this.order).then(response => {
                this.reception = response.data;

                console.log(this.reception, "reception");
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
                                nbColis +=  (parseInt(obj.renbcl) + parseInt(obj.renbpl));
                    
                                this.selected.poids    += parseFloat(obj.repoid);
                                this.selected.volume   += parseFloat(obj.revolu);
                            }else if(this.eventCmdSelected.ischecked == 0 && this.eventCmdSelected.idcmd==obj.reidre){
                                nbCommande--;
                                nbColis -=  (parseInt(obj.renbcl) + parseInt(obj.renbpl));
                    
                                this.selected.poids    -= parseFloat(obj.repoid);
                                this.selected.volume   -= parseFloat(obj.revolu);

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
                    this.selected.volume = this.selected.volume.toFixed(2);
                    this.selected.poids = this.selected.poids.toFixed(2);
                    this.setProgressCont(this.selected.volume);
                }
               
                this.isLoading = false;

                // get Ŝpecification

                EventBus.$emit('SET_PRODUIT_SPECIFIK', { 
                    prd: this.reception.data
                });
            });
        },
        save(){

            this.submitted_add = true;
             // stop here if form is invalid
            this.$v.initChargement.$touch();
            if (this.$v.initChargement.$invalid) {
                return;
            }

            this.submitted_circle=true;

            var date1 = new Date(this.initChargement.dateDebut);
            var date2 = new Date(this.initChargement.dateCloture);

            if(date1.getTime() > date2.getTime()){
                Vue.swal.fire(
                      'warning!',
                      'Date début incorrecte!',
                      'warning'
                    );
                this.submitted_circle=false;

                return false;
            }


            var dateD = new Date(this.initChargement.dateDepart);
            var dateA = new Date(this.initChargement.dateArrivee);

            if(dateD.getTime() > dateA.getTime()){
                Vue.swal.fire(
                      'warning!',
                      'Date départ incorrecte!',
                      'warning'
                    );
                this.submitted_circle=false;

                return false;
            }



            axios.post((this.isEdited?"/gerer/editDossier":"/gerer/createDossier"), {
                'numdossier'  : this.initChargement.numDossier,
                'datedebut'   : this.initChargement.dateDebut,
                'datecloture' : this.initChargement.dateCloture, 
                'nomnavire'   : this.initChargement.nomNavire,
                'booking'     : this.initChargement.numBooking,
                'datedepart'   : this.initChargement.dateDepart,
                'datearrivee' : this.initChargement.dateArrivee, 
                'termretour'  : this.initChargement.termRetour,
                'typeCmd'     : this.initChargement.typeCommande,
                'entrepot'    : this.initChargement.entrepot,
                'clientID'    : this.idClient,
                'entiteID'    : this.idEntite

            }).then(response => {
              
                if(response.data.code==0){
                    Vue.swal.fire(
                      'succés!',
                      (this.isEdited?'Dossier modifié avec succés':'Dossier crée avec succés!'),
                      'success'
                    );
                    this.submitted_add = false;
                    this.submitted_circle = false;

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

                this.isEdited = false;
            });
        },
        currentDateTime() {
            const current = new Date();
            const date = current.getDate()+'/'+(current.getMonth()+1)+'/'+current.getFullYear();
            const time = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
            const dateTime = date;

            return dateTime;
        },
        getCmdSelected(idDossier, typeCommande, genererPDF){
            axios.get('/gerer/getCmd/'+idDossier+'/'+typeCommande).then(response => {
                this.checkedCommandes = response.data.result;
                if(genererPDF){
                    this.generatePdf(true);
                }
                
            });
        },
        editDossier(pre){
            this.isEdited = true;
            this.initChargement.numDossier = pre.id;
            this.initChargement.dateDebut= pre.dateDebutNatif;
            this.initChargement.dateCloture= pre.dateClotureNatif;
            this.initChargement.numBooking= pre.booking;
            this.initChargement.nomNavire= pre.nomNavire;
            this.initChargement.termRetour= pre.terminal_retour;
            this.initChargement.dateDepart= pre.dateDepartNatif;
            this.initChargement.dateArrivee= pre.dateArriveeNatif;
            this.initChargement.typeCommande= pre.typecmdId;
            this.initChargement.entrepot= pre.entrepotID;
        },
        valider(){
            this.commandeSelected = [];
            this.commandeNoSelected = [];
            
            var self = this;
            $(".inputCmd").each(function(){
                if($(this).is(':checked')){
                    console.log("OK", $(this).val());
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

            // Lever le 16 / 06 / 2023 

           /* if(this.commandeNoSelected.length > 0){
                 Vue.swal.fire(
                      'warning!',
                      'Veuillez choisir ou rejeter le(s) commande(s) non selectionnée(s)!',
                      'warning'
                    );
                return false;
            } */


            Vue.swal.fire({
                  title: 'Confirmez la validation',
                  text: "Dossier n° "+this.selected.id,
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


                        axios.post("/gerer/createDossier/valider/"+this.currentClient['id']+"/"+this.idEntite, {
                            'idsCmd': this.commandeSelected,
                            'ignored': this.commandeNoSelected,
                            'id_dossier' : this.selected.id,
                            'typeCmd' : this.selected.typeCommande

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
                                        localStorage.setItem('current_page_preclient', this.page);   
                                        location.reload();
                                    });   
                                  
                                }, 5000);

                              

                                // Envoi notification avec le fichier PDF

                                this.getCmdSelected(this.selected.id, this.selected.typeCommande, true);
                                

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
        flushFormular(){

            this.initChargement.numDossier= '';
            this.initChargement.dateDebut=   null;
            this.initChargement.dateCloture= null;
            this.initChargement.typeCommande= "";
            this.initChargement.numBooking= '';
            this.initChargement.nomNavire= '';
            this.initChargement.termRetour= '';
            this.initChargement.dateDepart= null;
            this.initChargement.dateArrivee= null;

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

                    {text: 'N°Dossier '+that.selected.id+'\n'+that.selected.typeCmd, fontSize: 20, bold: true, alignment: 'center', color: '#3490dc'}, 

                    {text: ['Entrepôt: ', {text: that.selected.entrepot, fontSize: 14}],  alignment: 'right'}]);


                entete.push([{text: "\n\nClient: "+that.currentClient['clnmcl'], fontSize: 13, alignment: 'left', colSpan: 3}]);

                entete.push([{text: 'Prévision de chargement pour le '+that.selected.dateDebut+' ET '+that.selected.dateCloture+"\n\n", fontSize: 12, alignment: 'center', colSpan: 3}]); 

                var infoNavire=[];

                infoNavire.push([
                    {text: 'Booking: '+ that.selected.booking, fontSize: 12, alignment: 'left', lineHeight: 1},
                    {text: 'Nom navire: '+ that.selected.nomNavire, fontSize: 12, alignment: 'center', lineHeight: 1},
                    {text: 'Terminal retour: '+ that.selected.termRetour, fontSize: 12, alignment: 'right', lineHeight: 1},
                ]); 
                 infoNavire.push([
                    {text: 'Date départ: '+ that.selected.dateDepart+' ~ Date arrivée: '+ that.selected.dateArrivee, fontSize: 12, alignment: 'center', lineHeight: 1,colSpan: 3}
                ]); 

                var navire = new Table(infoNavire).widths('*').margin([0, 0, 0, 7]).end;
               

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


                    //cmdCell.push(obj.rencmd+" "+prio);

                    if(!Array.isArray(obj.listgroup) || obj.listgroup.length==0){
                        cmdCell.push(obj.rencmd+" "+prio);
                    }else{
                        for(var cm=0; cm< obj.listgroup.length; cm++){
                            cmdCell.push(obj.listgroup[cm]+" "+prio);
                        }
                    }
                    
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

                pdf.add(navire);

                pdf.add(table);

                pdf.add(
                    pdf.ln(2)
                );

                var endPage = [];

                // formater le nom du fichier
                var labelCmd1 = that.selected.typeCmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
            
                var nameFile = 'dossier-'+that.selected.id+'_'+labelCmd1+"_du_"+that.selected.dateDebut.replaceAll("/", "-")+"_au_"+that.selected.dateCloture.replaceAll("/", "-")+".pdf";

                var qrTotaux = [];

                var legendTotaux = [];

                legendTotaux.push([tabtotaux, {text: legend1+' '+legend2, fontSize: 10, bold: true, alignment: 'left'}]);

                qrTotaux.push([legendTotaux, new QR(location.origin+"/pdf/prechargement/"+nameFile).fit(80).alignment('right').end]); 

                var tableQR = new Table(qrTotaux).widths(['*', 80]).layout('noBorders').end;

                pdf.add(tableQR);

                if(isnotification){
                    
                   var self = that; 
                    pdf.create().getDataUrl(function(url) { 

                        console.log(url, "File PDF"); 
                        axios.post("/gerer/createDossier/notification/"+self.currentClient['id']+"/"+self.idEntite, {
                            'idsCmd': self.commandeSelected,
                            'id_dossier' : self.selected.id,
                            'entrepot' : self.selected.idEntrepot,
                            'date_debut': self.selected.dateDebut.replaceAll("/", "-"),
                            'date_fin': self.selected.dateCloture.replaceAll("/", "-"),
                            'typeCmd': self.selected.typeCmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase(), 
                            'base64_file_pdf': url

                        });/*.then(response => {

                            
                                 

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

            
           
    
           },
           showDossier(dossier){
            this.commandeSelected = [];
            this.commandeNoSelected = [];
            this.isDetail = true;
            this.selected.identifiant = dossier.identifiant;
            this.selected.id         = dossier.id;
            this.selected.nbrCmd     = dossier.nbrCmd;
            this.selected.nbrColis   = parseInt(dossier.total_colis)+parseInt(dossier.total_pallette);
            this.selected.poids      = dossier.total_poids;
            this.selected.volume     = dossier.total_volume;
            this.selected.typeCmd    = dossier.typecmd;
            this.selected.dateDebut  = dossier.dateDebut;
            this.selected.dateCloture  = dossier.dateCloture;
            this.selected.booking     = dossier.booking;
            this.selected.dateDepart  = dossier.dateDepart;
            this.selected.dateArrivee = dossier.dateArrivee;
            this.selected.termRetour  = dossier.terminal_retour;
            this.selected.nomNavire  = dossier.nomNavire;
            this.selected.isSelected  = true;
            this.selected.typeCommande = dossier.typecmdId;
            this.selected.etat = dossier.etat;
            this.selected.entrepot = dossier.entrepot;
            this.selected.idEntrepot = dossier.entrepotID; 
            this.setProgressCont(dossier.total_volume);
            this.getReception();
            this.getCmdSelected(this.selected.id, this.selected.typeCommande, false);




           },
           back(){
            /*this.getPrechargement(this.selected.currentPage);
            this.isDetail = false;
            this.commandeSelected = [];
            this.commandeNoSelected = [];
            this.eventCmdSelected.ischecked = -1;
            this.eventCmdSelected.idcmd = '';
            this.run = false;
            this.showCurrentOrder = false;*/
             localStorage.setItem('current_page_preclient', this.page);  
                location.reload();

           },
           reactiver(pre){
                Vue.swal.fire({
                  title: 'Confirmez la réactivation',
                  text: "Dossier de préchargement n° "+pre.id,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '',
                  confirmButtonText: 'Oui, réactivé!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.post('/gerer/reactiver/'+pre.id+"/"+this.idEntite+'?identifiant='+pre.identifiant).then(response => {
                            
                            if(response.data.code != 0){
                                Vue.swal.fire(
                                  'Error!',
                                   response.data.message,
                                  'error'
                                );

                                return false;
                            }
                             
                             this.getPrechargement();


                        });
                  
                  }
                })
           },
           showInvoice(pre){
            var labelCmd = pre.typecmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
            
                this.pdfFileModal = 'dossier-'+pre.id+'_'+labelCmd+"_du_"+pre.dateDebut.replaceAll("/", "-")+"_au_"+pre.dateCloture.replaceAll("/", "-")+".pdf";
           },
           closeModalPdf(){
                 this.$refs.closePoupPdf.click();
            },
            showModal(dry){

                 EventBus.$emit('VIEW_CMD', {
                    openView: true,
                    dry: dry,
                    fournisseur: this.listFournisseurs,
                    typeCommande: this.typeCmd,
                    entrepot: this.listEntrepots,
                    idClient: this.idClient
                });

            },  
            showMotifCreationModal(dry){
                EventBus.$emit('VIEW_MOTIF_CREATION', { 
                    dryMotif: dry
                }); 
            },          
            showMotifModal(dry){
                EventBus.$emit('VIEW_MOTIF', { 
                    dryCmd: dry
                }); 
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
            showOrders(type){
               this.isDetail = true;
               this.showCurrentOrder=true;
               this.selected.typeCommande = type.id;
               this.current_type_commande = type.typcmd;
               this.getReception();
            },
              getCountFacture(doc){
                if(Array.isArray(doc)){
                    return doc.length;
                }

                console.log("TES", doc);
                return 0;
            },
             showFacture(fact){
                     EventBus.$emit('VIEW_FACT', { 
                        listeFacture: fact.refasc,
                        idReception: fact.reidre,
                        can_modify: false
                    }); 
                }
        },
        mounted() {
          this.getPrechargement(localStorage.getItem('current_page_preclient')=== null? this.page : localStorage.getItem('current_page_preclient'));

          // Lister reception aprés enregistrement du motif
          EventBus.$on('LISTER_RECEPTION', (event) => {
              this.getReception();
          });
        }
    }
</script>
