<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;
use App\Notifications\empotageCommandesTransitaire;
use App\Models\Empotage;
use App\Models\Reception;
use App\Http\Resources\ReceptionResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EmpotageResource;
use App\Http\Resources\ContenaireEmpotageResource;
use App\Models\ChargementCreation;
use App\Models\TypActivity;
use App\Models\LogActivity;
use App\Models\Client;
use App\Models\Entite; 
use App\Models\User; 
use App\Models\UserRole;
use App\Models\ContenairesEmpotage;
use DB;
use File;

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
            //->leftJoin('receptions', 'receptions.dossier_empotage_id', '=', 'empotages.id')
            ->leftJoin('users', 'empotages.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'empotages.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('contenaires_empotage', 'empotages.id', '=', 'contenaires_empotage.empotages_id')
            ->leftJoin('contenaires', 'contenaires_empotage.contenaires_id', '=', 'contenaires.id')
            ->leftJoin('entrepots', 'empotages.entrepots_id', '=', 'entrepots.id')
            
            ->select(DB::raw('count(contenaires_empotage.id) as nbreContenaireEmpote'), 
                //'receptions.dossier_empotage_id',
               /* DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd'), 
                'empotages.nbreContenaire', */
                 
                'empotages.id as IdEmpotage', 
                'empotages.reference as numDossier', 
              /*  'empotages.numContenaire as numContenaire', 
                'contenaires.nom as nomContenaire', 
                'contenaires.id as IDContenaire',
                'contenaires.volume as capacite',*/
                'empotages.users_id', 
               // 'empotages.plomb as plomb', 
                'empotages.date_depart as dateDepart', 
                'empotages.date_arrivee as dateArrivee', 
                'empotages.is_close as cloture',
                'empotages.created_at as created_at_empotage',
                'empotages.reetat as etat',
                'empotages.created_at as created_at',
                'empotages.numDocim as numDocim',
              //  'empotages.rapport_pdf as rapport_pdf',
                'empotages.complements_document as docs',
                'users.username as user',
                'entrepots.nomEntrepot as nomEntrepot', 
                'entrepots.id as idEntrepot',
                'type_commandes.typcmd as typecmd',
                'type_commandes.tcolor as tcolor',
                'type_commandes.id as typecmdID',
                /*'contenaires.nom as contenaire'*/)->where('empotages.reetat', false)->where('empotages.clients_id', request('id'))->groupBy('empotages.id')->orderBy('empotages.created_at', 'desc');

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
           
        }


      
        return EmpotageResource::collection($query);
    }

    public function  createEmpotage(Request $request){
        $user = Auth::user();

        // Check num dossier exist or not

        $check = Empotage::where('reference', request('reference'))->where('type_commandes_id', request('typeCmd'))->get();
        
        if(sizeof($check) > 0){
            return response([
                "code" => 1,
                "message" => "Rapport d'empotage existe déja!"
            ]);
        }

        Empotage::setIDClient(request('idClient'), $user->entites_id); 

        $store = Empotage::create([
            "reference" => request('reference'),
            //"numContenaire" => request('tc'),
            //"contenaires_id" => request('typetc'),
            "type_commandes_id" => request('typeCmd'),
            "entrepots_id" => request('idEntrepot'),
            /*'plomb' => request('plomb'),
            "poidEmpote" => 0,
            "volumeEmpote" =>  0, 
            "colisEmpote" => 0,*/
            "clients_id" => request('idClient'), 
            "date_depart" => request('date_depart'), 
            "date_arrivee" => request('date_arrivee'), 
            "users_id" => Auth::user()->id,
            "reetat" => 0,
            "is_close" => false,
            //"rapport_pdf" => ""
        ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    public function  modifyEmpotage(Request $request){
        $user = Auth::user();

        $rapport = Empotage::where('id', request('id'))->firstOrFail();

        Empotage::setIDClient(request('idClient'), $user->entites_id); 

        $rapport->update([
            "reference" => request('reference'),
            "numContenaire" => request('tc'),
            "contenaires_id" => request('typetc'),
            "type_commandes_id" => request('typeCmd'),
            'plomb' => request('plomb'),
            "date_depart" => request('date_depart'), 
            "date_arrivee" => request('date_arrivee')
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

        $keyword = request('keysearch');

        $sort = request('column');

        if (isset($paginate)) {

            $dries = Reception::where('dossier_id', request('ref'))->where(function($query){
                            $query->orWhere('dossier_empotage_id', request('id_empotage'))->orWhere('dossier_empotage_id', 0)->orWhere('dossier_empotage_id', NULL)->orWhere('dossier_empotage_id', '');
                        })->where(function($query){
                            $query->orWhere('numero_contenaire', request('contenaireSelected'))->orWhere('numero_contenaire', 0)->orWhere('numero_contenaire', NULL)->orWhere('numero_contenaire', '');
                        })
            ->leftJoin('empotages', 'empotages.id', '=', 'receptions.dossier_empotage_id')
            ->leftJoin('users as a', 'empotages.users_id', '=', 'a.id')
            ->leftJoin('users as b', 'receptions.users_id', '=', 'b.id')
            ->select('*','receptions.type_commandes_id as typeCmd','b.username as user_created','a.username as prechargeur')->where('receptions.clients_id', request('id'))->where('receptions.type_commandes_id', request('typecmd'))->where('receptions.entrepots_id', request('idEntrepot')); 

            if(request('contenaireEtat') == 1){
                $dries = $dries->whereNotNull('douane');
                $dries = $dries->where('douane', '!=', '');
            }

            if($keyword!=''){
                $dries = $dries->search($keyword);
            }

            if($sort!=''){
                $order = request('order');
              
                $dries = $dries->orderBy(strval($sort), $order);
            }else{
                $dries = $dries->orderBy("receptions.redali", "DESC"); 
            }

            $dries = $dries->paginate($paginate);

        }else{
           
        }
      
        return ReceptionResource::collection($dries);

    }
    public function updateDouane(){
        
        /*Reception::where('reidre', request('id'))
          ->update([
            "douane" => request('douane')
        ]);*/


        // update value
        $value=0;
        if(request('douane')!=''){
            $value = request('idEmpotage');
        }
        Reception::where('reidre', request('idreception'))
          ->update([
            "dossier_empotage_id" => $value,
            "douane" => request('douane'),
            "numero_contenaire" => request('contenaireSelected')
        ]);
        // recalcul total 
        $results = DB::table('contenaires_empotage')
            ->leftJoin('receptions', 'receptions.numero_contenaire', '=', 'contenaires_empotage.id')
            //->leftJoin('users', 'empotages.users_id', '=', 'users.id')
            //->leftJoin('type_commandes', 'empotages.type_commandes_id', '=', 'type_commandes.id')
            ->groupBy('contenaires_empotage.id')
            ->select('receptions.dossier_empotage_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd'))
            ->where('contenaires_empotage.empotages_id', request('idEmpotage'))->get();
    
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
        $results = Reception::where('dossier_empotage_id',request('id'))->where("numero_contenaire", request('IDContenaire'))->where('type_commandes_id', request('typecommande'))->get();  
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


    public function cloturer(){

        // Verifier s'il y'a des contenaires en cours dans le dossier

        $check = ContenairesEmpotage::where("empotages_id", request('id'))->where("etat", false)->get()->count();
       
        if($check > 0){
             return response([
                "code" => 1,
                "message" => "Avant de cloturer, valider le(s) contenaire(s) en cours."
            ]);
        }

        // Verifier s'il y'a des complement de dossier

        $check = Empotage::where("id", request('id'))->where(function($query){
                            $query->orWhere('complements_document', NULL)->orWhere('complements_document', '[]');
                        })->get()->count();
       
        if($check > 0){
             return response([
                "code" => 1,
                "message" => "Ajouter au moins un complement de dossier avant de cloturer."
            ]);
        }


        // Desactiver le dossier dans Prechargement
        DB::table('chargement_creations')->where("numdossier", request('id_dossier'))->where("type_commandes_id", request('typeCmd'))->update([
                "is_empote" => true
            ]);

        // Réinitialiser les commandes qui n'ont pas été choisi (Pas de num de douane ou num contenaire)
        Reception::where('dossier_id',request('id_dossier'))->where("type_commandes_id", request('typeCmd'))->where(function($query){ $query->orWhere('numero_contenaire', '')->orWhere('numero_contenaire', 0)->orWhere('numero_contenaire', NULL);
                    })->update([
            "dossier_id" => NULL,
            "douane" => NULL,
            "dossier_empotage_id" => NULL
        ]);
        
        
        // cloturer le dossier dans empotage 
        Empotage::where('id', request('id'))
              ->update([
                "is_close"   => 0, // Le client va cloturer apres num Docim & declaration douane
                "reetat"   => 1
        ]);

        return response([
            "code" => 0
        ]);
   }

    public function valider(){
        $user =Auth::user();
        $client = Client::where('id', request('id'))->whereJsonContains('clenti', Auth::getUser()->entites_id)->get()->first();

        if(!$client){
            abort(404);
        }
        $response = true;


        // Réinitialiser les commandes qui n'ont pas été choisi (Pas de num de douane)
        //A voir
        /*if(sizeof(request('ignored')) > 0){
            Reception::whereIn('reidre',request('ignored'))->update([
                "dossier_id" => null
            ]);
        }*/
        
        // Empoter les commandes qui ont été choisies
        if(sizeof(request('idsCmd')) > 0){
            $response = Reception::whereIn('reidre',request('idsCmd'))
              ->update([
                "dossier_empotage_id" => request('idEmpotage'),
                "numero_contenaire" => request('IDContenaire')
            ]);


            // Associer les commandes à un numero de dossier
            activity(TypActivity::MODIFIER)->withProperties(request('idsCmd'))->performedOn($client)->log('Validation empotage n°dossier'.request('typeCmd'));
            $lastID = LogActivity::latest('id')->first();
            $query = LogActivity::where("id", $lastID['id'])->update(["subject_type" => $user->entites_id]);

        }

        // Changer etat contenaire

        DB::table('contenaires_empotage')->where("id", request('IDContenaire'))->update([
            "etat" => 1
        ]);

        if($response){

            // Revoir 
            /*if(sizeof(request('ignored')) > 0){
                 $response = Reception::whereIn('reidre',request('ignored'))
                  ->update([
                    "dossier_empotage_id" => Null
                ]);
            }*/
            
            // Valider et cloturer en meme temps
            //A voir aussi
            /*$resp = Empotage::where('id',request('idEmpotage'))
              ->update([
                "reetat" => true,
                "is_close" => true
            ]);*/

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
        $user = Auth::user();

        /*$res = Empotage::select('id')->where('reference', request('id'))->get()->toArray();
        $ids = [];

        foreach($res as $item){
            $ids[] = $item['id'];
            
        }*/

        Reception::where('dossier_empotage_id', request('id'))->update([
            "dossier_empotage_id" => null
        ]);
        
        $rapport = Empotage::where('id', request('id'))->firstOrFail();

        Empotage::setIDClient(request('idClient'), $user->entites_id); 
        
        $rapport->delete();

          return response([
                "code" => 0,
                "message" => "OK"
            ]);
    }


    public function notifier(){
        $user = Auth::user();
        
        $base64_pdf = trim(request('base64_file_pdf'), "data:application/pdf;base64,");
        $base64_decode = base64_decode($base64_pdf);
        $pathFile = 'pdf/empotage/dossier-'.request('id_dossier').'_'.request('typeCmd').'_numtc-'.request('numtc').'_plomb-'.request('plomb').'.pdf';
        $pdf = fopen($pathFile, 'w');
        fwrite($pdf, $base64_decode);
        fclose($pdf);

       
        // Notification

        $transitaire = Entite::where('id', $user->entites_id)->get()->first();
        $societe = Client::where('id', request('id'))->get()->first();

        $commandes = Reception::whereIn('reidre',request('idsCmd'));


        $getMailClient = User::where("entites_id", $transitaire['id'])->whereJsonContains('roles', UserRole::ROLE_CLIENT)->whereJsonContains('client_supervisor', intval(request('IDclient')))->get();

        $emailSent=[];

        foreach($getMailClient as $user){
        
            $emailSent[] = $user['email'];
        }

        //Notification::route('mail', [])->notify(new empotageCommandesTransitaire($transitaire, $societe, $emailSent, $commandes, $pathFile, request('id_dossier'), request('numtc'), request('typetc'), request('plomb'))); 


        Notification::send($getMailClient, new empotageCommandesTransitaire($transitaire, $societe, $emailSent, $commandes, $pathFile, request('id_dossier'), request('numtc'), request('typetc'), request('plomb'), request('typeCommande')));
    }

    public function saveDoc(Request $request){
        try{   
                $filename = '';
                $allFileName=[];
                $docs = explode(",", $request->Document[0]);
         

               for ($x = 0; $x < $request->TotalFiles; $x++) 
               {
     
                   if ($request->hasFile('files'.$x)) 
                    {
                        $current_date_time = Carbon::now()->toDateTimeString();
                        $paseDate = explode(' ', $current_date_time);
                        $file     = $request->file('files'.$x); 
                        $filename = 'doc'.'_'.request('idEmpotage').'_'.($x+1).'_'.$paseDate[0].'_'.str_replace(":","-",$paseDate[1]).'.'.$file->getClientOriginalExtension();

                        $file->move("assets/documents/", $filename);
                        array_push($allFileName, $filename);
                      
                    }
               } 
        
               for($i=0; $i<sizeof($docs); $i++){
                    if($docs[$i]!=''){
                        array_push($allFileName, $docs[$i]); 
                    } 
               }
        
                Empotage::where('id', request('idEmpotage'))
                  ->update([
                    "complements_document" => json_encode($allFileName)
                ]);

        }catch(\Exceptions $e){
              return response([
                "code" => 1,
                "message" => $e->getMessage()
            ]);
        }

        // get Files
        //$files = Empotage::where('id', request('idEmpotage'))->pluck('complements_document')->first();

         return response([
            "code" => 0,
            "message" => "OK",
            "file" => $allFileName
        ]);
    }


     public function removeDoc(Request $request){
        try{   

                $allFileName=[];
                $docs = explode(",", $request->Document[0]);
         
                for($i=0; $i<sizeof($docs); $i++){
                    if($docs[$i]!='' && $docs[$i]!=$request->nameFile){
                        array_push($allFileName, $docs[$i]); 
                    } 
                }
        
                Empotage::where('id', request('idEmpotage'))
                  ->update([
                    "complements_document" => json_encode($allFileName)
                ]);

                // delete file

                File::delete("assets/documents/".$request->nameFile);
                

        }catch(\Exceptions $e){
              return response([
                "code" => 1,
                "message" => $e->getMessage()
            ]);
        }

        return response([
            "code" => 0,
            "message" => "OK",
            "file" => $allFileName
        ]);
    }



    

    public function  createContenaireEmpo(Request $request){
        $user = Auth::user();

        $store = ContenairesEmpotage::create([
            "empotages_id" => request('idEmpo'),
            "numContenaire" => request('tc'),
            "contenaires_id" => request('typetc'),
            'plomb' => request('plomb'),
            "poidEmpote" => 0,
            "volumeEmpote" =>  0, 
            "colisEmpote" => 0
        ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    public function  getContenaire(Request $request){
        $user = Auth::user();

        $query = DB::table('contenaires_empotage')
        ->leftJoin('contenaires', 'contenaires_empotage.contenaires_id', '=', 'contenaires.id')
        ->select('*', 'contenaires_empotage.id as idContenaire', 'contenaires_empotage.etat as etatContanaire')
        ->where('empotages_id', request('idEmpotage'))->get();




        return ContenaireEmpotageResource::collection($query);
    }


    

     public function reactiver(Request $request){
        $user = Auth::user();

        DB::table('contenaires_empotage')->where("id", request('id'))->update([
            "etat" => 0
        ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }


    public function deleteContenaire(Request $request){
        $user = Auth::user();

        // reinit all orders
        $response = Reception::where('numero_contenaire',request('id'))
              ->update([
                "numero_contenaire" => NULL,
                "douane" => NULL
            ]);

        $req = ContenairesEmpotage::where('id', request('id'))->firstOrFail();
        
        $req->delete();

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    public function setNumEmpotage(Request $request){
        $user = Auth::user();

        DB::table('empotages')->where("id", request('id'))->update([
            "numDocim" => request('docim')
        ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    public function saveDeclaration(Request $request){
        try{   
                $filename = '';
                $allFileName=[];
                $docs = explode(",", $request->Document[0]);
         

               for ($x = 0; $x < $request->TotalFiles; $x++) 
               {
     
                   if ($request->hasFile('files'.$x)) 
                    {
                        $current_date_time = Carbon::now()->toDateTimeString();
                        $paseDate = explode(' ', $current_date_time);
                        $file     = $request->file('files'.$x); 
                        $filename = 'doc'.'_'.request('idEmpotage').'_'.($x+1).'_'.$paseDate[0].'_'.str_replace(":","-",$paseDate[1]).'.'.$file->getClientOriginalExtension();

                        $file->move("assets/declarationsDouane/", $filename);
                        array_push($allFileName, $filename);
                      
                    }
               } 
        
               for($i=0; $i<sizeof($docs); $i++){
                    if($docs[$i]!=''){
                        array_push($allFileName, $docs[$i]); 
                    } 
               }
        
                Empotage::where('id', request('idEmpotage'))
                  ->update([
                    "declaration_douane" => json_encode($allFileName) 
                ]);

        }catch(\Exceptions $e){
              return response([
                "code" => 1,
                "message" => $e->getMessage()
            ]);
        }

        // get Files
        //$files = Empotage::where('id', request('idEmpotage'))->pluck('complements_document')->first();

         return response([
            "code" => 0,
            "message" => "OK",
            "file" => $allFileName
        ]);
    }
    
     public function removeDeclarationDouane(Request $request){
        try{   

                $allFileName=[];
                $docs = explode(",", $request->Document[0]);
                $values=NULL;
         
                for($i=0; $i<sizeof($docs); $i++){
                    if($docs[$i]!='' && $docs[$i]!=$request->nameFile){
                        array_push($allFileName, $docs[$i]); 
                    } 
                }

                if(sizeof($allFileName) > 0){
                    $values = json_encode($allFileName);
                }

        
                Empotage::where('id', request('idEmpotage'))
                  ->update([
                    "declaration_douane" => $values
                ]);

                // delete file

                File::delete("assets/declarationsDouane/".$request->nameFile);
                

        }catch(\Exceptions $e){
              return response([
                "code" => 1,
                "message" => $e->getMessage()
            ]);
        }

        return response([
            "code" => 0,
            "message" => "OK",
            "file" => $allFileName
        ]);
    }
    

    


    
}
