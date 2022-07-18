<template>
	<div>

        <div class="mb-3 mb-4">
            <h2>Préchargement</h2> 
            
            <table class="table table-bordered bg-white"> 
                <tr>
                    <th class="text-uppercase text-center badge-primary py-1">Dossier</th>
                    <th class="text-uppercase text-center thead-blue py-1">Prévision de chargement </th>
                    <th class="text-uppercase thead-blue py-1">Type Commande</th>
                    <th class="text-uppercase thead-blue py-1">Contenaire</th>
                </tr>
                <tr>
                    <td class="text-center align-middle h5">{{ numDossier }}</td>
                    <td class="align-middle text-center h5">
                        <i aria-hidden="true" class="fa fa-calendar"></i> {{ dateDebut }} ~
                        <i aria-hidden="true" class="fa fa-calendar"></i> {{ dateCloture }}
                    </td>
                    <td class="align-middle">
                        <select class="form-control mr-2 h5" v-model="selectedTypeCmd" v-on:change="loadChargement()">
                            <option value="1">Médicament</option>
                            <option value="2">Para pharmacie</option>
                        </select>
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
                            <th class="text-center p-2 border-right border-white h6">Précharger? </th>
                        </tr>
                    </thead>
                     <tbody>
                    <template v-if="!dries.data || !dries.data.length">
                        <tr><td colspan="14" class="text-center">Aucune donnée!</td></tr>
                    </template>

                      <tr v-for="dry in dries.data" :key="dry.reidre" class="bg-white">
                        <template v-if="dry.isPreLoad">
                          
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
                                <td class="p-2 text-center">
                                    <label class="switch">
                                        <input class="switch-input" v-model="checkedCommandes" type="checkbox" :value="dry" v-on:change="preChargement(dry)" /> 
                                        <span class="switch-label" data-on="Oui" data-off="Non"></span> 
                                        <span class="switch-handle"></span> 
                                    </label>
                                </td>
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
                                <td class="p-2 text-center">
                                    <label class="switch">
                                        <input class="switch-input" v-model="checkedCommandes" type="checkbox" :value="dry" v-on:change="preChargement(dry)" /> 
                                        <span class="switch-label" data-on="Oui" data-off="Non"></span> 
                                        <span class="switch-handle"></span> 
                                    </label>
                                </td>
                        </template>
                    </tr>
                </tbody>
                </table>
               
            </template>
            
        </div>
        <div class="mb-3 mb-4">

            <table class="table table-bordered bg-white"> 
                <tr>
                    <th class="thead-blue py-1 text-center">Nb de commande</th>
                    <th class="thead-blue py-1 text-center">Nb Colis empotés</th>
                    <th class="text-center thead-blue py-1">Poids empoté</th>
                    <th class="text-center thead-blue py-1">Volume empoté</th> 
                </tr>
                <tr v-for="pre in dataPre.data" class="bg-white">
                    <td class="text-center">
                        <template v-if="pre.commandes != null && pre.commandes !=''">
                            {{ pre.commandes.split(',').length }}
                        </template>
                        <template v-else>0</template>
                       
                    </td>  
                    <td class="text-center">{{ pre.colisEmpote }}</td>  
                    <td class="text-center">{{ pre.poidEmpote }}</td>   
                    <td class="text-center">{{ pre.volumeEmpote }}</td>      
                        
                </tr>
            </table>
            <div class="text-center">
                <button class="btn btn-lg btn-danger" :disabled = "checkedCommandes == ''" v-on:click="generatePdf()">Générer le fichier PDF</button>
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
          'numDossier',
          'idUser',
          'dateDebut',
          'dateCloture',
          'idTypcmd',
          'volumePied40',
          'volumePied20'
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
                dataPre:{},
                selectedTypeCmd: this.idTypcmd,
                selectedChargement: 'PRECHARGEMENT',
                checkedCommandes: [],
                commandes:[],
                listeCmd:[],
                volumePercent:0,
                capacite:'',
                nb40: 1,
                nb20: 1
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
            loadChargement(){

                
                 axios.get('/api/chargement/listcommandes/'+this.idClient+'?typeCmd=' + this.selectedTypeCmd+"&typeCharge="+this.selectedChargement).then(response => {
                    
                    // Get prechargement
                    axios.get('/api/getdataprecharger/'+this.idClient+'/' + this.selectedTypeCmd+"/"+this.numDossier).then(res => { 
                        console.log(res); 
                        this.nb40 = 1;
                        this.nb20 = 1;
                        this.isloading = true;
                        this.checkedCommandes = [];
                        this.commandes = [];
                        this.dataPre = res.data; 

                        if(this.dataPre.data[0].commandes){
                            this.listeCmd = this.dataPre.data[0].commandes.split(',');
                            for(var i=0; i<response.data.data.length; i++){
                                console.log("VALUE=", this.listeCmd);
                                if(this.listeCmd.includes(response.data.data[i].rencmd)){
                                    this.checkedCommandes.push(response.data.data[i]);
                                }
                            }
                        }
                        
                        var percent = ((this.dataPre.data[0].volumeEmpote/this.capacite)*100).toFixed(0);
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

                        this.totalPreChargmemnt.poidsEmpote = this.dataPre.data[0].poidEmpote;
                        this.totalPreChargmemnt.volumeEmpote = this.dataPre.data[0].volumeEmpote;
                        this.totalPreChargmemnt.colisEmpote = this.dataPre.data[0].colisEmpote;

                        
                        this.dries = response.data; 
                       
                        this.showData = true; 
                        this.isloading = false;
                        console.log('Capacite', this.capacite);
                        console.log('Percent', this.volumePercent, this.checkedCommandes);
                    });
                    
                });
            },
            checkExist(cmd){
                if(this.checkedCommandes.includes(cmd)){
                    return true;
                }else{
                    return false;
                }
            },
            preChargement(item){
                var isChecked=false;
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
                     console.log("Rem:");
                    this.commandes = []; //this.commandes.splice(0, 0);
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
                data.append('numDossier', this.numDossier);
                data.append('colisEmpote', this.totalPreChargmemnt.colisEmpote);
                data.append('poidsEmpote', this.totalPreChargmemnt.poidsEmpote);
                data.append('volumeEmpote', this.totalPreChargmemnt.volumeEmpote);
                data.append('idClient', this.idClient);
                data.append('tyeCommande', this.selectedTypeCmd);
                data.append('users_id', this.idUser);
                data.append('cmds', this.commandes);
                data.append('isChecked', isChecked?1:0);
                data.append('cmdSelected', item.rencmd);
                this.nb40 = 1; 
                this.nb20 = 1;
               
                axios.post("/api/preChargement", data).then(response => {
                    console.log('Reponse', response.data.code);
                    if(response.data.code==0){
                        // Get prechargement
                        axios.get('/api/getdataprecharger/'+this.idClient+'/' + this.selectedTypeCmd+"/"+this.numDossier).then(res => { 
                            this.dataPre = res.data; 
                            if(this.dataPre.data[0].commandes){
                                this.listeCmd = this.dataPre.data[0].commandes.split(',');
                            }
                            var percent = ((this.dataPre.data[0].volumeEmpote/this.capacite)*100).toFixed(0);

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
                            // A verifier
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
            entete.push([{text:['Client: ', {text: 'Duopharm', fontSize: 14}]}, {text: ['Entrepôt: ', {text: 'CNM', fontSize: 14}],  alignment: 'right'}]);
           
            entete.push([{text: 'N°Dossier '+this.numDossier, fontSize: 15, alignment: 'center', lineHeight: 2, colSpan: 2}]);
            entete.push([{text: 'Prévision de chargement pour le '+this.dateDebut+' ET '+this.dateCloture, fontSize: 11, alignment: 'center', colSpan: 2}]);
           

            var header = new Table(entete).widths('*').layout('noBorders').margin([0, 0, 0, 7]).end;

            const data = [];

            const headerTab = ['N°FE', 'N°ECV', 'N°CDE', 'Emballage', 'Fournisseurs', 'Poids', 'Volume', 'Factures'];
            
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

                

                
                const item = [obj.refere,obj.reecvr, obj.rencmd, emballage ,obj.fournisseurs, obj.repoid, obj.revolu, obj.renufa];
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

            pdf.add(table);
           

            pdf.create().open(); // download() or open()
    
           }
        },
        mounted() {    
            //this.loadChargement();   
            this.capacite=this.volumePied40;  

        }
    }
</script>
