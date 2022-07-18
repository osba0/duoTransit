<?php

use App\Http\Controllers\DryController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\ChargementController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncidentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user(); 
});



Route::post('/createDry', [DryController::class, 'create']);  

// Route::get('/clientsHome/{clientAuth}', [ClientController::class, 'index']);  


//Route::get('/dries/{id}', [ReceptionController::class, 'listeDries'])->middleware('auth:api');
//Route::get('/driesState/{id}', [ReceptionController::class, 'stateDries']);

//Route::post('/createReception', [ReceptionController::class, 'create']);

//Route::post('/modifyReception', [ReceptionController::class, 'modify']);

Route::delete('/deleteReception/{id}', [ReceptionController::class, 'delete']);

//Route::post('/checkDoublon', [ReceptionController::class, 'check']);

//Route::post('/chargement/save', [ChargementController::class, 'create']);

Route::get('/chargement/list', [ChargementController::class, 'liste']);

Route::get('/chargement/listcommandes/{id}', [ChargementController::class, 'getCommande']);
Route::post('/preChargement', [ChargementController::class, 'addPrechargement']);

//Route::get('/getPreChargmement', [ChargementController::class, 'getPreChargmement']); 
Route::get('/getdataprecharger/{idClient}/{typeCmd}/{numDossier}', [ChargementController::class, 'getDataPreCharger']); 



//Route::get('/configuration/getClient', [ConfigurationController::class, 'getClient']);
//Route::delete('/configuration/deleteClient/{id}', [ConfigurationController::class, 'clientDelete']);
//Route::post('/configuration/createClient', [ConfigurationController::class, 'createClient']);
//Route::post('/configuration/modifyClient', [ConfigurationController::class, 'modifyClient']);

//Route::get('/configuration/getFournisseur', [ConfigurationController::class, 'getFournisseur']);
/*Route::delete('/configuration/deleteFournisseur/{id}', [ConfigurationController::class, 'fournisseurDelete']);
Route::post('/configuration/createFournisseur', [ConfigurationController::class, 'createFournisseur']);
Route::post('/configuration/modifyFournisseur', [ConfigurationController::class, 'modifyFournisseur']);*/

Route::get('/livesearch', [ChargementController::class, 'filterDossier']);
Route::post('/empotage/createEmpotage', [ChargementController::class, 'createEmpotage']);
//Route::get('/empotage/list', [ChargementController::class, 'getEmpotage']);
Route::post('/updateDouane', [ChargementController::class, 'updateDouane']);
Route::post('/empotage', [ChargementController::class, 'setEmpotage']);
Route::get('/refreshEmpotage/{id}', [ChargementController::class, 'refreshEmpotage']);


//Route::get('/configuration/getUser', [UserController::class, 'list'])->middleware('auth:api');
//Route::post('/configuration/createUser', [UserController::class, 'store'])->middleware('auth:api');
//Route::post('/configuration/modifyUser', [UserController::class, 'update']);
//Route::delete('/configuration/deleteUser/{id}', [UserController::class, 'destroy']);
//Route::post('/configuration/modifPwd/{id}', [UserController::class, 'changePassword'])->middleware(['auth']);
//Route::get('/livesearchCmd', [IncidentController::class, 'filterCommande']);
//Route::post('/incidents/createIncident', [IncidentController::class, 'store']);
//Route::get('/incidents/getIncident/{id}', [IncidentController::class, 'list']);

Route::post('/chargement/cloturer/{ref}', [ChargementController::class, 'cloturer']);
Route::get('/historique/list', [ChargementController::class, 'getHisto']);










