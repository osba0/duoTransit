<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use App\Http\Resources\UserResource; 
use App\Http\Resources\ClientResource; 
use App\Models\Client;
use App\Models\TypActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Contracts\Activity;

class UserController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth'); //If user is not logged in then he can't access this page
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if(!($user->hasRole(UserRole::ROLE_ADMIN) || $user->hasRole(UserRole::ROLE_ROOT))){
             abort(401);
        }
        
        $client = Client::get();

        $isAdmin = 0;
        
        if($user->hasRole(UserRole::ROLE_ADMIN)){
            $isAdmin = 1;
            $roles = UserRole::getRoleAdminList(); 
        }else{
            $roles = UserRole::getRoleList(); 
        }

        activity(TypActivity::LISTER)->log('Affichage page utilisateur');

        return  view('backend.user.index', ['clients' => $client, 'roles' => $roles, 'isAdmin' => $isAdmin]);
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

        if (isset($paginate)) {
            if($user->hasRole(UserRole::ROLE_ADMIN)){
                  $users = User::whereJsonContains("roles", $user->roles[0])->orderBy('id', 'desc')->paginate($paginate);
            }else{
                 $users = User::orderBy('id', 'desc')->paginate($paginate);
            }
           
        }else{
            $users = User::get();
        }

        return UserResource::collection($users);
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
        $check = User::where('username', request('login'))->get();
        
        if(sizeof($check) > 0){
            return response([
                "code" => 1,
                "message" => "Login existe déja!"
            ]);
        }

        try{            
            
            $store = User::create([
                "firstname" => request('nom'),
                "lastname"  => request('prenom'),
                "username"     => request('login'),
                "email"     => request('email'),
                "roles"    => array(request('profil')),
                "password"  => Hash::make(request('password')),
                "entites_id" => $user->entites_id,
                "client_supervisor" => (request('profil')==UserRole::ROLE_CLIENT?json_decode(request('clientsAuth')):'')    
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword($id)
    {
        //$check = User::where('password', Hash::make(request('passwordactual')))->get();
         $hashedPassword = Auth::user()->password;
 
       if (\Hash::check(request('passwordactual') , $hashedPassword )) {
            User::where('id', request('id'))
              ->update([
              "password"  => Hash::make(request('password')),  

              ]);
        }else{
            return response([
                "code" => 1,
                "message" => "Mot de passe actuel erroné!"
            ]);
        }
     return response([
            "code" => 0,
            "message" => "OK"
        ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user =  User::findOrFail(request('id'));
        $user->update([
                "firstname" => request('nom'),
                "lastname"  => request('prenom'),
                "email"     => request('email'),
                "roles"    => array(request('profil')),
                "client_supervisor" => (request('profil')==UserRole::ROLE_CLIENT?json_decode(request('clientsAuth')):'')  

          ]);

        /* User::where('id', request('id'))
              ->update([
                "firstname" => request('nom'),
                "lastname"  => request('prenom'),
                "email"     => request('email'),
                "roles"    => array(request('profil')),
                "client_supervisor" => (request('profil')==UserRole::ROLE_CLIENT?json_decode(request('clientsAuth')):'')  

          ]);*/

        return response([
            "code" => 0,
            "message" => "OK"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail(request('id'));
        $user->delete();
        //User::where('id', request('id'))->delete();

        return response([
            "code" => 0,
            "message" => "OK" 
        ]);
    }
}