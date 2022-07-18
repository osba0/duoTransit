<template>
    <div>
        <div class="d-flex justify-content-between align-content-center mb-2">
            <div class="d-flex">
                <div>
                    <div class="d-flex align-items-center">
                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                            >Nbre de ligne par Page</label
                        > 
                        <select
                            v-model="paginate"
                            class="form-control form-control-sm"
                        >
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                    </div>
                </div>
        
            </div>
            <div class="pr-0 col-md-4">
                <input
                    v-model="search"  
                    type="search"
                    class="form-control"
                    placeholder="Rechercher..."
                />
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-striped">
                <thead class="thead-blue">
                     <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Etat</th>
                        <th class="text-nowrap">Date cloture</th>
                        <th class="text-nowrap">Date creation</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                      <tr
                        v-for="dry in dries.data"
                        :key="dry.id">
        
                        <td>{{ dry.id }}</td>
                        <td>{{ dry.nom }}</td>
                        <td>{{ dry.description }}</td>
                        <td class="align-middle">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" :checked="dry.etat" class="custom-control-input" :id="'dry' + dry.id">
                              <label class="custom-control-label" :for="'dry' + dry.id"></label>
                            </div>
                            
                        </td>
                        <td class="align-middle align-middle text-nowrap">
                            <span v-if = "dry.datecloture=' '" class="badge badge-info">En cours</span> 
                        </td>
                        <td class="align-middle text-nowrap">{{ dry.created_at }}</td>
                        <td class="text-right">
                            <div class="d-flex justify-content-end">
                               <a title="Afficher"  class="btn btn-circle border btn-circle-sm m-1">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a title="Réception" href="/reception" class="btn btn-info btn-circle m-1">
                                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                </a>
                                <a title="Chargement" href="/chargement" class="btn btn-success btn-circle btn-circle-lg m-1">
                                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="dries"
                @pagination-change-page="getDries"
            ></pagination>
        </div>
        <div class="modal fade" id="newDry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <form @submit.prevent="createDry">
                <div class="modal-content">
                  <div class="modal-header text-left">
                    <h4 class="modal-title w-100 font-weight-bold">Nouveau dossier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoup">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body mx-3">
                    <div class="md-form mb-3">
                        <label data-error="wrong" data-success="right" for="formname">Nom</label>
                        <input type="text" id="formname" v-model="name" v-bind:class="{'form-control':true, 'is-invalid' : valid }"> 
                        <div class="invalid-feedback">Le nom est obligatoire!</div>
                    </div>
                    <div class="md-form mb-4">
                      <label data-error="wrong" data-success="right" for="form2">Description</label>
                      <div><textarea class="form-control" v-model="description"></textarea></div>
                    </div>

                  </div>
                  <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-info">Créer</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            name: "",
            description: "",
            isModalVisible: false,
            dries: {},
            paginate: 10,
            search: "",
            valid : false
        };
    },

    watch: {
       paginate: function(){
            this.getDries();
       }
    },

    methods: {
       
        getDries(page = 1) {
            axios.get('/api/dries/1?page=' + page + "&paginate=" + this.paginate).then(response => {
                console.log(response);
                this.dries = response.data;
            });
        },

        validate(){
           if(this.name == ''){
              this.valid = false; 
           }
        },

        createDry() {

            axios.post('/api/createDry', {
                'name' : this.name,
                'description' : this.description
            }).then(response => {
                console.log('Rep', response.data.code);
                if(response.data.code==0){
                    this.$refs.closePoup.click();
                    this.$toast.success("Dossier Crée avec succés");
                    this.getDries();

                }else{

                }
               
            });
        }
    },

    mounted() {
        this.getDries();
    }
};
</script>
