<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceptionController;
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
use App\Http\Controllers\ManageExcelController;
use App\Models\Contenaire;
use App\Models\Entrepot;
use App\Models\UserRole;
use App\Models\Client;
use App\Models\TypeCommande;
use App\Models\Fournisseur;
use App\Models\Entite;
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

Route::get('/transitaire/{entite}', [IndexController::class, 'index'])->middleware(['auth']);

Route::get('/dries/{id}', function () {
    return view('backend.dies');
})->middleware(['auth']);

Route::get('/{currententite}/reception/{slug}', [ReceptionController::class, 'index'])->name('reception')->middleware(['auth']);

Route::post('/checkDoublon', [ReceptionController::class, 'check'])->middleware(['auth']);

Route::post('/setRate/{id}', [ReceptionController::class, 'setRate'])->middleware(['auth']); 
Route::get('/dries/{id}/{entite}', [ReceptionController::class, 'listeDries'])->middleware('auth');
Route::post('/createReception', [ReceptionController::class, 'create'])->middleware(['auth']);
Route::post('/modifyReception', [ReceptionController::class, 'modify'])->middleware(['auth']);
Route::get('/driesState/{id}/{entite}', [ReceptionController::class, 'stateDries'])->middleware(['auth']);
Route::post('/removeFacture/{idReception}', [ReceptionController::class, 'removeFacture'])->middleware(['auth']);

Route::post('/incidents/createIncident', [IncidentController::class, 'store'])->middleware(['auth']);
Route::get('/incidents/getIncident/{id}', [IncidentController::class, 'list'])->middleware(['auth']);
Route::delete('/incidents/deleteIncident/{id}', [IncidentController::class, 'delete'])->middleware(['auth']);

Route::get('/livesearchCmd', [IncidentController::class, 'filterCommande']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'configuration'], function () {

    //Route::get('/clients', [ConfigurationController::class, 'listeClient'])->name('clients')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);
    
    Route::get('/clients', function () {
        $typeCmd = TypeCommande::get(); 
        $fournis = Fournisseur::get(); 
        $entites = Entite::get(); 
        return view('backend.configuration.clients', ['listTypeCmds' => $typeCmd, 'listFournisseurs' => $fournis, 'listTransitaire' => $entites]);
    })->name('clients')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);
    
    Route::get('/fournisseurs', function () {
       
        $listClient = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get(); 
        return view('backend.configuration.fournisseurs', ['listClient' => $listClient]);
    })->name('fournisseurs')->middleware(['auth', 'role:' . UserRole::ROLE_ROOT]);

    Route::get('/fournisseurs/{currententite}', function () {
         $listClient = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get();
        return view('backend.configuration.fournisseurs', ['listClient' => $listClient]);
    })->name('fournisseursAdmin')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

    Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs')->middleware(['auth', 'role:' . UserRole::ROLE_ROOT]);

    Route::get('/utilisateurs/{currententite}', [UserController::class, 'index'])->name('utilisateursAdmin')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

    Route::post('/statusCompte', [UserController::class, 'statusCompte'])->middleware(['auth']);;

     Route::get('/entrepots', function () {
        return view('backend.configuration.entrepots');
    })->name('entrepots')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

      Route::get('/contenaires', function () {
        return view('backend.configuration.contenaires');
    })->name('contenaires')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

    Route::get('/typecommande', function () {
         $listClient = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get(); 
        return view('backend.configuration.typecommande', ['listClient' => $listClient]);

    })->name('typecommande')->middleware(['auth', 'role:' . UserRole::ROLE_ROOT]);

    Route::get('/typecommande/{currententite}', function () {
         $listClient = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get();
        return view('backend.configuration.typecommande', ['listClient' => $listClient]);
    })->name('typecommandeAdmin')->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

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


Route::get('/incidents/{id}', [IncidentController::class, 'index'])->name('index')->middleware(['auth']);

Route::get('/{currententite}/historique-empotage/{id}', [HistoActionController::class, 'historiqueEmpotage'])->name('historique-empotage')->middleware(['auth'])->middleware(['auth', 'role:' . UserRole::ROLE_ADMIN]);

