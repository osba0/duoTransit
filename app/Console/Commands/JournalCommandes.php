<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Notification;
use App\Notifications\receptionCommandes;


use Mail;
 
use App\Mail\receptionCommandesMail;


use Carbon\Carbon;
use App\Models\UserRole;
use DB;

class JournalCommandes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les saisies commandes du jour aux clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //\Log::info(env('APP_URL'));
        // Get clients 
        $clients =  DB::table('clients')->where("cletat", true)->get();

        foreach($clients as $client){
            // Get Orders du jour
        
            $query = DB::table('receptions')->whereBetween('recrea', [/*Carbon::yesterday()->toDateString().*/'2022-08-01 00:00:00', Carbon::today()->toDateString().' 23:00:00'])->where("receptions.clients_id", $client->id)
            ->leftJoin('type_commandes', 'receptions.type_commandes_id', '=', 'type_commandes.id')
            ->leftJoin('fournisseurs', 'receptions.fournisseurs_id', '=', 'fournisseurs.id')
            ->leftJoin('entrepots', 'receptions.entrepots_id', '=', 'entrepots.id')
            ->leftJoin('entites', 'receptions.entites_id', '=', 'entites.id')
            ->leftJoin('clients', 'receptions.clients_id', '=', 'clients.id')->get();


            $getMailClient = DB::table('users')->whereJsonContains('roles', UserRole::ROLE_CLIENT)->whereJsonContains('client_supervisor', intval($client->id))->get();

            $emailSent=[];

            foreach($getMailClient as $user){
            
                $emailSent[] = $user->email;
            }


            Mail::to($emailSent)->send(new receptionCommandesMail($query));
         
            if (Mail::failures()) {
                   \Log::info('Sending to '.$client->clnmcl.': Sorry! Please try again latter');
            }else{
                \Log::info('Sending to '.$client->clnmcl.': Great! Successfully send in your mail');
            }
        }
        
    }
}
