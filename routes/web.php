<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\ChargementController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\HistoActionController;
use App\Http\Controllers\PrechargementController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\EmpotageController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\NotificationController;
use App\Models\Contenaire;
use App\Models\Entrepot;
use App\Models\UserRole;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->middleware(['auth'])->name('home');

Route::get('/dries/{id}', function () {
    return view('backend.dies');
})->middleware(['auth']);

Route::get('/reception/{slug}', [ReceptionController::class, 'index'])->name('reception')->middleware(['auth']);

Route::get('/prechargement/{id}', [ChargementController::class, 'preCharge'])->name('prechargement')->middleware(['auth']);

Route::post('/checkDoublon', [ReceptionController::class, 'check'])->middleware(['auth']);

Route::post('/setRate/{id}', [ReceptionController::class, 'setRate'])->middleware(['auth']); 
Route::get('/dries/{id}', [ReceptionController::class, 'listeDries'])->middleware('auth');
Route::post('/createReception', [ReceptionController::class, 'create'])->middleware(['auth']);
Route::post('/modifyReception', [ReceptionController::class, 'modify'])->middleware(['auth']);
Route::get('/driesState/{id}', [ReceptionController::class, 'stateDries'])->middleware(['auth']);

Route::post('/incidents/createIncident', [IncidentController::class, 'store'])->middleware(['auth']);
Route::get('/incidents/getIncident/{id}', [IncidentController::class, 'list'])->middleware(['auth']);
 Route::delete('/incidents/deleteIncident/{id}', [IncidentController::class, 'delete'])->middleware(['auth']);

 Route::get('/livesearchCmd', [IncidentController::class, 'filterCommande']);

Route::get('/empotage/{id}', [ChargementController::class, 'empotage'])->name('empotage')->middleware(['auth']);

Route::get('/prechargement/{id}/{numero}/{typeCmd}', [ChargementController::class, 'listePreCharge'])->name('listprechargement')->middleware(['auth']);

Route::get('/chargement-list/{id}', [ChargementController::class, 'index'])->name('chargement-list')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

Route::get('/chargement/{id}/{numero}', [ChargementController::class, 'getChargmement'])->middleware(['auth']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'configuration'], function () {

    //Route::get('/clients', [ConfigurationController::class, 'listeClient'])->name('clients')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);
    
    Route::get('/clients', function () {
        return view('backend.configuration.clients');
    })->name('clients')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);
    
    Route::get('/fournisseurs', function () {
       
        $listClient = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get(); 
        return view('backend.configuration.fournisseurs', ['listClient' => $listClient]);
    })->name('fournisseurs')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

    Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs')->middleware(['auth']);

     Route::get('/entrepots', function () {
        return view('backend.configuration.entrepots');
    })->name('entrepots')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

      Route::get('/contenaires', function () {
        return view('backend.configuration.contenaires');
    })->name('contenaires')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

    Route::get('/typecommande', function () {
        return view('backend.configuration.typecommande');
    })->name('typecommande')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

    Route::get('/entite', function () {
        $contenaires = Contenaire::get(); 
        $entrepots = Entrepot::get(); 
        return view('backend.configuration.entite', ['contenaires' => $contenaires, 'entrepots' => $entrepots]);
    })->name('entite')->middleware(['auth', 'role:' . UserRole::ROLE_ROOT]);

    Route::get('/journal', function () {
        $clients = Client::get(); 
        return view('backend.configuration.journal', ['clients' => $clients]);
    })->name('mouchard')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);
    
});

Route::get('/empotage/{id}/{numero}/{typeCmd}/{idSelected}', [ChargementController::class, 'empotageData'])->middleware(['auth']);

Route::get('/incidents/{id}', [IncidentController::class, 'index'])->name('index')->middleware(['auth']);

Route::get('/historique-empotage/{id}', [HistoActionController::class, 'historiqueEmpotage'])->name('historique-empotage')->middleware(['auth']);

Route::get('/historique-prechargement/{id}', [HistoActionController::class, 'historiquePrechargement'])->name('historique-prechargement')->middleware(['auth']);


Route::get('/activity/{id}', [ActivityController::class, 'index'])->name('journal')->middleware(['auth']);
Route::get('/activity/getInfos/{id}', [ActivityController::class, 'getData'])->name('get_activity')->middleware(['auth']);
Route::get('/activity/count/{type}', [ActivityController::class, 'getCount'])->name('get_count')->middleware(['auth']);
 