Route::middleware(['role:' . UserRole::ROLE_CLIENT])->group(function () {
    Route::get('/{currententite}/historique-docim/{id}', [HistoActionController::class, 'historiqueEmpotage'])->name('historique-docim');
 });

Route::middleware(['role:' . UserRole::ROLE_CONSULTATION])->group(function () {
    Route::get('/{currententite}/consultation/{id}', [HistoActionController::class, 'historiqueEmpotage'])->name('consultation');
 });

/* Route::middleware(['auth'])->group(function () {
      Route::middleware(['roles:' . UserRole::ROLE_CONSULTATION.','. UserRole::ROLE_CLIENT])->group(function () {
        Route::get('/historique-docim/{id}', [HistoActionController::class, 'historiqueEmpotage'])->name('historique-docim');
     });
});*/

//Route::get('/historique-docim/{id}', [HistoActionController::class, 'historiqueEmpotage'])->name('historique-docim')->middleware(['auth', 'role:' . UserRole::ROLE_CLIENT, 'role:' . UserRole::ROLE_CONSULTATION]);

Route::get('/{currententite}/historique-prechargement/{id}', [HistoActionController::class, 'historiquePrechargement'])->name('historique-prechargement')->middleware(['auth']);


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
    //Route::group(['prefix' => '/{currententite}/gerer'], function () {
        Route::get('/{currententite}/gerer/prechargement/{id}', [GestionController::class, 'index'])->name('gerer_prechargement');
        Route::post('/gerer/createDossier', [GestionController::class, 'create'])->middleware(['auth']);
        Route::post('/gerer/editDossier', [GestionController::class, 'editDossier'])->middleware(['auth']);
        Route::post('/gerer/createDossier/valider/{id}/{entite}', [GestionController::class, 'valider'])->middleware(['auth']);
        Route::post('/gerer/createDossier/notification/{id}/{entite}', [GestionController::class, 'notifier'])->middleware(['auth']);
        Route::get('/gerer/dossier/list/{id}/{entite}', [GestionController::class, 'listing'])->middleware(['auth']);
        Route::get('/gerer/dossier/pre/reception/{id}/{entite}/{typecmd}', [GestionController::class, 'listingReception'])->middleware(['auth']);
        Route::post('/gerer/dossier/setPrechargement/{entite}', [GestionController::class, 'precharger'])->middleware(['auth']);
        Route::get('/gerer/getCmd/{id}/{typecommande}', [GestionController::class, 'getCommande'])->middleware(['auth']);
        
        Route::get('/getmotif/{idreception}', [ReceptionController::class, 'getMotif'])->middleware(['auth']);
        
        
        Route::post('/gerer/saveMotif', [GestionController::class, 'saveMotif'])->middleware(['auth']);
        Route::delete('/gerer/deletePre/{id}/{entite}', [GestionController::class, 'deletePre']);
        Route::post('/gerer/reactiver/{id}/{entite}', [GestionController::class, 'reactiver'])->name('reactiverPrechargement');

        Route::get('/gerer/prechargement/{id}/{dossier}', [GestionController::class, 'index'])->name('details_prechargement');

        Route::get('/{currententite}/gerer/empotage/{id}', [GestionController::class, 'indexEmpotage'])->name('gerer_empotage');

        Route::post('/gerer/empotage/notification/{id}/{entite}', [EmpotageController::class, 'notifier'])->middleware(['auth']);
        Route::post('/gerer/empotage/createEmpotage', [EmpotageController::class, 'createEmpotage']);
        Route::post('/gerer/empotage/modifyEmpotage', [EmpotageController::class, 'modifyEmpotage']);
        Route::delete('/gerer/deleteEmpotage/{id}/{entite}', [EmpotageController::class, 'deleteEmpotage']); 
        Route::get('/gerer/livesearch', [EmpotageController::class, 'filterDossier']);
        Route::get('/gerer/empotage/getContenaire/{idEmpotage}', [EmpotageController::class, 'getContenaire']); 
        Route::post('/gerer/empotage/reactiver/{id}', [EmpotageController::class, 'reactiver']); 
        Route::post('/gerer/empotage/contenaire/suppression/{id}', [EmpotageController::class, 'deleteContenaire']);
         
        Route::post('/gerer/empotage/createContenaireEmpotage', [EmpotageController::class, 'createContenaireEmpo'])->middleware(['auth']);

        Route::get('/gerer/dossier/empotage/reception/{id}/{typecmd}', [EmpotageController::class, 'listingReceptionEmpotage'])->middleware(['auth']); 
        Route::post('/gerer/updateDouane', [EmpotageController::class, 'updateDouane']);
        Route::post('/gerer/updateDepalettisation', [EmpotageController::class, 'updateDepalettisation']);
        
        Route::post('/gerer/dossier/setEmpotage', [EmpotageController::class, 'empoter'])->middleware(['auth']);
        Route::get('/gerer/getCmd/empoter/{id}/{typecommande}', [EmpotageController::class, 'getCommandeEmpoter'])->middleware(['auth']);
        Route::post('/gerer/validationEmpotage/valider/{id}/{entite}', [EmpotageController::class, 'valider'])->middleware(['auth']);
        Route::post('/gerer/empotage/cloturer/{id}', [EmpotageController::class, 'cloturer']);
        Route::post('/gerer/empotage/savepdf', [EmpotageController::class, 'savepdf']);
  //  });

});

