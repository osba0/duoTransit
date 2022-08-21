<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TypActivity;

use App\Http\Resources\ActivityResource;

class ActivityController extends Controller
{
    public function index(){

        $client = Client::get()->where('slug', request('id'))->first();
        if(!$client)  abort(404);

        return  view('backend.activity.index', ['logo' => $client->cllogo, 'id_client' => $client->id,  'client' => $client]);
    }

    public function getData(Request $request){

        $user = Auth::user();

        $client = Client::get()->where('slug', request('id'))->first();
        

        $paginate = request('paginate');

        if (isset($paginate)) {

            $query = DB::table('activity_log')
            ->leftJoin('users as a', 'activity_log.causer_id', '=', 'a.id')
            ->select('*','a.username as user','a.firstname as firstname','a.lastname as lastname', 'activity_log.id as idLog', 'activity_log.created_at as dateLog')->orWhere("subject_id", $client['id']);


            if(request('type')!=''){
                switch(request('type')){
                    case 'connexion': 
                        $query = $query->where('log_name', '=', TypActivity::CONNEXION)->orWhere('subject_type',$user->entites_id)->where('subject_id',null);
                    break;

                    case 'affichage': 
                        $query = $query->where('log_name', '=', TypActivity::LISTER);
                    break;

                    case 'insertion': 
                        $query = $query->where('log_name', '=', TypActivity::CREER);
                    break;

                    case 'modification': 
                        $query = $query->where('log_name', '=', TypActivity::MODIFIER);
                    break;

                     case 'suppression': 
                        $query = $query->where('log_name', '=', TypActivity::SUPPRIMER);
                    break;

                    default: 
                        $query = $query->where('log_name', '=', '-1');
                }

            }else{
                $query = $query->orWhere('subject_type',$client['clenti'])->where("subject_id", $client['id'])->orWhere('subject_id',null);
            }
            // A decommanter pour obtenir le log des connexion -> where("log_name",'!=', TypActivity::CONNEXION) 
            $query = $query->where("log_name",'!=', TypActivity::CONNEXION)->orderBy('activity_log.created_at', 'desc')->paginate($paginate);

        }else{
           
        }
      
        return ActivityResource::collection($query);
    }

    public function getCount(string $type): JsonResponse
    {
         $user = Auth::user();


        $query = DB::table('activity_log')->where("subject_id", request('client_id'));

        try {
            switch($type){
                case 'connexion': 
                    $query = $query->where('log_name', '=', TypActivity::CONNEXION)->orWhere('subject_type',$user->entites_id)->where('subject_id',null);
                    //$query = $query->whereDate('imported_at', '=', Carbon::today());
                break;

                case 'affichage': 
                    $query = $query->where('log_name', '=', TypActivity::LISTER);
                break;

                case 'insertion': 
                    $query = $query->where('log_name', '=', TypActivity::CREER);
                break;

                case 'modification': 
                    $query = $query->where('log_name', '=', TypActivity::MODIFIER);
                break;

                 case 'suppression': 
                    $query = $query->where('log_name', '=', TypActivity::SUPPRIMER);
                break;

                default: 
                    $query = $query->where('log_name', '=', '-1');
            }
           

            return response()->json(
                [
                    'message' => 'Success',
                    'count' => $query->get()->count()
                ]
            );

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
