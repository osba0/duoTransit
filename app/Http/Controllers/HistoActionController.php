<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Contenaire;
use App\Models\TypeCommande;
use App\Models\Entite;
use App\Models\Reception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\UserRole;
use App\Models\User;
use App\Models\Entrepot;

use App\Http\Resources\EmpotageResource;
use App\Http\Resources\ReceptionResource;
use App\Http\Resources\DossierPrechargementResource;


class HistoActionController extends Controller
{
  

    public function historiqueEmpotage(Request $request){

        $user = Auth::user();

        $entite = Entite::where('id', $user->entites_id)->get()->first();
        
        $client = Client::get()->where('slug', request('id'))->first();

        if(!$client)  abort(404);

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get(); 

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->get(); 

        $contenaires = Contenaire::whereIn('id',$entite->contenaires_id)->get(); 

        $entrepots = Entrepot::get();  

        
        return  view('backend.historique_empotage.index', ['logo' => $client->cllogo, 'id_client' => $client->id, 'typeCmd' => $typeCmd, 'client' => $client, 'fournisseurs' => $fournis, 'listContenaire' => $contenaires, "entrepots" => $entrepots]);
    }

    public function searchHisto(Request $request){
        //var_dump(request('filtre.typeCmd'));

        $user = Auth::user();

        $paginate = request('paginate');

        $sort = request('column');

        if (isset($paginate)) {

            $req = DB::table('empotages')
            ->leftJoin('receptions', 'receptions.dossier_empotage_id', '=', 'empotages.id')
            ->leftJoin('users', 'empotages.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'empotages.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('contenaires', 'empotages.contenaires_id', '=', 'contenaires.id')
            ->leftJoin('entrepots', 'empotages.entrepots_id', '=', 'entrepots.id')
            ->groupBy('empotages.id')
            ->select('receptions.dossier_empotage_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('SUM(receptions.renbpl + receptions.renbcl) as colis_total'),
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
                'empotages.date_depart as dateDepart', 
                'empotages.date_arrivee as dateArrivee', 
                'empotages.is_close as cloture',
                'empotages.created_at as created_at_empotage',
                'empotages.reetat as etat',
                'empotages.rapport_pdf as rapport_pdf',
                'empotages.created_at as created_at',
                'empotages.complements_document as docs',
                'users.username as user',
                'entrepots.nomEntrepot as nomEntrepot', 
                'entrepots.id as idEntrepot',
                'type_commandes.typcmd as typecmd',
                'type_commandes.tcolor as tcolor',
                'type_commandes.id as typecmdID',
                'contenaires.nom as contenaire')->where('empotages.reetat', true)->where('receptions.clients_id', request('id'))->whereBetween('empotages.created_at', [request('filtre.dateDebut').' 00:00:00', request('filtre.dateFin').' 23:59:59']);



            if(request('filtre.typeCmd')!=''){
                $req = $req->where('receptions.type_commandes_id', request('filtre.typeCmd'));
            }
            if(request('filtre.fournisseur')!=''){
                $req = $req->where('receptions.fournisseurs_id', request('filtre.fournisseur'));
            }
            if(request('filtre.commande')!=''){
                $cmd = request('filtre.commande');
                $term = "$cmd%";
                $req = $req->where('receptions.rencmd', 'like', $term);
            }
            if(request('filtre.dossier')!=''){
                $cmd = request('filtre.dossier');
                $term = "$cmd%";
                $req = $req->where('empotages.reference', 'like', $term);
            }
            if($sort!=''){
                $order = request('order');
                $req = $req->orderBy(strval($sort), $order);
            }else{
                 $req = $req->orderBy("created_at", "DESC"); 
            }

            $req = $req->paginate($paginate); 

        }else{
           
        }

       // var_dump($req); die();
      
        return EmpotageResource::collection($req);
    }
    public function searchCmdReception()
    {
        $user = Auth::user();

        $paginate = request('paginate');

        $keyword = request('keysearch');

        $order = request('order');

        $sortedColumn = request('column');

        if (isset($paginate)) {

            $histo =Reception::
            leftJoin('empotages', 'empotages.id', '=', 'receptions.dossier_empotage_id')
            ->leftJoin('fournisseurs as four', 'receptions.fournisseurs_id', '=', 'four.id')
            ->leftJoin('users as a', 'empotages.users_id', '=', 'a.id')
            ->leftJoin('users as b', 'receptions.users_id', '=', 'b.id')
            ->select('*','four.fonmfo as fonmfo', 'b.username as user_created','a.username as prechargeur')->where('receptions.dossier_empotage_id', request('id_empotage'))->where('receptions.clients_id', request('id'))->where('receptions.type_commandes_id', request('typecmd'));
            if(request('filtre_four')!=''){
                $histo = $histo->where('receptions.fournisseurs_id', request('filtre_four'));
            }

            if($keyword!=''){
                $histo = $histo->search($keyword);
            }

            if($sortedColumn!=""){
                $histo = $histo->orderBy($sortedColumn, $order);
            }

            $histo = $histo->paginate($paginate); 

        }else{
           
        }
      
        return ReceptionResource::collection($histo);

    }

    /** Histo préchargement **/
    
     public function historiquePrechargement(Request $request){

        $user = Auth::user();

        if(!($user->hasRole(UserRole::ROLE_CLIENT))){
             abort(401);
        }

        $entite = Entite::where('id', $user->entites_id)->get()->first();
        
        $client = Client::get()->where('slug', request('id'))->first();

        if(!$client) abort(404);

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get(); 

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->get(); 

        $contenaires = Contenaire::whereIn('id',$entite->contenaires_id)->get();

        $entrepots = Entrepot::get();  
        
        return  view('backend.historique_prechargement.index', ['logo' => $client->cllogo, 'id_client' => $client->id, 'typeCmd' => $typeCmd, 'client' => $client, 'fournisseurs' => $fournis, 'listContenaire' => $contenaires, "entrepots" => $entrepots]);
    }

    public function searchHistoPre(Request $request){ 
        //var_dump(request('filtre.typeCmd'));

        $user = Auth::user();

        $paginate = request('paginate');

        if (isset($paginate)) {

            $req = DB::table('dossier_prechargements')
            ->leftJoin('receptions', 'receptions.dossier_prechargements_id', '=', 'dossier_prechargements.id')
            ->leftJoin('users', 'dossier_prechargements.users_id', '=', 'users.id')
            ->leftJoin('type_commandes', 'dossier_prechargements.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('contenaires', 'dossier_prechargements.contenaires_id', '=', 'contenaires.id')
             ->leftJoin('entrepots', 'dossier_prechargements.entrepots_id', '=', 'entrepots.id')
            ->groupBy('dossier_prechargements.id')
            ->select('receptions.dossier_prechargements_id', 
                DB::raw('SUM(receptions.repoid) as total_poids'), 
                DB::raw('SUM(receptions.revolu) as total_volume'), 
                DB::raw('SUM(receptions.renbcl) as total_colis'), 
                DB::raw('SUM(receptions.renbpl) as total_palette'), 
                DB::raw('count(receptions.rencmd) as total_cmd'), 
                DB::raw('SUM(receptions.revafa) as total_mnt'), 
                'dossier_prechargements.nbreContenaire as nbreContenaire', 
                'dossier_prechargements.id as idPre', 

                'contenaires.nom as nomContenaire', 
                'contenaires.id as IDContenaire',
                'contenaires.volume as capacite',
                'dossier_prechargements.users_id', 
               
                'dossier_prechargements.created_at as created_at_pre',
                'dossier_prechargements.updated_at as updated_at_pre', 
                'dossier_prechargements.reetat as etat',
                'dossier_prechargements.rapport_pdf as rapport_pdf',
                
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'type_commandes.id as typecmdID',
                'type_commandes.tcolor as typecmdColor',
                 'entrepots.id as entrepots_id',
                'entrepots.nomEntrepot as entrepots_name',
                'contenaires.nom as contenaire')->where('receptions.clients_id', request('id'))->whereBetween('dossier_prechargements.updated_at', [request('filtre.dateDebut').' 00:00:00', request('filtre.dateFin').' 23:59:59']);

            if(request('filtre.typeCmd')!=''){
                $req = $req->where('receptions.type_commandes_id', request('filtre.typeCmd'));
            }
            if(request('filtre.fournisseur')!=''){
                $req = $req->where('receptions.fournisseurs_id', request('filtre.fournisseur'));
            }
            if(request('filtre.commande')!=''){
                $cmd = request('filtre.commande');
                $term = "$cmd%";
                $req = $req->where('receptions.rencmd', 'like', $term);
            }
            $req = $req->orderBy("dossier_prechargements.updated_at", 'DESC')->paginate($paginate); 

        }else{
           
        }
      
        return DossierPrechargementResource::collection($req);
    }

    public function searchCmdReceptionPre()
    {
        $user = Auth::user();

        $paginate = request('paginate');

        if (isset($paginate)) {

            $histo = DB::table('receptions')
            ->leftJoin('dossier_prechargements', 'dossier_prechargements.id', '=', 'receptions.dossier_prechargements_id')
            ->leftJoin('fournisseurs as four', 'receptions.fournisseurs_id', '=', 'four.id')
            ->leftJoin('users as a', 'dossier_prechargements.users_id', '=', 'a.id')
            ->leftJoin('users as b', 'receptions.users_id', '=', 'b.id')
            ->select('*','four.fonmfo as fonmfo', 'b.username as user_created','a.username as prechargeur')->where('receptions.dossier_prechargements_id', request('id_pre'))->where('receptions.clients_id', request('id'))->where('receptions.type_commandes_id', request('typecmd'));
            if(request('filtre_four')!=''){
                $histo = $histo->where('receptions.fournisseurs_id', request('filtre_four'));
            }
            if(request('filtre_cmd')!=''){
                $cmd = request('filtre_cmd');
                $term = "$cmd%";
                $histo = $histo->where('receptions.rencmd', 'like', $term);
            }
            $histo = $histo->paginate($paginate); 

        }else{
           
        }
      
        return ReceptionResource::collection($histo);

    }
}
