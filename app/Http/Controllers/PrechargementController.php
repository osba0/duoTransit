<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\DossierPrechargementResource;
use App\Http\Resources\ReceptionResource;

use App\Models\TypeCommande;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\DossierPrechargement;
use App\Models\Contenaire;
use App\Models\Reception;
use App\Models\Entite;

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

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->get(); 

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->get(); 

        $contenaires = Contenaire::whereIn('id',$entite->contenaires_id)->get(); 

        $defaultContenaire = Contenaire::get()->where("isdefault", true)->first(); 

        if(is_null($client)){
            $data = ['logo' => '', 'id_client' => ''];
        }else{
            $data = ['logo' => $client->cllogo, 'id_client' => $client->id, 'typeCmd' => $typeCmd, 'fournisseurs' => $fournis, 'defaultContenaire' => $defaultContenaire, 'listContenaire' => $contenaires];
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
                    "reetat"            => 1,
                    "users_id"          => Auth::user()->id,
                    "entites_id"        => Auth::user()->entites_id,
                    "type_commandes_id" => request('typeCmd')
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

        $sql = "";

      

        if (isset($paginate)) {

            $query = DB::table('dossier_prechargements')
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
                'dossier_prechargements.nbreContenaire', 
                'dossier_prechargements.id as idPre', 
                'dossier_prechargements.users_id', 
                'dossier_prechargements.created_at as created_at_pre',
                'dossier_prechargements.reetat as etat',
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'contenaires.nom as contenaire')->where('dossier_prechargements.entites_id', $user->entites_id);

            if(request('key')==1){
                 $query = $query->where('dossier_prechargements.reetat', 1); 
            }

            $query = $query->paginate($paginate);
        }else{
            $query = DB::table('dossier_prechargements')
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
                'dossier_prechargements.nbreContenaire', 
                'dossier_prechargements.id as idPre', 
                'dossier_prechargements.users_id', 
                'dossier_prechargements.created_at as created_at_pre',
                'dossier_prechargements.reetat as etat',
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'contenaires.nom as contenaire' )->where('dossier_prechargements.entites_id', $user->entites_id)
            ->get();
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

        if (isset($paginate)) {
            $dries = Reception::receptionsQuery()->where('entites_id', $user->entites_id)->where('dossier_prechargements_id', request('idPre'))->orWhere('dossier_prechargements_id', 0)->orWhere('dossier_prechargements_id', NULL)->paginate($paginate);
        }else{
            $dries = Reception::where('clients_id', request('id'))->where('entites_id', $user->entites_id)->orderBy('redali', 'asc')->get();
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
                                  'total_cmd'      => $key->total_cmd];

        }
        return response([
            "code" => 0,
            "result" => $details
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
