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
                        <th class="p-2 border-right border-white h6">Transitaire</th>
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
                          
                          <label class="badge bg-success mr-2  text-capitalize" v-for="ent in user.entite" >{{getEntiteName(ent)}}</label>
                          
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
                             <a title="Editer le profil" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editUser(user)" data-toggle="modal" data-target="#newUser">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                 <a title="Modifier le mot de passe" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editPwd(user)" data-toggle="modal" data-target="#editAccess">
                                    <i class="fa fa-key" aria-hidden="true"></i>
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
        <!--Modifier Mot de passe-->
        <div class="modal fade" id="editAccess" tabindex="-1" role="dialog" aria-labelledby="myModalChange"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Changement de mot de passe</h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalPwd()" aria-label="Close" ref="closePoupClosePwd">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  
                    <div class="modal-body mx-3">
                         <form @submit.prevent="savePwd" autocomplete="off" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-12 d-flex justify-content-start align-items-center">
                                   Utilisateur: <span class="badge badge-primary mx-2">{{ userAccess.userCompte }}</span><span>{{ userAccess.userActual }}</span>
                                </div>
                           </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <div v-if="this.submittedPwd && $v.userAccess.password.$error" class="text-center">
                                      <span v-if="userAccess.password && !$v.userAccess.password.minLength" class="badge bg-danger mr-2">Le mot de passe doit comporter au moins 6 caractères</span>
                                    </div> 
                                    <div v-if="this.submittedPwd && $v.userAccess.confirmPasswordNew.$error" class="text-center">
                                      <span v-if="userAccess.confirmPasswordNew && !$v.userAccess.confirmPasswordNew.sameAsPassword" class="badge bg-danger mr-2">Le mot de passe et la confirmation du mot de passe doivent correspondre</span>
                                    </div>
                                </div>
                           </div>
                            <div class="row">
                                <div class="col-12 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                    <div class="md-form w-100">
                                    <label for="passwordNew" class="d-block m-0 w-100 pr-2" >Nouveau mot de passe</label>
                                    <input class="w-100 form-control"   autocomplete="new-password" v-model="userAccess.password" type="password" id="passwordNew" :class="{ 'border-danger': submittedPwd && !$v.userAccess.password.required }"/>
                                </div>
                                 </div>
                                 </div>
                                 <div class="col-12 my-2">
                                    <div class="w-100 my-2">
                                         <label for="confirmPasswordNew"  class="d-block m-0 w-100 pr-2" style='white-space: nowrap;'>
                                        Confirmer le mot de passe
                                       </label>
                                        <input class="w-100 form-control" id="confirmPasswordNew" type="password" v-model="userAccess.confirmPasswordNew" :class="{ 'border-danger': submittedPwd && !$v.userAccess.confirmPasswordNew.required }"/>
                                    </div>
                                 </div>
                                 
                             </div>
                             
                            <button type="submit" class="btn btn-success">Modifier</button>
                            <button type="button" v-on:click="closeModalPwd()" class="btn btn-warning">Annuler</button>
                         </form>
                    </div>
                    
             </div>
            
          </div>
        </div>
         <!-- Modal users-->
        <div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="myModalFournisseur"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Utilisateur</template>
                        <template v-else>Nouveau Utilisateur</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveUser" autocomplete="off" enctype="multipart/form-data" key=1 >
                            <div class="d-flex">
                                <div class="w-50">
                                    <div class="row">
                                        <div class="col-12 d-flex flex-column justify-content-start align-items-center">
                                            <div class="w-100 d-flex my-2 align-items-center">
                                             <label for="profil" class="d-block m-0 text-right w-35 pr-2" >Profil</label>
                                              
                                            <select v-model="userForm.profil" class="form-control form-control-sm w-65" :class="{ 'border-danger': submitted && !$v.userForm.profil.required }"  :disabled="userForm.profil == 'Root'" @change="onProfil()">
                                                <option value="">Choisir</option>
                                               <option v-for="role in roles" :value="role">{{role}}</option>
                                            </select>

                                            </div>
                                        </div>
                                        <div class="col-12 d-flex flex-wrap justify-content-end align-items-center">
                                            <div class="w-100 d-flex align-items-center my-2" v-if="noClientRole">
                                                <label  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                                    Transitaire
                                                </label>
                                                <select v-model="userForm.entite" :disabled="checkIsAdmin()" class="form-control form-control-sm w-65">
                                                    <option value="">Choisir</option>
                                                    <option v-for="entite in list_entites" :value="entite.id">{{ entite.nom }}</option>
                                                </select>
                                            </div>

                                            <div v-for="entite in list_entites" class="d-block mr-3" v-else>
                                                <input :id="'entite_'+entite.id" :value="entite.id" name="entiteCl" type="checkbox" v-model="userForm.entiteClients">
                                                <label :for="'entite_'+entite.id"  class="mb-0 cursor-pointer">{{ entite.nom }}</label>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-12 my-2 d-flex flex-column justify-content-start align-items-center">
                                            <div class="w-100 d-flex align-items-center my-2">
                                               <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                                Nom
                                               </label>
                                                <input class="w-65 form-control" id="nom" v-model="userForm.nom" 
                                                :class="{ 'border-danger': submitted && !$v.userForm.nom.required }" />
                                            </div>
                                            
                                         </div>
                                          <div class="col-12 my-2 d-flex flex-column justify-content-start align-items-center">
                                            <div class="w-100 d-flex align-items-center">
                                                 <label for="prenom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                                Prenom
                                               </label>
                                                <input class="w-65 form-control" id="prenom" v-model="userForm.prenom" :class="{ 'border-danger': submitted && !$v.userForm.prenom.required }"/>
                                            </div>
                                         </div>
                                        
                                     </div>
                                </div>
                                <div class="w-50">
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
                                        <div class="col-12 my-2 d-flex flex-column align-items-center">
                                             <div class="w-100 d-flex align-items-center">
                                                        <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                                        <label for="cc" class="d-block m-0 text-right w-35 pr-2" >Email</label>
                                                        <input class="w-65 form-control"  autocomplete="off" v-model="userForm.email" type="text" id="cc" :class="{ 'border-danger': submitted && !$v.userForm.email.required }"/>
                                                    </div>
                                             </div>
                                         </div>
                                         <div class="col-12 my-2 d-flex flex-column justify-content-start align-items-center">
                                            
                                            
                                            <div class="w-100 d-flex align-items-center">
                                                 <label for="identifiant"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                                Login
                                               </label>
                                                <input :disabled = "modeModify" class="w-65 form-control" id="identifiant" v-model="userForm.username"  :class="{ 'border-danger': submitted && !$v.userForm.username.required }"/>
                                            </div>
                                         </div>
                                         
                                     </div>
                                     <div class="row" :class="modeModify?'d-none':''">
                                        <div class="col-12 d-flex flex-column align-items-center">
                                         <div class="w-100 d-flex align-items-center my-2">
                                                    <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                                    <label for="access" class="d-block m-0 text-right w-35 pr-2" >Mot de passe</label>
                                                    <input class="w-65 form-control" autocomplete="new-password" v-model="userForm.password" type="password" id="access" :class="{ 'border-danger': submitted && !$v.userForm.password.required }"/>
                                                </div>
                                         </div>
                                            
                                            <div class="w-100 d-flex align-items-center my-2"></div>
                                         </div>
                                         <div class="col-12  d-flex flex-column justify-content-start align-items-center">
                                            
                                            
                                            <div class="w-100 d-flex align-items-center">
                                                 <label for="confirmPassword" autocomplete="new-password" class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;line-height: 18px;'>
                                                Confirmer<br/> mot de passe
                                               </label>
                                                <input class="w-65 form-control" id="confirmPassword" type="password" v-model="userForm.confirmPassword" :class="{ 'border-danger': submitted && !$v.userForm.confirmPassword.required }"/>
                                            </div>
                                         </div>
                                         
                                     </div>
                                </div>
                            </div>
                           
                            

                              
                              <div class="row">
                                    
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
          'listEntites',
          'isAdmin',
          'slugClient'
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
                    idClientAuth: [],
                    entite: '',
                    entiteClients: []

                },
                userAccess: {
                    id:'',
                    userActual: '',
                    userCompte: '',
                    confirmPassword: '',
                    password: ''
                },
                hasImage: false,
                submitted: false,
                noClientRole: true,
                users: {},
                paginate: 10,
                modeModify: false,
                clients: JSON.parse(this.listClients),
                list_entites: JSON.parse(this.listEntites),
                roles: JSON.parse(this.listRoles),
                value: [],
                options:  JSON.parse(this.listClients),
                submittedPwd: false
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
            userAccess : {
                password: { required, minLength: minLength(6) },
                confirmPasswordNew: { required, sameAsPassword: sameAs('password') }

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



                 if((this.userForm.profil=='Client' || this.userForm.profil=='Consultation') && this.userForm.entiteClients.length == 0  ){
                   
                    Vue.swal.fire(
                          'Warning!',
                          'Choisir un transitaire!',
                          'warning'
                        )
                   return false;
                }

                 if((this.userForm.profil=='Client' || this.userForm.profil=='Consultation') && this.userForm.idClientAuth.length == 0 ){
                    Vue.swal.fire(
                          'Warning!',
                          'Choisir une société autorisée!',
                          'warning'
                        )
                   return false;
                }

                
                data.append('entite',  this.userForm.entite);
                data.append('entiteClients',  JSON.stringify(this.userForm.entiteClients));
                data.append('clientsAuth',  JSON.stringify(this.userForm.idClientAuth));
                data.append('slugClient', this.slugClient);  


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
                axios.get('/configuration/getUser?slug='+this.slugClient+'&page=' + page + "&paginate=" + this.paginate).then(response => {
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
            editPwd(user){
                this.userAccess.userActual = user.lastname+' '+user.firstname;
                this.userAccess.userCompte = user.login;
                this.userAccess.id = user.id;
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

                    this.userForm.entiteClients = user.entite; 
                }else{
                    this.noClientRole = true;
                    this.value = [];
                     this.userForm.entite = user.entite[0]; 


                }
                this.userForm.username  = user.login;
                

              
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;

            },
            closeModalPwd(){
                  this.$refs.closePoupClosePwd.click();
                this.userAccess.id='';
                this.userAccess.userActual='';
                this.userAccess.password='';
                this.userAccess.confirmPasswordNew='';
            },
            getEntiteName(id){

                for(var i=0; i<this.list_entites.length; i++){
                  
                    if(id==this.list_entites[i].id){

                        return this.list_entites[i].nom;
                    }
                }
            },
            checkIsAdmin(){
                if(this.slugClient!=""){
                    for(var i=0; i<this.list_entites.length; i++){
                  
                        if(this.slugClient==this.list_entites[i].slug){
                            this.userForm.entite = this.list_entites[i].id;
                            return true;
                        }
                    } 
                }

                return false;
               
            },
             savePwd(){
                this.submittedPwd = true;
                // stop here if form is invalid
                this.$v.userAccess.$touch();
                if (this.$v.userAccess.$invalid) {
                    return;
                }
              
                const data = new FormData();
                data.append('password', this.userAccess.password);
                data.append('id', this.userAccess.id); 

                axios.post("/configuration/modifAccess/", data).then(response => {
                  
                    if(response.data.code==0){
                       this.closeModalPwd();

                        Vue.swal.fire(
                          'Succés!',
                          'Mot de passe modifié crée avec succés!',
                          'success'
                        );
                    }else{
                         Vue.swal.fire(
                          '',
                          response.data.message,
                          'warning'
                        )
                    }
                    this.submittedPwd = false;
                   
                });
            }
        },
        mounted() {console.log("ded dd", this.listEntites, "ded dd");
         this.getUser();
        }
    }
</script>
