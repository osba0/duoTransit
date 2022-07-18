<template>
	<div>
		<div class="d-flex justify-content-center">
            <div class="w-50">
                <form @submit.prevent="loadChargement" enctype="multipart/form-data" key=1 class="d-flex justify-content-center">
                    <select class="form-control mr-2" v-model="selectedTypeCmd">
                        <option value="">Type de Commande</option>
                        <option value="1">Médicament</option>
                        <option value="2">Para pharmacie</option>
                    </select>
                    <select class="form-control mr-2" v-model="selectedChargement">
                        <option value="">Choisir</option>
                        <option value="PRECHARGEMENT">Pré chargement</option>
                        <option value="CHARGEMENT">Chargement</option>
                    </select>
                    <button class="btn btn-success">Afficher</button>
                </form>
            </div>
        </div> 
        <div class="py-5 text-center">
            <template v-if="isloading">
                <img src="/images/in-progress.gif">
            </template>
            <template v-else-if="!showData">
                <div class="border d-inline-block p-5 bg-white">
                    Pour afficher les données <br>
                    Veuillez choisir le type commande et le type de chargement 
                </div>
                
            </template>
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
                        <td class="p-2 align-middle">
                             <label class="badge badge-primary mr-1 numCmdLab w-100">{{ dry.rencmd }}</label>
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
                            <!--div class="d-flex justify-content-center">
                               <input type="checkbox" name="checkCmd" :value="dry" :id="'num_'+dry.reidre" v-model="checkedCommandes" class="styled" v-on:change="preChargement(dry)"/>
                               <label :for="'num_'+dry.reidre" class="withStyle"><div class="tick"></div></label>   
                            </div-->
                            <label class="switch">
                                <input class="switch-input" v-model="checkedCommandes" type="checkbox" :value="dry" v-on:change="preChargement(dry)"/>
                                <span class="switch-label" data-on="Oui" data-off="Non"></span> 
                                <span class="switch-handle"></span> 
                            </label>
                        </td>
                    </tr>
                </tbody>
                </table>
                <div>
                    <button class="btn btn-lg btn-danger" :disabled = "checkedCommandes == ''" v-on:click="generatePdf()">Générer le fichier PDF</button>
                </div>
            </template>
            
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
                selectedTypeCmd: '',
                selectedChargement: '',
                checkedCommandes: [],
                commandes:[] 
            }
    	},
        validations: {

        },
        watch: {

        },
        methods : { 
            loadChargement(){
                this.isloading = true;
                 axios.get('/api/chargement/listcommandes/'+this.idClient+'?typeCmd=' + this.selectedTypeCmd+"&typeCharge="+this.selectedChargement).then(response => {
                    
                    this.dries = response.data;
 
                    var thiss= this;
                    setTimeout(function(){
                        thiss.showData = true; 
                        thiss.isloading = false;
                    },2000);
                });
            },
            preChargement(item){
                if(this.checkedCommandes.includes(item)){
                   this.totalPreChargmemnt.poidsEmpote  += item.repoid;
                   this.totalPreChargmemnt.volumeEmpote += item.revolu;

                    if(item.renbcl > 0){
                        this.totalPreChargmemnt.colisEmpote += item.renbcl;
                    }
                    if(item.renbpl > 0){
                        this.totalPreChargmemnt.colisEmpote += item.renbpl;
                    }
                    this.commandes.push(item.rencmd);

                }else{
                    this.commandes = this.commandes.splice(item.rencmd, 1);
                    this.totalPreChargmemnt.poidsEmpote  -= item.repoid;
                    this.totalPreChargmemnt.volumeEmpote -= item.revolu;

                    if(item.renbcl > 0){
                        this.totalPreChargmemnt.colisEmpote -= item.renbcl;
                    }
                    if(item.renbpl > 0){
                        this.totalPreChargmemnt.colisEmpote -= item.renbpl;
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

                axios.post("/api/preChargement", data).then(response => {
                    console.log('Reponse', response.data.code);
                    if(response.data.code==0){
                        //this.$toast.success('Ajouter avec success');
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
            entete.push([{text:['Client: ', {text: 'Duopharm', fontSize: 14}]}, {text: ['Entrepôt: ', {text: 'CMA', fontSize: 14}],  alignment: 'right'}]);
           
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

         /*   pdf.footer = function(page, pages) {
  return {
    margin: [5, 0, 10, 0],
    height: 30,
    columns: [{
      alignment: "left",
      text: 'This is your left footer column',
    }, {
      alignment: "right",
      text: [
        { text: page.toString(), italics: true },
          " of ",
        { text: pages.toString(), italics: true }
      ]
    }]
  }
} */

            pdf.footer(function(currentPage, pageCount) { return  { margin: [20, 0, 20, 0], height: 30, columns: [{alignment: "left",
      text: 'iTransit'}, {text: currentPage.toString() + ' / ' + pageCount, alignment: "right"}]}; });

            pdf.add(header);

            pdf.add(table);
           

            pdf.create().open(); // download() or open()
    
           }
        },
        mounted() {         
        }
    }
</script>
