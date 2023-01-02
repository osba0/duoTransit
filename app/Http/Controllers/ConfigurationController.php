<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Resources\ClientResource;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Entite;
use App\Models\Fournisseur;
use App\Models\Entrepot;
use App\Models\TypeCommande;
use App\Models\Reception;
use App\Models\Contenaire;
use App\Http\Resources\FournisseurResource;
use App\Http\Resources\TypeCommandeResource;
use App\Http\Resources\EntiteResource;
use App\Http\Resources\EntrepotResource;
use App\Http\Resources\ContenaireResource;
use App\Helpers\LogActivity;
use File;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ClientsNotification;
use Illuminate\Support\Facades\Notification;
use DB;

class ConfigurationController extends Controller
{
    protected $idEntite = null; 

    public function __construct()
    {
       $this->middleware('auth');     

    }
    /*
    ***************************** ACTION CLIENT 
    ***************************** Author: OSBA
    ***************************** Modifié: 25/12/2020  
    */

    /**
    * Liste les clients sur le tableau de bord.
    *
    * 
    */
    public function clientHome(Request $request)
    { 
        $user = Auth::user();
        

    
        
        if($user->roles[0]==UserRole::ROLE_CLIENT){
          
            if(sizeof(json_decode(request("clientAuth"))) > 0){
                $clients = Client::whereIn('id', json_decode(request("clientAuth")))->get();
            }  
        }else{
          
            $clients = Client::whereJsonContains('clenti', $user->entites_id)->get(); 
        }
        
        return ClientResource::collection($clients);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClient()
    {
        $user = Auth::user();
        $paginate = request('paginate');

        if(isset($paginate)) {
            $clients = Client::/*whereJsonContains('clenti', $user->entites_id)->*/orderBy('id', 'desc')->paginate($paginate);
        }else{
            $clients = Client::get()/*->whereJsonContains('clenti', $user->entites_id)*/;
        }


       // LogActivity::addToLog('Liste page client.', 'Affichage de la page client', 'Admin'); 

        foreach ($user->unreadNotifications as $notification) {
           $notification->markAsRead();
        }



        return ClientResource::collection($clients);
        
    }

    public function getConfig()
    {
        $typeCmd = TypeCommande::get(); 
        $fournis = Fournisseur::get(); 
        $entites = Entite::get(); 

        return response([
            "code" => 0,
            "typeCmd" => $typeCmd,
            "entites" => $entites
        ]);
        
    }

   /* public function listeClient(){
       
        $user = Auth::user();
        $client = Client::get()->where('clenti', $user->entites_id);
       
        $roles = UserRole::getRoleList();

        return  view('backend.configuration.clients', ['clients' => $client, 'roles' => $roles]);
    } */

    public function clientDelete(Request $request)
    {
        $client = Client::where('id', request('id'))->delete();

        if(!is_null(request('logo')) || !empty(request('logo'))) File::delete("images/logo/".request('logo'));

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    public function createClient(Request $request){
        $user = Auth::user();
         try{
           
            $filename = '';

            if(!is_null($request->file('file'))){

                $file = $request->file('file');
        
                $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
               
                $request->file->move("images/logo/", $filename);
            }
            $new_client = [
                "clnmcl" => request('nom'),
                "clmail" => request('email'),
                "slug"   => \Str::slug(request('nom')),
                "cladcl" => request('adresse'),
                "cltele" => request('telephone'),
                'cllogo' => $filename,
                'pays'   => request('pays'),
                "cletat" => 1,
                "clfocl" => json_decode(request('fournisseurs')), 
                "cltyco" => json_decode(request('typecmd')), 
                "clenti" => json_decode(request('entite')) 
            ]; 
            $store = Client::create($new_client);

           // $user->notify(new ClientsNotification($new_client)); 
           /* $users = User::where("entites_id", $user->entites_id)->get();

            Notification::send($users, new ClientsNotification($new_client));*/

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
    public function modifyClient(Request $request)
    {
        $filename = '';

        if(!is_null($request->file('file'))){

            $file = $request->file('file');

            $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
           
            $request->file->move("images/logo/", $filename);
        }
        if(!is_null(request('imageSet'))){
            $filename = request('imageSet');
        }
            
        Client::where('id', request('id'))
              ->update([
                "clnmcl" => request('nom'),
                "clmail" => request('email'),
                "cladcl" => request('adresse'),
                "cltele" => request('telephone'),
                'cllogo' => $filename,
                'pays' => request('pays'),
                "cletat" => 1,
                "clfocl" => json_decode(request('fournisseurs')), 
                "cltyco" => json_decode(request('typecmd')), 
                "clenti" => json_decode(request('entite')) 
          ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    /*
    ***************************** ACTION  
    ***************************** Author: OSBA
    ***************************** Modifié: 25/12/2020  
    */
    public function getFournisseur()
    {
        if(request('slug')!=''){
            $client = Client::where('slug', request('slug'))->whereJsonContains('clenti', Auth::getUser()->entites_id)->get()->first();

            if(!$client){
                abort(404);
            }
        }

        $paginate = request('paginate');

        if (isset($paginate)) {
            if(request('slug')==''){
                $fournisseurs = Fournisseur::orderBy('id', 'desc')->paginate($paginate);
            }else{
                $fournisseurs = Fournisseur::whereIn('id',$client->clfocl)->orderBy('id', 'desc')->paginate($paginate);
            }
            
        }else{
            if(request('slug')==''){
                $fournisseurs = Fournisseur::get();
            }else{
               $fournisseurs = Fournisseur::whereIn('id',$client->clfocl)->get();
            }
            
        }

        return FournisseurResource::collection($fournisseurs);
    }

    public function fournisseurDelete(Request $request)
    {
        $exist = Reception::where('fournisseurs_id', request('id'))->get();
        if(count($exist) > 0){
            return response([
                "code" => 1,
                "message" => "Exist" 
            ]);
        }else{
            // Supprimer les affectations
            /*$statF =  DB::table('clients')->select('id', 'clfocl')->whereJsonContains("clenti", Auth::user()->entites_id)->get()->toArray();

            foreach ($statF as $value){
                $fo = json_decode($value->clfocl);
                unset($fo[array_search(request('id'), $fo)]); 
                Client::where('id', $value->id)
                    ->update([
                        "clfocl" => array_unique($fo)
                    ]);
            }*/


            Fournisseur::where('id', request('id'))->delete();

            if(!is_null(request('logo')) || !empty(request('logo'))) File::delete("images/logo/".request('logo'));

            $listClientAll = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get(); 

            /*foreach($listClientAll as $client){

                $tabFour = $client['clfocl'];

                if (($key = array_search(request('id'), $tabFour)) !== false) {
                    unset($tabFour[$key]);
                }

                Client::where('id', $client['id'])->update([
                    "clfocl" => array_unique($tabFour)
                ]);
            }*/
        }
       

        return response([
            "code" => 0,
            "message" => "OK" 
        ]);
    }

    public function createFournisseur(Request $request){
         try{

            $filename = '';

            if(!is_null($request->file('file'))){

                $file = $request->file('file');
        
                $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
               
                $request->file->move("images/logo/", $filename);
            }
            
            $store = Fournisseur::create([
                "fonmfo" => strtoupper(request('nom')),
                "foemail" => request('email'),
                "foadrs" => request('adresse'),
                "fotele" => request('telephone'),
                'fologo' => $filename,
                "foetat" => 1
            ]);


            // Pour le profil Admin ajouter directement le fournisseur au client
            if($store){
                if(request('slug')!=''){
                    $lastIDFour = Fournisseur::latest('id')->first(); 

                    $client = Client::where('slug',request('slug'))->first(); 

                    $listfournisseur = $client['clfocl'];

                    if(!is_array($listfournisseur)){
                        $listfournisseur=[];    
                    }

                    array_push($listfournisseur, intval($lastIDFour['id']));

                    // update

                    Client::where('slug', request('slug'))
                    ->update([
                        "clfocl" => array_unique($listfournisseur)
                    ]);
                }
            }

                

            // get last ID 
            
            /*$lastIDFour = Fournisseur::latest('id')->first();
            
            $listClientSet = json_decode(request('listClientAjouter'));

            $listClientAll = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get(); 

            foreach($listClientAll as $client){

                if (in_array($client['id'], $listClientSet)){

                    $tabFour = $client['clfocl'];

                    array_push($tabFour, $lastIDFour['id']);

                    Client::where('id', $client['id'])
                          ->update([
                            "clfocl" => array_unique($tabFour)
                      ]);
                }
            }*/
           

            //var_dump(json_decode(request('listClientAjouter'))); die();
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
    
    public function modifyFournisseur(Request $request)
    {
        $filename = '';

        if(!is_null($request->file('file'))){

            $file = $request->file('file');

            $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
           
            $request->file->move("images/logo/", $filename);
        }
        if(!is_null(request('imageSet'))){
            $filename = request('imageSet');
        }
       
        Fournisseur::where('id', request('id'))
          ->update([
            "fonmfo" => request('nom'),
            "foemail" => request('email'),
            "foadrs" => request('adresse'),
            "fotele" => request('telephone'),
            'fologo' => $filename
        ]);



        /*$listClientSet = json_decode(request('listClientAjouter'));

        $listClientAll = Client::whereJsonContains('clenti',Auth::user()->entites_id)->get(); 
    
        foreach($listClientAll as $client){

            if (in_array($client['id'], $listClientSet)){

                $tabFour = $client['clfocl'];

                array_push($tabFour, intval(request('id')));

                Client::where('id', $client['id'])->update([
                    "clfocl" => array_unique($tabFour)
                ]);
            }else{

                $tabFour = $client['clfocl'];

                if (($key = array_search(request('id'), $tabFour)) !== false) {
                    unset($tabFour[$key]);
                }

                Client::where('id', $client['id'])->update([
                    "clfocl" => array_unique($tabFour)
                ]);
            }
        }*/

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }


    public function ajoutClientFour()
    {
       $client = Client::where('id',request('client'))->first(); 

       $listfournisseur = $client['clfocl'];

       if(!is_array($listfournisseur)){
            $listfournisseur=[];    
       }

       array_push($listfournisseur, intval(request('fournisseur')));

       // update

       Client::where('id', request('client'))
          ->update([
            "clfocl" => array_unique($listfournisseur)
        ]);


        return response([
            "code" => 0,
            "message" => "OK"
        ]);
      
    }


     public function retirerClientFour()
    {
       $client = Client::where('id',request('client'))->first(); 

       $listfournisseur = $client['clfocl'];

       $newlist = [];


       for($i=0; $i<sizeof($listfournisseur); $i++){
            if($listfournisseur[$i] != request('fournisseur')){
                $newlist[] = $listfournisseur[$i];
            }
       }

       // update

       Client::where('id', request('client'))
          ->update([
            "clfocl" => array_unique($newlist)
        ]);


        return response([
            "code" => 0,
            "message" => "OK"
        ]);
      
    }



        /*
    ***************************** ACTION  
    ***************************** Author: OSBA
    ***************************** Modifié: 25/12/2020  
    */
    public function getEntite()
    {

        $paginate = request('paginate');

        if (isset($paginate)) {
            $entites = Entite::orderBy('id', 'desc')->paginate($paginate);
        }else{
            $entites = Entite::get();
        }

        return EntiteResource::collection($entites);
    }

    public function EntiteDelete(Request $request)
    {
        Entite::where('id', request('id'))->delete();

        if(!is_null(request('logo')) || !empty(request('logo'))) File::delete("images/logo/".request('logo'));

          return response([
                "code" => 0,
                "message" => "OK" 
            ]);
    }

    public function createEntite(Request $request){
        try{

            $filename = '';

            if(!is_null($request->file('file'))){

                $file = $request->file('file');
        
                $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
               
                $request->file->move("images/logo/", $filename);
            }
            
            
            $store = Entite::create([
                "nom" => request('nom'),
                "slug" => \Str::slug(request('nom')),
                "adresse" => request('adresse'),
                "telephone" => request('telephone'),
                "fax" => request('fax'),
                "email" => request('email'),
                'logo' => $filename,
                "etat" => 1,
                "entrepots_id" => array_unique(json_decode(request('entrepots'))), 
                "contenaires_id" => array_unique(json_decode(request('contenaires')))

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
    
    public function modifyEntite(Request $request)
    {
        $filename = '';

        if(!is_null($request->file('file'))){

            $file = $request->file('file');

            $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
           
            $request->file->move("images/logo/", $filename);
        }
        if(!is_null(request('imageSet'))){
            $filename = request('imageSet');
        }
            
        Entite::where('id', request('id'))
              ->update([
                "nom" => request('nom'),
                "adresse" => request('adresse'),
                "telephone" => request('telephone'),
                "fax" => request('fax'),
                "email" => request('email'),
                'logo' => $filename,
                "entrepots_id" => request('entrepots'),  //array_unique(json_decode(request('entrepots')))
                "contenaires_id" => request('contenaires')

          ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }


       /*
    ***************************** ACTION  
    ***************************** Author: OSBA
    ***************************** Modifié: 25/12/2020  
    */
    public function getEntrepot()
    {

        $paginate = request('paginate');

        if (isset($paginate)) {
            $entrepots = Entrepot::orderBy('id', 'desc')->paginate($paginate);
        }else{
            $entrepots = Entrepot::get();
        }

        return EntrepotResource::collection($entrepots);
    }

    public function entrepotDelete(Request $request)
    {
        Entrepot::where('id', request('id'))->delete();

          return response([
                "code" => 0,
                "message" => "OK" 
            ]);
    }

    public function createEntrepot(Request $request){
         try{
            $store = Entrepot::create([
                "nomEntrepot" => request('nomEntrepot'),
                "titulaire" => request('titulaire'),
                "email" => request('email'),
                "telephone" => request('telephone'),
                "adresse" => request('adresse')
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
    
    public function modifyEntrepot(Request $request)
    {
            
        Entrepot::where('id', request('id'))
              ->update([
                "nomEntrepot" => request('nomEntrepot'),
                "titulaire" => request('titulaire'),
                "email" => request('email'),
                "telephone" => request('telephone'),
                "adresse" => request('adresse')

          ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

      /*
    ***************************** ACTION  
    ***************************** Author: OSBA
    ***************************** Modifié: 25/12/2020  
    */
    public function getContenaire()
    {

        $paginate = request('paginate');

        if (isset($paginate)) {
            $contenaires = Contenaire::orderBy('id', 'desc')->paginate($paginate);
        }else{
            $contenaires = Contenaire::get();
        }

        return ContenaireResource::collection($contenaires);
    }

    public function contenaireDelete(Request $request)
    {
        Contenaire::where('id', request('id'))->delete();

          return response([
                "code" => 0,
                "message" => "OK" 
            ]);
    }

    public function createContenaire(Request $request){
         try{

            if(request('isdefault')==1){
                  $query = DB::table('contenaires')->update([
                        "isdefault"  => 0

                  ]);
            }
            
            $store = Contenaire::create([
                "nom" => request('nom'),
                "volume" => request('capacite'),
                "isdefault"  => request('isdefault')
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
    
    public function modifyContenaire(Request $request)
    {
        if(request('isdefault')==1){
             $query = DB::table('contenaires')->update([
                    "isdefault"  => 0

              ]);
        }
            
        Contenaire::where('id', request('id'))
              ->update([
                "nom" => request('nom'),
                "volume" => request('capacite'),
                "isdefault"  => request('isdefault')

          ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }
    
  /*
    ***************************** ACTION  
    ***************************** Author: OSBA
    ***************************** Modifié: 25/12/2020  
    */
    public function getTypeCommande()
    {
       
        $paginate = request('paginate');
        
        if(request('slug')!=''){
            $client = Client::/*where('slug', request('slug'))->*/whereJsonContains('clenti', Auth::getUser()->entites_id)->get()->first();
            if(!$client){
                abort(404);
            }
        }
        

        if (isset($paginate)) {
            if(request('slug')==''){
                $typeCmd = TypeCommande::orderBy('id', 'desc')->paginate($paginate);
            }else{
                $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->orderBy('id', 'desc')->paginate($paginate);
            }
            
        }else{
             if(request('slug')==''){
                $typeCmd = TypeCommande::get();
            }else{
               $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->get();
            }
            
        }

        return TypeCommandeResource::collection($typeCmd);
    }

    public function typeCommandeDelete(Request $request)
    {
        TypeCommande::where('id', request('id'))->delete();

          return response([
                "code" => 0,
                "message" => "OK" 
            ]);
    }

    public function createTypeCommande(Request $request){
         try{
            
            $store = TypeCommande::create([
                "typcmd" => request('type'),
                "tcolor" => request('color'),
                "etat" => 1
            ]);

             // Pour le profil Admin ajouter directement le type au client

             if($store){
                if(request('slug')!=''){
                   $lastID = TypeCommande::latest('id')->first(); 
                   $client = Client::where('slug',request('slug'))->first(); 

                   $listtypecmd = $client['cltyco'];

                   if(!is_array($listtypecmd)){ 
                        $listtypecmd=[];    
                   }

                   array_push($listtypecmd, intval($lastID['id']));

                   // update

                   Client::where('slug', request('slug'))
                      ->update([
                        "cltyco" => array_unique($listtypecmd)
                    ]);
                }
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
    
    public function modifyTypeCommande(Request $request)
    {
            
        TypeCommande::where('id', request('id'))
              ->update([
                "typcmd" => request('type'),
                "tcolor" => request('color'),
                "etat" => request('etat')
          ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }
    
    public function etatTypeCommande(){
        TypeCommande::where('id', request('id'))
              ->update([
                "etat" => request('etat')
          ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    public function ajoutClientTypeCmd()
    {
       $client = Client::where('id',request('client'))->first(); 

       $listtypecmd = $client['cltyco'];

       if(!is_array($listtypecmd)){
            $listtypecmd=[];    
       }

       array_push($listtypecmd, intval(request('typecommande')));

       // update

       Client::where('id', request('client'))
          ->update([
            "cltyco" => array_unique($listtypecmd)
        ]);


        return response([
            "code" => 0,
            "message" => "OK"
        ]);
      
    }


     public function retirerClientTypeCmd()
    {
       $client = Client::where('id',request('client'))->first(); 

       $listtypecmd = $client['cltyco'];

       $newlist = [];


       for($i=0; $i<sizeof($listtypecmd); $i++){
            if($listtypecmd[$i] != request('typecommande')){
                $newlist[] = $listtypecmd[$i];
            }
       }

       // update

       Client::where('id', request('client'))
          ->update([
            "cltyco" => array_unique($newlist)
        ]);


        return response([
            "code" => 0,
            "message" => "OK"
        ]);
      
    }

     public function etatFournisseur(){
        Fournisseur::where('id', request('id'))
              ->update([
                "foetat" => request('etat')
          ]);

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }


  
   
}
