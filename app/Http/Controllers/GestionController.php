<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\DossierRessource;
use App\Http\Resources\ReceptionResource;
use Illuminate\Support\Facades\Notification;
use App\Notifications\prechargementCommandesTransitaire;

use App\Models\TypeCommande;
use App\Models\Client;
use App\Models\Empotage;
use App\Models\Fournisseur;
use App\Models\DossierPrechargement;
use App\Models\Contenaire;
use App\Models\Reception;
use App\Models\Entite;
use App\Models\ChargementCreation;
use App\Models\TypActivity;
use App\Models\LogActivity;
use App\Models\Entrepot; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserRole;
use App\Models\User;


use Spatie\Activitylog\Contracts\Activity;

class GestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //If user is not logged in then he can't access this page
       
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

         if(is_null($user)){
            return  redirect(route('login'));
        }

        if(!($user->hasRole(UserRole::ROLE_ADMIN) || $user->hasRole(UserRole::ROLE_ROOT))){
             abort(401);
        }

        $entite = Entite::where('slug', request('currententite'))->get()->first();


        $client = Client::where('slug', request('id'))->whereJsonContains('clenti', $entite->id)->get()->first();

        if(!$client)  abort(404);

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get(); 

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->get(); 

        $contenaires = Contenaire::whereIn('id', (array) $entite->contenaires_id)->get(); 

        $defaultContenaire = Contenaire::get()->where("isdefault", true)->first();

        $nbrCmdACharger =  DB::table('receptions')->where('receptions.clients_id', $client['id'])->where("receptions.entites_id", $entite->id)->leftJoin('dossier_prechargements', 'receptions.dossier_prechargements_id', '=', 'dossier_prechargements.id')
                 ->select('receptions.type_commandes_id', DB::raw('count(*) as total'))
                 ->groupBy('receptions.type_commandes_id')->whereNotNull('dossier_prechargements_id')->where(function($query){
                        $query->orWhere('dossier_id', request('idPre'))->orWhere('dossier_id', 0)->orWhere('dossier_id', NULL);
                        
                        })->where("dossier_prechargements.reetat", true)
                 ->get();  

        $entrepots = Entrepot::get(); 

        if(is_null($client)){
            $data = ['logo' => '', 'id_client' => ''];
        }else{
            $data = ['logo' => $client->cllogo, 'id_client' => $client->id, 'client' => $client ,'typeCmd' => $typeCmd, 'fournisseurs' => $fournis, 'defaultContenaire' => $defaultContenaire, 'listContenaire' => $contenaires, "entite" => $entite, "entrepots" => $entrepots, 'nbrCmdACharger' => $nbrCmdACharger];
        }

        activity(TypActivity::LISTER)->performedOn($client)->log('Affichage validation préchargement');
        $lastID = LogActivity::latest('id')->first();
        $query = LogActivity::where("id", $lastID['id'])->update(["subject_type" => $entite->id]);
        
        
        return  view('backend.gestion.prechargement', $data);
    }

    public function create(Request $request)
    {
         $user = Auth::user();
         // Check num dossier exist or not

        $check = ChargementCreation::where('numDossier', request('numdossier'))->where('type_commandes_id', request('typeCmd'))->get();
        
        if(sizeof($check) > 0){
            return response([
                "code" => 1,
                "message" => "Numéro dossier existe déja!"
            ]);
        }
        try{    
            ChargementCreation::setIDClient(request('clientID'), request('entiteID')); 

            $store = ChargementCreation::create([
                "numDossier"           => request('numdossier'),
                "dateDebut"            => request('datedebut'),
                "dateCloture"          => request('datecloture'),
                "booking"              => request('booking'),
                "nomNavire"            => request("nomnavire"),
                "dateDepart"           => request('datedepart'),
                "dateArrivee"          => request('datearrivee'),
                "terminalRetour"       => request('termretour'),
                "clients_id"           => request('clientID'),
                "users_id"             => $user->id,
                "type_commandes_id"    => request('typeCmd'),
                "entrepots_id"         => request('entrepot'),
                "entites_id"           => request('entiteID'),
                "is_empote"               => false,
                "reetat"               => false
            ]); 

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

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $user = Auth::user();

        $paginate = request('paginate');

        $keyword = request('keysearch');
        $typeCommande = request('typeCmd');
        $etatFiltre = request('etatFiltre');

        if (isset($paginate)) {

            $pre = DB::table('chargement_creations')
            ->leftJoin('receptions', 'receptions.dossier_id', '=', 'chargement_creations.numdossier')
            ->leftJoin('users', 'chargement_creations.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'chargement_creations.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('entrepots', 'chargement_creations.entrepots_id', '=', 'entrepots.id')
            ->groupBy('chargement_creations.id')
            ->select('receptions.dossier_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd'), 
                'chargement_creations.id', 
                'chargement_creations.numdossier', 
                'chargement_creations.users_id', 
                'chargement_creations.dateDebut',
                'chargement_creations.dateCloture',
                'chargement_creations.dateDepart',
                'chargement_creations.dateArrivee',
                'chargement_creations.booking',
                'chargement_creations.nomNavire',
                'chargement_creations.terminalRetour',
                'chargement_creations.reetat as etat',
                'chargement_creations.created_at as creation_dos',
                'users.username as user',
                'entrepots.nomEntrepot as nomEntrepot', 
                'entrepots.id as idEntrepot',
                'type_commandes.id as idTypeCmd',
                'type_commandes.tcolor as tcolor',
                'type_commandes.typcmd as typecmd')->where('chargement_creations.is_empote', false)->where('chargement_creations.clients_id', request('id'))->orderBy('chargement_creations.created_at', 'desc');
            if($keyword!=''){
                $term = "%$keyword%";

                $pre = $pre->where(function ($query) use ($term) {
                    $query->where('numDossier', 'like', $term);
                });
            }
            if($typeCommande!=''){
                $pre = $pre->where('chargement_creations.type_commandes_id', $typeCommande);
            }
            if($etatFiltre!=''){
                $pre = $pre->where('chargement_creations.reetat', $etatFiltre);
            }

            $pre = $pre->where('chargement_creations.entites_id', intval(request('entite')));

            
            $pre = $pre->paginate($paginate);
        }else{
          
        }

        
      
      
        return DossierRessource::collection($pre);
    }

    public function listingReception()
    {
        $user = Auth::user();

        $paginate = request('paginate');

        $keyword = request('keysearch');
        
        $sort = request('column');

        $filtreRate = request('filtreRate');

        if (isset($paginate)) {

            $dries = Reception::where('receptions.clients_id', request('id'))->where(function($query){
                if(request('etat')==1){
                    $query->where('dossier_id', request('idPre'));
                }else{
                    $query->orWhere('dossier_id', request('idPre'))->orWhere('dossier_id', 0)->orWhere('dossier_id', NULL);
                }
                
                })
            ->leftJoin('dossier_prechargements', 'dossier_prechargements.id', '=', 'receptions.dossier_prechargements_id')
            ->leftJoin('users as a', 'dossier_prechargements.users_id', '=', 'a.id')
            ->leftJoin('users as b', 'receptions.users_id', '=', 'b.id')
            ->select('*','b.username as user_created','a.username as prechargeur')->where("dossier_prechargements.reetat", true)->where('receptions.type_commandes_id', request('typecmd'))->where('receptions.entites_id', intval(request('entite')))->where('receptions.entrepots_id', request('idEntrepot')); 

            if($keyword!=''){
                $dries = $dries->search($keyword);
            }

            if($filtreRate!=''){

                $dries = $dries->filtreRate($filtreRate); 
            }

            if($sort!=''){
                $order = request('order');
              
                $dries = $dries->orderBy(strval($sort), $order);
            }else{
                $dries = $dries->orderBy("receptions.redali", "DESC"); 
            }
            
            $dries = $dries->paginate($paginate);

        }else{
           // $dries = Reception::where('clients_id', request('id'))->orderBy('redali', 'asc')->get();
        }
      
        return ReceptionResource::collection($dries);

    }


     public function precharger()
    {
        $value=0;
        if(request('ischecked')==1){
            $value=request('idPrehargement');
        }
        

        Reception::whereIn('reidre', request('listCmdNoSelect'))
          ->update([
            "dossier_id" => null
        ]);

        Reception::where('reidre', request('idreception'))
          ->update([
            "dossier_id" => $value
        ]);

        


        $results = DB::table('receptions')->select(DB::raw("SUM(repoid) as total_poids")
                              ,DB::raw("SUM(revolu) as total_volume")
                              ,DB::raw("SUM(renbcl) as total_colis")
                              ,DB::raw("SUM(renbpl) as total_palette"))->whereIn('reidre',request('listCmd'))->where('receptions.entites_id', request('entite'))->get();



            $details=[];

         
      
        foreach ($results as $key) {
            $details[] = (object) [
              'total_poids'   => $key->total_poids,
              'total_volume'   => $key->total_volume,
              'total_palette'  => $key->total_palette,
              'total_colis'    => $key->total_colis,
             // 'total_cmd'      => $key->total_cmd
            ];         
        }
        if(sizeof($details)==0){
            $details[] = (object) [
              'total_poids'   => 0,
              'total_volume'   => 0,
              'total_palette'  => 0,
              'total_colis'    => 0
            ];         
        }
        return response([
            "code" => 0,
            "result" => $details
        ]);
    }
    public function getCommande(){
        $results = Reception::where('dossier_id',request('id'))->where('type_commandes_id', request('typecommande'))->get(); 
        $details=[];

        foreach ($results as $key) {
           
           $details[] = (object) ['refere'         => $key->refere,
                                  'reecvr'         => $key->reecvr,
                                  'rencmd'         => $key->rencmd,
                                  'repoid'         => $key->repoid,
                                  'renufa'         => $key->renufa,
                                  'renbcl'         => $key->renbcl,
                                  'renbpl'         => $key->renbpl,
                                  'revolu'         => $key->revolu,
                                  'renufa'         => $key->renufa,
                                  'priorite'       => $key->priorite,
                                  'fournisseurs'   => $key->fournisseur->fonmfo];
   
        }
        return response([
            "code" => 0,
            "result" => $details
        ]);
    }

    public function valider(){

        $user = Auth::user();

        $client = Client::where('id', request('id'))->whereJsonContains('clenti', intval(request('entite')))->get()->first();
        if(!$client){
            abort(404);
        }

        $response = true;

        if(sizeof(request('idsCmd')) > 0){

            $rep = Reception::whereIn('reidre',request('idsCmd'))->update([
                "dossier_id" => request('id_dossier')
            ]);

            // Journal Associer les commandes à un numero de dossier

            activity(TypActivity::MODIFIER)->withProperties(request('idsCmd'))->performedOn($client)->log('Validation préchargement n°dossier'.request('id_dossier'));
            $lastID = LogActivity::latest('id')->first();
            $query = LogActivity::where("id", $lastID['id'])->update(["subject_type" => request('entite')]);

        }

        

        if($response){
            if(sizeof(request('ignored')) > 0){
                 $response = Reception::whereIn('reidre',request('ignored'))
                  ->update([
                    "dossier_id" => Null
                ]);
            }

            $resp = ChargementCreation::where('numDossier',request('id_dossier'))->where('type_commandes_id', request('typeCmd'))
              ->update([
                "reetat" => true
            ]);
             return response([
                "code" => 0,
                "result" => 'Success'
            ]);
         }else{
             return response([
                "code" => 1,
                "result" => $response
            ]);
         }
    }


    public function notifier(){
        $user = Auth::user();
        
        $base64_pdf = trim(request('base64_file_pdf'), "data:application/pdf;base64,");
        $base64_decode = base64_decode($base64_pdf);
        $pathFile = 'pdf/prechargement/dossier-'.request('id_dossier').'_'.request('typeCmd').'_du_'.request('date_debut').'_au_'.request('date_fin').'.pdf';
        $pdf = fopen($pathFile, 'w');
        fwrite($pdf, $base64_decode);
        fclose($pdf);

       
        // Notification

        $transitaire = Entite::where('id', intval(request('entite')))->get()->first();
        $societe = Client::where('id', request('id'))->get()->first();

        $commandes = Reception::whereIn('reidre',request('idsCmd'));


        //$getMailEntrepot = Entrepot::leftJoin('receptions', 'entrepots.id','=', 'receptions.entrepots_id')->select('email')->whereIn('reidre', request('idsCmd'))->distinct()->get();
        $getMailEntrepot = Entrepot::where('id',request('entrepot'))->get();

        $emailSent=[];

        foreach($getMailEntrepot as $entrepot){
            $emailSent[] = $entrepot['email'];
        }

        try{

            Notification::route('mail', [])->notify(new prechargementCommandesTransitaire($transitaire, $societe, $emailSent, $commandes, $pathFile, request('id_dossier'), request('date_debut'), request('date_fin'))); 
            $res = [
                "code" => 0,
                "result" => "OK"
            ];

        }catch (Exception $e) {
            $res = [
                "code" => 1,
                "result" => $e->getMessage()
            ];
            
        }

        return response($res);

       
    }

    public function deletePre(Request $request)
    {
        $user = Auth::user();
        $res = Empotage::select('id')->where('reference', request('id'))->get()->toArray();
        $ids = [];

        foreach($res as $item){
            $ids[] = $item['id'];
            
        }

        Reception::whereIn('dossier_empotage_id', $ids)->update([
            "dossier_empotage_id" => ''
        ]);

        Reception::where('dossier_id', request('id'))->update([
            "dossier_id" => ''
        ]);




        $dossier =  ChargementCreation::where('numDossier','=',request('id'))->where('type_commandes_id', request('typeCmd'))->firstOrFail(); 

        ChargementCreation::setIDClient(request('clientID'), intval(request('entite'))); 

        $dossier->delete();
    

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }
    public function reactiver(){
        $user = Auth::user();
        
        $dossier =  ChargementCreation::where('id','=',request('identifiant'))->firstOrFail(); 
        
        ChargementCreation::setIDClient(request('clientID'), intval(request('entite'))); 

        $rep = $dossier->update(["reetat" => false]);


        if($rep){
             $response = [
                "code" => 0,
                "message" => "OK"
            ];
        }else{
            $response = [
                "code" => 1,
                "message" => "Echec"
            ];
        }

        return response($response);
    }
    /*************************** Empotage *********************************/
     public function indexEmpotage()
    {
        $user = Auth::user();
        if(is_null($user)){
            return  redirect(route('login'));
        }
        if(!($user->hasRole(UserRole::ROLE_ADMIN) || $user->hasRole(UserRole::ROLE_ROOT))){
             abort(401);
        }

        $entite = Entite::where('slug', request('currententite'))->get()->first();



        $client = Client::where('slug', request('id'))->whereJsonContains('clenti',$entite->id)->get()->first();
        if(!$client)  abort(404);

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get(); 

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->get(); 

        $contenaires = Contenaire::whereIn('id', (array) $entite->contenaires_id)->get();  //json_decode($entite->contenaires_id, true)

        $defaultContenaire = Contenaire::get()->where("isdefault", true)->first(); 

        $entrepots = Entrepot::get(); 

        $listeDossier =  DB::table('chargement_creations')->where('chargement_creations.entites_id',$entite->id)
            ->leftJoin('empotages as empo', function($join){
                $join->on('empo.reference', '=', 'chargement_creations.numdossier');
                $join->on('empo.type_commandes_id','=','chargement_creations.type_commandes_id');    
            })->select('*','chargement_creations.type_commandes_id as type_commandes', 'chargement_creations.entrepots_id as entrepots', 'chargement_creations.id as idpre')->where(function($query){
                $query->orWhereNull('empo.reference')->orWhereNull('empo.type_commandes_id')->orWhereNull('empo.entrepots_id');

            })/*->where('chargement_creations.reetat', true)*/->get();

        if(is_null($client)){
            $data = ['logo' => '', 'id_client' => ''];
        }else{
            $data = ['logo' => $client->cllogo, 'id_client' => $client->id, 'client' => $client, 'typeCmd' => $typeCmd, 'fournisseurs' => $fournis, 'defaultContenaire' => $defaultContenaire, 'listContenaire' => $contenaires,"entite" => $entite, 'client' => $client, 'entrepots' => $entrepots, 'listeDossier' => $listeDossier];
        }

        activity(TypActivity::LISTER)->performedOn($client)->log('Affichage empotage');
        $lastID = LogActivity::latest('id')->first();
        $query = LogActivity::where("id", $lastID['id'])->update(["subject_type" => $entite->id]);
        
        return  view('backend.gestion.empotage', $data);
    }

}
