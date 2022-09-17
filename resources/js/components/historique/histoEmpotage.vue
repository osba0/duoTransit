 <template>
    <div>
        <template v-if="!isDetail"> 
            <form v-on:submit.prevent="search">
            <div class="row justify-content-center histoform">
                <div class="col-md-12">
                    <div class="d-inline-block">
                        <div class="border p-3 bg-white justify-content-center rounded">
                            <div class="filtreTireur">
                                <template v-if="gestionDocim!=1">
                                    <div class="d-flex mb-3">
                                        <div class="mr-3 text-left" style="width: 180px">
                                            <label class="mr-3  text-left w-100 mb-0">Date Début</label>
                                            <date-picker v-model="filtre.dateDebut" required valueType="YYYY-MM-DD"  :disabled-date="disabledFutureDate" input-class="form-control" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                                        </div>
                                        <div class="mr-3 text-left" style="width: 180px">
                                              <label class="text-left w-100 mb-0">Date Fin</label>
                                              <date-picker v-model="filtre.dateFin" required valueType="YYYY-MM-DD" input-class="form-control w-100" placeholder="dd/mm/yyyy" format="DD/MM/YYYY"></date-picker>
                                        </div>
                                    </div>
                                </template>
                                <div class="d-flex align-items-end">
                                    <div class="mr-3 text-left" style="width: 180px">
                                        <label class="text-left w-100 mb-0">Type Commande</label>
                                        <select class="form-control" v-model="filtre.typeCmd">
                                            <option value="">Tout</option>
                                            <option v-for="type in typeCmd"  :value="type.id">{{type.typcmd}}</option>
                                        </select>
                                    </div>
                                    <div class="mr-3 text-left" style="width: 180px">
                                        <label class="text-left w-100 mb-0">Fournisseur</label>
                                        <select class="form-control" v-model="filtre.fournisseur">
                                            <option value="">Tout</option>
                                            <option :value="four.id" v-for="four in listFournisseurs">{{four.fonmfo}}</option>
                                            
                                        </select>
                                    </div>
                                    <div class="mr-3 text-left" style="width: 180px">
                                        <label class="text-left w-100 mb-0">N°Dossier</label>
                                        <input type="text" class="form-control"  v-model="filtre.dossier">
                                    </div>
                                     <div class="mr-3 text-left" style="width: 180px">
                                        <label class="text-left w-100 mb-0">N°Commande</label>
                                        <input type="text" class="form-control"  v-model="filtre.commande">
                                    </div>
                                     <div class="mr-3 text-left" style="width: 180px">
                                        <label class="text-left w-100 mb-0">N°Docim</label>
                                        <input type="text" class="form-control"  v-model="filtre.docim">
                                    </div>
                                    <div>
                                       <button type="submit" class="btn btn-success ml-3"> <span v-if="isloading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Rechercher</button>
                                    </div>
                                </div>
                            </div>
                             
                        </div>  
                    </div>
                </div>
              
            </div>
        </form>
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
                                        <template v-if="userRole=='client'">
                                        <th class="p-2 border-right border-white h6 bg-primary">N° Docim</th>
                                        </template>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[0])">
                                            
                                            N° Dossier <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                        <th class="p-2 border-right border-white h6" title="Date départ du bâteau">Date Départ</th>
                                        <th class="p-2 border-right border-white h6"  title="Date arrivée du bâteau">Date Arrivée</th>
                                        <!--th class="p-2 border-right border-white h6">N°TC</th>
                                        <th class="p-2 border-right border-white h6">Type TC</th>
                                        <th class="p-2 border-right border-white h6">N°Plomb</th>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[3])">Nbre colis total <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[1])">Poids total <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[2])">Volume total <i class="fa fa-sort" aria-hidden="true" ></i></th-->
                                        
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
                                            <template v-if="userRole=='client'">
                                                <td class="position-relative"><div class="position-absolute typeCmd" v-bind:style="[true ? {'background': res.typeCmd_color} : {'background': '#ccc'}]"></div> 
                                                   <span v-if="res.numDocim != ''" class="text-primary font-weight-bold">
                                                        {{res.numDocim}} 
                                                        <i v-if="gestionDocim==1" class="fa fa-pencil"  v-on:click="addNumDocim(res, res.numDocim)"></i>
                                                    </span>  
                                                    <button v-if="res.numDocim == ''" class="badge border-0 badge-info" v-on:click="addNumDocim(res)">Ajouter</button>
                                                   
                                                </td>
                                            </template>
                                            <td class="position-relative"><div v-if="userRole!='client'" class="position-absolute typeCmd" v-bind:style="[true ? {'background': res.typeCmd_color} : {'background': '#ccc'}]"></div> {{ res.reference }}</td>
                                            <td class="p-2 align-middle">
                                               {{ res.dateDepart }}
                                            </td>
                                            <td class="p-2 align-middle">
                                               {{ res.dateArrivee }}
                                            </td>
                                            <!--td class="p-2 align-middle">
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
                                            <td>{{ res.total_volume }}</td-->
                                            
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
                                                    <!--div @click="detailsCommande(res)" class="d-flex cursor-pointer bg-primary position-relative rounded-circle boxAction justify-content-center align-items-center mr-2" title="Liste des commandes">
                                                    <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre">{{ res.nbrCmd > 9 ? '+9' : res.nbrCmd }}</span> <i class="fa fa-list-ul" aria-hidden="true"></i>
                                                    </div>
                                                    <a v-if="res.etat==1" href="#" title="Rapport Empotage" class="boxAction btn btn-circle border-0 btn-circle-sm m-1 position-relative bg-danger"  @click="showInvoice(res)" data-toggle="modal" data-target="#openFacture">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    </a-->
                                                    <a href="#" title="Liste contenaire" class="btn p-0 m-1 ml-2 position-relative"  @click="showContenaire(res)">
                                                        <img src="/images/contenaire.png" alt="Contenaire" height="30">
                                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{res.totalContenaire}}</span>
                                                        <!--span v-if="gestionDocim==1" class="position-absolute d-flex align-items-center justify-content-center rounded-circle isValidate text-white">-</span-->
                                                    </a>
                                                    <a v-if="res.etat==1" href="#" title="Complément de document" class="btn p-0 m-1 position-relative ml-2"  @click="showDocument(res)" data-toggle="modal" data-target="#openDocument">
                                                        <img src="/images/document_compl.png" alt="Documents" height="30">
                                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{ getCountDoc(res.document) > 9 ? '+9' : getCountDoc(res.document) }}</span>
                                                        <!--span v-if="gestionDocim==1" class="position-absolute d-flex align-items-center justify-content-center rounded-circle isValidate text-white">-</span-->
                                                     
                                                    </a>

                                                    <template v-if="userRole=='client'">
                                                        
                                                         <a v-if="res.etat==1" href="#" title="Déclaration Douane" class="btn p-0 m-1 ml-2 position-relative"  @click="showDeclarationDouane(res)" data-toggle="modal" data-target="#openDeclarationDouane">
                                                            <img src="/images/douane.png" alt="Documents" height="30">
                                                            <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{ getCountDeclDouane(res.declDounae) > 9 ? '+9' : getCountDeclDouane(res.declDounae) }}</span>
                                                         
                                                        </a>
                                                        <a title="Cloturer" v-if="gestionDocim==1" class="btn m-1 btn-circle border btn-circle-sm m-1 ml-3 bg-white" v-on:click="cloturer(res)">
                                                            <i class="fa fa-check" aria-hidden="true"></i>
                                                        </a>
                                                    </template>



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
                     <div>
                        <div class="d-flex flex-row justify-content-center">
                            <template v-for="contenaire, index in contenaires.data">
                                <div class="position-relative ml-3" style="width:60px">
                                    <button v-on:click="contenaireSelectionner(index)" class="btn rounded-circle btn-default mb-2" :class="index==currentIndexContenaire ? 'bg-primary':'bg-light'"><span class="h1"><i class="fa fa-cube"></i></span>
                                    </button>
                                    <button v-if="contenaire.etat == 1" title="Rapport d'empotage" style="top: -3px; right: -3px;"  v-on:click="showRapport(index)" class="badge bg-white border-0 shadow-sm position-absolute rounded-circle" data-toggle="modal" data-target="#openFacture"><i class="fa fa-file-pdf-o text-danger"></i></button>
                                  
                                    <button v-if="contenaire.etat == 0" style="top: -3px; right: -3px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="supprimerContenaire(contenaire)"><i class="fa fa-times"></i></button>
                                </div>
                            </template>                            
                        </div>
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
                            <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[0])">N°CDE 
                                <i class="fa fa-sort" aria-hidden="true" ></i>
                            </th>
                            <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[1])">N°FE <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[2])">N°ECV <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[5])">Poids <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[6])">Volume <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-nowrap p-2 border-right border-white h6">Factures</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Douanes</th>
                            <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[4])">Date livraison <i class="fa fa-sort" aria-hidden="true" ></i></th>
                            <th class="text-nowrap p-2 border-right border-white h6">Crée par?</th>
                            <th class="p-2 border-right border-white text-left h6">Validé par?</th>
                            
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
                        <td class="p-2 align-middle">
                             {{dry.renufa}}
                        </td>
                        <td class="p-2 align-middle">
                             {{dry.douane}}
                        </td>
                        <td class="p-2 align-middle"><i class="fa fa-calendar" aria-hidden="true"></i> {{ dry.redali }}</td>
                        <td class="p-2 align-middle text-nowrap"><i class="fa fa-user" aria-hidden="true"></i> {{ dry.user_created}}</td>
                        <td class="p-2 align-middle">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            {{dry.prechargeur}}
                           
                        </td>
                        <td class="p-2 text-right">
                             <a title="Voir les détails" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white position-relative" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                 <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                            </a>
                            <button :disabled="dry.refasc === null || dry.refasc === ''" title="Voir la facture" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-white" v-on:click="showFacture(dry.refasc)" data-toggle="modal" data-target="#openFacture">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i>
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
        <!-- Modal Document-->
        <div class="modal fade fullscreenModal" id="openDocument" tabindex="-1" role="dialog" aria-labelledby="myModalFacture"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left align-items-center">
                        <h4 class="modal-title font-weight-bold">Documents</h4>
                        <!--template v-if="userRole=='admin'">
                            <div class="flex-1 text-center">
                                <label class="mb-0">Ajout un fichier</label>
                                <input type="file" id="file" name="file" multiple ref="fileDoc" v-on:change="handleFileUploadDoc()"/>
                                <button class="btn btn-success" v-on:click="saveDocs()">Enregister</button>
                            </div>
                        </template-->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupDoc" v-on:click="search(currentPage)">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        
                    </div>
                    <div class="modal-body mx-3 overflow-hidden">
                        <div class="d-flex justify-content-between h-100">
                            <div>
                                <div class="d-flex flex-column">
                                    <template v-for="doc, index in tabDoc">
                                        <div class="position-relative">
                                            <button v-on:click="getDoc(index)" class="btn rounded-circle  btn-default mb-2" :class="index==currentIndex ? 'bg-light':''">
                                                <span class="h1"><i class="fa fa-file"></i></span>
                                            </button>
                                            <!--template v-if="userRole=='admin'">
                                                <button style="top: -3px; right: -3px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="removeDoc(doc)"><i class="fa fa-times"></i></button>
                                            </template-->
                                        </div>
                                    </template>
                                   
                                </div>
                            </div>
                             <template v-if="tabDoc.length > 0">
                                <embed :src="'/assets/documents/'+tabDoc[currentIndex]" frameborder="0" width="95%" height="450px">
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
        
        <!-- Modal Declration douane-->
        <div class="modal fade fullscreenModal" id="openDeclarationDouane" tabindex="-1" role="dialog" aria-labelledby="myModalDouane"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left align-items-center">
                        <h4 class="modal-title font-weight-bold">Déclaration Douane</h4>
                        <div class="flex-1 text-center" v-if="gestionDocim==1">
                            <label class="mb-0">Ajout un fichier</label>
                            <input type="file" id="file" name="file" multiple ref="fileDouane" v-on:change="handleFileUploadDouane()"/>
                            <button class="btn btn-success" v-on:click="saveDocsDouane()">Enregister</button>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupDocDounane" v-on:click="search(currentPage)">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3 overflow-hidden">
                        <div class="d-flex justify-content-between h-100">
                            <div>
                                <div class="d-flex flex-column">
                                    <template v-for="doc, index in tabDeclaration">
                                        <div class="position-relative">
                                            <button v-on:click="getDocDouane(index)" class="btn rounded-circle  btn-default mb-2" :class="index==currentIndexDouane ? 'bg-light':''">
                                                <span class="h1"><i class="fa fa-file"></i></span>
                                            </button>
                                            <button v-if="gestionDocim==1" style="top: -3px; right: -3px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="removeDocDouane(doc)"><i class="fa fa-times"></i></button>
                                        </div>
                                    </template>
                                   
                                </div>
                            </div>
                             <template v-if="tabDeclaration.length > 0">
                                <embed :src="'/assets/declarationsDouane/'+tabDeclaration[currentIndex]" frameborder="0" width="95%" height="450px">
                              </template>
                             <template v-else>
                                <div class="w-100 text-center">Aucun document </div> 
                              </template>
                            
                         </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closePoupDocDounane()" class="btn btn-warning">Fermer</button>
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
            'listEntrepots',
            'userRole',
            'gestionDocim'
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
                commande: '',
                docim: ''
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
            order: 'asc',
            attachments: [],
            attachmentsDouane: [],
            currentEmpotage: null,
            tabDoc: [],
            currentIndex: 0,
            currentPage: 1,
            contenaires: {},
            currentIndexContenaire: 0,
            tabDeclaration: [],
            currentIndexDouane: 0
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
        addNumDocim(empo, value=""){
            var numDocim = "";
            Vue.swal.fire({
              title: 'Entrer le n° docin',
              input: 'text',
              inputValue: value,
              inputAttributes: {
                autocapitalize: 'off'
              },
              showCancelButton: true,
              confirmButtonText: 'Valider',
              showLoaderOnConfirm: true,
              inputValidator: (value) => {
                    if (!value) {
                      return 'Entrer une valeur!'
                    }
                  },
              preConfirm: (val) => {
                axios.post('/addNumDocim/'+empo.id+"?docim="+val).then(response => {
                    console.log(response);
                    if(response.data.code == 0){
                        Vue.swal.fire(
                          'Succés!',
                          'N°Docim mis à jour!',
                          'success'
                        ).then((result) => {
                            // redirection   
                            location.reload();
                        }); 
                     }else{
                        Vue.swal.fire(
                          'Warning!',
                          "",
                          'warning'
                        );
                     }

                });  
              },
              allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                
            });
        },
        getDoc(index){
            this.currentIndex = index;
        },
        getDocDouane(index){
            this.currentIndexDouane = index;
        },
        sortByColumn(column) {
            this.sortedColumn = column;
            this.order = (this.order === 'asc') ? 'desc' : 'asc';
        },
        sortByColumnSearch(column) {
            this.sortedColumnSearch = column;
            this.orderSearch = (this.orderSearch === 'asc') ? 'desc' : 'asc';
        },
        getCountDoc(doc){
            if(Array.isArray(doc)){
                return doc.length;
            }
            return 0;
        },
        getCountDeclDouane(doc){
            if(Array.isArray(doc)){
                return doc.length;
            }
            return 0;
        },
        disabledFutureDate(date) {
          const today = new Date();
          today.setHours(0, 0, 0, 0);

          return date > today;
        },
        handleFileUploadDoc(){
                this.attachments = [];
                for(var i=0; i<this.$refs.fileDoc.files.length;i++){
                    this.attachments.push(this.$refs.fileDoc.files[i])
                }
            },
        handleFileUploadDouane(){
                this.attachmentsDouane = [];
                for(var i=0; i<this.$refs.fileDouane.files.length;i++){
                    this.attachmentsDouane.push(this.$refs.fileDouane.files[i])
                }
            },
        saveDocsDouane(){
             const data = new FormData();

            if(!this.attachmentsDouane.length > 0){
                 Vue.swal.fire(
                          '',
                          'Ajouter un document avant de valider!',
                          'warning'
                        )   
                return false;
            }
            
            data.append('file[]', this.attachmentsDouane);

            for (let i = 0; i < this.attachmentsDouane.length; i++) {
                data.append('files' + i, this.attachmentsDouane[i]);
            }


            data.append('TotalFiles', this.attachmentsDouane.length);

            data.append('Document[]', this.tabDeclaration  ); 

            axios.post("/saveDocsDouane/"+this.currentEmpotage, data,  {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        } 
                }).then(response => {
                   
                    this.$refs.fileDouane.value = null;
                    if(response.data.code==0){
                         this.tabDeclaration = response.data.file;
                         this.currentIndexDouane = 0;
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
                         this.currentIndex = 0;
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
                                     this.currentIndex = 0;
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
        removeDocDouane(nameDoc){
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

                        data.append('Document[]', this.tabDeclaration); 
                        data.append('nameFile', nameDoc); 

                        axios.post("/removeDocsdouane/"+this.currentEmpotage, data,  {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    } 
                            }).then(response => {
                               
                                if(response.data.code==0){
                                     this.tabDeclaration = response.data.file;
                                     this.currentIndex = 0;
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
        search(page=1){
            this.showResult = true;
            this.isloading = true;
            this.currentPage = page;
            this.reinit();
            axios.post('/search/histoEmpotage/'+this.clientCurrent.id+"?column="+this.sortedColumnSearch+"&order="+this.orderSearch+"&isDocim="+this.gestionDocim,{
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
        getReception(page = 1){
            // get commandes
          
            axios.get('/histoEmpotage/reception/'+this.clientCurrent.id+"/"+this.selected.typeCmd+'?page=' + page + "&paginate=" + this.paginateRecep+"&ref="+this.selected.dossier+"&id_empotage="+this.selected.identifiant+"&filtre_four="+this.filtre.fournisseur+"&keysearch="+this.searchRecep+"&column="+this.sortedColumn+"&order="+this.order+"&contenaireSelected="+this.contenaires.data[this.currentIndexContenaire].id+"&contenaireEtat="+this.contenaires.data[this.currentIndexContenaire].etat).then(response => {
                this.reception = response.data;
                this.selected.nbrcmd = this.reception.data.length;
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
        showRapport(index){
            this.pdfFileModal = null;
            this.pdfFile = null;

            // rafraichir all contenaire 
              axios.get('/gerer/empotage/getContenaire/'+this.selected.identifiant)
                .then(response => {
                    this.contenaires = response.data;

                    if(this.contenaires.data.length > 0){

                        this.contenaireSelectionner(index);
                         var labelCmd = this.selected.typeCommande.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
                        this.pdfFileModal = 'dossier-'+this.selected.dossier+'_'+labelCmd+'_numtc-'+this.selected.numtc+'_plomb-'+this.selected.plomb+".pdf";

                    }else{
                        Vue.swal.fire(
                          'Warning!',
                          "Aucun rapport trouvé",
                          'warning'
                        );
                    }
            }).catch(error => {});

           
       },
       detailsCommande(empotage){
            this.isDetail=true;
            this.selected.numtc    = empotage.numContenaire;
            this.selected.typetc   = empotage.typeContenaire;
            this.selected.plomb    = empotage.plomb;
            this.selected.identifiant = empotage.id;
            this.selected.typeCmd    = empotage.typeCommandeID;
            this.selected.dossier = empotage.reference;
            this.selected.nbrcmd = empotage.nbrCmd;
            this.selected.typeCommande = empotage.typeCommande;

            this.getReception();
            
        },
       showContenaire(empo){
            this.isDetail = true;
            this.selected.identifiant = empo.id;
            this.selected.dossier     = empo.reference;
            this.selected.typeCmd     = empo.typeCommandeID;
            this.selected.typeCommande = empo.typeCommande;
            this.selected.entrepot    =  empo.entrepot;
            this.selected.idEntrepot  =  empo.entrepotID;
            // get all contener
              axios.get('/gerer/empotage/getContenaire/'+empo.id)
                .then(response => {
                    this.contenaires = response.data;
                    if(this.contenaires.data.length > 0){
                      this.contenaireSelectionner(0);

                    }
            }).catch(error => {});
       },
       contenaireSelectionner(index){

            this.currentIndexContenaire = index;

            this.selected.etat = this.contenaires.data[index].etat;


            this.selected.plomb = this.contenaires.data[index].plomb;
            this.selected.numtc = this.contenaires.data[index].numContenaire;
            this.selected.typetc = this.contenaires.data[index].typeContenaire;


            this.selected.capacite = this.contenaires.data[index].capaciteContenaire;
            this.capacite = this.contenaires.data[index].capaciteContenaire;

            this.selected.poids      = this.contenaires.data[index].total_poids;
            this.selected.volume     = this.contenaires.data[index].total_volume;
            this.selected.nbrColis   = this.contenaires.data[index].colis_total;

            this.getReception();

       },
       showDocument(empo){
            this.tabDoc = [];
            this.currentEmpotage = empo.id;
            if(Array.isArray(empo.document)){
                this.tabDoc = empo.document;
            }
            
           
       },
       showDeclarationDouane(empo){
            this.tabDeclaration = [];
            this.currentEmpotage = empo.id;
            if(Array.isArray(empo.declDounae)){
                this.tabDeclaration = empo.declDounae;
            }
            
           
       }
       ,
       closePoupDocDounane(){
        this.$refs.closePoupDocDounane.click();
            this.search(this.currentPage);
       }
       ,
        closeModalDoc(){
            this.$refs.closePoupDoc.click();
            this.search(this.currentPage);
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
                            axios.post('/numdocum/cloturer/'+empotage.id+"?id_dossier="+empotage.reference+"&typeCmd="+empotage.typeCommandeID).then(response => {
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
    },
    mounted() {
      this.search();
    }
   
};
</script>
