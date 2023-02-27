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
                                        <label class="text-left w-100 mb-0">N° Dossier</label>
                                        <input type="text" class="form-control"  v-model="filtre.dossier">
                                    </div>
                                     <div class="mr-3 text-left" style="width: 180px">
                                        <label class="text-left w-100 mb-0">N° Commande</label>
                                        <input type="text" class="form-control"  v-model="filtre.commande">
                                    </div>
                                      <div class="mr-3 text-left" style="width: 180px">
                                        <label class="text-left w-100 mb-0">N° Facture</label>
                                        <input type="text" class="form-control"  v-model="filtre.numfact">
                                    </div>
                                    <template v-if="userRole=='client' || userRole=='consultation'">
                                        <div class="mr-3 text-left" style="width: 180px">
                                            <label class="text-left w-100 mb-0">N° DOCIM</label>
                                            <input type="text" class="form-control"  v-model="filtre.docim">
                                        </div>
                                    </template>
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
                                        <template v-if="userRole=='client' || userRole=='consultation'">
                                        <th class="p-2 border-right border-white h6 bg-primary">N° DOCIM</th>
                                        </template>
                                        <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumnSearch(columnsSearch[0])">
                                            
                                            N° Dossier Transitaire <i class="fa fa-sort" aria-hidden="true" ></i></th>
                                        <th class="p-2 border-right border-white h6" title="Date départ du bâteau">Date Départ</th>
                                        <th class="p-2 border-right border-white h6"  title="Date arrivée du bâteau">Date Arrivée</th>
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
                                            <template v-if="userRole=='client' || userRole=='consultation'">
                                                <td class="position-relative"><div class="position-absolute typeCmd" v-bind:style="[true ? {'background': res.typeCmd_color} : {'background': '#ccc'}]"></div> 
                                                  
                                                    <button v-if="res.numDocim == '' || res.numDocim == null" class="position-relative badge border-0 badge-info" v-on:click="addNumDocim(res)">Ajouter <span class="position-absolute text-danger" style="top:-5px; right:-5px">●</span></button>
                                                     <span v-else class="text-primary font-weight-bold cursor-pointer"  v-on:click="addNumDocim(res, res.numDocim)">
                                                        {{res.numDocim}} 
                                                        <i v-if="gestionDocim==1" class="fa fa-pencil"></i>
                                                    </span>  
                                                   
                                                </td>
                                            </template>
                                            <td class="position-relative"><div v-if="userRole!='client' && userRole!='consultation'" class="position-absolute typeCmd" v-bind:style="[true ? {'background': res.typeCmd_color} : {'background': '#ccc'}]"></div> {{ res.reference }}</td>
                                            <td class="p-2 align-middle">
                                               {{ res.dateDepart }}
                                            </td>
                                            <td class="p-2 align-middle">
                                               {{ res.dateArrivee }}
                                            </td>
                                            
                                            <td>
                                                 <template v-if="(userRole=='client' || userRole=='consultation') && res.is_close==0">
                                                     <span v-if="res.etat==1" class="badge badge-success">Validé</span>
                                                 </template>
                                                <span v-if="res.is_close==1 || (res.etat==1 && userRole=='admin')" class="badge badge-primary">Cloturé</span>
                                               
                                            </td>
                                            <td>{{ res.date }}</td>
                                            <td>{{ res.user }}</td>
                                             <td class="p-2 align-middle"> 
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a href="#" title="Autres documents" class="btn p-0 m-1 ml-2  position-relative"  @click="showAutreDocument(res)" data-toggle="modal" data-target="#openAutreDocument">
                                                        <i class="fa fa-file-pdf-o mt-2" aria-hidden="true" style="font-size: 22px"></i>
                                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white" :class="[getCountDoc(res.autre_document) == 0? 'bg-secondary':'']">{{ getCountDoc(res.autre_document) > 9 ? '+9' : getCountDoc(res.autre_document) }}</span>
                                                     
                                                    </a>
                                                    <a href="#" title="Liste contenaire" class="btn p-0 m-1 ml-2 position-relative"  @click="showContenaire(res)">
                                                        <img src="/images/contenaire.png" alt="Contenaire" height="30">
                                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{res.totalContenaire}}</span>
                                                    </a>
                                                    <a v-if="res.etat==1" href="#" title="Complément de document" class="btn p-0 m-1 position-relative ml-2"  @click="showDocument(res)" data-toggle="modal" data-target="#openDocument">
                                                        <img src="/images/document_compl.png" alt="Documents" height="30">
                                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{ getCountDoc(res.document) > 9 ? '+9' : getCountDoc(res.document) }}</span>
                                                     
                                                    </a>

                                                    <template v-if="userRole=='client' || userRole=='consultation'">
                                                        
                                                         <a v-if="res.etat==1" href="#" title="Déclaration Douane" class="btn p-0 m-1 ml-2 position-relative"  @click="showDeclarationDouane(res)" data-toggle="modal" data-target="#openDeclarationDouane">
                                                            <img src="/images/douane.png" alt="Documents" height="30">
                                                            <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white" :class="[getCountDeclDouane(res.declDounae) == 0? 'bg-danger':'']">{{ getCountDeclDouane(res.declDounae) > 9 ? '+9' : getCountDeclDouane(res.declDounae) }}</span>
                                                         
                                                        </a>
                                                        <a title="Cloturer" v-if="gestionDocim==1" class="btn m-1  border border-success bg-success text-white btn-circle border btn-circle-sm m-1 ml-3 bg-white" v-on:click="cloturer(res)">
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
                    <button class="btn btn-primary mb-3 mr-4" @click="reinit()">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour 
                    </button>
                     <div class="mb-3 w-100 overflow-auto">
                        <div class="d-flex flex-row align-items-center justify-content-end outlineBtn">
                            <template v-for="contenaire, index in contenaires.data">
                                <div class="position-relative ml-3">
                                    <button v-on:click="contenaireSelectionner(index)" class="btn btn-default d-flex flex-column mb-0 p-0">
                                        <img src="/images/contenaire.png" alt="Contenaire" height="35"  :class="index==currentIndexContenaire ? 'activeContent':'imageGrey'">
                                         <span class="badge w-100 mt-1" :class="index==currentIndex ? 'badge-primary':'badge-secondary opacity-7'">{{ contenaire.numContenaire}}</span>
                                    </button>                                  
                                    <button v-if="contenaire.etat == 0" style="top: 0px; right: 0px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="supprimerContenaire(contenaire)"><i class="fa fa-times"></i></button>
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
                                                <th class="align-middle">N°Dossier: <b>{{ selected.dossier }}</b></th>
                                                <th class="align-middle">Nombre de commande: <b>{{ selected.nbrcmd }}</b></th> 
                                                <th class="align-middle">N°TC: <b>{{ selected.numtc }}</b></th>
                                                <th class="align-middle">Type TC: <b>{{ selected.typetc }}</b></th>
                                                <th class="align-middle">Plomb: <b>{{ selected.plomb }}</b></th>
                                                <th class="align-middle">
                                                    <a @click="showPhoto( selected.photos )" data-toggle="modal" data-target="#openPhotoChargment" class="cursor-pointer">
                                                        <p class="position-relative d-inline-block pr-4">
                                                             <template v-if="selected.photos.length > 0">
                                                                <span v-for="(photo, index) in selected.photos" class="border mr-1 mb-2 position-absolute shadow-sm rounded-circle" :style="{top:0.2*index-15+ 'px', left: 2*index+ 'px'}">             
                                                                        <img :src="'/assets/photos_chargement/'+photo" width="50" height="50" class="rounded">  
                                                                </span>
                                                              
                                                            </template>
                                                             <template v-else> 
                                                               Aucune Photo
                                                            </template>
                                                        </p>
                                                    </a>  
                                                </th>
                                                <th class="text-center"><button v-on:click="showRapport(currentIndexContenaire)" class="btn p-0 m-0 mb-0" data-toggle="modal" data-target="#openModalEmpo" title="Rapport d'empotage"><span class="h3 mb-0"><i class="fa fa-file-pdf-o text-danger"></i></span></button></th>
                                            </tr>
                                      
                                        </tbody>

                                    </table>
                                </div>
                             
                            </td>
                        </tr>
                    </table>
                </div>
                <hr>
                <div><typeproduit></typeproduit></div>
                <div class="d-flex w-100 justify-content-between align-content-center mb-2">
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
                            placeholder="Rechercher par n°cmd, n°fe, n°ecv / bbe,n°fact, utilisateur, fournisseur..."
                        />
                    </div>
                   
                </div> 
                <table class="table">
                    <thead class="thead-blue hasborder">
                         <tr>
                            <th class="p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[2])"><div class='d-flex align-items-center'><span>N°ECV / BBE</span><i class="fa fa-sort" aria-hidden="true" ></i></div></th> 
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[5])"><div class='d-flex align-items-center'><span>Poids</span> <i class="fa fa-sort" aria-hidden="true" ></i></div></th>
                            <th class="text-right p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[6])"><div class='d-flex align-items-center'><span>Volume</span> <i class="fa fa-sort" aria-hidden="true" ></i></div></th>
                            <th class="text-nowrap p-2 border-right border-white h6">Factures</th>
                            <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">Mnt Facture</th>
                            <th class="p-2 border-right border-white h6 cursor-pointer white-space-nowrap"  v-on:click="sortByColumn(columns[1])">Dépalettisation</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Douanes</th>
                            <th class="text-nowrap p-2 border-right border-white h6 cursor-pointer" v-on:click="sortByColumn(columns[4])"><div class='d-flex align-items-center'><span>Date livraison</span> <i class="fa fa-sort" aria-hidden="true" ></i></div></th>
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
                         <td class="p-2 align-middle position-relative"><div class="position-absolute typeCmd" v-bind:style="[true ? {'background': dry.typeCmd_color} : {'background': '#ccc'}]"></div>  <label class="numCmd badge w-100" :class="getTypeProduit(dry.typeproduit)">
                                {{ dry.reecvr }}
                            </label></td>
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
                             {{ format_nbr(dry.revafa)}}
                        </td>
                        <td class="p-2 align-middle">
                             {{dry.depalettisation}}
                        </td>
                        <td class="p-2 align-middle">
                             {{dry.douane}}
                        </td>
                        <td class="p-2 align-middle">{{ dry.redali }}</td>
                        <td class="p-2 align-middle text-nowrap">{{ dry.user_created}}</td>
                        <td class="p-2 align-middle">
                            {{dry.prechargeur}}
                        </td>
                        <td class="p-2 text-right">
                            <div class='d-flex align-items-center'>
                                <a title="Voir les détails" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white position-relative" v-on:click="showModal(dry)" data-toggle="modal" data-target="#detailReception">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                     <i :class="{ noFile: dry.hasIncident === null || dry.hasIncident === '' || dry.hasIncident == 0}" class="fa fa-circle position-absolute notif text-danger" aria-hidden="true"></i>
                                </a>
                                <!--button :disabled="dry.refasc === null || dry.refasc === ''" title="Voir la facture" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-white" v-on:click="showFacture(dry.refasc)" data-toggle="modal" data-target="#openFacture">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i>
                                </button-->
                                 <button :disabled="dry.refasc === null || dry.refasc === ''" title="Voir la facture" class="btn btn-circle btnAction border btn-circle-sm mx-1 position-relative bg-white" v-on:click="showFacture(dry)" data-toggle="modal" data-target="#openFacture">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <!--i :class="{ noFile: dry.refasc === null || dry.refasc === ''}" class="fa fa-circle position-absolute notif" aria-hidden="true"></i-->
                                <span :class="{ 'bg-light2': getCountFacture(dry.refasc) == 0, 'bg-green2' : getCountFacture(dry.refasc) > 0}" class="position-absolute d-flex align-items-center justify-content-center rounded-circle iconenbre text-white">{{ getCountFacture(dry.refasc) > 9 ? '+9' : getCountFacture(dry.refasc) }}</span>
                                </button>
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
            </template>
        </template>
        <!-- Modal Facture-->
        <modalFacture></modalFacture>  
        <div class="modal fade fullscreenModal" id="openModalEmpo" tabindex="-1" role="dialog" aria-labelledby="myModalEmpo"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="pdfFile != null">Facture</template>
                            <template v-if="pdfFileModal != null">Rapport d'empotage</template>
                            <template v-if="pdfFileModal != null && pdfFile != null"> Document </template>
                            </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupPdfEmpo">
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
                    <button type="button" v-on:click="closeModalPdfEmpo()" class="btn btn-warning">Fermer</button>
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


        <!-- Modal Autre Document-->
        <div class="modal fade fullscreenModal" id="openAutreDocument" tabindex="-1" role="dialog" aria-labelledby="myModalFacture"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left align-items-center">
                        <h4 class="modal-title font-weight-bold">Autre documents</h4>
                        <template v-if="userRole=='admin'">
                            <div class="flex-1 text-center">
                                <label class="mb-0">Ajout un fichier</label>
                                <input type="file" id="fileAutre" name="fileAutre" multiple ref="fileAutreDoc" v-on:change="handleFileUploadOtherDoc()"/>
                                <button class="btn btn-success" v-on:click="saveAutreDocs()">Enregister</button>
                            </div>
                        </template>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupAutreDoc" v-on:click="search(currentPage)">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        
                    </div>
                    <div class="modal-body mx-3 overflow-hidden">
                        <div class="d-flex justify-content-between h-100">
                            <div>
                                <div class="d-flex flex-column">
                                    <template v-for="doc, index in tabAutreDoc">
                                        <div class="position-relative">
                                            <button v-on:click="getAutreDoc(index)" class="btn rounded-circle  btn-default mb-2" :class="index==currentIndexAutre ? 'bg-light':''">
                                                <span class="h1"><i class="fa fa-file"></i></span>
                                            </button>
                                            <template v-if="userRole=='admin'">
                                                <button style="top: -3px; right: -3px;" class="badge badge-danger position-absolute rounded-circle  border-0" v-on:click="removeAutreDoc(doc)"><i class="fa fa-times"></i></button>
                                            </template>
                                        </div>
                                    </template>
                                   
                                </div>
                            </div>
                             <template v-if="tabAutreDoc.length > 0">
                                <embed :src="'/assets/documents/'+tabAutreDoc[currentIndexAutre]" frameborder="0" width="95%" height="450px">
                              </template>
                             <template v-else>
                                <div class="w-100 text-center">Aucun document </div> 
                              </template>
                            
                         </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalAutreDoc()" class="btn btn-warning">Fermer</button>
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
                      
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupPhoto">
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
import modalFacture from '../../components/modal/facture.vue';
import typeproduit from '../../components/modal/typeproduit.vue';

