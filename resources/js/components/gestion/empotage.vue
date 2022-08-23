<template>
    <div>
        <!--PageLoader :is-loading = isLoading ></PageLoader--> 
        <div class="row">
          <div class="col-sm-9 d-flex align-items-center">
               <h2>Empotage<template v-if="isDetail">:</template></h2>
               <template v-if="isDetail">
                   <span class="pl-2 h3 text-primary font-weight-bold"> N° Dossier {{ selected.dossier }}&nbsp;</span>
                </template>
          </div>
          <div class="col-sm-3">
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
                                    <th class="p-2 border-right border-white h6">N°TC</th>
                                    <th class="p-2 border-right border-white h6">Type TC</th>
                                    <th class="p-2 border-right border-white h6">N°Plomb</th>
                                    <th class="p-2 border-right border-white h6">Entrepôt</th>
                                    <th class="p-2 border-right border-white h6">Date Départ</th>
                                    <th class="p-2 border-right border-white h6">Date Arrivée</th>
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
                                       
                                        <td class="p-2 align-middle">
                                            {{ empo.numContenaire }}
                                        </td>
                                        <td class="p-2 align-middle">
                                           {{ empo.typeContenaire }}
                                        </td>
                                        <td class="p-2 align-middle">
                                           {{ empo.plomb }}
                                        </td>
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
                                            <button v-if="empo.etat==0"  @click="showDossier(empo)" class="btn btn-info btn-sm">Ouvrir</button>
                                            <!--button title="Editer" href="#" class="btn btn-secondary btn-sm mx-2" v-on:click="editEmpotage(empotage)" data-toggle="modal" data-target="#newEmpotage">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i> Modifier
                                            </button-->
                                            <a v-if="empo.etat==0"  title="Editer" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white"  data-toggle="modal" data-target="#newEmpotage" v-on:click="editEmpotage(empo)">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a v-if="empo.etat==0"  title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1 bg-white" v-on:click="deleteEmpotage(empo)">
                                                <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                            </a>

                                            <!--button title="Supprimer" href="#" class="btn btn-sm btn-danger" v-on:click="deleteEmpotage(empotage)">
                                                <i class="fa fa-close text-white" aria-hidden="true"></i> Supprimer
                                            </button-->
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
                <button class="btn btn-warning mb-3" @click="back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour
                </button>
                <div class="d-flex">
                    <div class="h5 mb-0 rounded bg-white py-2 px-3 border">N°TC: <b>{{ selected.numtc }}</b></div>
                    <div class="h5 mb-0 rounded bg-white py-2 px-3 border mx-3">Type TC: <b>{{ selected.typetc }}</b></div>
                    <div class="h5 mb-0 rounded bg-white py-2 px-3 border">Plomb: <b>{{ selected.plomb }}</b></div>
                </div>
            </div>
           
            <div class="mb-3 mb-3 d-block">
                
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
                                        <th>Volume empoté: {{ format_nbr(selected.volume) }} m<sup>3</sup></th>
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
            </div>
            <div class="d-flex justify-content-end align-items-center mr-3  mb-3 sucesss"> 
                <!--button class="btn btn-lg btn-danger" :disabled = "selected.dossier == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button-->
    
                <div>
                     <!--button class="btn btn-lg btn-secondary text-white mr-3  mx-2" :disabled = "checkedCommandes == '' || selected.isClosed==1 || selected.etat == 0" v-on:click="cloturer()">Cloturer</button-->
                    <button class="btn btn-lg btn-primary" :disabled = "(selected.dossier == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()"><i class="fa fa-check"></i> Valider</button>
                </div>
               
            </div>
            <hr>

            <div class="d-flex justify-content-between align-content-center mb-2">
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
               
            </div>  
                
                <table class="table">
                    <thead class="thead-blue">
                         <tr>
                            <th class="p-2 border-right border-white h6">N°CDE</th>
                            <th class="p-2 border-right border-white h6">N°FE</th>
                            <th class="p-2 border-right border-white h6">N°ECV</th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6">Poids (KG)</th>
                            <th class="text-right p-2 border-right border-white h6">Volume (m<sup>3</sup>)</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Date livraison</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Crée par</th>
                            <!--th class="p-2 border-right border-white text-left h6">Préchargé par le client?</th-->
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
                        <!--td class="p-2 align-middle">
                            
                            <template v-if="dry.idPre > 0">
                                 <span class="badge badge-success">oui</span> {{dry.prechargeur}}
                            </template>
                            <template v-else>
                                  <span class="badge badge-warning">non</span>
                            </template>
                           
                        </td-->
                         <td class="p-2 align-middle">
                              <img :class="'loader_'+dry.reidre" style="display:none" src="/images/in-progress.gif"/>
                            
                              <input class="text-center val-douane" type="text" :data-id="dry.reidre" v-model="douane[dry.reidre]" @focus="focusDoune(dry.reidre)" @blur="saveDouane(dry)" :placeholder="dry.douane"> 
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
                </table>
                <div class="d-flex mt-4 justify-content-center">
                    <pagination
                        :data="reception"
                        @pagination-change-page="getReception"
                    ></pagination>
                    
                </div>
                <hr>
                <div class="d-flex justify-content-end mr-3  mb-3 sucesss"> 
                    <!--button class="btn btn-lg btn-danger" :disabled = "selected.dossier == '' || selected.etat == 0" v-on:click="generatePdf()">Générer le fichier PDF</button-->
                    <button class="btn btn-lg btn-primary" :disabled = "(selected.dossier == '' || selected.etat == 1) || (!reception.data || !reception.data.length)" v-on:click="valider()"><i class="fa fa-check"></i> Valider</button>
                </div>
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
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="plomb"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                       Plomb
                                       </label>
                                        <input class="w-65 form-control" id="plomb" v-model="empotageForm.plomb"/>
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-row justify-content-between align-items-center">
                                    

                                    <div class="w-49 d-flex align-items-center my-2">
                                       <label for="tc"  class="d-block m-0 text-left pr-2" style='white-space: nowrap;'>
                                        N° TC
                                       </label>
                                        <input class="w-65 form-control" id="tc" v-model="empotageForm.tc" />
                                    </div>
                                    
                                    <div class="w-49 d-flex align-items-center my-2">
                                         <label for="typetc"  class="d-block m-0 text-left  w-35 pr-2" style='white-space: nowrap;'>
                                       Type TC
                                       </label>
                                       
                                        <!--select class="w-65 form-control" v-model="empotageForm.typetc">
                                            <option value="4">20 DRY</option>
                                            <option value="3">40 DRY</option>
                                        </select-->
                                        <select class="form-control ml-2" v-model="empotageForm.typetc">
                                            <option value=''>Choisir le contenaire</option>
                                            <option v-for="contenaire in listContenaire"  :value="contenaire.id">{{contenaire.nom}}</option>
                                        </select>
                                       
                                    </div>
                                 </div>
                                
                             </div>
                             <div class="row">
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2 dateW65">
                                        <label class="d-block m-0 text-right  w-35 pr-2">Date Départ</label>
                                        <date-picker v-model="empotageForm.dateDepart" required valueType="YYYY-MM-DD" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                                    </div>
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2 dateW65">
                                        <label class="d-block m-0 text-left pr-2">Date Arrivée</label>
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

    </div>
