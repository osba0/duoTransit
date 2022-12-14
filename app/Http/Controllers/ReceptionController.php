<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReceptionResource;
use App\Http\Resources\TypeCommandeResource;
use Illuminate\Support\Facades\Notification;
use App\Notifications\receptionCommandes;

use App\Models\Reception;
use App\Models\TypeCommande;
use App\Models\Client;
use App\Models\Entrepot;
use App\Models\Fournisseur;
use App\Models\UserRole;
use App\Models\TypActivity;
use App\Models\LogActivity;
use App\Models\Entite;
use App\Models\User; 
use App\Models\ImportCommandes;
use Illuminate\Http\Request;

use App\Http\Resources\ClientResource;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use DB;



class ReceptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //If user is not logged in then he can't access this page
    }
   
    public function index(){

        $user = Auth::user();

        if(is_null($user)){
            return  redirect(route('login'));
        }

        if(!($user->hasRole(UserRole::ROLE_USER)) && !($user->hasRole(UserRole::ROLE_ADMIN))){
             abort(401);
        }

        $client = Client::where('slug', request('slug'))->whereJsonContains('clenti', Auth::getUser()->entites_id)->get()->first();
        if(!$client){
            abort(404);
        }
        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get(); 

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->where("foetat", true)->get(); 

        $entrepots = Entrepot::get(); 

        if(is_null($client)){
            $data = ['logo' => '', 'id_client' => ''];
        }else{
            $data = ['logo' => $client->cllogo, 'id_client' => $client->id, 'client'=>$client, 'typeCmd' => $typeCmd, 'fournisseurs' => $fournis, 'entrepots' => $entrepots];
        }

        activity(TypActivity::LISTER)->performedOn($client)->log('Affichage de la page réception');
        $lastID = LogActivity::latest('id')->first();
        $query = LogActivity::where("id", $lastID['id'])->update(["subject_type" => $user->entites_id]);
        
        return  view('backend.reception.receptions', $data);
   }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listeDries()
    {
        $user = Auth::user();

        $paginate = request('paginate');

        $keyword = request('keysearch');
        
        $dries = Reception::receptionsQuery();
        
        if($keyword!=''){
            $dries = $dries->search($keyword); 
        }

        $dries = $dries->where('receptions.clients_id', request('id'))->where('receptions.entites_id', request('entite'))->where(function($query){
                $query->orWhere('dossier_id', NULL)->orWhere('dossier_id', 0);
            })->where(function($query){
                $query->orWhere('dossier_empotage_id', NULL)->orWhere('dossier_empotage_id', 0);
            })->where(function($query){
                $query->orWhere('dossier_prechargements_id', NULL)->orWhere('dossier_prechargements_id', 0);
            })->leftJoin('users', 'receptions.users_id', '=', 'users.id')
            ->select('*','users.username as user_created');

        // Profil User lister que ses receptions

        if($user->hasRole(UserRole::ROLE_USER)){
             $dries->where("users_id", $user->id);
        }
        

        if(isset($paginate)) {
            $dries = $dries->paginate($paginate);
        }else{
            $dries = $dries->get();
        }
      
        return ReceptionResource::collection($dries);

    }

    public function stateDries()
    {
        $recep = Reception::receptionsQuery()->where('clients_id', request('id'))->where('receptions.entites_id', request('entite'));

        $keyword = request('keysearch');

        if($keyword!=''){
            $recep = $recep->search($keyword); 
        }

        $recep = $recep->where(function($query){
                $query->orWhere('dossier_id', NULL)->orWhere('dossier_id', 0);
            })->where(function($query){
                $query->orWhere('dossier_empotage_id', NULL)->orWhere('dossier_empotage_id', 0);
            })->where(function($query){
                $query->orWhere('dossier_prechargements_id', NULL)->orWhere('dossier_prechargements_id', 0);
            })->where('reetat', true)->get();
      

        $client = Client::where('id', request('id'))->whereJsonContains('clenti', Auth::getUser()->entites_id)->get()->first();
        if(!$client){
            abort(404);
        }
        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get(); 
       

        // Nbre jour moyen
        $diff_in_days = 0;
        $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
        foreach ($recep as $key) {
            $from = \Carbon\Carbon::createFromFormat('Y-m-d', $key->redali);
            $diff_in_days += $today->diffInDays($from);
        }
      
       return response([
            "poidsTotal"  => $recep->sum->repoid,
            "volumeTotal" => $recep->sum->revolu,
            "nbreColis"   => $recep->sum->renbcl + $recep->sum->renbpl,
            "nbrJourMoyen" => sizeof($recep)!=0? ceil($diff_in_days/sizeof($recep)):0,
            "commandesTotal" => $recep->count(),
            "typeCmd" => TypeCommandeResource::collection($typeCmd)
        ]);

    }

     public function check()
    {
        $check = Reception::where('fournisseurs_id', request('fournisseur'))->where('entrepots_id', request('entrepot'))->where('rencmd', request('numCommande'))->where('renufa', request('numfact'))->get();
        
        if(sizeof($check) > 0){
            return response([
                "code" => 1
            ]);
        }else{
             return response([
                "code" => 0
            ]);
        }

         
    }
   public function create(Request $request)
    {
        $user = Auth::user();

        try{

            $filename = '';

            if(!is_null($request->file('file'))){

                $file = $request->file('file');


                $current_date_time = Carbon::now()->toDateTimeString();
                $paseDate = explode(' ', $current_date_time);

                $filename = 'fact_'.explode('.', $request->file('file')->getClientOriginalName())[0].'_'.request('numfact').'_'.$paseDate[0].'_'.str_replace(":","-",$paseDate[1]).'.'.$file->getClientOriginalExtension();
               
                $request->file->move("assets/factures/", $filename);
            }

            $params = [
                "refere" => request('fe'),
                "reecvr" => request('ecv'),
                "renufa" => request('numfact'),
                "revafa" => request('montfact'),
                "fournisseurs_id" => request('fournisseur'),
                "type_commandes_id" => request('type_commande'),
                "clients_id" => request('client'),
                "entrepots_id" => request('entrepot'),
                'users_id' => $user->id,
                "repoid" => request('poidstotal'),
                "revolu" => str_replace(",",".", request('volumetotal')),
                "renbcl" => request('nbrcolis'),
                
                "reemba" => request('emballage'),
                "reempl" => request('emplacemnt'),
                "redali" => request('dateliv'),
                'rencmd' => request('numCommande'),
                'renbpl' => request('nbrpalette'),
                'recomt' => request('commentaire'),
                'refasc' => $filename,
                "entites_id" => request('IDentite'),
                //"recmds" => json_encode(request('commandes')),
                "reetat" => true,
                "regroup" => array_unique(json_decode(request('group')))
            ];
            
            Reception::setIDClient(request('client'), request('IDentite'));  
          
            $store = Reception::create($params);

            // Update table import commande
            $cmd =  ImportCommandes::where('commandes','=',request('numCommande'))->first(); 
            
            if($cmd){
                $cmd->update([
                    "etat_cmd" => 1
                ]);
            }

        }catch(\Exceptions $e){
              return response([
                "code" => 1,
                "message" => $e->getMessage()
            ]);
        }

        return response([
            "code" => 0,
            "message" => $params
        ]);

        /*if($store){
            
            // Notifier
            $transitaire = Entite::where('id', $user->entites_id)->get()->first();
            $societe = Client::where('id', request('client'))->get()->first();

            // add fournisseur
            $params["fournisseur"] = Fournisseur::where('id', $params['fournisseurs_id'])->get()->first()['fonmfo'];
            $params["entrepot"] = Entrepot::where('id', $params['entrepots_id'])->get()->first()['nomEntrepot'];
            $params["typeCmd"] = TypeCommande::where('id', $params['type_commandes_id'])->get()->first()['typcmd'];


            $getMailClient = User::where("entites_id", $transitaire['id'])->whereJsonContains('roles', UserRole::ROLE_CLIENT)->whereJsonContains('client_supervisor', intval(request('client')))->get();

            $emailSent=[];

            foreach($getMailClient as $user){
            
                $emailSent[] = $user['email'];
            }
            
            $pathFile = "assets/factures/". $filename;

            Notification::send($getMailClient, new receptionCommandes($transitaire, $societe, $emailSent, $params, $pathFile));

            return response([
                "code" => 0,
                "message" => "OK"
            ]);
        }else{
            return response([
                "code" => 1,
                "message" => "KO"
            ]);
        }*/

        
    }

    public function sendNotificationReception(){
        $user = Auth::user();

        $params = request('data');
        // Notifier
        $transitaire = Entite::where('id', $user->entites_id)->get()->first();
        $societe = Client::where('id', $params['clients_id'])->get()->first();

        // add fournisseur
        $params["fournisseur"] = Fournisseur::where('id', $params['fournisseurs_id'])->get()->first()['fonmfo'];
        $params["entrepot"] = Entrepot::where('id', $params['entrepots_id'])->get()->first()['nomEntrepot'];
        $params["typeCmd"] = TypeCommande::where('id', $params['type_commandes_id'])->get()->first()['typcmd'];


        $getMailClient = User::where("entites_id", $transitaire['id'])->whereJsonContains('roles', UserRole::ROLE_CLIENT)->whereJsonContains('client_supervisor', intval($params['clients_id']))->get();

        $emailSent=[];

        foreach($getMailClient as $user){
        
            $emailSent[] = $user['email'];
        }
        
        $pathFile = "assets/factures/". $params['refasc'];

        Notification::send($getMailClient, new receptionCommandes($transitaire, $societe, $emailSent, $params, $pathFile, $params['refasc']));
    }

    public function modify(Request $request)
    {
        $user = Auth::user();

        $filename = '';

        if(!is_null($request->file('file')) && request('nameFacture')==''){

            $file = $request->file('file');

            //$filename = 'fact_'.time().'.'.$file->getClientOriginalExtension();

            $current_date_time = Carbon::now()->toDateTimeString();
            $paseDate = explode(' ', $current_date_time);

            $filename = 'fact_'.explode('.', $request->file('file')->getClientOriginalName())[0].'_'.request('numfact').'_'.$paseDate[0].'_'.str_replace(":","-",$paseDate[1]).'.'.$file->getClientOriginalExtension();
           
            $request->file->move("assets/factures/", $filename);
        }else{
            $filename = request('nameFacture');
        }

        //Reception::setIDClient(request('client'), $user->entites_id);

        $cmd =  Reception::where('reidre','=',request('reidre'))->firstOrFail(); 
        Reception::setIDClient(request('client'), request('IDentite'));     
        $cmd->update([
                "refere" => request('fe'),
                "reecvr" => request('ecv'),
                "renufa" => request('numfact'),
                "revafa" => request('montfact'),
                "fournisseurs_id" => request('fournisseur'),
                "type_commandes_id" => request('type_commande'),
                "clients_id" => request('client'),
                "entrepots_id" => request('entrepot'),
                'users_id' => $user->id,
                "repoid" => request('poidstotal'),
                "revolu" => request('volumetotal'),
                "renbcl" => request('nbrcolis'),
                "reemba" => request('emballage'),
                "reempl" => request('emplacemnt'),
                "redali" => request('dateliv'),
                'rencmd' => request('numCommande'),
                'renbpl' => request('nbrpalette'),
                'recomt' => request('commentaire'),
                'refasc' => $filename,
                "reetat" => true

          ]);


       

        //activity(TypActivity::MODIFIER)->withProperties($updated->attributeValuesToBeLogged())->log('Modification réception');

          return response([
                "code" => 0,
                "message" => "OK"
            ]);
    }

     public function delete(Request $request)
    {
        $user = Auth::user();
        $cmd =  Reception::where('reidre','=',request('id'))->firstOrFail(); 
        Reception::setIDClient(request('idClient'), $user->entites_id);
        $cmd->delete();

          return response([
                "code" => 0,
                "message" => "OK"
            ]);
    }

    public function setRate(Request $request)
    {
        $user = Auth::user();
        $cmd =  Reception::where('reidre','=',request('idReception'))->firstOrFail(); 
        
        $cmd->update(["priorite" => request('rate')]);

          return response([
                "code" => 0,
                "message" => "OK"
            ]);
    }


    
}
