<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\UserRole;
use App\Models\ImportCommandes;
use App\Http\Resources\ImportCMDResource;
use App\Imports\CommandesImport;

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


        if(is_null($client)){
            $data = ['logo' => '', 'id_client' => ''];
        }else{
            $data = ['logo' => $client->cllogo, 'id_client' => $client->id, 'client' => $client];
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
        
        if(isset($paginate)) {
            $cmds = ImportCommandes::paginate($paginate);
        }else{
            $cmds = ImportCommandes::get();
        }



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
      // var_dump($client ); die();
      Excel::import(new CommandesImport($client),request()->file('file'));

             
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