Route::middleware(['web','auth'])->group(function () {
    Route::get('/configuration/clientsHome/{clientAuth}', [ConfigurationController::class, 'clientHome']);     

    Route::post('/configuration/createUser', [UserController::class, 'store']);
    Route::get('/configuration/config', [ConfigurationController::class, 'getConfig']);
    Route::get('/configuration/getClient', [ConfigurationController::class, 'getClient']);
    Route::delete('/configuration/deleteClient/{id}', [ConfigurationController::class, 'clientDelete']);
    Route::post('/configuration/createClient', [ConfigurationController::class, 'createClient']);
    Route::post('/configuration/modifyClient', [ConfigurationController::class, 'modifyClient']);
    

    // Gestion
    Route::group(['prefix' => 'gerer'], function () {
        Route::get('/prechargement/{id}', [GestionController::class, 'index'])->name('gerer_prechargement');
        Route::post('/createDossier', [GestionController::class, 'create'])->middleware(['auth']);
        Route::post('/createDossier/valider/{id}', [GestionController::class, 'valider'])->middleware(['auth']);
        Route::post('/createDossier/notification/{id}', [GestionController::class, 'notifier'])->middleware(['auth']);
        Route::get('/dossier/list/{id}', [GestionController::class, 'listing'])->middleware(['auth']);
        Route::get('/dossier/pre/reception/{id}/{typecmd}', [GestionController::class, 'listingReception'])->middleware(['auth']);
        Route::post('/dossier/setPrechargement', [GestionController::class, 'precharger'])->middleware(['auth']);
        Route::get('/getCmd/{id}/{typecommande}', [GestionController::class, 'getCommande'])->middleware(['auth']);
        Route::delete('/deletePre/{id}', [GestionController::class, 'deletePre']);
        Route::post('/reactiver/{id}', [GestionController::class, 'reactiver'])->name('reactiverPrechargement');

        Route::get('/prechargement/{id}/{dossier}', [GestionController::class, 'index'])->name('details_prechargement');

        Route::get('/empotage/{id}', [GestionController::class, 'indexEmpotage'])->name('gerer_empotage');

        Route::post('/empotage/notification/{id}', [EmpotageController::class, 'notifier'])->middleware(['auth']);
        Route::post('/empotage/createEmpotage', [EmpotageController::class, 'createEmpotage']);
        Route::post('/empotage/modifyEmpotage', [EmpotageController::class, 'modifyEmpotage']);
        Route::delete('/deleteEmpotage/{id}', [EmpotageController::class, 'deleteEmpotage']); 
        Route::get('/livesearch', [EmpotageController::class, 'filterDossier']);

        Route::get('/dossier/empotage/reception/{id}/{typecmd}', [EmpotageController::class, 'listingReceptionEmpotage'])->middleware(['auth']); 
        Route::post('/updateDouane', [EmpotageController::class, 'updateDouane']);
        Route::post('/dossier/setEmpotage', [EmpotageController::class, 'empoter'])->middleware(['auth']);
        Route::get('/getCmd/empoter/{id}/{typecommande}', [EmpotageController::class, 'getCommandeEmpoter'])->middleware(['auth']);
        Route::post('/validationEmpotage/valider/{id}', [EmpotageController::class, 'valider'])->middleware(['auth']);
        Route::post('/empotage/cloturer/{ref}', [EmpotageController::class, 'cloturer']);
        Route::post('/empotage/savepdf', [EmpotageController::class, 'savepdf']);
    });

});

Route::post('/chargement/save', [ChargementController::class, 'create'])->middleware(['auth']);

Route::get('/precharger/{id}', [PrechargementController::class, 'index'])->name('precharger')->middleware(['auth']);

Route::get('/getPreChargmement', [ChargementController::class, 'getPreChargmement'])->middleware(['auth']);

Route::post('/createDossierPre', [PrechargementController::class, 'create'])->middleware(['auth']);

Route::post('/setPrechargement', [PrechargementController::class, 'precharger'])->middleware(['auth']);

Route::get('/prechargement/getreception/{id}', [PrechargementController::class, 'listingReception'])->middleware(['auth']);
Route::get('/prechargement/list/{id}', [PrechargementController::class, 'listing'])->middleware(['auth']);
Route::get('/empotage/list/{id}', [EmpotageController::class, 'listing'])->middleware(['auth']);

Route::post('/search/histoEmpotage/{id}', [HistoActionController::class, 'searchHisto'])->middleware(['auth']);
Route::post('/search/histoPrechargement/{id}', [HistoActionController::class, 'searchHistoPre'])->middleware(['auth']);


