<template>
	<div>

        <div class="mb-3 mb-4">
            <h2>Saisie Empotage</h2> 
            
            <table class="table table-bordered bg-white"> 
                <tr>
                    <th width="15%" class="text-uppercase text-center badge-primary py-1">N°Referene</th>
                    <th width="15%" class="text-uppercase text-center badge-success py-1">N°Contenaire</th>
                    <th width="15%" class="text-uppercase thead-blue py-1">Type Commande</th>
                    <th class="text-uppercase thead-blue py-1">Infos Contenaire</th>
                   
                    <th class="text-uppercase thead-blue py-1">Etat Contenaire</th>
                </tr>
                <tr>
                    <td class="text-center align-middle h5">
                        {{ empotageCurrent[indexEmpotage].reference }}
                    </td>
                    
                    <td class="align-middle">
                        <select class="form-control m-0 mr-2 h5" v-model="numCont" v-on:change="changeContenaire()">
                          <option :value="nbr" v-for="nbr in nbrConenaire">n°{{nbr}}</option>
                        </select>
                    </td>
                     <td class="align-middle">
                        <select disabled class="form-control m-0 mr-2 h5" v-model="selectedTypeCmd">
                            <option value="1">Médicament</option>
                            <option value="2">Para pharmacie</option>
                        </select>
                    </td>
                    <td class="align-middle h5">
                       <ul class="m-0 list-unstyled">
                           <li><label class="badge badge-info">N° TC</label> : {{ empotageCurrent[indexEmpotage].tc }}</li>
                           <li><label class="badge badge-info">TYPE TC </label> : {{ empotageCurrent[indexEmpotage].typeTc }}</li>
                           <li><label class="badge badge-info">PLOMB </label> : {{ empotageCurrent[indexEmpotage].plomb }}</li>
                       </ul>
                    </td>
                   
                    <td class="pt-1 pb-4">
                        <div class="d-flex m-0 customRadio justify-content-center">
                            <p class="pr-3 m-0">
                                <input type="radio" :value="volumePied40"  v-model="capacite" id="test1" name="radio-group">
                                <label for="test1"><span>{{nb40}}</span>x40 DRY</label>
                            </p>
                            <p class="m-0">
                                <input type="radio" :value="volumePied20" v-model="capacite" id="test2" name="radio-group">
                                <label for="test2"><span>{{nb20}}</span>x20 DRY</label>
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
 
        <div class="py-2 text-center">
            <template v-if="isloading">
                <img src="/images/in-progress.gif">
            </template>
            <!--template v-else-if="!showData">
                <div class="border d-inline-block p-5 bg-white">
                    Pour afficher les données <br>
                    Veuillez choisir le type commande et le type de chargement 
                </div>
                
            </template-->
            <template v-if="showData">

                <table class="table" id="table-1">
                    <thead class="thead-blue">
                         <tr>
                            <th class="p-2 border-right border-white h6">N°CDE</th>
                            <th class="p-2 border-right border-white h6">N°FE</th>
                            <th class="p-2 border-right border-white h6">N°ECV</th>
                            <th class="p-2 border-right border-white h6">Fournisseur</th>
                            <th class="p-2 border-right border-white h6">Emballage</th>
                            <th class="text-right p-2 border-right border-white h6">Poids</th>
                            <th class="text-right p-2 border-right border-white h6">Volume</th>
                            <th class="p-2 border-right border-white h6">Num Fact</th>
                            <th class="text-right p-2 border-right border-white h6">Montant Facture</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Date livraison</th>
                            <th class="text-center p-2 border-right border-white h6">Préchargé? </th>
                            <th class="text-nowrap p-2 border-right border-white h6">Douane</th>
                            <th class="text-nowrap p-2 border-right border-white h6">Empoter?</th>
                        </tr>
                    </thead>
                    <tbody>
                    <template v-if="!dries.data || !dries.data.length">
                        <tr><td colspan="14" class="text-center">Aucune donnée!</td></tr>
                    </template>

                    <tr v-for="dry in dries.data" :key="dry.reidre" :class="!dry.isLoad?'nochoose':''" class="bg-white">
                        <template v-if="dry.isLoad">
                          
                            <template v-if="checkExist(dry)">
                                <td class="p-2 align-middle">
                                    {{ dry.rencmd }}
                                </td>
                                <td class="p-2 align-middle">{{ dry.refere }}</td>
                                <td class="p-2 align-middle">{{ dry.reecvr }}</td>
                                <td class="p-2 align-middle text-uppercase">{{ dry.fournisseurs }}</td>
                                <td class="p-2 align-middle">
                                    <template v-if="dry.renbcl > 0">
                                        <div><label class="badge w-100 badge-secondary mr-1">{{dry.renbcl}} Colis</label></div>
                                    </template>
                                    <template v-if="dry.renbpl > 0">
                                     <label class="badge w-100 badge-info mr-1">{{dry.renbpl}} Pal</label>
                                    </template>
                                
                                </td>
                                <td class="p-2 align-middle text-right">{{ dry.repoid }}</td>
                                <td class="p-2 align-middle text-right">{{ dry.revolu }}</td>
                                
                                <td class="p-2 align-middle text-right">{{ dry.renufa }}</td>
                                <td class="p-2 align-middle text-right">{{ dry.revafa }}</td>
                                <td class="p-2 align-middle"><i class="fa fa-calendar" aria-hidden="true"></i> {{ dry.redali }}</td>
                                <td class="p-2 text-center align-middle">
                                    <span class="badge badge-success" :class="dry.isPreLoad?'':'d-none'">OUI</span>
                                    <span class="badge badge-danger" :class="!dry.isPreLoad?'':'d-none'">NON</span>
                                </td>
                                <td class="p-2 align-middle text-center">
                                   <input type="text" class="text-center" v-model="douane[dry.reidre]" @focus="focusDoune(dry.reidre)" @blur="saveDouane(dry.reidre)" :placeholder="dry.douane"> 
                                <td class="p-2 align-middle text-center"><label class="switch">
                                        <input class="switch-input"  v-model="checkedCommandes" type="checkbox" :value="dry" v-on:change="empoter(dry)" /> 
                                        <span class="switch-label" data-on="Oui" data-off="Non"></span> 
                                        <span class="switch-handle"></span> 
                                    </label></td>
                            </template>
                            
                        </template>
                        <template v-else>
                             <td class="p-2 align-middle">
                                     {{ dry.rencmd }}
                                </td>
                                <td class="p-2 align-middle">{{ dry.refere }}</td>
                                <td class="p-2 align-middle">{{ dry.reecvr }}</td>
                                <td class="p-2 align-middle text-uppercase">{{ dry.fournisseurs }}</td>
                                <td class="p-2 align-middle">
                                    <template v-if="dry.renbcl > 0">
                                        <div><label class="badge w-100 badge-secondary mr-1">{{dry.renbcl}} Colis</label></div>
                                    </template>
                                    <template v-if="dry.renbpl > 0">
                                     <label class="badge w-100 badge-info mr-1">{{dry.renbpl}} Pal</label>
                                    </template>
                                
                                </td>
                                <td class="p-2 align-middle text-right">{{ dry.repoid }}</td>
                                <td class="p-2 align-middle text-right">{{ dry.revolu }}</td>
                                
                                <td class="p-2 align-middle text-right">{{ dry.renufa }}</td>
                                <td class="p-2 align-middle text-right">{{ dry.revafa }}</td>
                                <td class="p-2 align-middle"><i class="fa fa-calendar" aria-hidden="true"></i> {{ dry.redali }}</td>
                               
                                <td class="p-2 text-center align-middle">
                                    <span class="badge badge-success" :class="dry.isPreLoad?'':'d-none'">OUI</span>
                                    <span class="badge badge-danger" :class="!dry.isPreLoad?'':'d-none'">NON</span>
                                </td>
                                 <td class="p-2 align-middle text-center">
                                      <img :class="'loader_'+dry.reidre" style="display:none" src="/images/in-progress.gif"/>
                                    
                                      <input class="text-center" type="text" v-model="douane[dry.reidre]" @focus="focusDoune(dry.reidre)" @blur="saveDouane(dry.reidre)" :placeholder="dry.douane"> 
                                 </td>
                                 <td class="p-2 align-middle text-center"><label class="switch">
                                        <input class="switch-input"  v-model="checkedCommandes" type="checkbox" :value="dry" v-on:change="empoter(dry)" /> 
                                        <span class="switch-label" data-on="Oui" data-off="Non"></span> 
                                        <span class="switch-handle"></span> 
                                    </label></td>
                        </template>
                    </tr>
                </tbody>
                </table>
               
            </template>
            
        </div>
        <div class="mb-3 mb-4">

            <table class="table table-bordered bg-white styleTotaux"> 
                <tr>
                    <th class="thead-blue py-1 text-center">Nb de commande</th>
                    <th class="thead-blue py-1 text-center">Nb Colis empotés</th>
                    <th class="text-center thead-blue py-1">Poids empoté</th>
                    <th class="text-center thead-blue py-1">Volume empoté</th> 
                </tr>
                <tr  class="bg-white">
                    <td class="text-center">
                        <!--template v-if="pre.commandes != null && pre.commandes !=''">
                            {{ pre.commandes.split(',').length }}
                        </template>
                        <template v-else>0</template-->
                       <span class="badge badge-pill badge-warning">{{checkedCommandes.length}}</span>
                    </td>  
                    <td class="text-center">
                        <span class="badge badge-pill badge-warning">
                        {{ totalPreChargmemnt.colisEmpote }}
                        </span>
                     </td>  
                    <td class="text-center">
                        <span class="badge badge-pill badge-warning">
                        {{ totalPreChargmemnt.poidsEmpote }}
                        </span>
                    </td>   
                    <td class="text-center">
                        <span class="badge badge-pill badge-warning">
                        {{ totalPreChargmemnt.volumeEmpote }}
                        </span>
                    </td>      
                        
                </tr>
            </table>
            <div class="d-flex justify-content-center py-3">
                <button class="btn btn-lg btn-danger mx-2" :disabled = "checkedCommandes == ''" v-on:click="generatePdf()"><i class="fa fa-file-pdf"></i> Générer le fichier PDF</button>

                 <button class="btn btn-lg btn-success mx-2" :disabled = "checkedCommandes == ''" v-on:click="cloturer()"><i class="fa fa-check"></i> Cloturer</button>
            </div>
        </div>
	</div>
