<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empotage;
use App\Models\Reception;
use App\Http\Resources\ReceptionResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EmpotageResource;
use App\Models\ChargementCreation;
use DB;

class EmpotageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //If user is not logged in then he can't access this page
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
            $query = DB::table('empotages')
            ->leftJoin('receptions', 'receptions.dossier_empotage_id', '=', 'empotages.id')
            ->leftJoin('users', 'empotages.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'empotages.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('contenaires', 'empotages.contenaires_id', '=', 'contenaires.id')
            ->groupBy('empotages.id')
            ->select('receptions.dossier_empotage_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd'), 
                'empotages.nbreContenaire', 
                'empotages.id as IdEmpotage', 
                'empotages.reference as numDossier', 
                'empotages.numContenaire as numContenaire', 
                'contenaires.nom as nomContenaire', 
                'contenaires.id as IDContenaire',
                'contenaires.volume as capacite',
                'empotages.users_id', 
                'empotages.plomb as plomb', 
                'empotages.is_close as cloture',
                'empotages.created_at as created_at_empotage',
                'empotages.reetat as etat',
                'empotages.created_at as created_at',
                'empotages.rapport_pdf as rapport_pdf',
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'type_commandes.tcolor as tcolor',
                'type_commandes.id as typecmdID',
                'contenaires.nom as contenaire')->where('empotages.clients_id', request('id'))->orderBy('empotages.created_at', 'desc');

            if(request('etatFiltre')!=""){
                 $query = $query->where('empotages.reetat', request('etatFiltre')); 
            }
            
            if(request('typeCmd')!=""){
                 $query = $query->where('empotages.type_commandes_id', request('typeCmd')); 
            }

            if(request('keysearch')!=""){
                $word = request('keysearch');
                $term = "%$word%";
                $query = $query->where('empotages.reference', 'like', $term)->orWhere('empotages.plomb', 'like', $term)->orWhere('empotages.numContenaire', 'like', $term); 
            }

            if(request('key')==1){
                 $query = $query->where('empotages.reetat', 1); 
            }

            $query = $query->paginate($paginate);
        }else{
            /*$query = DB::table('dossier_prechargements')
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
            ->get();*/
        }
      
        return EmpotageResource::collection($query);
    }

    public function  createEmpotage(Request $request){
        $store = Empotage::create([
            "reference" => request('reference'),
            "numContenaire" => request('tc'),
            "contenaires_id" => request('typetc'),
            "type_commandes_id" => request('typeCmd'),
            'plomb' => request('plomb'),
            "poidEmpote" => 0,
            "volumeEmpote" =>  0, 
            "colisEmpote" => 0,
            "clients_id" => request('idClient'), 
            "users_id" => Auth::user()->id,
            "reetat" => 0
        ]);
        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

     public function  modifyEmpotage(Request $request){

        $store = Empotage::where('id', request('id'))->update([
            "reference" => request('reference'),
            "numContenaire" => request('tc'),
            "contenaires_id" => request('typetc'),
            "type_commandes_id" => request('typeCmd'),
            'plomb' => request('plomb')
        ]);
        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    

    public function filterDossier(Request $request)
    {
        $data = ChargementCreation::where('numDossier', 'LIKE','%'.$request->keyword.'%')->get();
        return response()->json($data); 
    }


    public function listingReceptionEmpotage()
    {
        $user = Auth::user();

        $paginate = request('paginate');

        if (isset($paginate)) {

            $dries = Reception::where('dossier_id', request('ref'))->where(function($query){
                            $query->orWhere('dossier_empotage_id', request('id_empotage'))->orWhere('dossier_empotage_id', 0)->orWhere('dossier_empotage_id', NULL);
                        })
            ->leftJoin('empotages', 'empotages.id', '=', 'receptions.dossier_empotage_id')
            ->leftJoin('users as a', 'empotages.users_id', '=', 'a.id')
            ->leftJoin('users as b', 'receptions.users_id', '=', 'b.id')
            ->select('*','b.username as user_created','a.username as prechargeur')->where('receptions.clients_id', request('id'))->where('receptions.type_commandes_id', request('typecmd'))->paginate($paginate); 

        }else{
            //$dries = Reception::where('clients_id', request('id'))->orderBy('redali', 'asc')->get();
        }
      
        return ReceptionResource::collection($dries);

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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function empoter()
    {  
        // update value
        $value=0;
        if(request('ischecked')==1){
            $value=request('idEmpotage');
        }
        Reception::where('reidre', request('idreception'))
          ->update([
            "dossier_empotage_id" => $value
        ]);
        // recalcul total 
        $results = DB::table('empotages')
            ->leftJoin('receptions', 'receptions.dossier_empotage_id', '=', 'empotages.id')
            ->leftJoin('users', 'empotages.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'empotages.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('contenaires', 'empotages.contenaires_id', '=', 'contenaires.id')
            ->groupBy('empotages.id')
            ->select('receptions.dossier_empotage_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd')/*, 
                'empotages.nbreContenaire', 
                'empotages.id as idPre', 
                'empotages.users_id', 
                'empotages.created_at as created_at_pre',
                'empotages.reetat as etat',
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'contenaires.nom as contenaire'*/)->where('empotages.id', request('idEmpotage'))->get();
    
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


    public function getCommandeEmpoter(){
        $results = Reception::where('dossier_empotage_id',request('id'))->get(); 
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
                                  'douane'         => $key->douane];
   
        }
        return response([
            "code" => 0,
            "result" => $details
        ]);
    }

    public function valider(){
        $response = true;

        if(sizeof(request('idsCmd')) > 0){
            $response = Reception::whereIn('reidre',request('idsCmd'))
              ->update([
                "dossier_empotage_id" => request('idEmpotage')
            ]);

        }

        

        if($response){
            if(sizeof(request('ignored')) > 0){
                 $response = Reception::whereIn('reidre',request('ignored'))
                  ->update([
                    "dossier_empotage_id" => Null
                ]);
            }

            $resp = Empotage::where('id',request('idEmpotage'))
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

    public function cloturer(){
         
        Empotage::where('id', request('ref'))
              ->update([
                "is_close"   => 1
        ]);

        return response([
            "code" => 0
        ]);
   }

   public function savepdf(){
         
        Empotage::where('id', request('id_rapport'))
              ->update([
                "rapport_pdf"   => request('dataPdf')
        ]);

        return response([
            "code" => 0
        ]);
   }

     public function deleteEmpotage(Request $request)
    {
        $res = Empotage::select('id')->where('reference', request('id'))->get()->toArray();
        $ids = [];

        foreach($res as $item){
            $ids[] = $item['id'];
            
        }

        Reception::whereIn('dossier_empotage_id', $ids)->update([
            "dossier_empotage_id" => ''
        ]);
        
        Empotage::where('id', request('id'))->delete();

          return response([
                "code" => 0,
                "message" => "OK"
            ]);
    }
}
