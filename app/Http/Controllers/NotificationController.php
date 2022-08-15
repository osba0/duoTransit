<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entite;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //If user is not logged in then he can't access this page
    }


    public function show(){
        $user = Auth::user();

        $entite = Entite::where('id', $user->entites_id)->get()->first();
        
        $client = Client::get()->where('slug', request('client'))->first();

        if(!$client)  abort(404);
        
        $notification = $user->notifications()->where('id',request('notification_id'))->first();

        $notification->markAsRead();


        return  view('notification/view', ['logo' => $client->cllogo, 'notification' => $notification]);

       

    }
}