</template> 
<script>
                
    import { PdfMakeWrapper, Table } from 'pdfmake-wrapper';

    import { ITable } from 'pdfmake-wrapper/lib/interfaces'; 

    import pdfFonts from "pdfmake/build/vfs_fonts";
    
    import { required, minLength, between } from 'vuelidate/lib/validators';
     
    
	export default { 
        props: [ 
          'idClient',
          'volumePied40',
          'volumePied20',
          'detailsEmpotage',
          'idSelected',
          'nameClient',
          'paysClient'
        ],
        data() {   
            return {
                totalPreChargmemnt:{
                    poidsEmpote: 0,
                    volumeEmpote: 0,
                    colisEmpote: 0
                },
                isloading: false,  
                showData: false,
                dries: {},               
                selectedChargement: 'CHARGEMENT',
                checkedCommandes: [],
                commandes:[],
                listeCmd:[],
                volumePercent:0,
                capacite:'',
                nb40: 1,
                nb20: 1,
                // Empotage
                indexEmpotage: 0,
                empotageCurrent: JSON.parse(this.detailsEmpotage),
                nbrConenaire: JSON.parse(this.detailsEmpotage).length,
                numCont: 1,
                selectedTypeCmd: 0,
                douane:[],
                loader:[]
            }
    	},
        validations: {

        },
        watch: {
            capacite: function(val){
                this.loadChargement();
            }
        },
        methods : { 
            saveDouane(id){ 
                $(".loader_"+id).show();
                const data = new FormData();
                data.append('id', id);
                data.append('douane', this.douane[id]);
                axios.post("/api/updateDouane", data).then(response => {
                    $(".loader_"+id).hide(); 
                });
            },
            focusDoune(id){
                
                
            },
            changeContenaire(){
                this.indexEmpotage = this.numCont-1;
                if(JSON.parse(this.detailsEmpotage)[this.indexEmpotage].typeTc==20){
                    this.capacite=this.volumePied20;
                }else{
                    this.capacite=this.volumePied40;
                }

                this.loadChargement();
            },
            loadChargement(){
                 axios.get('/api/chargement/listcommandes/'+this.idClient+'?typeCmd=' + this.selectedTypeCmd+"&typeCharge="+this.selectedChargement).then(response => {
                    
                    // Get empotage
                   
                        this.nb40 = 1;
                        this.nb20 = 1;
                        this.isloading = true;
                        this.checkedCommandes = [];
                        this.commandes = [];
                        var cmdSelected=this.empotageCurrent[this.indexEmpotage].commandes;
                        if(cmdSelected!='null'){
                            
                            this.listeCmd = JSON.parse(cmdSelected).split(','); 
                            for(var i=0; i<response.data.data.length; i++){
                                if(this.listeCmd.includes(response.data.data[i].rencmd)){
                                    this.checkedCommandes.push(response.data.data[i]);
                                }
                            }
                        }


                        var valVoume = this.empotageCurrent[this.indexEmpotage].volumeEmpote;
                        if(valVoume=="" || valVoume==null){
                            valVoume=0;
                        }
                        
                        
                        var percent = ((valVoume/this.capacite)*100).toFixed(0);
                        if(percent > 100){
                             var surplus =  Math.floor(percent/100);
                             this.volumePercent = (percent%100);
                            if(this.capacite==this.volumePied40){
                                this.nb40+=surplus;
                            }else{
                                this.nb20+=surplus;
                            }
                        }else{
                            this.volumePercent = percent;
                        }

                        this.totalPreChargmemnt.poidsEmpote = this.empotageCurrent[this.indexEmpotage].poidEmpote;
                        this.totalPreChargmemnt.volumeEmpote = this.empotageCurrent[this.indexEmpotage].volumeEmpote;
                        this.totalPreChargmemnt.colisEmpote = this.empotageCurrent[this.indexEmpotage].colisEmpote;

                        
                        this.dries = response.data; 
                       
                        this.showData = true; 
                        this.isloading = false;
                        console.log('Capacite', this.capacite);
                        console.log('Percent', this.volumePercent, this.checkedCommandes);
                        console.log('Commande liste:', this.checkedCommandes);
                    
                });
            },
            checkExist(cmd){
                 if(this.checkedCommandes.includes(cmd)){
                    return true;
                 }else{
                    return false;
                 }
            }
            ,
            empoter(item){
                var isChecked=false;
                console.log("REBATASS: ", this.checkedCommandes);
                console.log("JAJAUEFF: ", this.commandes);
                if(this.checkedCommandes.includes(item)){
                   var isChecked=true;
                   this.totalPreChargmemnt.poidsEmpote  += item.repoid;
                   this.totalPreChargmemnt.volumeEmpote += item.revolu;

                    if(item.renbcl > 0){
                        this.totalPreChargmemnt.colisEmpote += item.renbcl;
                    }
                    if(item.renbpl > 0){
                        this.totalPreChargmemnt.colisEmpote += item.renbpl;
                    }
                    this.commandes = []; 
                    for(var i=0; i< this.checkedCommandes.length; i++){
                       var obj = this.checkedCommandes[i];
                       this.commandes.push(obj.rencmd);
                    }

                }else{
                    this.commandes = []; 
                    for(var i=0; i< this.checkedCommandes.length; i++){
                       var obj = this.checkedCommandes[i];
                       this.commandes.push(obj.rencmd);
                    }


                    this.totalPreChargmemnt.poidsEmpote  -= item.repoid;
                    this.totalPreChargmemnt.volumeEmpote -= item.revolu;
                    if(this.totalPreChargmemnt.poidsEmpote < 0){
                        this.totalPreChargmemnt.poidsEmpote = 0;
                    }
                    if(this.totalPreChargmemnt.volumeEmpote < 0){
                        this.totalPreChargmemnt.volumeEmpote = 0;
                    }

                    if(item.renbcl > 0){
                        this.totalPreChargmemnt.colisEmpote -= item.renbcl;
                    }
                    if(item.renbpl > 0){
                        this.totalPreChargmemnt.colisEmpote -= item.renbpl;
                    }
                     if(this.totalPreChargmemnt.colisEmpote < 0){
                        this.totalPreChargmemnt.colisEmpote = 0;
                    }
                }
                const data = new FormData();
                data.append('id', this.empotageCurrent[this.indexEmpotage].id);
                data.append('colisEmpote', this.totalPreChargmemnt.colisEmpote);
                data.append('poidsEmpote', this.totalPreChargmemnt.poidsEmpote);
                data.append('volumeEmpote', this.totalPreChargmemnt.volumeEmpote);
                data.append('idClient', this.idClient);
                data.append('cmds', this.commandes);
                data.append('isChecked', isChecked?1:0);
                data.append('cmdSelected', item.rencmd);
                this.nb40 = 1; 
                this.nb20 = 1;           

                axios.post("/api/empotage", data).then(response => {
                    if(response.data.code==0){
                        
                        var percent = ((this.totalPreChargmemnt.volumeEmpote/this.capacite)*100).toFixed(0);

                        if(percent > 100){
                             var surplus =  Math.floor(percent/100);
                             this.volumePercent = (percent%100);
                            if(this.capacite==this.volumePied40){
                                this.nb40+=surplus;
                            }else{
                                this.nb20+=surplus;
                            }
                        }else{
                            this.volumePercent = percent;
                        }

                        axios.get('/api/refreshEmpotage/'+this.idClient+'?typeCmd=' + this.selectedTypeCmd+'&numero='+this.empotageCurrent[this.indexEmpotage].reference).then(res => {
                                this.empotageCurrent = res.data.data; 
                                this.loadChargement();    
                            });
                    }else{
                        this.$toast.success('Erreur');
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
           generatePdf(){
            PdfMakeWrapper.setFonts(pdfFonts);

            const pdf = new PdfMakeWrapper();
            pdf.defaultStyle({
                fontSize: 10
            });



            var entete=[];
             entete.push([{text: 'Marine +', fontSize: 22, bold: true, alignment: 'left'}, {text: 'Date: '+ this.currentDateTime(), fontSize: 8, alignment: 'right', lineHeight: 1}]); 
            entete.push([{text:''}, {text: ['Entrepôt: ', {text: 'CNM', fontSize: 14}],  alignment: 'right'}]);
           
            entete.push([{text: 'N°Dossier '+this.numDossier, fontSize: 15, alignment: 'center', lineHeight: 2, colSpan: 2}]);
             entete.push([{text: "Destination: "+this.paysClient, fontSize: 13, alignment: 'left', colSpan: 2}]);
            entete.push([{text: "Rapport d'empotage pour le compte de: Marine + "+this.nameClient, fontSize: 13, alignment: 'left', colSpan: 2}]);
           

            var header = new Table(entete).widths('*').layout('noBorders').margin([0, 0, 0, 7]).end;

            var infos_contenaire = [];
            infos_contenaire.push([
                {text:['N° TC: ', {text: this.empotageCurrent[this.indexEmpotage].tc, fontSize:12, bold: true}]},
                {text:['TYPE TC: ', {text: this.empotageCurrent[this.indexEmpotage].typeTc+' DRY', fontSize:12, bold: true}]},{text:['PLOMB: ', {text: this.empotageCurrent[this.indexEmpotage].plomb, fontSize:12, bold: true}]}]);

            var contenaire = new Table(infos_contenaire).widths(['28%', '28%', '28%', '28%']).margin([0, 0, 0, 7]).end;

            var totaux = [[{text: 'Nb de commande', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Nb Colis empoté', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Poids empoté', fontSize: 10, bold: true, alignment: 'center'}, {text: 'Volume empoté', fontSize: 10, bold: true, alignment: 'center'} ]];
            totaux.push([{text: this.checkedCommandes.length, fontSize: 10, bold: true, alignment: 'center'}, {text: this.totalPreChargmemnt.colisEmpote, fontSize: 10, bold: true, alignment: 'center'}, {text: this.totalPreChargmemnt.poidsEmpote, fontSize: 10, bold: true, alignment: 'center'}, {text: this.totalPreChargmemnt.volumeEmpote, fontSize: 10, bold: true, alignment: 'center'} ]);

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

            const headerTab = ['Référence', 'Emballage', 'Designation', 'Poids', 'Volume', 'Factures', 'Douanes'];
            
            data.push(headerTab); 

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

                

                
                const item = [obj.refere, emballage ,obj.fournisseurs, obj.repoid, obj.revolu, obj.renufa, obj.douane];
                data.push(item);
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
            text: 'iTransit'}, {text: currentPage.toString() + ' / ' + pageCount, alignment: "right"}]}; });

            pdf.add(header);

            pdf.add(contenaire);

            pdf.add(table);

            pdf.add(tabtotaux);
           

            pdf.create().open(); // download() or open()
    
           },
           cloturer(){
            Vue.swal.fire({
                  title: 'Confirmez la cloture',
                  text: "Empotage "+JSON.parse(this.detailsEmpotage)[this.indexEmpotage].id,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, cloturer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.post('/api/chargement/cloturer/'+JSON.parse(this.detailsEmpotage)[this.indexEmpotage].id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Cloturé!',
                              'Dossier cloturé.',
                              'success'
                            );


                        });
                  
                  }
                })
           }
           ,
           // init val
           init(){
                  
                this.selectedTypeCmd=JSON.parse(this.detailsEmpotage)[this.indexEmpotage].typeCmd;
                this.numDossier=JSON.parse(this.detailsEmpotage)[this.indexEmpotage].reference;
                if(JSON.parse(this.detailsEmpotage)[this.indexEmpotage].typeTc==20){
                    this.capacite=this.volumePied20;
                }else{
                    this.capacite=this.volumePied40;
                }
                
                 var tabJson = JSON.parse(this.detailsEmpotage);   
                // get default contenaire
                for(var i=0; i<tabJson.length; i++){
                    if(this.idSelected == tabJson[i].id){
                        this.indexEmpotage = i; 
                        this.numCont = i+1;
                    }
                }

           }
        },
        mounted() { 
            this.init();
            
            this.loadChargement(); 
             
        }
    }
</script>