Route::post('/saveDocs/{idEmpotage}', [EmpotageController::class, 'saveDoc'])->middleware(['auth']);

Route::post('/saveOtherDocs/{idEmpotage}', [EmpotageController::class, 'saveOtherDoc'])->middleware(['auth']);

Route::post('/savePhotos/{idcontenaire}', [EmpotageController::class, 'savePhotos'])->middleware(['auth']);
Route::post('/removePhotos/{idcontenaire}', [EmpotageController::class, 'removePhotos'])->middleware(['auth']);

Route::post('/saveDocsDouane/{idEmpotage}', [EmpotageController::class, 'saveDeclaration'])->middleware(['auth']); 

Route::post('/removeDocs/{idEmpotage}', [EmpotageController::class, 'removeDoc'])->middleware(['auth']);

Route::post('/removeAutreDocs/{idEmpotage}', [EmpotageController::class, 'removeOtherDoc'])->middleware(['auth']);

Route::post('/removeDocsdouane/{idEmpotage}', [EmpotageController::class, 'removeDeclarationDouane'])->middleware(['auth']);


Route::get('{currententite}/precharger/{id}', [PrechargementController::class, 'index'])->name('precharger')->middleware(['auth']);



Route::post('/createDossierPre', [PrechargementController::class, 'create'])->middleware(['auth']);

Route::post('/setPrechargement/{entite}', [PrechargementController::class, 'precharger'])->middleware(['auth']);

Route::get('/prechargement/getreception/{id}/{entite}', [PrechargementController::class, 'listingReception'])->middleware(['auth']);
Route::get('/prechargement/list/{id}/{entite}', [PrechargementController::class, 'listing'])->middleware(['auth']);
Route::get('/empotage/list/{id}/{entite}', [EmpotageController::class, 'listing'])->middleware(['auth']);

Route::post('/search/histoEmpotage/{id}/{entite}', [HistoActionController::class, 'searchHisto'])->middleware(['auth']);
Route::post('/search/histoPrechargement/{id}/{entite}', [HistoActionController::class, 'searchHistoPre'])->middleware(['auth']);


Route::get('/histoEmpotage/reception/{id}/{typecmd}', [HistoActionController::class, 'searchCmdReception'])->middleware(['auth']);
Route::get('/histoPrechargement/reception/{id}/{typecmd}', [HistoActionController::class, 'searchCmdReceptionPre'])->middleware(['auth']);

Route::get('/configuration/getFournisseur', [ConfigurationController::class, 'getFournisseur']);


Route::delete('/configuration/deleteUser/{id}', [UserController::class, 'destroy']);
Route::post('/configuration/modifyUser', [UserController::class, 'update']);


