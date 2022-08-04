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
Vue.component('dry-index', require('./components/dry/DryIndex.vue').default);
Vue.component('reception', require('./components/dry/Reception.vue').default);   
Vue.component('chargement', require('./components/dry/Chargement.vue').default);
Vue.component('chargement-view', require('./components/dry/ChargementView.vue').default);
Vue.component('prechargement', require('./components/dry/Prechargement.vue').default);
Vue.component('precharger', require('./components/dry/PreCharger.vue').default);  
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('client', require('./components/configuration/ClientComponent.vue').default);
Vue.component('fournisseur', require('./components/configuration/FournisseurComponent.vue').default);
Vue.component('empotage', require('./components/dry/Empotage.vue').default);  
Vue.component('empotagedata', require('./components/dry/EmpotageData.vue').default);  
Vue.component('userlist', require('./components/configuration/user/UserDataTable.vue').default); 
Vue.component('changepassword', require('./components/configuration/user/UserChangePassword.vue').default); 
Vue.component('incidents', require('./components/incidents/Incidents.vue').default);    
Vue.component('historique', require('./components/dry/Historique.vue').default);  
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


   
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs. 
 */

const app = new Vue({
    el: '#app_run'
});


