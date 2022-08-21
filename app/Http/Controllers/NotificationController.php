<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entite;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //If user is not logged in then he can't access this page
    }

     public function index(){
        $user = Auth::user();

        $entite = Entite::where('id', $user->entites_id)->get()->first();
        
        $client = Client::get()->where('slug', request('client'))->first();

        if(!$client)  abort(404);

        return  view('notification/index', ['logo' => $client->cllogo, 'client' => $client]);
    }


    public function listeNotification(){
        $user = Auth::user();

        $paginate = request('paginate');
        
        $notification = $user->notifications()->paginate($paginate);
        
       return NotificationResource::collection($notification);
    }

    public function marquerLu()
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->where('id',request('id'))->first();

        $notification->markAsRead();

          return response([
                "code" => 0,
                "message" => ""
            ]);
    }

     public function marquerToutLu()
    {
        $user = Auth::user();

        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return response([
            "code" => 0,
            "message" => ""
        ]);
    }


    public function show(){


        $user = Auth::user();

        $entite = Entite::where('id', $user->entites_id)->get()->first();
        
        $client = Client::get()->where('slug', request('client'))->first();

        if(!$client)  abort(404);
        
        $notification = $user->notifications()->where('id',request('notification_id'))->first();

        $notification->markAsRead();

        return  view('notification/view', ['client'=> $client, 'logo' => $client->cllogo, 'notification' => $notification]);

       

    }
}