export default { 
    props: [
            'listFournisseurs',
            'typeCmd',
            'clientCurrent',
            'listEntrepots',
            'userRole',
            'gestionDocim',
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
                typeCommande: '',
                photos:''
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
            currentIndexDouane: 0,
            messageError: '',
            currentIndexPhoto: 0,
            attachmentsPhoto: [],
            tabPhoto: [],
            attachmentsAutreDoc: [],
            currentIndexAutre: 0,
            tabAutreDoc: []
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
        }, 
       messageError: function(value){
             this.messageError = value;
        }
        
    },
    methods: {
        addNumDocim(empo, value=""){
            var numDocim = "";
            Vue.swal.fire({
              title: 'Entrer le n° DOCIM',
              //input: 'text',
              html:
                '<div class="d-flex justify-content-center align-items-center"><input id="swal-input1" style="width:150px;" class="swal2-input m-0"  autocomplete="off" autofocus/>' +'<span class="px-3">/</span>'+
                '<input id="swal-input2" style="width:150px" value="'+new Date().getFullYear()+'" class="swal2-input m-0"></div>'+
                '<div id="messageError" class="text-danger text-center w-100 pt-2">'+this.messageError+'</div>',
             // inputValue: value==  '/' +new Date().getFullYear() ? value : value + new Date().getFullYear(),
              inputAttributes: {
                autocapitalize: 'off'
              },
              showCancelButton: true,
              confirmButtonText: 'Valider',
              showLoaderOnConfirm: true,
              focusConfirm: false,
              inputValidator: (value) => {
                    if (!value) {
                      return 'Entrer une valeur!'
                    }
                  },
              preConfirm: (val) => {

                var numDoc = document.getElementById('swal-input1').value+'/'+document.getElementById('swal-input2').value;
    
                if(document.getElementById('swal-input2').value=='' || document.getElementById('swal-input1').value==''){
                    this.messageError = "Entrer un n°DOCIM valide"; 
                    Vue.swal.close();
                    this.addNumDocim(empo, value);

                    return false
                }
               
                axios.post('/addNumDocim/'+empo.id+"?docim="+numDoc).then(response => {
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
        getAutreDoc(index){
            this.currentIndexAutre = index;
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
        handleFileUploadOtherDoc(){
            this.attachmentsAutreDoc = [];
            for(var i=0; i<this.$refs.fileAutreDoc.files.length;i++){
                this.attachmentsAutreDoc.push(this.$refs.fileAutreDoc.files[i])
            }
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

            data.append('Document[]', this.tabDeclaration); 

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
        saveAutreDocs(){
            const data = new FormData();

            if(!this.attachmentsAutreDoc.length > 0){
                Vue.swal.fire(
                      '',
                      'Ajouter un document avant de valider!',
                      'warning'
                    )   
                return false;
            }
            
            data.append('file[]', this.attachmentsAutreDoc);

            for (let i = 0; i < this.attachmentsAutreDoc.length; i++) {
                data.append('files' + i, this.attachmentsAutreDoc[i]);
            }


            data.append('TotalFiles', this.attachmentsAutreDoc.length);

            data.append('Document[]', this.tabAutreDoc); 

            axios.post("/saveOtherDocs/"+this.currentEmpotage, data,  {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        } 
                }).then(response => {
                   
                    this.$refs.fileAutreDoc.value = null;
                    if(response.data.code==0){
                         this.tabAutreDoc = response.data.file;
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
        format_nbr(mnt){
            if(mnt != '' && mnt != null){
                return mnt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            }
            return mnt;
        },
        removeAutreDoc(nameDoc){
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

                    axios.post("/removeAutreDocs/"+this.currentEmpotage, data,  {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                } 
                        }).then(response => {
                           
                            if(response.data.code==0){
                                 this.tabAutreDoc = response.data.file;
                                 this.currentIndexAutre = 0;
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
            axios.post('/search/histoEmpotage/'+this.clientCurrent.id+'/'+this.idEntite+"?column="+this.sortedColumnSearch+"&order="+this.orderSearch+"&isDocim="+this.gestionDocim,{
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
                if(this.typeCmd[i].id === parseInt(id)){ 
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
                 // get Ŝpecification

                    EventBus.$emit('SET_PRODUIT_SPECIFIK', { 
                        prd: this.reception.data
                    });
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
                        this.pdfFileModal = 'dossier-'+this.formatage(this.selected.dossier)+'_'+labelCmd+'_numtc-'+this.formatage(this.selected.numtc)+'_plomb-'+this.formatage(this.selected.plomb)+".pdf";

                    }else{
                        Vue.swal.fire(
                          'Warning!',
                          "Aucun rapport trouvé",
                          'warning'
                        );
                    }
            }).catch(error => {});

           
       },
       formatage(str){
        return str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
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

            this.selected.photos = this.contenaires.data[index].photos_chargement;

            this.getReception();

       },
        showAutreDocument(empo){
            this.tabAutreDoc = [];
            this.currentEmpotage = empo.id;
            if(Array.isArray(empo.autre_document)){
                this.tabAutreDoc = empo.autre_document;
            }
            
           
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
        closeModalAutreDoc(){
            this.$refs.closePoupAutreDoc.click();
            this.search(this.currentPage);
        },
        closeModalPdf(){
            this.$refs.closePoupPdf.click();
        },
        closeModalPdfEmpo(){
            this.$refs.closePoupPdfEmpo.click();
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
                      text:   (empotage.numDocim==null || empotage.numDocim=='') ? '' : 'N° DOCIM: '+empotage.numDocim,
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
            getPhoto(index){
                this.currentIndexPhoto = index;
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
            },
             showFacture(fact){
                 EventBus.$emit('VIEW_FACT', { 
                    listeFacture: fact.refasc,
                    idReception: fact.reidre,
                    can_modify: false
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
            }
    },
    mounted() {
      this.search();
    }
   
};
</script>
