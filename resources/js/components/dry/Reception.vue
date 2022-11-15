<template>
	<div class="bgPage">
        <!--PageLoader :is-loading = isLoading ></PageLoader--> 
        <div class="row">
          <div class="col-sm-6">
               <h2>Réception</h2>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Acceuil</a></li>
              <li class="breadcrumb-item active"><a href="#">Réception</a></li>
            </ol>
          </div>
        </div>
		<div class="row"> 
        <div class="col">
            <div class="tb-block block-rounded mb-2">
                <div class="block-content block-content-full ribbon ribbon-modern">
                  <div class="ribbon-box d-flex justify-content-between">
                    <span>Nbre commandes</span>
                    <span></span>
                  </div>
                  <div class="text-center py-3">
                    <h4 class="mb-0">{{ format_nbr(nbrCommande) }}</h4>
                  </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="tb-block block-rounded mb-2">
                <div class="block-content block-content-full ribbon ribbon-modern">
                  <div class="ribbon-box d-flex justify-content-between">
                    <span>Nbre colis total</span>
                    <span></span>
                  </div>
                  <div class="text-center py-3">
                    <h4 class="mb-0">{{ format_nbr(nbrColis) }}</h4>
                  </div>
                </div>
            </div>
        </div>
         <div class="col">
            <div class="tb-block block-rounded mb-2">
                <div class="block-content block-content-full ribbon ribbon-modern">
                  <div class="ribbon-box d-flex justify-content-between">
                    <span>Poids total (KG)</span>
                    <span></span>
                  </div>
                  <div class="text-center py-3">
                    <h4 class="mb-0">{{ format_nbr(totalPoids) }}</h4>
                  </div>
                </div>
            </div>
        </div>
         <div class="col">
            <div class="tb-block block-rounded mb-2">
                <div class="block-content block-content-full ribbon ribbon-modern">
                  <div class="ribbon-box d-flex justify-content-between">
                    <span>Volume total (m<sup>3</sup>)</span>
                    <span></span>
                  </div>
                  <div class="text-center py-3">
                    <h4 class="mb-0">{{ format_dec(totalVolume) }}</h4>
                  </div>
                </div>
            </div>
        </div>        
    </div>
    <div class="row d-flex align-items-center justify-content-between mb-3">
            <ul class="legend mt-4 mb-2 pl-3 flex-1">
                <li v-for="type in typeCmd" class="d-flex align-items-center">
                    <span class="etat_T m-0 mr-1 border-0" :style="{'background': type.tcolor}"></span> 
                    <label class="m-0 mr-2">{{type.typcmd}}</label>
                </li>
            </ul>
            <div class="mt-2 mr-3">
                <a href="#" class="text-white btn btn-primary font-weight-bold" data-toggle="modal" data-target="#newReception">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nouvelle Réception
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
                            <option :value="type.id" v-for="type in typeCommandeUsed">{{type.type}}</option>
                            
                        </select>
                    </div>
                </div>
        
            </div>
            <div class="pr-0 col-md-4">
                <input
                    v-model.lazy="search"
                    type="search"
                    class="form-control"
                    placeholder="Rechercher par n°cmd, n°fe, n°ecv / bbe,n°fact, utilisateur, fournisseur..."
                />
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue" :class="[isLoading ? '' : 'hasborder']"> 
                     <tr>
                        <th class="p-2 border-right border-white h6 d-none">Réf</th>
                        <!--th class="p-2 border-right border-white h6">Type Commande</th-->
                        <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[0])">N°CDE 
                            <i class="fa fa-sort" aria-hidden="true" ></i>
                        </th>
                        <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[1])">
                        N°FE <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[2])">N°ECV / BBE <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th class="p-2 border-right border-white h6 ">Fournisseur</th>
                        <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[7])">Emballage <i class="fa fa-sort" aria-hidden="true" ></i></th> 
                        <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[5])">Poids(KG)  <i class="fa fa-sort" aria-hidden="true" ></i></th>
                        <th class="text-right p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[6])">Volume (m<sup>3</sup>) <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[3])">Num Fact <i class="fa fa-sort" aria-hidden="true" ></i></th>
                        <!--th class="text-right p-2 border-right border-white h6">Montant Facture</th-->
                        <th class="text-nowrap cursor-pointer p-2 border-right border-white h6" v-on:click="sortByColumn(columns[4])">Nbre Jour <i class="fa fa-sort" aria-hidden="true" ></i></th>
                        <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer white-space-nowrap" v-on:click="sortByColumn(columns[4])">Date livraison <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th class="text-nowrap p-2 border-right border-white h6">Utilisateur</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>

                <tbody class="bgStripe" :class="[isLoading ? 'loader-line' : '']">
                	<template v-if="!dries.data || !dries.data.length">
                		<tr><td colspan="14" class="bg-white text-center" v-if="checking">Aucune donnée!</td></tr>
                    </template>
                    <template v-else>
                    	<tr v-for="dry in dries.data" :key="dry.reidre" class="bg-white">
                        
                        <td class="p-2 align-middle position-relative d-none">
                            <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': dry.typeCmd_color} : {'background': '#ccc'}]"></div>
                        	{{ dry.reidre }}
                        </td>
                        <!--td class="p-2 align-middle">
                        	{{ dry.type_commandes }}
                        </td--> 
                        <td class="p-2 align-middle position-relative ">
                             <div class="position-absolute typeCmd" v-bind:style="[true ? {'background': dry.typeCmd_color} : {'background': '#ccc'}]"></div>
                        	 <!--label class="badge badge-primary mr-1 numCmdLab w-100">{{ dry.rencmd }}</label-->
                             {{ dry.rencmd }}
                        </td>
                        <td class="p-2 align-middle">{{ dry.refere }}</td>
                        <td class="p-2 align-middle">{{ dry.reecvr }}</td>
                        <td class="p-2 align-middle text-uppercase">{{ dry.fournisseurs }}</td>
                        <td class="p-2 align-middle white-space-nowrap">
                        		<template v-if="dry.renbcl > 0">
		                     			<label class="badge badge-secondary mr-1">{{(dry.renbcl)}} Colis</label>
		                     	  </template>
		                     	  <template v-if="dry.renbpl > 0">
		                     		 <label class="badge badge-info mr-1">{{(dry.renbpl)}} Pal</label>
		                     	  </template>
                        
                        </td>
                        <td class="p-2 align-middle text-right">{{ format_nbr(dry.repoid) }}</td>
                        <td class="p-2 align-middle text-right">{{ (dry.revolu) }}</td>
                        
                        <td class="p-2 align-middle text-right">{{ dry.renufa }}</td>
                        <!--td class="p-2 align-middle text-right">{{ dry.revafa }}</td-->
                        <td class="p-2 align-middle white-space-nowrap">{{ dry.nbreJour }}</td>
                        <td class="p-2 align-middle white-space-nowrap"><i class="fa fa-calendar" aria-hidden="true"></i> {{ dry.redali }}</td>
                        <td class="p-2 align-middle text-nowrap"><i class="fa fa-user" aria-hidden="true"></i> {{ dry.reuser}}</td>
                        <td class="p-2 text-right align-middle">
                            <div class="d-flex justify-content-end">
                                <a title="Voir les détails" href="#" class="btn btnAction mx-1 btn-circle border btn-circle-sm bg-white" v-on:click="showDetail(dry)" data-toggle="modal" data-target="#detailReception">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a title="Editer" href="#" class="btn btnAction mx-1 btn-circle border btn-circle-sm bg-white" v-on:click="editDry(dry)" data-toggle="modal" data-target="#newReception">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <button :disabled="dry.refasc === null || dry.refasc === ''" title="Voir la facture" class="btn btn-circle btnAction border btn-circle-sm mx-1 position-relative bg-white" v-on:click="showFacture(dry.refasc)" data-toggle="modal" data-target="#openFacture">
				                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
				                    <i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i>
				                </button>
                                 <a title="Incident" href="#" class="btn btnAction mx-1 btn-circle position-relative border btn-circle-sm bg-white" v-on:click="incident(dry)" data-toggle="modal" data-target="#newIncident">
                                    <i class="fa fa-bolt" aria-hidden="true"></i>
                                    <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                                </a>
                                 <a title="Supprimer" href="#" class="btn btnAction mx-1 border-danger btn-circle border btn-circle-sm bg-white" v-on:click="deleteDry(dry)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="thead-blue">
                        <td colspan="4" class="p-2 h5 align-middle text-right border-right thead-blue">TOTAL</td>
                        <td class="p-2 align-middle h6 text-center border-right thead-blue">{{ format_nbr(nbrColis) }}</td>
                        <td class="p-2 align-middle h6 text-right border-right thead-blue">{{ format_nbr(totalPoids) }}</td>
                        <td class="p-2 align-middle h6 text-right border-right thead-blue">{{ format_nbr(totalVolume) }}</td>
                        <td colspan="5" class="p-2 h5 align-middle text-center  border-right" :class="[nbrJoursMoy<=8?'bg-success':'', (nbrJoursMoy>8 && nbrJoursMoy<=15)?'bg-warning':'', nbrJoursMoy>15?'bg-danger':'']">Nbr Jours Moyen: {{nbrJoursMoy}}</td>
                        
                    </tr>
                    </template>

                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="dries"
                :limit=10
                @pagination-change-page="getDries"
            ></pagination>
            
        </div>

		<!-- Modal Create Reception -->

		<div class="modal fade" id="newReception" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
          	<!-- Reception Init Form -->
          	<form @submit.prevent="checkReception" enctype="multipart/form-data" key=1 v-if="show">
                <div class="modal-content">
                  <div class="modal-header text-left">
                    <h4 class="modal-title w-100 font-weight-bold">Nouvelle Réception</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupInit">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body mx-3">
					
                  	<div class="row">
              			<div class="col-6 my-2 d-flex flex-column align-items-center">
            		 	
	                       <div class="w-100 d-flex align-items-center my-2">
		                       <label for="formname"  class="d-block m-0 text-right w-35 pr-2" >
		                       	Fournisseur
		                       </label>
		                       <div class="w-65" >

			                       <select class="form-control" v-model="initRecep.fournisseur" :class="{ 'border-danger': submitted && !$v.initRecep.fournisseur.required }" >
			                       	  <option value="">Choisir</option>
			                       	  
			                        	<option :value="four.id" v-for="four in listFournisseurs">{{four.fonmfo}}</option>
			                          
			                        	
			                        </select>
		                       </div>
		                    </div>
			                
		                     <div class="w-100 d-flex align-items-center my-2">
		                        <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
			                        <label for="numfact" class="d-block m-0 text-right w-35 pr-2" >N° Facture</label>
			                        <input autocomplete="off" class="w-65 form-control" v-model="initRecep.numfact" type="text" id="numfact" :class="{ 'border-danger': submitted && !$v.initRecep.numfact.required }" >
			                    </div>
		                     </div>
		                 </div>
		                 <div class="col-6 my-2 d-flex flex-column align-items-center">
		                 	   <div class="w-100 d-flex align-items-center my-2">
			                        <label class="d-block m-0 text-right w-35 pr-2">
			                        	Entrepot
			                        </label>
			                        <div class="w-65" >
				                       <select class="form-control" v-model="initRecep.entrepot" :class="{ 'border-danger': submitted && !$v.initRecep.entrepot.required }" >
                                            <option value="">Choisir</option>
                                            <option :value="entrepot.id"  v-for="entrepot in listEntrepots">{{entrepot.nomEntrepot}}</option>
				                        </select>
				                    </div>
			                    </div>
			                    <div class="w-100 d-flex align-items-center my-2">
			                        <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
				                        <label class="d-block m-0 text-right w-35 pr-2" for="numCommande">N°Commande</label>
				                        <input class="w-65 form-control" autocomplete="off" v-model="initRecep.numCommande" type="text" id="numCommande" :class="{ 'border-danger': submitted && !$v.initRecep.numCommande.required }" >
				                       
				                    </div>
				                     
			                     </div>
		                 	
		                 </div>
		             </div>

                  </div>
                   <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-info">Suivant</button>
                    <button type="button" v-on:click="closeModalInit()" class="btn btn-warning">Annuler</button>
                  </div>
                </div>
            </form>
            <transition>

            <!-- Reception details -->

            <form @submit.prevent="createReception" enctype="multipart/form-data"  key=1 v-if="!show">
                <div class="modal-content">
                  <div class="modal-header text-left">
                    <h4 class="modal-title w-100 font-weight-bold">
                    	<template v-if="modeModify">Modification Réception</template>
	                  	<template v-else>Nouvelle Réception</template>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="closeModal()" ref="closePoup">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body mx-3">
                  	<div class="row">
                  		 <div class="col-6 my-2 d-flex flex-column align-items-center">
                                <div class="w-100 d-flex align-items-center my-2">
                                   <label for="formname"  class="d-block m-0 text-right w-35 pr-2" >
                                    Fournisseur
                                   </label>
                                   <div class="w-65" >                                     
                                       <select class="form-control" v-model="reception.fournisseur" readonly>

                                            <option :value="four.id" v-for="four in listFournisseurs" v-if = "reception.fournisseur == four.id">{{four.fonmfo}}</option>
                                            
                                        </select>
                                   </div>
                                </div>
                                <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label for="numfact" class="d-block m-0 text-right w-35 pr-2" >N° Facture</label>
                                    <input autocomplete="off" class="w-65 form-control" v-model="reception.numfact" readonly type="text" id="numfact">
                                </div>
                                 <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
                                    <label for="montfact" class="d-block m-0 text-right w-35 pr-2" >Montant Facture (&euro;)</label>
                                    <input autocomplete="off" class="w-65 form-control" v-model="reception.montfact" type="text" id="montfact"  :class="{ 'border-danger': submitted && !$v.reception.montfact.required }">
                                </div>
			                     <div class="w-100 d-flex align-items-center my-2">
			                        <label class="d-block m-0 text-right w-35 pr-2">
			                        	Type Commande <span class="red">*</span>
			                        </label>
			                        <div class="w-65" >
                                        <select
                                            v-model="reception.typeCmd"
                                            class="form-control form-control-sm"  :class="{ 'border-danger': submitted && !$v.reception.typeCmd.required }" >
                                            <option value="">Choisir</option>
                                            <option :value="type.id" v-for="type in typeCmd">{{type.typcmd}}</option>
                                            
                                        </select>
			                        </div>
			                    </div>
			                    <div class="w-100 my-2 d-flex justify-content-between align-items-center">
			                        <label class="d-block m-0 text-right w-35 pr-2" for="numfe">N°FE</label>
			                        <input autocomplete="off" class="w-65 form-control" v-model="reception.numfe" type="text" id="numfe" :class="{ 'border-danger': submitted && !$v.reception.numfe.required }" >
			                    </div>
			                     <div class="w-100 my-2 d-flex justify-content-between align-items-center">
			                        <label class="d-block m-0 text-right w-35 pr-2" for="numecv">N°ECV / BBE</label>
			                        <input autocomplete="off" class="w-65 form-control" type="text" id="numecv" v-model="reception.numecv" :class="{ 'border-danger': submitted && !$v.reception.numecv.required }" >
			                    </div>

			                    
			                    <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center dateLiv">
				                        <label data-error="wrong" data-success="right" for="formname" class="d-block m-0 text-right w-35 pr-2">Date de livraison</label>
                                        <date-picker v-model="reception.datalivr" class="flex-1" :disabled-date="disabledFutureDate"  required valueType="YYYY-MM-DD" input-class="form-control w-100" placeholder="dd/mm/yyyy" format="DD/MM/YYYY" :input-class="{ 'border-danger': submitted && !$v.reception.datalivr.required }"></date-picker>
				                    </div>
				                   <div class="md-form w-100 d-flex my-3 justify-content-between align-items-start">
				                        <label for="formname" class="d-block m-0 text-right w-35 pr-2">Facture scanné</label>
				                        <div class="w-65">
				                        	<!--input type="file" id="formname" v-on:change="onFileChange" accept=".pdf,.doc"-->
				                        	 <input type="file" id="file" name="file" ref="file" v-on:change="handleFileUpload()" :disabled = "modeModify && hasPdf"/>
                                             <template v-if="modeModify && hasPdf">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    <span class="ml-1 small">{{nameFacture}}</span>
                                                    <button class="btn p-0" type="button" v-on:click="removePDF(nameFacture)"><i aria-hidden="true" class="fa fa-close text-danger"></i></button>
                                                </div>
                                               
                                            </template>
				                        </div>
				                        
				                    </div>
                  		 </div>
                  		  <div class="col-6 my-2 d-flex flex-column align-items-center">
                                <div class="w-100 d-flex align-items-center my-2">
                                    <label class="d-block m-0 text-right w-35 pr-2">
                                        Entrepot
                                    </label>
                                    <div class="w-65" >
                                       <select class="form-control" v-model="reception.entrepot" readonly>
                                            <option value="">Choisir</option>
                                            <option :value="entrepot.id"  v-for="entrepot in listEntrepots">{{entrepot.nomEntrepot}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="w-100 my-2 d-flex justify-content-between align-items-center">
                                    <label class="d-block m-0 text-right w-35 pr-2" for="numCommande">N°Commande</label>
                                    <input autocomplete="off" class="w-65 form-control" v-model="reception.numCommande" readonly type="text" id="numCommande">
                                </div>
                  		  	    <div class="w-100 d-flex my-2 justify-content-between align-items-center">
			                        <label class="d-block m-0 text-right w-35 pr-2" for="nbrpalette">
			                        Nbr Palette
			                         </label>
			                        <input autocomplete="off" class="w-65 form-control" type="number" v-model="reception.nbrpalette" id="nbrpalette"  :class="{ 'border-danger': submitted && !$v.reception.nbrcolis.required && !$v.reception.nbrpalette.required }">
			                    </div>
			                    <div class="w-100 d-flex my-2 justify-content-between align-items-center">
			                        <label class="d-block m-0 text-right w-35 pr-2" for="nbrcolis">
			                        Nbr Colis
			                         </label>
			                        <input autocomplete="off" class="w-65 form-control" type="number" v-model="reception.nbrcolis" id="nbrcolis" :class="{ 'border-danger': submitted && !$v.reception.nbrcolis.required && !$v.reception.nbrpalette.required }">
			                    </div>
			                  
                  		 	    <div class="w-100 my-2 d-flex justify-content-between align-items-center">
			                        <label  for="poidstotal" class="d-block m-0 text-right w-35 pr-2">Poids (KG)</label>
			                        <input autocomplete="off" class="w-65 form-control" type="decimal" id="poidstotal" v-model="reception.poidstotal" :class="{ 'border-danger': submitted && !$v.reception.poidstotal.required }">
			                    </div>
			                     <div class="w-100 d-flex my-2 justify-content-between align-items-center">
			                        <label for="volumetotal" class="d-block m-0 text-right w-35 pr-2" >
			                        	Volume (m<sup>3</sup>)
			                        </label>
			                        <input autocomplete="off" class="w-65 form-control" type="decimal" v-model="reception.volumetotal" id="volumetotal" :class="{ 'border-danger': submitted && !$v.reception.volumetotal.required }">
			                    </div>
			                     
			                    
			                     <div class="md-form w-100 d-flex my-2 justify-content-between align-items-center">
			                        <label for="commentaire" class="d-block m-0 text-right w-35 pr-2" >Commentaires</label>
			                        <textarea class="w-65 form-control" v-model="reception.commentaire" id="commentaire"></textarea>   
			                    </div>
                  		 </div>
                  	</div>
                  </div>
                  <div class="modal-footer d-flex justify-content-center">
                  	<template v-if="modeModify">
		                    <button type="submit" class="btn btn-success">Modifier</button>
		                    <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler la modification</button>
                  	</template>
                  	<template v-else>
              			<button type="button" class="btn btn-default border" v-on:click="show = !show">
					        Précédent
					    </button>
	                    <button type="submit" class="btn btn-success" :disabled="isRun?true:false">
                            <template v-if="isRun">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </template>
                        Enregister
                        </button>
	                    <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler</button>
                  	</template>
                  
                  </div>
                </div>
            </form>
            </transition>
          </div>
        </div>

        
        <modalFacture></modalFacture>
        <modalDetailsCommande></modalDetailsCommande>

          <!-- Modal incidents-->
        <div class="modal fade" id="newIncident" tabindex="-1" role="dialog" aria-labelledby="myModalIncident"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Nouvel incident</h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalIncident()" aria-label="Close" ref="closePoupIncident">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveIncident" enctype="multipart/form-data" key=1  autocomplete="off">
                           
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                            <label for="commande" class="d-block m-0 w-100 pr-2" >N° Commande</label>
                                            <input disabled = "true" class="w-100 form-control" autocomplete="off" id="commande" v-model="incidentForm.commande" aria-haspopup="true" aria-expanded="false" /> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 my-2 d-flex flex-column align-items-center"></div>
                            </div>
                             <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                        <label for="Objet" class="d-block m-0 w-100 pr-2" >Objet</label>
                                        <input class="w-100 form-control"  v-model="incidentForm.objet" type="text" id="Objet" :class="{ 'border-danger': submitted && !$v.incidentForm.objet.required }"/>
                                    </div>
                                 </div>
                                </div>
                                <div class="col-6 my-2 d-flex flex-column align-items-center"></div>
                             </div>
                             <div class="row">
                                 <div class="col-12 my-2">
                                    <div class="w-100 my-2">
                                         <label for="commentaires"  class="d-block m-0 w-100 pr-2" style='white-space: nowrap;'>
                                        Commentaires
                                       </label>
                                       <textarea class="w-100 form-control" v-model="incidentForm.commentaires"></textarea>
                                       
                                    </div>
                                 </div>
                             
                            </div>
                            <div class="row">
                                 <div class="col-12 md-form w-100 my-2">
                                    <label for="photos" class="d-block m-0 w-100 pr-2">Photos</label>
                                     <input type="file" id="file" name="file" multiple ref="fileIncident" v-on:change="handleFileUploadIncident()"/>
                                </div>
                            </div>
                             <div class="modal-footer d-flex justify-content-center"> 
                                <button type="submit" class="btn btn-success"><template v-if="isRun">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </template>Enregister</button>
                                <button type="button" v-on:click="closeModalIncident()" class="btn btn-warning">Annuler</button>
                            </div>
                         </form>
                    </div>
                    
             </div>
            
          </div>
        </div>
	</div>
</template>
<script type="text/ecmascript-6">
    import modalFacture from '../../components/modal/facture.vue';
    import modalDetailsCommande from '../../components/modal/detailsCommande.vue';
	import { required, minLength, between } from 'vuelidate/lib/validators';
    import PageLoader from '../../components/PageLoader.vue';  
    import { EventBus } from "../../event-bus"; 
	export default {
		props: [
          'idClient',
          'idEntite',
          'listFournisseurs',
          'typeCmd',
          'listEntrepots'
        ],
         components: {
            PageLoader,
            modalDetailsCommande,
            modalFacture
          },
		data() { 
            return {
                nowDate: new Date().toISOString().slice(0,10),
            	totalPoids: 0,
            	totalVolume: 0,
                totalFourniseur: 0,
            	nbrColis: 0,
            	nbrCommande: 0,
                nbrJoursMoy: 0,
                typeCommandeUsed: {},
            	initRecep :{
            		fournisseur: '',
            		numCommande: null,
            		entrepot: '',
            		numfact: null

            	},
                hasPdf: false,
                nameFacture:'',
				reception : {
					typeCmd : '',
					entrepot: 1,
					fournisseur: '',
					numfe: "",
					numecv: "",
					nbrcolis: "",
					nbrpalette: "",
					poidstotal: null,
					volumetotal: null,
					emballage: '',
					is_sort: "",
					emplacemnt: "",
					numfact: null,
					montfact: null,
					datalivr: "",
					cmd : "",
					commandeSaisies: [],
					numCommande: null,
					commentaire: "",
					file: ''
				},
				dries: {},
				paginate: 10,
				search: "",

				
				filterType: '',
				show: true,
				submitted: false,
				modeModify: false,
                isRun: false,

                isLoading: true,

				selectedTypeCmd: "",
                viewReception: {},
                incidentForm :{
                    id: '',
                    commande:'',
                    objet: '',
                    commentaires:'',
                    file: [],

                },
                attachments: [],
                checking: false,
                // Sort column
                columns: ['rencmd', 'refere', 'reecvr', 'renufa', 'redali', 'repoid', 'revolu', 'totalColis'],
                sortedColumn: '',
                order: 'asc'
            }
        },

        validations: {

        	initRecep : {
	            numCommande: { required },
	            numfact:     { required },
	            entrepot:    { required },
	            fournisseur: { required },

	        },

	        reception : {
	            typeCmd:     { required },
	            numfe:       { required },
	            numecv:      { required },
	            datalivr:    { required },
	            poidstotal:  { required },
	            volumetotal: { required },
	            montfact:    { required },
	            nbrcolis:    { required },
	            nbrpalette:  { required }
	        },
            incidentForm : {
                objet: { required }
            }
            

        },

        watch: {

	       paginate: function(){
	            this.getDries();
	       },
	       selectedTypeCmd: function(value) {
	            this.getDries();
	       },
           search: function(value) {
                this.getDries();
           },
           order: function(value) {
                this.getDries();
           }
		},

        methods : {
             /**
             * Sort the data by column.
             * */
            sortByColumn(column) {
             /* if (column === this.sortedColumn) {
                this.order = (this.order === 'asc') ? 'desc' : 'asc'
              } else {
                this.sortedColumn = column
                this.order = 'asc'
              } */
              this.sortedColumn = column;
              this.order = (this.order === 'asc') ? 'desc' : 'asc';
            },
            closePreview(){
                this.ispreviewModal=false;
            },
            disabledFutureDate(date) {
              const today = new Date();
              today.setHours(0, 0, 0, 0);

              return date > today;
            },
            incident(item){
                this.incidentForm.commande=item.rencmd;
            },
             handleFileUploadIncident(){
                this.attachments = [];
                for(var i=0; i<this.$refs.fileIncident.files.length;i++){
                    this.attachments.push(this.$refs.fileIncident.files[i])
                }
            },
            saveIncident(){

                this.submitted = true;
                this.isRun = true;
                // stop here if form is invalid
                this.$v.incidentForm.$touch();
                if (this.$v.incidentForm.$invalid) {
                    return;
                }
              
                const data = new FormData();
                data.append('objet', this.incidentForm.objet);
                data.append('commentaires', this.incidentForm.commentaires);
                data.append('commandes', this.incidentForm.commande);
                data.append('idClient', this.idClient); 
                data.append('IDentite', this.idEntite);
                data.append('file[]', this.attachments);

                for (let i = 0; i < this.attachments.length; i++) {
                data.append('files' + i, this.attachments[i]);
                }
                data.append('TotalFiles', this.attachments.length);

                let action = "createIncident";

                if(this.modeModify){
                    data.append('id', this.incidentForm.id);
                    action = "modifyIncident";
                }

                axios.post("/incidents/"+action, data,  {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        } 
                }).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoupIncident.click();
                        this.flushDataIncident();
                        Vue.swal.fire(
                          'succés!',
                          'Incident crée avec succés!',
                          'success'
                        )

                        this.getDries();
                        

                    }else{
                         Vue.swal.fire(
                          'error!',
                          response.data.message,
                          'error'
                        )
                    }
                    this.submitted = false;
                    this.isRun = false;
                    //this.closeModalIncident();
                   
                });
            },
            flushDataIncident(){
                this.incidentForm.commande = "";
                this.incidentForm.commentaires = "";
                this.incidentForm.objet = "";
                this.$refs.fileIncident.value = null
                this.incidentForm.file = "";
                this.attachments = [];

            },
            getFournis(id){
                for(var i=0; i<this.listFournisseurs.length; i++){
                    if(this.listFournisseurs[i].id==id){
                        return this.listFournisseurs[i].fonmfo; 
                    }
                }
                return id;
            },
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
                    if(this.typeCmd[i].id==id){
                        return this.typeCmd[i].typcmd; 
                    }
                }
                return id;
            },
            removePDF(nameFact){

                Vue.swal.fire({
                  title: 'Confirmez la suppression de la facture',
                  text: nameFact,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        this.hasPdf = false;
                        this.reception.file = '';
                        this.nameFacture = '';
                  
                  }
                });
            },
        	handleFileUpload(){

        		this.reception.file = this.$refs.file.files[0];
        	},
        	addCommande(){

        		this.commandeSaisies.push(this.cmd);
        	   
        	},
        	deleteCommande(cmd){

        		this.commandeSaisies.splice(cmd, 1);
        	},
        	checkReception(){

        		this.submitted = true;

                // stop here if form is invalid
                this.$v.initRecep.$touch();
                if (this.$v.initRecep.$invalid) {
                    return;
                }
                
        		axios.post('/checkDoublon', {
	                'fournisseur' : this.initRecep.fournisseur,
	                'entrepot'    : this.initRecep.entrepot,
					'numfact'     : this.initRecep.numfact,
					'numCommande' : this.initRecep.numCommande
		            }).then(response => {
		                console.log('Rep', response.data.code);
		                if(response.data.code==0){
		                    this.show = false;
		                    this.reception.fournisseur = this.initRecep.fournisseur;
			                this.reception.entrepot    = this.initRecep.entrepot;
			                this.reception.numfact     = this.initRecep.numfact;
			                this.reception.numCommande = this.initRecep.numCommande;  
		                }else{
		                	this.show = true;
		                	this.$toast.warning("Cet enregistement existe déja!");
		                }
		                this.submitted = false;
		               
		            });
        	},

        	createReception(){

                this.submitted = true;
                this.isRun = true;

                if(this.reception.nbrcolis > 0){
                	if(this.reception.nbrpalette == ""){
                		this.reception.nbrpalette = 0;
                	}
                }

                if(this.reception.nbrpalette > 0){
                	if(this.reception.nbrcolis == ""){
                		this.reception.nbrcolis = 0;
                	}
                }

        		// stop here if form is invalid
                this.$v.reception.$touch();

                if (this.$v.reception.$invalid) {
                    this.isRun = false;
                    return;
                }

        	    const data = new FormData();
				data.append('file', this.reception.file);
				data.append('type_commande', this.reception.typeCmd);
				data.append('fournisseur', this.reception.fournisseur);
				data.append('entrepot', this.reception.entrepot);
				data.append('fe', this.reception.numfe);
				data.append('ecv', this.reception.numecv);
				data.append('nbrcolis', this.reception.nbrcolis);
				data.append('nbrpalette', this.reception.nbrpalette);
				data.append('poidstotal', this.reception.poidstotal);
				data.append('volumetotal', this.reception.volumetotal);
				data.append('emballage', this.reception.emplacemnt);
				data.append('numfact', this.reception.numfact);
				data.append('montfact', this.reception.montfact);
				data.append('dateliv', this.reception.datalivr);
				data.append('numCommande', this.reception.numCommande);
				data.append('commentaire', this.reception.commentaire);
				data.append('client', this.idClient);
                data.append('IDentite', this.idEntite);

				let action = "createReception";
				let msgSuc = "Enregistré avec succés!";

				if(this.modeModify){
					data.append('reidre', this.reception.reidre);
					action = "modifyReception";
					msgSuc = "Modification effectuée avec succés!";
                    data.append('nameFacture', this.nameFacture);
				}


    	        axios.post("/"+action, data).then(response => {
	                console.log('Rep', response.data.code);
                     this.$refs.file.value = null;
                     this.reception.file = null;
	                if(response.data.code==0){
	                    this.$refs.closePoup.click();
	                    Vue.swal.fire(
                          'succés!',
                          msgSuc,
                          'success'
                        );
                        this.sortedColumn = "";
	                    this.getDries();
	                    this.flushData();
	                    this.show = true;

                        //this.sendNotification(response.data.message);

	                }else{

	                }
	                this.submitted = false;
                    this.isRun = false;
	               
	            }).catch(err => {
                        Vue.swal.close();
                        console.log(err.code);
                        console.log(err.message);

                        Vue.swal.close();
                            Vue.swal.fire(
                          'Warning!',
                          'Echec envoi de mail',
                          'warning'
                        ).then((result) => {
                            // redirection   
                            location.reload();
                        });
                    });
        		
        	},

            sendNotification(data){
                axios.post("/sendNotificationReception", {'data':data});
            },

	        getDries(page = 1){
                if(this.search==''){ 
                    this.isLoading=true;
                }
                
                const requestOne = 
	        	axios.get('/dries/'+this.idClient+'/'+this.idEntite+'?page=' + page + "&paginate=" + this.paginate + "&typeCmd=" + this.selectedTypeCmd+"&keysearch="+this.search+"&column="+this.sortedColumn+"&order="+this.order);

                const requestTwo =  axios.get('/driesState/'+this.idClient+'/'+this.idEntite+'?page=' + page + "&paginate=" + this.paginate + "&typeCmd=" + this.selectedTypeCmd+"&keysearch="+this.search);

                axios.all([requestOne, requestTwo]).then(responses => {

                      const responseOne = responses[0];

                      const responseTwo = responses[1]; 

                       this.dries = responseOne.data;
                        if(this.dries.data.length > 0){
                           
                        }else{
                             this.checking=true;
                        }

                         this.totalPoids = responseTwo.data.poidsTotal;
                            this.nbrColis = responseTwo.data.nbreColis;
                            this.totalVolume = responseTwo.data.volumeTotal;
                            this.nbrCommande = responseTwo.data.commandesTotal; 
                            this.nbrJoursMoy = responseTwo.data.nbrJourMoyen;
                            this.typeCommandeUsed = responseTwo.data.typeCmd;
                            this.checking=true;
                        this.closeLoader();

                    }).catch(errors => {

                      // react on errors.

                    })
        	},

        	closeModal(){
                this.show = true;
                this.submitted = false;
                this.modeModify = false;
				this.$refs.closePoup.click();
				this.flushData();
				

        	},
            closeModalInit(){
                this.$refs.closePoupInit.click();
                this.flushData();
                this.show = true;
                this.submitted = false;
                this.modeModify = false;

            },
            closeModalDetail(){
                 this.$refs.closePoupDetail.click();
                 this.closePreview();
            },

        	flushData(){
				this.reception.typeCmd = "";
				this.reception.fournisseur = "";
				this.reception.entrepot= 1;
				this.reception.numfe= "";
				this.reception.numecv= "";
				this.reception.nbrcolis= "";
				this.reception.nbrpalette= "";
				this.reception.poidstotal= "";
				this.reception.volumetotal= "";
				this.reception.is_sort= "";
				this.reception.emballage= "";
				this.reception.emplacemnt= "";
				this.reception.numfact= "";
				this.reception.montfact= "";
				this.reception.datalivr= "";
				this.reception.numCommande = "";
                this.reception.commentaire="";

                this.initRecep.numCommande = "";
                this.initRecep.numfact= "";
                this.initRecep.entrepot= "";
                this.initRecep.fournisseur= "";
        	},
        	getClient(){
	        	axios.get('/client/'+this.idClient).then(response => {
	                console.log(response);
	            
	            });
        	},
            showDetail(dry){
                 EventBus.$emit('VIEW_CMD', {
                    openView: true,
                    dry: dry,
                    fournisseur: this.listFournisseurs,
                    typeCommande: this.typeCmd,
                    entrepot: this.listEntrepots,
                    idClient: this.idClient,
                    canDeleteIncident: true
                }); 
            },
             showFacture(fact){
                 EventBus.$emit('VIEW_FACT', {
                    pathFile: fact
                }); 
            },
        	editDry(dry){

        		this.modeModify = true;
        		this.show = false;
				this.submitted = false;
                this.reception.reidre = dry.reidre;
        		this.reception.typeCmd = dry.id_commandes;
				this.reception.fournisseur = dry.id_fournisseurs;
				this.reception.entrepot= 1;
				this.reception.numfe= dry.refere;
				this.reception.numecv= dry.reecvr;
				this.reception.nbrcolis= dry.renbcl;
				this.reception.nbrpalette= dry.renbpl;
				this.reception.poidstotal=  dry.repoid;
				this.reception.volumetotal= dry.revolu;
				this.reception.numfact= dry.renufa;
				this.reception.montfact= dry.revafa;
				this.reception.datalivr= dry.redalivraison;
				this.reception.numCommande = dry.rencmd;
				this.reception.commentaire = dry.recomt; 
                if(dry.refasc==null || dry.refasc==''){
                   
                    this.hasPdf=false;
                    this.nameFacture = '';
                }else{
                
                    this.hasPdf=true;
                    this.nameFacture = dry.refasc;
                }
        	},

            closeLoader(){
                var thiss = this;
                setTimeout(function(){
                  thiss.isLoading=false;
                },500);
            },

        	deleteDry(dry){
        		Vue.swal.fire({
				  title: 'Confirmez la suppression',
				  text: "Commande n° "+dry.rencmd,
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#d33',
				  cancelButtonColor: '#3085d6',
				  confirmButtonText: 'Oui, supprimer!'
				}).then((result) => {
				  if (result.isConfirmed) {
					  	axios.delete('/deleteReception/'+dry.reidre+'/'+dry.clients_id).then(response => {
			                console.log(response);
			             	 Vue.swal.fire(
						      'Deleted!',
						      'Your file has been deleted.',
						      'success'
						    );
			             	 this.modeModify = false;
			             	 this.getDries();


			            });
				  
				  }
				})
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
            closeModalIncident(){
                this.$refs.closePoupIncident.click();
            },
            closeModalPreview(){
                this.$refs.closePopupPhotos.click();

                  $('#detailReception').modal('show');
                //  this.$refs.closePoupDetail.click();
                //this.$refs.detailRep.classList.toggle("show");
            },
            format_nbr(mnt){
                return mnt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            },
            format_dec(mnt){
                return mnt.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            }
        },
        
        mounted() {
	        this.getDries();
            this.totalFourniseur = this.listFournisseurs.length;

	    }

	}
</script>