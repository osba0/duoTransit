require('./bootstrap');

require('alpinejs');

window.Vue = require('vue').default;


import VueToast from 'vue-toast-notification';

import 'vue-toast-notification/dist/theme-sugar.css';

import Vuelidate from 'vuelidate';

import VueSweetalert2 from 'vue-sweetalert2';

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';

import rate from 'vue-rate'
import 'vue-rate/dist/vue-rate.css'

Vue.use(rate);


Vue.use(VueToast,{
    position: 'top' 
});

Vue.use(Vuelidate);

Vue.use(VueSweetalert2);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))





Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('pageloader', require('./components/PageLoader.vue').default);    
Vue.component('client-list', require('./components/principal/IndexComponent.vue').default);

Vue.component('reception', require('./components/dry/Reception.vue').default);   

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('client', require('./components/configuration/ClientComponent.vue').default);
Vue.component('fournisseur', require('./components/configuration/FournisseurComponent.vue').default);
Vue.component('userlist', require('./components/configuration/user/UserDataTable.vue').default); 
Vue.component('changepassword', require('./components/configuration/user/UserChangePassword.vue').default); 
Vue.component('incidents', require('./components/incidents/Incidents.vue').default);    

Vue.component('entite', require('./components/configuration/EntiteComponent.vue').default);  
Vue.component('entrepot', require('./components/configuration/Entrepot.vue').default);
Vue.component('contenaire', require('./components/configuration/Contenaire.vue').default);
Vue.component('typecommande', require('./components/configuration/TypeCommande.vue').default);

Vue.component('historique-empotage', require('./components/historique/histoEmpotage.vue').default);   
Vue.component('historique-prechargement', require('./components/historique/histoPrechargement.vue').default);   

Vue.component('prechargement-client', require('./components/prechargement/index.vue').default);  

Vue.component('modalDetailsCommande', require('./components/modal/detailsCommande.vue').default); 
Vue.component('modalFacture', require('./components/modal/facture.vue').default); 

Vue.component('empotage-entite', require('./components/gestion/empotage.vue').default);  

Vue.component('prechargement-entite', require('./components/gestion/prechargement.vue').default);   

Vue.component('activity-index', require('./components/activity/index.vue').default);  


Vue.component('activity-journal', require('./components/configuration/journal.vue').default); 

Vue.component('notification-list', require('./components/notification/index.vue').default); 

Vue.component('choose-entite', require('./components/chooseEntite.vue').default); 

Vue.component('import-commande', require('./components/import/importCommande.vue').default); 
   
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs. 
 */

const app = new Vue({
    el: '#app_run'
});

// return to login 
window.axios.interceptors.response.use(
    (response) => {
        if(response.status === 401) {
            Vue.swal.fire(
              'Authorisation!',
              'You are not authorized',
              'warning'
            ).then((result) => {
                 // redirect to login page
                 window.location.href = "/login";
            });
        }
        return response;
    },
    error => {
        if (error.response && (419 === error.response.status || 401 === error.response.status)) {

            Vue.swal.fire(
              'Session exprirée!',
              'Veuillez vous reconnecter!',
              'warning'
            ).then((result) => {
                 // redirect to login page
                 window.location.href = "/login";
            });
          
        }
    }
);




