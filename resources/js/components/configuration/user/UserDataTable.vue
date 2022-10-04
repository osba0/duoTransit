<template>
    <div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">ID</th>
                        <th class="p-2 border-right border-white h6">Nom</th>
                        <th class="p-2 border-right border-white h6">Prenom</th>
                        <th class="p-2 border-right border-white h6">Email</th>
                        <th class="p-2 border-right border-white h6">Login</th>
                        <th class="p-2 border-right border-white h6">Profil</th>
                        <th class="p-2 border-right border-white h6">Société(s) autorisée(s)</th>
                        <th class="p-2 border-right border-white h6">Etat</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody class="bgStripe">
                      <tr v-for="user in users.data" class="bg-white"> <!--:set="roles = convertJson(user.roles)"-->
                        <td class="p-2 align-middle">
                            {{ user.id }}
                        </td>
                         <td class="p-2 align-middle text-uppercase">
                            {{ user.firstname }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ user.lastname }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ user.email }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ user.login }}
                        </td>
                        <td class="p-2 align-middle">
                          
                          <label class="badge bg-success mr-2  text-capitalize" v-for="role in user.roles" >{{role}}</label>
                          
                        </td>
                        <td class="p-2 align-middle">
                           <label class="badge bg-info mr-2" v-for="idCli in user.client_supervisor" >
                            <template v-for="client in clients">
                                <template v-if="client.id==idCli">
                                {{client.clnmcl}}
                                </template>
                            </template>
                           
                            </label>
                        </td>
                         <td class="p-2 align-middle"> 
                            <template v-if="user.login == 'osba' || user.login == 'root'">
                               <label class="switch">
                                    <input class="switch-input inputCmd" :checked="user.etat==1" disabled type="checkbox" :value="user.id" v-on:change="etat($event,user)" /> 
                                    <span class="switch-label disabled" data-on="Activé" data-off="Désactivé"></span> 
                                    <span class="switch-handle"></span> 
                                </label>
                            </template> 
                             <template v-else>
                                <label class="switch">
                                    <input class="switch-input inputCmd" :checked="user.etat==1" type="checkbox" :value="user.id" v-on:change="etat($event,user)" /> 
                                    <span class="switch-label" data-on="Activé" data-off="Désactivé"></span> 
                                    <span class="switch-handle"></span> 
                                </label>
                            </template> 
                        </td>
                         <td class="p-2 text-right">
                             <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editUser(user)" data-toggle="modal" data-target="#newUser">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            <template v-if="user.login == 'root'">
                                <a title=""  class="btn m-1 border-danger btn-circle border btn-circle-sm m-1">
                                    <i class="fa fa-circle-o text-danger" aria-hidden="true"></i>
                                </a>
                            </template>
                            <template v-else>
                                <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteUser(user)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                            </template>
                            
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="users"
                @pagination-change-page="getUser"
            ></pagination>
        </div>
         <!-- Modal users-->
        <div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="myModalFournisseur"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification User</template>
                        <template v-else>Nouveau User</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveUser" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <div v-if="this.submitted && $v.userForm.email.$error" class="text-center">
                                      <span v-if="userForm.email && !$v.userForm.email.email" class="badge bg-danger mr-2">Enter valid email address</span>
                                      <span v-if="userForm.email && $v.userForm.email.email && !$v.userForm.email.maxLength" class="badge bg-danger mr-2">Email is allowed only 30 characters</span>
                                    </div> 
                                    <div v-if="this.submitted && $v.userForm.password.$error" class="text-center">
                                      <span v-if="userForm.password && !$v.userForm.password.minLength" class="badge bg-danger mr-2">Password must be minimum 6 characters</span>
                                    </div> 
                                    <div v-if="this.submitted && $v.userForm.confirmPassword.$error" class="text-center">
                                      <span v-if="userForm.confirmPassword && !$v.userForm.confirmPassword.sameAsPassword" class="badge bg-danger mr-2">Password and Confirm Password should match</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Nom
                                       </label>
                                        <input class="w-65 form-control" id="nom" v-model="userForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.userForm.nom.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="prenom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Prenom
                                       </label>
                                        <input class="w-65 form-control" id="prenom" v-model="userForm.prenom" :class="{ 'border-danger': submitted && !$v.userForm.prenom.required }"/>
                                    </div>
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="email" class="d-block m-0 text-right w-35 pr-2" >Email</label>
                                            <input class="w-65 form-control"  v-model="userForm.email" type="text" id="email" :class="{ 'border-danger': submitted && !$v.userForm.email.required }"/>
                                        </div>
                                 </div>
                                    
                                    <div class="w-100 d-flex align-items-center my-2"></div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="prenom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Login
                                       </label>
                                        <input :disabled = "modeModify" class="w-65 form-control" id="username" v-model="userForm.username"  :class="{ 'border-danger': submitted && !$v.userForm.username.required }"/>
                                    </div>
                                 </div>
                                 
                             </div>

                              <div class="row" :class="modeModify?'d-none':''">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="password" class="d-block m-0 text-right w-35 pr-2" >Mot de passe</label>
                                            <input class="w-65 form-control"  v-model="userForm.password" type="password" id="password" :class="{ 'border-danger': submitted && !$v.userForm.password.required }"/>
                                        </div>
                                 </div>
                                    
                                    <div class="w-100 d-flex align-items-center my-2"></div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="confirmPassword"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Confirmer mot de passe
                                       </label>
                                        <input class="w-65 form-control" id="confirmPassword" type="password" v-model="userForm.confirmPassword" :class="{ 'border-danger': submitted && !$v.userForm.confirmPassword.required }"/>
                                    </div>
                                 </div>
                                 
                             </div>
                              <div class="row">
                                   <div class="col-6 my-2 d-flex flex-column">
                                           <div class="w-100 d-flex my-2 align-items-center">
                                                 <label for="profil" class="d-block m-0 text-right w-35 pr-2" >Profil</label>
                                                  
                                                <select v-model="userForm.profil" class="form-control form-control-sm w-65" :class="{ 'border-danger': submitted && !$v.userForm.profil.required }"  :disabled="userForm.profil == 'Root'" @change="onProfil()">
                                                    <option value="">Choisir</option>
                                                   <option v-for="role in roles" :value="role">{{role}}</option>
                                                </select>

                                            </div>
                                            <div class="w-100 d-flex align-items-center my-2"></div>
                                        
                                     </div>
                                      <div class="col-6 my-2 d-flex flex-column">
                                    <div class="w-100 d-flex" v-if="isAdmin==0">
                                        <label class="typo__label pt-3 d-block m-0 text-right w-35  pr-2 nowrap">Société(s) Autorisée(s)</label>
                                        <div class="w-65 p-2">
                                            <multiselect v-model="value" :options="clients" :disabled = "noClientRole" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="clnmcl" track-by="id" :preselect-first="false">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} client(s) selectionné(s)</span> 
                                                </template> 
                                            </multiselect>
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
    import { required, email, minLength, sameAs, between } from 'vuelidate/lib/validators';
    import Multiselect from 'vue-multiselect';
    export default {
        props: [
          'listRoles',
          'listClients',
          'isAdmin'
        ],
         components: {
            Multiselect   
          },
        data() { 
            return {
                userForm :{
                    id: '',
                    nom: '',
                    prenom:'',
                    email: '',
                    profil: '',
                    username: '',
                    password: '',
                    confirmPassword: '',
                    idClientAuth: []

                },
                hasImage: false,
                submitted: false,
                noClientRole: true,
                users: {},
                paginate: 10,
                modeModify: false,
                clients: JSON.parse(this.listClients),
                roles: JSON.parse(this.listRoles),
                value: [],
                options:  JSON.parse(this.listClients)
            }

        },
        validations: {
            userForm : {
                nom: { required },
                prenom: { required },
                username: { required },
                email: { required, email },
                profil: { required },
                password: { required, minLength: minLength(6) },
                confirmPassword: { required, sameAsPassword: sameAs('password') }

            },
        },
        watch: {
           paginate: function(){

                this.getUser();
           }
        },
         methods : { 
            etat(event, four){

                var ischecked=0;
                if (event.target.checked) {
                    ischecked=1;
                }

                const data = new FormData();
                data.append('id', four.id);
                data.append('etat', ischecked);

                axios.post("/configuration/statusCompte", data).then(response => {
                     let res = response.data.result;
               
                    Vue.swal.fire(
                          'succés!',
                           ischecked==0? 'Compte désactivé avec succés!': 'Compte activé avec succés!',
                          'success'
                        ).then((result) => {
                           this.getUser();
                        });
               
                });
            },
            onProfil(){
                if(this.userForm.profil=='Client' || this.userForm.profil=='Consultation'){
                    this.noClientRole = false;
                }else{
                    this.noClientRole = true;

                }
            },
            removeImage(){
                this.hasImage = false;
                this.userForm.logo = "";
            },
            handleFileUpload(){

                this.userForm.logo = this.$refs.file.files[0];  
            },
            saveUser(){

                this.submitted = true;

                // stop here if form is invalid
                this.$v.userForm.$touch();
                if (this.$v.userForm.$invalid) {
                    return;
                }
              
               const data = new FormData();
                data.append('nom', this.userForm.nom);
                data.append('prenom', this.userForm.prenom);
                data.append('email', this.userForm.email);
                data.append('login', this.userForm.username);
                data.append('password', this.userForm.password);
                data.append('profil', this.userForm.profil.toLowerCase());
                this.userForm.idClientAuth = []; 

                // get clients
                for(var i=0; i<this.value.length; i++){
                    var item = this.value[i];
                    this.userForm.idClientAuth.push(item.id);  
                }

                 if((this.userForm.profil=='Client' || this.userForm.profil=='Consultation') && this.userForm.idClientAuth.length == 0 ){
                    Vue.swal.fire(
                          'Warning!',
                          'Choisir une société autorisée!',
                          'warning'
                        )
                   return false;
                }

                data.append('clientsAuth',  JSON.stringify(this.userForm.idClientAuth));

                let action = "createUser";

                if(this.modeModify){
                    data.append('id', this.userForm.id);
                    action = "modifyUser";
                }

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getUser();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Utilisateur crée avec succés!',
                          'success'
                        )
                        

                    }else{
                         Vue.swal.fire(
                          'error!',
                          response.data.message,
                          'error'
                        )
                    }
                    this.submitted = false;
                    this.noClientRole = true;
                    this.value = [];
                   
                });
            },
            flushData(){
                this.userForm.nom = "";
                this.userForm.prenom = "";
                this.userForm.email= "";
                this.userForm.profil= "";
                this.userForm.password="";
                this.userForm.confirmPassword="";
            },
            getUser(page = 1){
                axios.get('/configuration/getUser?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.users = response.data;
                });
            },
            deleteUser(user){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: user.firstname+' '+user.lastname,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteUser/'+user.id).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Utilisateur supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getUser();


                        });
                  
                  }
                })
            },
            editUser(user){
                this.modeModify      = true;
                this.submitted       = false;
                this.userForm.id     = user.id;
                this.userForm.nom    = user.firstname;
                this.userForm.prenom = user.lastname;
                this.userForm.email  = user.email;
                this.userForm.password  = '******';
                this.userForm.confirmPassword  = '******';
                this.userForm.profil = user.roles[0].substr(0,1).toUpperCase()+user.roles[0].substr(1); 
                if(this.userForm.profil=="Client" || this.userForm.profil=="Consultation"){
                    this.noClientRole = false;
                    var selected = [];
                    for(var i=0; i< user.client_supervisor.length; i++){
                        selected.push(this.options.find(option => option.id === user.client_supervisor[i]));
                    }
                    this.value = selected;
                }else{
                    this.noClientRole = true;
                    this.value = [];
                }
                this.userForm.username  = user.login;
                

              
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            }
        },
        mounted() {console.log("ded dd", this.listRoles, "ded dd");
         this.getUser();
        }
    }
</script>