<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\UserRole;
use App\Models\ImportCommandes;
use App\Http\Resources\ImportCMDResource;
use App\Imports\CommandesImport;
use App\Models\TypeCommande;
use App\Models\Fournisseur;

use Maatwebsite\Excel\Facades\Excel;

class ManageExcelController extends Controller
{
        /**

    * @return \Illuminate\Support\Collection

    */

    public function importExportView()

    {

        $user = Auth::user();

        if(is_null($user)){
            return  redirect(route('login'));
        }

        if(!($user->hasRole(UserRole::ROLE_CLIENT))){
             abort(401);
        }



        $client = Client::where('slug', request('slug'))/*->whereJsonContains('clenti', Auth::getUser()->entites_id)*/->get()->first();

        if(!$client){
            abort(404);
        }

        $typeCmd = TypeCommande::whereIn('id',$client->cltyco)->where("etat", true)->get(); 

        $fournisseurs = Fournisseur::get(); 



        if(is_null($client)){
            $data = ['logo' => '', 'id_client' => ''];
        }else{
            $data = ['logo' => $client->cllogo, 'id_client' => $client->id, 'client' => $client, 'typeCmd' => $typeCmd, 'fournisseurListe' => $fournisseurs];
        }
        
        return  view('import', $data);

    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCommandes()
    {
        $user = Auth::user();
        $paginate = request('paginate');


        $cmds = ImportCommandes::importcmdQuery();

        if(isset($paginate)) {
            $cmds = $cmds->paginate($paginate);
        }else{
            $cmds = $cmds->get(); 
        }
        
        /*if(isset($paginate)) {
            if(request('etat')!=""){
                 $cmds = ImportCommandes::where("etat_cmd", request('etat'))->paginate($paginate);
            }else{
                $cmds = ImportCommandes::paginate($paginate);
            }
           
        }else{
            if(request('etat')!=""){
                $cmds = ImportCommandes::where("etat_cmd", request('etat'))->get();
            }else{
                $cmds = ImportCommandes::get();
            }
            
        }*/



        return ImportCMDResource::collection($cmds);
        
    }

     

    /**

    * @return \Illuminate\Support\Collection

    */

    public function export() 

    {

        return Excel::download(new UsersExport, 'users.xlsx');

    }

     

    /**

    * @return \Illuminate\Support\Collection

    */

    public function import() 

    {

      $client = Client::where('slug', request('slug'))->get()->first();
   
      Excel::import(new CommandesImport($client, request('typeCommande')),request()->file('file'));
       
      return response([
            "code" => 0,
            "message" => "ok"
        ]);

    }

     public function delete(Request $request)
    {
        $user = Auth::user();
        $cmd =  ImportCommandes::where('id','=',request('id'))->firstOrFail(); 
        $cmd->delete();

          return response([
                "code" => 0,
                "message" => "OK"
            ]);
    }


    

     public function checkCmd(Request $request)
    {
        $user = Auth::user();
        $cmd =  ImportCommandes::where('commandes', request('cmd'))->first(); 

          return response([
                "code" => 0,
                "commande" => $cmd
            ]);
    }
}
