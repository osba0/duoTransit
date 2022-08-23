<template>
    <div>
        <div class="row mb-3">
          <div class="col-sm-6">
               <h2>Notifications</h2>
          </div>
           <div class="col-sm-6 text-right">
               <button class="btn btn-success text-white" :disabled="totalUnread==0" @click="marquerToutLu()"><i aria-hidden="true" class="fa fa-check"></i> Marquer tout comme lu</button>
          </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive p-0">
                    <table class="table borderDark">
                        <thead class="thead-blue hasborder">
                             <tr>
                                <th class="p-2 border-right border-white h6" width="25%">Titre</th>
                                <th class="p-2 border-right border-white h6" width="30%">Description</th>
                                <th class="p-2 border-right border-white h6">Fichier</th>
                                <th class="p-2 border-right border-white h6">Lue?</th>
                                <th class="p-2 border-right border-white h6">Utilisateur</th>
                                <th class="p-2 border-right border-white h6">Date</th>
                                <th class="text-right p-2 border-right border-white h6">Action</th>
                            </tr>
                        </thead>
                     <tbody>
                        <template v-if="!notifications.data || !notifications.data.length">
                                <tr><td colspan="6" class="bg-white text-center">Aucun fournisseur défini!</td></tr>
                            </template>
                              <tr v-for="notif in notifications.data" :class="!notif.read? 'bg-white': 'bg-light opacity-light'">
                                <td classborderDark="p-2 align-top">
                                   <h5 :class="[!notif.read? 'font-weight-bold':'']">{{ notif.data["title"] }}</h5> 
                                </td>
                                 <td class="p-2 align-middle">
                                    {{ notif.data["description"] }}
                                </td>
                                <td class="p-2 align-middle">
                                    <button v-if="notif.data['fichier']!=''" :disabled="notif.data['fichier'].split('.').length == 1" title="Voir le fichier" class="btn btn-circle border btn-circle-sm m-1 position-relative bg-primary"  @click="showFichier(notif.data['fichier'])" data-toggle="modal" data-target="#openFichier">
                                        <i class="fa fa-file text-white" aria-hidden="true"></i>
                                    </button>
                                </td>
                                <td class="p-2 text-left align-middle">
                                    <span v-if="notif.read" class="badge badge-success">Oui</span>
                                    <span v-else class="badge badge-warning">Non</span>
                                </td>
                                <td class="p-2 text-left align-middle">{{ notif.user }}</td>
                                <td class="p-2 align-middle"> Il y'a {{ notif.date }}</td>
                                <td class="text-right align-middle">
                                    <div class="d-flex w-100 justify-content-end">
                                        <a v-if="!notif.read" @click="marquerLu(notif)" title="Marquer comme lu" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white"><i aria-hidden="true" class="fa fa-check"></i></a>
                                        <a v-if="notif.read" title="Lu" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-info"><i v-if="notif.read" class="fa fa-check-square-o text-white" aria-hidden="true"></i></a>
                                        <a title="Voir les détails" :href="'/notifications/mark-as-read/'+notif.data['slug']+'/'+notif.id" class="btn m-1 btn-circle border btn-circle-sm m-1 bg-white"><i aria-hidden="true" class="fa fa-eye"></i></a>
                                    </div>
                                   
                                  </td>
                               
                            </tr>
                        </tbody>
                    </table>
                </div>
                 <div class="d-flex mt-4 justify-content-center">
                    <pagination
                        :data="notifications"
                        @pagination-change-page="getNotification"
                    ></pagination>
                </div>
            </div>
        </div>

        <!-- Modal Facture-->
        <div class="modal fade fullscreenModal" id="openFichier" tabindex="-1" role="dialog" aria-labelledby="myModalFacture"
          aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">Fichier</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closePoupPdf">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <template v-if="pathFile != null">
                            <embed :src="pathFile" frameborder="0" width="100%" height="450px">
                          </template>
                         <template  v-else> Auncun fichier </template>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    <button type="button" v-on:click="closeModalPdf()" class="btn btn-warning">Fermer</button>
                  </div>
             </div>
            
          </div>
        </div>
    </div>
</template>
<script>
    import { required, minLength, between } from 'vuelidate/lib/validators';
    import Multiselect from 'vue-multiselect';
    export default {
         props: [
            'totalUnread'
        ],
         components: {
            Multiselect
          },
        data() { 
           
            return {
                notifications: {},
                paginate: 10,
                pathFile: null
            }
 
        },
        validations: {
        },
        watch: {
            paginate: function(){
                this.getNotification();
            }
        },
        methods : { 
            getNotification(page = 1){
                axios.get('/notif/list?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.notifications = response.data;
                });
            },
            marquerLu(notif){
                 axios.post('/notif/marquerLu/' + notif.id).then(response => {
                   location.reload();
                });
            },
            marquerToutLu(){
                 axios.post('/notif/marquerToutLu').then(response => {
                   location.reload();
                });
            },
            showFichier(path){
               this.pathFile = path;
            },
              closeModalPdf(){
                 this.pathFile = null;
                 this.$refs.closePoupPdf.click();
            }
        },
        mounted() {
         this.getNotification();
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>