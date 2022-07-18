<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Entrepot;
use App\Models\User;
use App\Models\Reception;
use App\Models\TypeCommande;
use App\Models\Entite;
use App\Models\Contenaire;
use App\Http\Resources\TypeCommandeResource;
use App\Http\Resources\FournisseurResource;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;



class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::getUser();
        $client = Client::get()->count();
        $fournisseur = Fournisseur::get()->count();
        $entrepot = Entrepot::get()->count();
        $dry = Reception::get()->count();
        $entite = Entite::get()->count();
        $contenaire = Contenaire::get()->count();


        $users = User::where("entites_id", $user->entites_id)->get()->count();
      

        $fournis = Fournisseur::get();
        $typeCmd = TypeCommande::get();

        if(sizeof($typeCmd) > 0){
            $typeCommande = TypeCommandeResource::collection($typeCmd);
            foreach ($typeCommande as $key) {
               
               $listTypeCmd[] = (object) ['typcmd' => $key['typcmd'], 'id' => $key['id'], 'color' => $key['tcolor']];

            }
        }
        
        $request->session()->put('typeCmd', $listTypeCmd);
        $request->session()->put('fournis', $fournis);
        $request->session()->put('idUser', $user->id);
        $request->session()->put('clientSup', $user->getClientSupervisor()); 

       
        return  view('home', ['roles' => $user->roles[0],'totalClient' => $client, 'totalEntite' => $entite,'totalContenaire' => $contenaire, 'totalUser' => $users,'totalFournisseur' => $fournisseur, 'totaEntrepot' => $entrepot,"typeCmdNbr" => $typeCmd->count(),"fourNbr" => $fournis->count(), 'totalCommande'=> $dry, 'fournis' => FournisseurResource::collection($fournis), 'clientSup'=> json_encode($user->getClientSupervisor())]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
