 <template>
    <div>
        <div class="row">
            <div class="col cursor-pointer" :class="connexion == 0 ? 'disabled-div' : ''">
                <div class="mb-4 bg-white rounded-lg" :class="typeAction == 'connexion' ? '' : 'p-2'">
                    <div class="rounded-lg p-3 bg-light-blue"
                         role="button"
                         :class="typeAction == 'connexion' ? 'main-type-action-press-filter' : ''"
                         @click="filterActions('connexion')">
                        <div class="h1 text-primary font-weight-bold">{{ connexion }}</div>
                        <span class="text-primary d-block text-uppercase font-weight-bold h6 m-0">Connexion</span>
                    </div>
                </div>
            </div>
            <div class="col cursor-pointer" :class="affichage == 0 ? 'disabled-div' : ''">
                <div class="mb-4 bg-white rounded-lg" :class="typeAction == 'affichage' ? '' : 'p-2'">
                    <div class="rounded-lg p-3 bg-light-blue"
                         role="button"
                         :class="typeAction == 'affichage' ? 'main-type-action-press-filter' : ''"
                         @click="filterActions('affichage')">
                        <div class="h1 text-primary font-weight-bold">{{ affichage }}</div>
                        <span class="text-primary d-block text-uppercase font-weight-bold h6 m-0">Affichage</span>
                    </div>
                </div>
            </div>
            <div class="col cursor-pointer" :class="insertion == 0 ? 'disabled-div' : ''">
                <div class="mb-4 bg-white rounded-lg" :class="typeAction == 'insertion' ? '' : 'p-2'">
                    <div class="rounded-lg p-3 bg-light-blue"
                         role="button"
                         :class="typeAction == 'insertion' ? 'main-type-action-press-filter' : ''"
                         @click="filterActions('insertion')">
                        <div class="h1 text-primary font-weight-bold">{{ insertion }}</div>
                        <span class="text-primary d-block text-uppercase font-weight-bold h6 m-0">Création</span>
                    </div>
                </div>
            </div>
            <div class="col cursor-pointer" :class="modification == 0 ? 'disabled-div' : ''">
                <div class="mb-4 bg-white rounded-lg" :class="typeAction == 'modification' ? '' : 'p-2'">
                    <div class="rounded-lg p-3 bg-light-blue"
                         role="button"
                         :class="typeAction == 'modification' ? 'main-type-action-press-filter' : ''"
                         @click="filterActions('modification')">
                        <div class="h1 text-primary font-weight-bold">{{ modification }}</div>
                        <span class="text-primary d-block text-uppercase font-weight-bold h6 m-0">Modification</span>
                    </div>
                </div>
            </div>
             <div class="col cursor-pointer" :class="suppression == 0 ? 'disabled-div' : ''">
                <div class="mb-4 bg-white rounded-lg" :class="typeAction == 'suppression' ? '' : 'p-2'">
                    <div class="rounded-lg p-3 bg-light-blue"
                         role="button"
                         :class="typeAction == 'suppression' ? 'main-type-action-press-filter' : ''"
                         @click="filterActions('suppression')">
                        <div class="h1 text-primary font-weight-bold">{{ suppression }}</div>
                        <span class="text-primary d-block text-uppercase font-weight-bold h6 m-0">Suppréssion</span>
                    </div>
                </div>
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
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                    </div>
                </div>
        
            </div>
            <div class="d-flex align-items-end">
                <!--div>
                    <div class="d-flex align-items-center">
                        <label class="text-nowrap mr-2 mb-0"
                            >Type action</label> 
                        <select
                            v-model="selectedTypeCmd"
                            class="form-control form-control-sm">
                            <option value="">Afficher tout</option>
                            <option :value="type.id" v-for="type in typeActivity">{{type}}</option>
                            
                        </select>
                    </div>
                </div-->
        
            </div>
            <div class="pr-0 col-md-4">
                <input
                    v-model.lazy="search"
                    type="search"
                    class="form-control"
                    placeholder="Rechercher par n°cmd, n°fe, n°ecv,n°fact, utilisateur, fournisseur..."
                />
            </div>
        </div>
        <div class="overflow-hidden position-relative">
            <table class="table">
                <thead class="thead-blue" :class="[isLoading ? '' : 'hasborder']">
                     <tr>
                        <th class="p-2 border-right border-white h6" width="3%">ID</th>
                        <th class="p-2 border-right border-white h6" width="10%">Type Action</th>
                        <th class="p-2 border-right border-white h6" width="20%">Description</th>
                        <th class="p-2 border-right border-white h6" width="*">Propiétés</th>
                        <!--th class="p-2 border-right border-white h6" width="30%">Type</th-->
                        <th class="text-nowrap p-2 border-right border-white h6" width="15%">Utilisateur</th>
                        <th class="text-nowrap p-2 border-right border-white h6" width="15%">Date</th>
                    </tr>
                </thead>
                <tbody class="bgStripe preChargeTable" :class="[isLoading ? 'loader-line' : '']"> 
                    <template v-if="!activities.data || !activities.data.length">
                        <tr><td colspan="14" class="bg-white text-center">Aucun résultat!</td></tr>
                    </template>
                    <template v-else>
                        <tr v-for="activity in activities.data" :key="activity.id" class="bg-white position-relative">
                            <td class="align-middle">
                                {{ activity.id }}
                            </td>
                            <td class="p-2 align-middle text-uppercase">
                                <template v-for="type, key, index in typeActivity">
                                    <template v-if="key==activity.logName">
                                       <span :class="'badge badge-'+colorType[index]">{{ type }} </span>
                                    </template>
                                </template>
                               
                            </td>
                            <td class="p-2 align-middle">
                                {{ activity.description }}
                            </td>
                            <td class="p-2 align-middle">
                               {{ activity.propriete }}
                            </td>
                              <!--td class="p-2 align-middle">
                               {{ activity.causertype }}
                            </td-->
                            <td class="p-2 align-middle">
                               {{ activity.fullname }}
                            </td>
                             <td class="p-2 align-middle">
                               {{ activity.date }}
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div class="d-flex mt-4 justify-content-center paginationDossier">
                <pagination
                    :data="activities"
                    :limit=5
                    @pagination-change-page="getActivity"
                ></pagination>
            
            </div>
            
         
        </div>
    </div>
