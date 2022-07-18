<?php

namespace App\Http\Controllers;

use App\Models\ChargementInit;
use App\Models\Reception;
use App\Models\Client;
use App\Models\PreChargementData;
use App\Models\TypeCommande;
use App\Http\Resources\TypeCommandeResource;
use App\Models\Empotage;
use DB;

use Illuminate\Http\Request;

use App\Http\Resources\ChargementInitResource;
use App\Http\Resources\ReceptionResource;
use App\Http\Resources\PrechargementResource;
use App\Http\Resources\EmpotageResource;
use Illuminate\Support\Facades\Auth;


class ChargementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //If user is not logged in then he can't access this page
    }

    public function index(){

        $client = Client::get()->where('id', request('id'))->first();

        return  view('backend.chargements', ['logo' => $client->cllogo, 'id_client' => $client->id]);
   }
   public function preCharge(){

        $client = Client::get()->where('id', request('id'))->first();

        return  view('backend.chargement.prechargement', ['logo' => $client->cllogo, 'id_client' => $client->id]);
   }

    public function empotage(Request $request){
        
        $client = Client::get()->where('id', request('id'))->first();
        
        return  view('backend.chargement.empotage', ['logo' => $client->cllogo, 'id_client' => $client->id, 'id_user' => $request->session()->get('idUser'), 'typeCmd' => $request->session()->get('typeCmd')]);
    }

     public function historique(Request $request){
        
        $client = Client::get()->where('id', request('id'))->first();
        
        return  view('backend.chargement.historique', ['logo' => $client->cllogo, 'id_client' => $client->id, 'id_user' => $request->session()->get('idUser'), 'typeCmd' => $request->session()->get('typeCmd')]);
    }

    

   

   public function liste(){

       $paginate = request('paginate');

        if (isset($paginate)) {
            $listeDossier = ChargementInit::paginate($paginate);  
          /* $listeDossier = DB::table('chargement_inits')
            ->rightJoin('pre_chargement_data', 'chargement_inits.clients_id', '=', 'pre_chargement_data.clients_id')
            ->paginate($paginate);*/

        }else{
            $listeDossier = ChargementInit::get();
        }

        return ChargementInitResource::collection($listeDossier);
   }

   public function create(Request $request)
    {
         $user = Auth::user();
        // Check num dossier exist or not

        $check = ChargementInit::where('numDossier', request('numdossier'))->get();
        
        if(sizeof($check) > 0){
            return response([
                "code" => 1,
                "message" => "Numéro dossier existe déja!"
            ]);
        }
        try{         

            $store = ChargementInit::create([
                "numDossier"  => request('numdossier'),
                "dateDebut"   => request('datedebut'),
                "dateCloture" => request('datecloture'),
                "clients_id"   => request('clientID'),
                "users_id"    => $user->id
            ]); 

            // initialiser prechargement
            $typeCmd = TypeCommande::get(); 
            $result = TypeCommandeResource::collection($typeCmd);
            foreach ($result as $key) {
                $store = PreChargementData::create([
                    "chargement_inits_numDossier" => request('numdossier'),
                    "type_commandes_id"   => $key->id,
                    "poidEmpote"          => 0,
                    "volumeEmpote"        => 0,
                    "colisEmpote"         => 0,
                    "clients_id"          => request('clientID'),
                    "users_id"            => $user->id,
                    "reetat"              => 1,
                    "commandes"           => null 
                ]);
            }


        }catch(\Exceptions $e){
              return response([
                "code" => 1,
                "message" => $e->getMessage()
            ]);
        }

        if($store){
            return response([
                "code" => 0,
                "message" => "OK"
            ]);
        }else{
            return response([
                "code" => 1,
                "message" => "KO"
            ]);
        }

        
    }

    public function getChargmement(){
        $client = Client::get()->where('id', request('id'))->first();

        $chargement = ChargementInit::get()->where('numDossier', request('numero'))->first();

        return  view('backend.chargement.chargement', ['logo' => $client->cllogo, 'id_client' => $client->id, 'numero' => request('numero'), 'dateDebut' => date('d/m/Y', strtotime($chargement['dateDebut'])), 'dateCloture' => date('d/m/Y', strtotime($chargement['dateCloture'])) ]);
   }

   public function listePreCharge(){
        $client = Client::get()->where('id', request('id'))->first();

        $chargement = ChargementInit::get()->where('numDossier', request('numero'))->first();

        return  view('backend.chargement.precharger', ['logo' => $client->cllogo, 'id_client' => $client->id, 'numero' => request('numero'), 'dateDebut' => date('d/m/Y', strtotime($chargement['dateDebut'])), 'dateCloture' => date('d/m/Y', strtotime($chargement['dateCloture'])), 'id_typeCmd' => request('typeCmd') ]);
   }

   

   public function getCommande(){
        if(request('typeCharge')=='PRECHARGEMENT'){
            $dries = Reception::where('clients_id', request('id'))->where('isLoad', false)->where('type_commandes_id', request("typeCmd"))->orderBy('recrea', 'desc')->get();
        }
        if(request('typeCharge')=='CHARGEMENT'){
            $dries = Reception::where('clients_id', request('id'))->where('type_commandes_id', request("typeCmd"))->orderBy('recrea', 'desc')->get();
        } 

        return ReceptionResource::collection($dries);
   }

   public function getPreChargmement(){
        $client = Client::get()->where('id', request('id'))->first();

        $prechargement = PreChargementData::get();

        return PrechargementResource::collection($prechargement);
   }

   public function getDataPreCharger(){

        $dataPre = PreChargementData::where('chargement_inits_numDossier', request('numDossier'))->where('type_commandes_id', request('typeCmd'))->where('clients_id', request('idClient'))->get();

        return PrechargementResource::collection($dataPre);
   }

   

   public function addPrechargement(){
        // check if dossier existe 
        $check = PreChargementData::where('chargement_inits_numDossier', request('numDossier'))->where('type_commandes_id', request('tyeCommande'))->get();  
        
        if(sizeof($check) > 0){
           PreChargementData::where('chargement_inits_numDossier', request('numDossier'))->where('type_commandes_id', request('tyeCommande'))
              ->update([
                "poidEmpote"   => request('poidsEmpote'),
                "volumeEmpote" => request('volumeEmpote'),
                "colisEmpote"  => request('colisEmpote'),
                "commandes"    => json_encode(request('cmds'))
          ]);
        }else{
           
            $store = PreChargementData::create([
                "chargement_inits_numDossier" => request('numDossier'),
                "type_commandes_id"   => request('tyeCommande'),
                "poidEmpote"          => request('poidsEmpote'),
                "volumeEmpote"        => request('volumeEmpote'),
                "colisEmpote"         => request('colisEmpote'),
                "clients_id"          => request('idClient'),
                "users_id"            => request('users_id'),
                "commandes"           => json_encode(request('cmds')), 
                "reetat"              => 1
            ]);
        }
        // change status 
        if(request('isChecked')==1){
            Reception::where('rencmd', request('cmdSelected'))->update([
                "isPreLoad"    => true
            ]); 
        }else{
            Reception::where('rencmd', request('cmdSelected'))->update([
                "isPreLoad"    => false
            ]); 
        }
        
        return response([
            "code" => 0
        ]);
      
   }

    public function filterDossier(Request $request)
    {
        $data = ChargementInit::where('numDossier', 'LIKE','%'.$request->keyword.'%')->get();
        return response()->json($data); 
    }

    public function  createEmpotage(Request $request){
        $store = Empotage::create([
            "reference" => request('reference'),
            "numContenaire" => request('tc'),
            "typeContenaire" => request('typetc'),
            "type_commandes_id" => request('typeCmd'),
            'plomb' => request('plomb'),
            "poidEmpote" => 0,
            "volumeEmpote" =>  0, 
            "colisEmpote" => 0,
            "commandes"  => json_encode(''),
            "clients_id" => request('idClient'), 
            "users_id" => request('user_id'),
            "reetat" => 0
        ]);
        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    public function  getEmpotage(Request $request){
        $paginate = request('paginate');

        if (isset($paginate)) {
            $empotage = Empotage::where('reetat', 0)->orderBy('reference', 'desc')->paginate($paginate);
        }else{
            $empotage = Empotage::get()->where('reetat', '0');
        }

        return EmpotageResource::collection($empotage);
    }
     public function  getHisto(Request $request){
        $paginate = request('paginate');

        if (isset($paginate)) {
            $empotage = Empotage::where('reetat', 1)->orderBy('reference', 'desc')->paginate($paginate);
        }else{
            $empotage = Empotage::get()->where('reetat', '0');
        }

        return EmpotageResource::collection($empotage);
    }
    
    public function empotageData(){
        $client = Client::get()->where('id', request('id'))->first();

        $results = Empotage::get()->where('reference', request('numero'))->where('type_commandes_id', request('typeCmd'));
        $empotage = EmpotageResource::collection($results);
    
        foreach ($results as $key) {
           
           $detailsEmpoatge[] = (object) [
                                        'id' => $key['id'], 
                                        'reference' => $key['reference'], 
                                        'tc' => $key['numContenaire'],
                                        'typeTc' => $key['typeContenaire'], 
                                        'plomb' => $key['plomb'], 
                                        'typeCmd' => request('typeCmd'),
                                        "poidEmpote"     => $key['poidEmpote'],
                                        "volumeEmpote"   => $key['volumeEmpote'],
                                        "colisEmpote"    => $key['colisEmpote'],
                                        'client_id' => request('id'),
                                        'commandes' => $key['commandes']
                                        ];

        }

        return  view('backend.chargement.empotageData', ['logo' => $client->cllogo, 'id_client' => $client->id,'pays_client' => $client->pays,'name_client' => $client->clnmcl,  'detailsEmpoatge' => $detailsEmpoatge, 'id_Selected' => request('idSelected')]);
   }

   public function refreshEmpotage(){

        $results = Empotage::get()->where('reference', request('numero'))->where('type_commandes_id', request('typeCmd'));
        $empotage = EmpotageResource::collection($results);
    
        foreach ($results as $key) {
           
           $detailsEmpoatge[] = (object) [
                                        'id' => $key['id'], 
                                        'reference' => $key['reference'], 
                                        'tc' => $key['numContenaire'],
                                        'typeTc' => $key['typeContenaire'], 
                                        'plomb' => $key['plomb'], 
                                        'typeCmd' => request('typeCmd'),
                                        "poidEmpote"     => $key['poidEmpote'],
                                        "volumeEmpote"   => $key['volumeEmpote'],
                                        "colisEmpote"    => $key['colisEmpote'],
                                        'client_id' => request('id'),
                                        'commandes' => $key['commandes']
                                        ];

        } 

        return response([
            "code" => 0,
            "data" => $detailsEmpoatge
        ]);
   }

   public function updateDouane(){
        
        Reception::where('reidre', request('id'))
          ->update([
            "douane" => request('douane')
        ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);

   }

   public function setEmpotage(){
        Empotage::where('id', request('id'))
              ->update([
                "poidEmpote"   => request('poidsEmpote'),
                "volumeEmpote" => request('volumeEmpote'),
                "colisEmpote"  => request('colisEmpote'),
                "commandes"    => json_encode(request('cmds'))
        ]);
        // change status 
        if(request('isChecked')==1){
            Reception::where('rencmd', request('cmdSelected'))->update([
                "isLoad"    => true
            ]); 
        }else{

            Reception::where('rencmd', request('cmdSelected'))->update([
                "isLoad"    => false
            ]); 
        }
        
        return response([
            "code" => 0
        ]);
      
   }

   public function cloturer(){
         Empotage::where('id', request('ref'))
              ->update([
                "reetat"   => 1
        ]);

        return response([
            "code" => 0
        ]);
   }
}
