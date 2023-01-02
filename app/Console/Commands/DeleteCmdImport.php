<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\ImportCommandes;
use Carbon\Carbon;

class DeleteCmdImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:cmd_importe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimé les commandes importées de plus de 3 mois';

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
        $from= Carbon::now()->subDays(30)->toDateString();

        $current = Carbon::now()->toDateString();

        //$res = ImportCommandes::whereBetween('date_transmission', array($diff,$current))->delete();

        $res = ImportCommandes::where('commandes', '59498')->delete(); 

        \Log::info('Delete '.$res);
    }
}
