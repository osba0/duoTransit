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

use App\Http\Resources\EmpotageResource;
use App\Http\Resources\ReceptionResource;


class HistoActionController extends Controller
{
  

    public function historiqueEmpotage(Request $request){

        $user = Auth::user();

        if(!($user->hasRole(UserRole::ROLE_ADMIN) || $user->hasRole(UserRole::ROLE_ROOT))){
             abort(401);
        }

        $entite = Entite::where('id', $user->entites_id)->get()->first();
        
        $client = Client::get()->where('slug', request('id'))->first();

        if(!$client)  abort(404);

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->get(); 

        $fournis = Fournisseur::whereIn('id',$client->clfocl)->get(); 

        $contenaires = Contenaire::whereIn('id',$entite->contenaires_id)->get(); 
        
        return  view('backend.historique_empotage.index', ['logo' => $client->cllogo, 'id_client' => $client->id, 'typeCmd' => $typeCmd, 'client' => $client, 'fournisseurs' => $fournis, 'listContenaire' => $contenaires]);
    }

    public function searchHisto(Request $request){
        //var_dump(request('filtre.typeCmd'));

        $user = Auth::user();

        $paginate = request('paginate');

        if (isset($paginate)) {

            $req = DB::table('empotages')
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
                'empotages.rapport_pdf as rapport_pdf',
                'empotages.created_at as created_at',
                'users.username as user',
                'type_commandes.typcmd as typecmd',
                'type_commandes.id as typecmdID',
                'contenaires.nom as contenaire')->where('receptions.clients_id', request('id'))->whereBetween('empotages.created_at', [request('filtre.dateDebut'), request('filtre.dateFin')]);

            if(request('filtre.typeCmd')!=''){
                $req = $req->where('receptions.type_commandes_id', request('filtre.typeCmd'));
            }
            if(request('filtre.fournisseur')!=''){
                $req = $req->where('receptions.fournisseurs_id', request('filtre.fournisseur'));
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

        if (isset($paginate)) {

            $histo = DB::table('receptions')
            ->leftJoin('empotages', 'empotages.id', '=', 'receptions.dossier_empotage_id')
            ->leftJoin('fournisseurs as four', 'receptions.fournisseurs_id', '=', 'four.id')
            ->leftJoin('users as a', 'empotages.users_id', '=', 'a.id')
            ->leftJoin('users as b', 'receptions.users_id', '=', 'b.id')
            ->select('*','four.fonmfo as fonmfo', 'b.username as user_created','a.username as prechargeur')->where('receptions.dossier_empotage_id', request('id_empotage'))->where('receptions.clients_id', request('id'))->where('receptions.type_commandes_id', request('typecmd'));
            if(request('filtre_four')!=''){
                $histo = $histo->where('receptions.fournisseurs_id', request('filtre_four'));
            }
            $histo = $histo->paginate($paginate); 

        }else{
           
        }
      
        return ReceptionResource::collection($histo);

    }
}
