<template>
<div>
   <!-- Modal users-->
    <div class="modal fade" id="changePwd" tabindex="-1" role="dialog" aria-labelledby="myModalChange"
      aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
            
                <div class="modal-header text-left">
                    <h4 class="modal-title w-100 font-weight-bold">Changement de mot de passe</h4>
                    <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                     <form @submit.prevent="savePwd" enctype="multipart/form-data" key=1 >
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center">
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
                             <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                        <label for="passwordactual" class="d-block m-0 w-100 pr-2" >Mot de passe actuel</label>
                                        <input class="w-100 form-control"  v-model="userForm.passwordactual" type="password" id="passwordactual"/>
                                    </div>
                             </div>
                             </div>
                            <div class="col-12 my-2 d-flex flex-column align-items-center">
                             <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100">
                                        <label for="password" class="d-block m-0 w-100 pr-2" >Mot de passe</label>
                                        <input class="w-100 form-control"  v-model="userForm.password" type="password" id="password" :class="{ 'border-danger': submitted && !$v.userForm.password.required }"/>
                                    </div>
                             </div>
                             </div>
                             <div class="col-12 my-2">
                                <div class="w-100 my-2">
                                     <label for="confirmPassword"  class="d-block m-0 w-100 pr-2" style='white-space: nowrap;'>
                                    Confirmer mot de passe
                                   </label>
                                    <input class="w-100 form-control" id="confirmPassword" type="password" v-model="userForm.confirmPassword" :class="{ 'border-danger': submitted && !$v.userForm.confirmPassword.required }"/>
                                </div>
                             </div>
                             
                         </div>
                         
                        <button type="submit" class="btn btn-success">Enregister</button>
                        <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler</button>
                     </form>
                </div>
                
         </div>
        
      </div>
    </div>
</div>
</template>

<script>
import { required, email, minLength, sameAs, between } from 'vuelidate/lib/validators'; 
export default {
    
    props: [ 
      'idClient'
    ],
    data () {
        return {
            userForm :{
                passwordactual: '',
                password: '',
                confirmPassword: ''

            },
            submitted: false,
        }
    },
    validations: {
            userForm : {
                password: { required, minLength: minLength(6) },
                confirmPassword: { required, sameAsPassword: sameAs('password') }

            },
        },
    computed: {
       
    },
    methods: {
         closeModal(){
            this.$refs.closePoup.click();
            this.flushData();
            this.submitted = false;

        },
        flushData(){
            this.userForm.password = "";
            this.userForm.confirmPassword = "";
        },
        savePwd(){
                this.submitted = true;
                // stop here if form is invalid
                this.$v.userForm.$touch();
                if (this.$v.userForm.$invalid) {
                    return;
                }
              
                const data = new FormData();
                data.append('passwordactual', this.userForm.passwordactual);
                data.append('password', this.userForm.password);

                axios.post("/api/configuration/modifPwd/"+this.idClient, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Mot de passe modifié crée avec succés!',
                          'success'
                        )
                        

                    }else{
                         Vue.swal.fire(
                          '',
                          response.data.message,
                          'warning'
                        )
                    }
                    this.submitted = false;
                   
                });
            }
    },
    mounted() {
        }
}
</script>
