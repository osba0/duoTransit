<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\DossierPrechargementResource;
use App\Http\Resources\ReceptionResource;

use Illuminate\Support\Facades\Notification;
use App\Notifications\prechargementCommandesClient;

use App\Models\TypeCommande;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\DossierPrechargement;
use App\Models\Contenaire;
use App\Models\Reception;
use App\Models\Entite;
use App\Models\User;
use App\Models\UserRole; 
use App\Models\TypActivity;
use App\Models\LogActivity;
use App\Models\Entrepot;



use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class PrechargementController extends Controller
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

        $entite = Entite::where('id', $user->entites_id)->get()->first();



        $client = Client::where('slug', request('id'))->whereJsonContains('clenti', Auth::getUser()->entites_id)->get()->first();
        if(!$client)  abort(404);

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get();  

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->get(); 

        $contenaires = Contenaire::whereIn('id',(array) $entite->contenaires_id)->get(); 

        $defaultContenaire = Contenaire::get()->where("isdefault", true)->first(); 

        $nbrCmdACharger =  DB::table('receptions')->where('receptions.clients_id', $client['id'])
                 ->select('type_commandes_id', DB::raw('count(*) as total'))
                 ->groupBy('type_commandes_id')->where(function($query){
                        $query->orWhere('dossier_id', 0)->orWhere('dossier_id', NULL);
                        
                        })->where(function($query){
                        $query->orWhere('dossier_prechargements_id', 0)->orWhere('dossier_prechargements_id', NULL);
                        
                        })
                 ->get();  

        

        $entrepots = Entrepot::get(); 

        if(is_null($client)){
            $data = ['logo' => '', 'id_client' => ''];
        }else{
            $data = ['logo' => $client->cllogo, 'id_client' => $client->id, 'client' => $client , 'typeCmd' => $typeCmd, 'fournisseurs' => $fournis, 'defaultContenaire' => $defaultContenaire, 'listContenaire' => $contenaires, "entite" => $entite, "entrepots" => $entrepots, 'nbrCmdACharger' => $nbrCmdACharger];
        }
        
        return  view('backend.prechargement.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // get default contenaire
        $defaultContenaire = Contenaire::get()->where("isdefault", true)->first(); 
        
        if($defaultContenaire['id']){
            try{   
                  
                $store = DossierPrechargement::create([
                    "contenaires_id"    => $defaultContenaire['id'],
                    "reetat"            => 0,
                    "users_id"          => Auth::user()->id,
                    "clients_id"        => request('clientID'),
                    "type_commandes_id" => request('typeCmd'),
                    "entrepots_id"      => request('entrepot')
                ]); 
                return response([
                    "code" => 0,
                    "message" => ""
                ]);

            }catch(\Exceptions $e){
                  return response([
                    "code" => 1,
                    "message" => $e->getMessage()
                ]);
            }
        }else{
             return response([
                    "code" => 1,
                    "message" => "Pas de contenaire par defaut trouvé"
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

        $sql = "";

        if (isset($paginate)) {

            $query = DB::table('dossier_prechargements')
            ->leftJoin('receptions', 'receptions.dossier_prechargements_id', '=', 'dossier_prechargements.id')
            ->leftJoin('users', 'dossier_prechargements.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'dossier_prechargements.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('contenaires', 'dossier_prechargements.contenaires_id', '=', 'contenaires.id')
            ->leftJoin('entrepots', 'dossier_prechargements.entrepots_id', '=', 'entrepots.id')
            ->select('receptions.dossier_prechargements_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd'), 
                DB::raw('SUM(receptions.revafa) as total_mnt'), 
                'dossier_prechargements.nbreContenaire', 
                'dossier_prechargements.id as idPre', 
                'dossier_prechargements.users_id', 
                'dossier_prechargements.created_at as created_at_pre',
                'dossier_prechargements.reetat as etat',
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'type_commandes.id as typecmdID',
                'type_commandes.tcolor as typecmdColor',
                'entrepots.id as entrepots_id',
                'entrepots.nomEntrepot as entrepots_name',
                'contenaires.nom as contenaire')->groupBy('dossier_prechargements.id', 'receptions.dossier_prechargements_id')->where('dossier_prechargements.clients_id', request('id'));

            if($keyword!=''){
                $term = "%$keyword%";

                $query = $query->where(function ($query) use ($term) {
                    $query->where('dossier_prechargements.id', 'like', $term);
                });
            }

            if($typeCommande!=''){
                $query = $query->where('dossier_prechargements.type_commandes_id', $typeCommande);
            }

            if($etatFiltre!=''){
                $query = $query->where('dossier_prechargements.reetat', $etatFiltre);
            }else{
                $query = $query->where('dossier_prechargements.reetat', 0);
            }

            $query = $query->orderBy('dossier_prechargements.id','DESC');
            
            $query = $query->paginate($paginate);

        }else{
           
        }
      
        return DossierPrechargementResource::collection($query);
    }

    /**
     * Store a newly created resource in list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listingReception()
    {
        $user = Auth::user();

        $paginate = request('paginate');


        $keyword = request('keysearch');

        $filtreRate = request('rate');
        
        $sort = request('column');
        

        if (isset($paginate)) {

            $dries = Reception::where('clients_id', request('id'))->where(function($query){
                     
                $query->orWhere('dossier_prechargements_id', request('idPre'))->orWhere('dossier_prechargements_id', 0)->orWhere('dossier_prechargements_id', NULL);
                })->where(function($query2){

                    $query2->orWhere('dossier_id', 0)->orWhere('dossier_id', NULL)->orWhere('dossier_prechargements_id', request('idPre'));
                })->where('type_commandes_id', request('typecmd'))->where('entrepots_id', request('entrepotID'));

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
            
            $dries = $dries->orderBy('redali', 'asc')->paginate($paginate);

        }else{
           
        }
      
        return ReceptionResource::collection($dries);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function precharger()
    {
        $value=0;
        if(request('ischecked')==1){
            $value=request('idPrehargement');
        }
        Reception::where('reidre', request('idreception'))
          ->update([
            "dossier_prechargements_id" => $value
        ]);

        $results = DB::table('dossier_prechargements')
            ->leftJoin('receptions', 'receptions.dossier_prechargements_id', '=', 'dossier_prechargements.id')
            ->leftJoin('users', 'dossier_prechargements.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'dossier_prechargements.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('contenaires', 'dossier_prechargements.contenaires_id', '=', 'contenaires.id')
            ->groupBy('dossier_prechargements.id')
            ->select('receptions.dossier_prechargements_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd'), 
                DB::raw('SUM(receptions.revafa) as total_mnt'), 
                'dossier_prechargements.nbreContenaire', 
                'dossier_prechargements.id as idPre', 
                'dossier_prechargements.users_id', 
                'dossier_prechargements.created_at as created_at_pre',
                'dossier_prechargements.reetat as etat',
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'contenaires.nom as contenaire' )->where('dossier_prechargements.id', request('idPrehargement'))->get();

            //$empotage = ReceptionResource::collection($results);
    
        foreach ($results as $key) {
           
           $details[] = (object) ['total_poids'    => $key->total_poids,
                                  'total_volume'   => $key->total_volume,
                                  'total_palette'  => $key->total_palette,
                                  'total_colis'    => $key->total_colis,
                                  'total_cmd'      => $key->total_cmd,
                                  'total_mnt'      => $key->total_mnt];

        }
        return response([
            "code" => 0,
            "result" => $details
        ]);
    }

    public function valider(){

        $user = Auth::user();

        $client = Client::where('id', request('id'))->whereJsonContains('clenti', Auth::getUser()->entites_id)->get()->first();
        if(!$client){
            abort(404);
        }

        $response = true;

        if(sizeof(request('idsCmd')) > 0){

            $rep = DossierPrechargement::where('id',request('id_prechargement'))->update([
                "reetat" => true,
                "nbreContenaire"=> request('nbrContenaire'),
                "contenaires_id"=> request('typeContenaire')
            ]);

            // Table reception déja mis a jour avec l'id prechargement

            // Journal Associer les commandes à un numero de dossier

            activity(TypActivity::MODIFIER)->withProperties(request('idsCmd'))->performedOn($client)->log('Validation préchargement client n°'.request('id_prechargement'));
            $lastID = LogActivity::latest('id')->first();
            $query = LogActivity::where("id", $lastID['id'])->update(["subject_type" => $user->entites_id]);

        }

        

        if($response){
            return response([
                "code" => 0,
                "result" => 'Success'
            ]);

        }else{
            return response([
            "code" => 1,
            "result" => "Ehec"
            ]);
        }
    }


    public function notifier(){
        $user = Auth::user();
        
        $base64_pdf = trim(request('base64_file_pdf'), "data:application/pdf;base64,");
        $base64_decode = base64_decode($base64_pdf);
        $pathFile = 'pdf/prechargementClient/prechargement-'.request('id_prechargement').'_'.request('typeCmd').'.pdf';
        $pdf = fopen($pathFile, 'w');
        fwrite($pdf, $base64_decode);
        fclose($pdf);

       
        // Notification

        $transitaire = Entite::where('id', $user->entites_id)->get()->first();
        $societe = Client::where('id', request('id'))->get()->first();

        $commandes = Reception::whereIn('reidre',request('idsCmd'));


        $getMailTransitaire = User::where("entites_id", $transitaire['id'])->whereJsonContains('roles', UserRole::ROLE_ADMIN)->get();

        $emailSent=[];

        foreach($getMailTransitaire as $user){
        
            $emailSent[] = $user['email'];
        }

        //$response = Notification::route('mail', [])->notify(new prechargementCommandesClient($transitaire, $societe, $emailSent, $commandes, $pathFile, request('id_prechargement'))); 

        $response = Notification::send($getMailTransitaire, new prechargementCommandesClient($transitaire, $societe, $emailSent, $commandes, $pathFile, request('id_prechargement'))); 

        $rep = [
            "code" => 0,
            "message" => "OK"
        ];

        return response($rep);

    }

    public function getCommande(){
        $results = Reception::where('dossier_prechargements_id',request('id'))->orderBy('priorite', 'DESC')->get(); 
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
                                  'fournisseurs'   => $key->fournisseur->fonmfo,
                                  'priorite'       => $key->priorite];
   
        }
        return response([
            "code" => 0,
            "result" => $details
        ]);
    }

    public function deletePre(){
        $user = Auth::user();
        $res = DossierPrechargement::select('id')->where('id', request('id'))->get()->toArray();
        $ids = [];

        foreach($res as $item){
            $ids[] = $item['id'];
            
        }

        Reception::whereIn('dossier_prechargements_id', $ids)->update([
            "dossier_prechargements_id" => NULL
        ]);



        $dossier =  DossierPrechargement::where('id','=',request('id'))->where('type_commandes_id', request('typeCmd'))->firstOrFail(); 

        DossierPrechargement::setIDClient(request('clientID'), $user->entites_id); 

        $dossier->delete();
    

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }
}