</template>

<script>

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import VueTimepicker from 'vue2-timepicker';
import 'vue2-timepicker/dist/VueTimepicker.css'; 
import { PdfMakeWrapper, Table } from 'pdfmake-wrapper';

export default { 
    props: [
            'clientCurrent',
            'typeActivity'
    ],
    data() {
        return {
            paginate: 5,
            activities: {},
            colorType: ['primary', 'success', 'warning', 'danger', 'dark'],
            isLoading: true,
            connexion: '',
            affichage: '',
            insertion: '',
            modification: '',
            suppression: '',
            typeAction: 0,
            search: '',
            typeSelected: ''

        };
    },
    watch: {
        paginate: function(){
            this.getActivity();
        },
        typeSelected: function(value) {
            this.getActivity();
        },
        search: function(value) {
            this.getActivity();
        }
    },
    methods: {
        getActivity(page=1){
          
            axios.get('/activity/getInfos/'+this.clientCurrent.slug+"?page="+page+"&paginate="+this.paginate+"&type="+this.typeSelected).then(response => {
                this.activities = response.data;
                 var that = this;
                setTimeout(function(){
                  that.isLoading=false;
                },500);
            });

        },
        getCount(type) {
            axios.get(`/activity/count/${type}`+'?client_id='+this.clientCurrent.id)
                .then(response => {
                    if (typeof response.data.count !== "undefined") {
                        this[type] = response.data.count;
                    }
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        filterActions(type){
            if (this.typeAction == type) {
                this.typeAction = 0;
                type='';
            } else {
                this.typeAction = type;
            }
            this.typeSelected = type;
        }
    },
    mounted() {
        this.getActivity();

        // Get the action count on init page
        this.getCount('connexion');
        this.getCount('modification');
        this.getCount('suppression');
        this.getCount('insertion');
        this.getCount('affichage');

        // Different scope inside setInterval
        let that = this;
        setInterval(() => {
            that.getCount('connexion');
            that.getCount('modification');
            that.getCount('suppression');
            that.getCount('insertion');
            that.getCount('affichage');
        }, 120000);
    }

};
</script>