Route::get('/histoEmpotage/reception/{id}/{typecmd}', [HistoActionController::class, 'searchCmdReception'])->middleware(['auth']);
Route::get('/histoPrechargement/reception/{id}/{typecmd}', [HistoActionController::class, 'searchCmdReceptionPre'])->middleware(['auth']);

Route::get('/configuration/getFournisseur', [ConfigurationController::class, 'getFournisseur']);


Route::delete('/configuration/deleteUser/{id}', [UserController::class, 'destroy']);
Route::post('/configuration/modifyUser', [UserController::class, 'update']);


Route::get('/configuration/getEntite', [ConfigurationController::class, 'getEntite']);
Route::delete('/configuration/deleteEntite/{id}', [ConfigurationController::class, 'entiteDelete']);
Route::post('/configuration/createEntite', [ConfigurationController::class, 'createEntite']);
Route::post('/configuration/modifyEntite', [ConfigurationController::class, 'modifyEntite']);

Route::get('/configuration/getTypeCommande', [ConfigurationController::class, 'getTypeCommande']);
Route::delete('/configuration/deleteTypeCommande/{id}', [ConfigurationController::class, 'typeCommandeDelete']);
Route::post('/configuration/createTypeCommande', [ConfigurationController::class, 'createTypeCommande']);
Route::post('/configuration/modifyTypeCommande', [ConfigurationController::class, 'modifyTypeCommande']);
Route::post('/configuration/etatTypeCommande', [ConfigurationController::class, 'etatTypeCommande']);


Route::get('/configuration/getEntrepots', [ConfigurationController::class, 'getEntrepot']); 
Route::delete('/configuration/deleteEntrepot/{id}', [ConfigurationController::class, 'entrepotDelete']);
Route::post('/configuration/createEntrepot', [ConfigurationController::class, 'createEntrepot']);
Route::post('/configuration/modifyEntrepot', [ConfigurationController::class, 'modifyEntrepot']);

Route::get('/configuration/getContenaires', [ConfigurationController::class, 'getContenaire']); 
Route::delete('/configuration/deleteContenaire/{id}', [ConfigurationController::class, 'contenaireDelete']);
Route::post('/configuration/createContenaire', [ConfigurationController::class, 'createContenaire']);
Route::post('/configuration/modifyContenaire', [ConfigurationController::class, 'modifyContenaire']);

Route::delete('/configuration/deleteFournisseur/{id}', [ConfigurationController::class, 'fournisseurDelete']);
Route::post('/configuration/createFournisseur', [ConfigurationController::class, 'createFournisseur']);
Route::post('/configuration/modifyFournisseur', [ConfigurationController::class, 'modifyFournisseur']);
Route::post('/configuration/modifPwd/{id}', [UserController::class, 'changePassword'])->middleware(['auth']);
Route::get('/configuration/getUser', [UserController::class, 'list'])->middleware(['auth']);

Route::delete('/deleteReception/{id}/{idClient}', [ReceptionController::class, 'delete'])->middleware(['auth']);


Route::post('/prechargementClient/valider/{id}', [PrechargementController::class, 'valider'])->middleware(['auth']); 
Route::get('/prechargementClient/getCmdChoisi/{id}', [PrechargementController::class, 'getCommande'])->middleware(['auth']); 


Route::post('/prechargementClient/notification/{id}', [PrechargementController::class, 'notifier'])->middleware(['auth']); 

Route::delete('/prechargementClient/delete/{id}', [PrechargementController::class, 'deletePre']);



Route::get('/qrcode', function () {
        return view('qrcode.facture-prechargement', ['contenaires' => '123']);
    })->name('qrcode');


Route::get('/notifications/mark-as-read/{client}/{notification_id}', [NotificationController::class, 'show'])->middleware(['auth']);
Route::get('/notifications/{client}', [NotificationController::class, 'index'])->middleware(['auth'])->name('listNotif');
Route::get('/notif/list', [NotificationController::class, 'listeNotification'])->middleware(['auth']);

Route::post('/notif/marquerLu/{id}', [NotificationController::class, 'marquerLu'])->middleware(['auth']); 

Route::post('/notif/marquerToutLu', [NotificationController::class, 'marquerToutLu'])->middleware(['auth']); 

Route::get('/checksession', [IndexController::class, 'checksession']); 


Route::post('/sendNotificationReception', [ReceptionController::class, 'sendNotificationReception']); 


require __DIR__.'/auth.php';
