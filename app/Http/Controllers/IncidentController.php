<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Reception;
use App\Models\Incidents;
use App\Http\Resources\IncidentResource;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use File;

class IncidentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //If user is not logged in then he can't access this page
    }
    public function index(Request $request){

        $client = Client::get()->where('slug', request('id'))->first();
        if(!$client){
            abort(404);
        }

        $data = ['logo' => $client->cllogo, 
                'id_client' => $client->id, 'client' => $client, 'id_user' => $request->session()->get('idUser')];

        return  view('backend.incidents.index', $data);
   }
   public function filterCommande(Request $request)
    {
        $data = Reception::where('rencmd', 'LIKE','%'.$request->keyword.'%')->get();
        return response()->json($data); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        try{   
            $filename = '';
            $allFileName=[];
            

           for ($x = 0; $x < $request->TotalFiles; $x++) 
           {
 
               if ($request->hasFile('files'.$x)) 
                {
                    $current_date_time = Carbon::now()->toDateTimeString();
                    $paseDate = explode(' ', $current_date_time);
                    $file     = $request->file('files'.$x); 
                    $filename = 'photo'.'_'.request('commandes').'_'.($x+1).'_'.$paseDate[0].'_'.str_replace(":","-",$paseDate[1]).'.'.$file->getClientOriginalExtension();

                    $file->move("assets/incidents/", $filename);
                    array_push($allFileName, $filename);
                  
                }
           }       
            
            $store = Incidents::create([
                "objet" => request('objet'),
                "commandes"  => request('commandes'),
                "commentaire"     => request('commentaires'), 
                "clients_id" => request('idClient'), 
                "users_id" => $user->id,    
                "entites_id" => $user->entites_id,    
                "photos" => json_encode($allFileName),
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $user = Auth::user();
        
        $paginate = request('paginate');
        $cmd = request("cmd"); // pour les details dans reception

        if (isset($paginate)) {
            $incidents = Incidents::where('clients_id',request('id'))->where('entites_id', $user->entites_id);
            if(isset($cmd)){
                 $incidents = $incidents->where("commandes", request("cmd"));
            
            }

            $incidents = $incidents->orderBy('id', 'desc')->paginate($paginate);
        }else{
            $incidents = Incidents::get()->where('clients_id',request('id'))->where('entites_id', $user->entites_id); 
        }

        return IncidentResource::collection($incidents);
    }


        public function delete(Request $request)
    {
       
       
       if(!is_null(request('logos')) || !empty(request('logos'))){

            $allLogos = json_decode(request('logos'));

            for($i=0; $i < sizeof($allLogos); $i++){
                File::delete("assets/incidents/".$allLogos[$i]);
            }
        }
        $incident = Incidents::where('id', request('id'))->delete();

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

}