</template>
<script>
    import { EventBus } from '../../event-bus';

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
            'listeDossier'
        ],  
        components: {
            PageLoader
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
                paginateRecep: 10,
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
                numDossierEdit: ''
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
            }
        },
        methods: { 
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
            axios.get('/empotage/list/'+this.idClient+'?page=' + page + "&paginate=" + this.paginate+ "&typeCmd=" + this.selectedTypeCmd+"&keysearch="+this.search+"&etatFiltre="+this.etatFiltre).then(response => {
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
            axios.get('/gerer/dossier/empotage/reception/'+this.idClient+"/"+this.selected.idCmd+'?page=' + page + "&paginate=" + this.paginateRecep+"&idEntrepot="+this.selected.idEntrepot+"&ref="+this.selected.dossier+"&id_empotage="+this.selected.identifiant+"&keysearch="+this.searchRecep).then(response => {

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
                }
               
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
            axios.get('/gerer/getCmd/empoter/'+id+'/'+typeCommande).then(response => {
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
            /*$(".inputCmd").each(function(){
                if($(this).is(':checked')){
                    self.commandeSelected.push($(this).val());
                }else{
                    self.commandeNoSelected.push($(this).val());
                }
                
            });*/

            $(".val-douane").each(function(){
                if($(this).val()!=''){
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
                  text: "Dossier n° "+this.selected.dossier,
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

                       axios.post("/gerer/validationEmpotage/valider/"+this.idClient, {
                            'idsCmd': this.commandeSelected,
                            'ignored': this.commandeNoSelected,
                            'idEmpotage' : this.selected.identifiant,
                            'typeCmd' : this.selected.idCmd,
                            'id_dossier': this.selected.dossier

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

                const headerTab = ['Référence', 'Emballage', 'Designation', 'Poids(KG)', 'Volume(m3)', 'Factures', 'Douanes'];

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

                    cmdCell.push(obj.refere+" "+prio);

                    const item = [cmdCell, emballage ,obj.fournisseurs, obj.repoid, obj.revolu, obj.renufa, obj.douane];
                    data.push(item);
                }

                var table = new Table(data).widths([70,70,'*',60,60,80,80]).layout({
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
            
                var nameFile = 'dossier-'+that.selected.dossier+'_'+labelCmd1+"_numtc-"+that.selected.numtc+"_plomb-"+that.selected.plomb+".pdf";


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


                    axios.post("/gerer/empotage/notification/"+self.clientCurrent['id'], {
                        'idsCmd': self.commandeSelected,
                        'id_dossier' : self.selected.dossier,
                        'numtc': self.selected.numtc,
                        'typetc': self.selected.typetc,
                        'plomb':  self.selected.plomb,
                        'typeCommande': self.selected.typeCmd,
                        'typeCmd': self.selected.typeCmd.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase(), 
                        'base64_file_pdf': url,
                        'IDclient': self.idClient

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
           showDossier(dossier){
                console.log("EDED: ", dossier.total_poids);
                this.commandeSelected = [];
                this.commandeNoSelected = [];
                this.isDetail = true;
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
            this.commandeSelected = [];
            this.commandeNoSelected = [];
            this.eventCmdSelected.ischecked = -1;
            this.eventCmdSelected.idcmd = '';

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
                data.append('tc', this.empotageForm.tc);
                data.append('typetc', this.empotageForm.typetc);
                data.append('plomb', this.empotageForm.plomb);
                data.append('idClient', this.idClient);
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
            setData(event) { 
                for(var i=0; i< this.listeDossier.length; i++){
                    if(this.listeDossier[i].idpre==event.target.value){
                        this.empotageForm.reference = this.listeDossier[i].numDossier;
                        this.empotageForm.typeCmd = this.listeDossier[i].type_commandes;
                        this.empotageForm.idEntrepot = this.listeDossier[i].entrepots;
                    }
                }
               
              
            },
            saveDouane(cmd){ 
                $(".loader_"+cmd.reidre).show();
                /*const data = new FormData();
                data.append('id', id);
                data.append('douane', this.douane[id]);
                axios.post("/gerer/updateDouane", data).then(response => {
                    $(".loader_"+id).hide(); 
                });*/

                var douane = this.douane[cmd.reidre];

                var placeholder = $(".val-douane[data-id='"+cmd.reidre+"']").attr("placeholder");


                if(!(typeof(douane) !== 'undefined') || !(douane !== null)){
                    douane='';                    
                }    

                if(douane==''){
                    if(!(typeof(placeholder) !== 'undefined') || !(placeholder !== null)){
                        
                    }else{
                        douane = placeholder;
                        this.douane[cmd.reidre] = douane;
                    }
                }          

               
                const data = new FormData();
                data.append('idEmpotage', this.selected.identifiant);
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

               
                  
                });

            },
            focusDoune(id){},
            cloturer(){
                 Vue.swal.fire({
                  title: 'Confirmez la cloture',
                  text:   'N°TC:'+this.selected.numtc+' - TYPE TC: '+ this.selected.typetc+' DRY - PLOMB: '+this.selected.plomb,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, cloturer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.post('/gerer/empotage/cloturer/'+this.selected.identifiant).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Cloturé!',
                              'Dossier cloturé.',
                              'success'
                            );


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
                        axios.delete('/gerer/deleteEmpotage/'+empotage.id+"?idClient="+this.idClient).then(response => {
                             Vue.swal.fire(
                              'Supprimé!',
                              'Dossier supprimé avec succés.',
                              'success'
                            );
                            /* this.modeModify = false;
                             this.getEmpotage();*/

                              location.reload();
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
            
                this.pdfFileModal = 'dossier-'+empo.reference+'_'+labelCmd+"_numtc-"+empo.numContenaire+"_plomb-"+empo.plomb+".pdf";
           },
             closeModalPdf(){
                 this.$refs.closePoupPdf.click();
            },
        },
        mounted() {
          this.getEmpotage();
          console.log("dddd", this.listeDossier);
        }
    }
</script>