Route::get('/configuration/getEntite', [ConfigurationController::class, 'getEntite']);
Route::delete('/configuration/deleteEntite/{id}', [ConfigurationController::class, 'entiteDelete']);
Route::post('/configuration/createEntite', [ConfigurationController::class, 'createEntite']);
Route::post('/configuration/modifyEntite', [ConfigurationController::class, 'modifyEntite']);

Route::post('/configuration/clientFournisseur/{fournisseur}/{client}', [ConfigurationController::class, 'ajoutClientFour']);

Route::post('/configuration/retirerclientFournisseur/{fournisseur}/{client}', [ConfigurationController::class, 'retirerClientFour']);


Route::post('/configuration/clientTypeCmd/{typecommande}/{client}', [ConfigurationController::class, 'ajoutClientTypeCmd']);

Route::post('/configuration/retirerclientTypeCmd/{typecommande}/{client}', [ConfigurationController::class, 'retirerClientTypeCmd']);


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
Route::post('/configuration/modifPwd', [UserController::class, 'changePassword'])->middleware(['auth']);
Route::post('/configuration/modifAccess', [UserController::class, 'changePasswordAccess'])->middleware(['auth']);
Route::post('/configuration/etatFournisseur', [ConfigurationController::class, 'etatFournisseur']);
Route::get('/configuration/getUser', [UserController::class, 'list'])->middleware(['auth']);

Route::delete('/deleteReception/{id}/{idClient}', [ReceptionController::class, 'delete'])->middleware(['auth']);

Route::post('/updateFacture/{idReception}', [ReceptionController::class, 'updateFacture'])->middleware(['auth']);


Route::post('/prechargementClient/valider/{id}/{entite}', [PrechargementController::class, 'valider'])->middleware(['auth']);    
Route::get('/prechargementClient/getCmdChoisi/{id}', [PrechargementController::class, 'getCommande'])->middleware(['auth']); 


Route::post('/prechargementClient/notification/{id}/{entite}', [PrechargementController::class, 'notifier'])->middleware(['auth']); 

Route::delete('/prechargementClient/delete/{id}/{entite}', [PrechargementController::class, 'deletePre']);

Route::post('/prechargementClient/cloturer/{id}', [PrechargementController::class, 'cloturer']);



Route::get('/qrcode', function () {
        return view('qrcode.facture-prechargement', ['contenaires' => '123']);
    })->name('qrcode');


Route::get('/{currententite}/notifications/mark-as-read/{client}/{notification_id}', [NotificationController::class, 'show'])->middleware(['auth']);
Route::get('/{currententite}/notifications/{client}', [NotificationController::class, 'index'])->middleware(['auth'])->name('listNotif');
Route::get('/notif/list', [NotificationController::class, 'listeNotification'])->middleware(['auth']);

Route::post('/notif/marquerLu/{id}', [NotificationController::class, 'marquerLu'])->middleware(['auth']); 

Route::post('/notif/marquerToutLu', [NotificationController::class, 'marquerToutLu'])->middleware(['auth']); 

Route::get('/checksession', [IndexController::class, 'checksession']); 


Route::post('/sendNotificationReception', [ReceptionController::class, 'sendNotificationReception']); 
Route::post('/addNumDocim/{id}', [EmpotageController::class, 'setNumEmpotage']); 

Route::get('/{currententite}/numdocim/{id}', [HistoActionController::class, 'indexDocim'])->name('gestion-docim')->middleware(['auth']);
Route::post('/numdocum/cloturer/{id}', [HistoActionController::class, 'cloturer'])->middleware(['auth']);

Route::get('/{currententite}/importCommande/{slug}', [ManageExcelController::class, 'importExportView'])->middleware(['auth']);

Route::get('export', [ManageExcelController::class, 'export'])->name('export')->middleware(['auth']);
Route::post('import', [ManageExcelController::class, 'import'])->name('import')->middleware(['auth']);
Route::get('listcmdimported', [ManageExcelController::class, 'getCommandes'])->name('listimported')->middleware(['auth']);
Route::delete('import/delete/{id}', [ManageExcelController::class, 'delete'])->middleware(['auth']);

Route::post('/checkCommandeImport', [ManageExcelController::class, 'checkCmd'])->middleware(['auth']);

require __DIR__.'/auth.php';
