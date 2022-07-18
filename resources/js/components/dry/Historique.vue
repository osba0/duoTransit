<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-bordered">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">Dossier</th>
                        <th class="p-2 border-right border-white h6">Type Commande</th>
                        <th class="p-2 border-right border-white h6">N° TC</th>
                        <th class="p-2 border-right border-white h6">Type TC</th>
                        <th class="p-2 border-right border-white h6">N°Plomb</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>  
             <tbody>
              <template v-if="!empotages.data || !empotages.data.length">    
                     <tr class="bg-white">
                       <td colspan="6" class="text-center">Aucune donnée!</td>
                    </tr>
                    </template>
                      <tr v-for="empotage in empotages.data">
                        <td class="p-2 align-middle">
                            {{ empotage.reference }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ empotage.typeCommande }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ empotage.numContenaire }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ empotage.typeContenaire }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ empotage.plomb }}
                        </td>
                         <td class="p-2 text-right">
                            <a title="Editer" :href="'/empotage/'+idClient+'/'+empotage.reference+'/'+empotage.typeCommandeID+'/'+empotage.id" class="btn m-1 border m-1">
                                    <i class="fa fa-pencil" aria-hidden="true"></i> Saisir
                            </a>
                                 <a title="Editer" href="#" class="btn m-1 border m-1" v-on:click="editEmpotage(empotage)" data-toggle="modal" data-target="#newEmpotage">
                                    <i class="fa fa-pencil" aria-hidden="true"></i> Modifier
                                </a>
                            <a title="Supprimer" href="#" class="btn m-1 border-danger m-1" v-on:click="deleteEmpotage(empotage)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i> Supprimer
                                </a>
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="empotages"
                @pagination-change-page="getEmpotage"
            ></pagination>
        </div>
         <!-- Modal Fournisseurs-->
        <div class="modal fade" id="newEmpotage" tabindex="-1" role="dialog" aria-labelledby="myModalEmpotage"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modifier</template>
                        <template v-else>Nouveau Rapport Empotation</template>
                        
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
                                        <input class="w-65 form-control" autocomplete="off" id="numDossier" aria-haspopup="true" aria-expanded="false" v-model="keyword" @focus="isFocus"  @blur="handleBlur"
                                        :class="{ 'border-danger': submitted && !$v.keyword.required }" />
                                         <ul class="dropdown-menu filterUl p-2" :class="{'d-block': showDropDown}" v-if="dossiers.length > 0">
                                            <li v-for="dossier in dossiers" :key="dossier.id" >
                                                <a class="p-2" v-text="dossier.numDossier" v-on:click="say(dossier.numDossier)"></a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="plomb"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                       Type Commande
                                       </label>
                                        <select class="form-control ml-2 h5 w-65" v-model="empotageForm.typeCmd">
                                            <option :value="type.id" v-for="type in typeCommande">{{type.typcmd}}</option>
                                        </select>
                                    </div>
                                    
                                    
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="tc"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        N° TC
                                       </label>
                                        <input class="w-65 form-control" id="tc" v-model="empotageForm.tc" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="typeTc"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                       Type TC
                                       </label>
                                       
                                        <select class="w-65 form-control" v-model="empotageForm.typetc">
                                            <option value="20">20 DRY</option>
                                            <option value="40">40 DRY</option>
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
                             </div>
                             <div class="modal-footer d-flex justify-content-center"> 

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
    </div>
</template>
<script>
    import { required, minLength, between } from 'vuelidate/lib/validators'
    export default {
        props: [ 
          'idClient',
          'idUser',
          'typeCmd'
        ],
        data() { 
            return {
                empotageForm :{
                    reference: '',
                    tc: '',
                    typeTc: '',
                    plomb: '',
                    typeCmd: ''

                },
                submitted: false,
                dataEmpotage: {},
                paginate: 5,
                modeModify: false,
                keyword: null,
                dossiers: [],
                showDropDown: false,
                typeCommande: JSON.parse(this.typeCmd),
                empotages: {}
            }

        },
        validations: {
             keyword: { required },
        },
        watch: {
            keyword(after, before) {
                this.getResults();
            },
           paginate: function(){
               this.getEmpotage();
           }
        },
        methods : { 
            say: function (message) { 
                this.keyword = message;
                this.showDropDown=false;
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
            getResults() {
                axios.get('/api/livesearch', { params: { keyword: this.keyword } })
                    .then(res => this.dossiers = res.data)
                    .catch(error => {});
            },
            getEmpotage(page = 1){
                
                axios.get('/api/historique/list?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.empotages = response.data; 
                });
            },
            saveEmpotage(){
                this.submitted = true;

                // stop here if form is invalid
                this.$v.keyword.$touch();
                if (this.$v.keyword.$invalid) {
                    return;
                }
                
                const data = new FormData();
                data.append('reference', this.keyword);
                data.append('typeCmd', this.empotageForm.typeCmd);
                data.append('tc', this.empotageForm.tc);
                data.append('typetc', this.empotageForm.typetc);
                data.append('plomb', this.empotageForm.plomb);
                data.append('idClient', this.idClient);
                data.append('user_id', this.idUser);

                let action = "createEmpotage";

                if(this.modeModify){
                    data.append('id', this.empotageForm.id);
    
                    action = "modifyEmpotage";
                }

                axios.post("/api/empotage/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        //this.getFournisseur();
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
                   
                });
            },
             flushData(){
                this.keyword = "";
                this.empotageForm.tc = "";
                this.empotageForm.typetc= "";
                this.empotageForm.plomb= "";
            },
             closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {
         this.getEmpotage();
        }
    }
</script>