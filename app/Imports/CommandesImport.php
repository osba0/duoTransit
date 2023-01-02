<?php

namespace App\Imports;

use App\Models\ImportCommandes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;



class CommandesImport implements ToModel, WithHeadingRow
{
    public $client=null;

    public function __construct($client){
        $this->client = $client;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = Auth::user();

        return new ImportCommandes([
            'type_commande'     => $row['type_commande'],
            'fournisseur'       => $row['fournisseur'], 
            'commandes'         => $row['commandes'], 
            'date_transmission' => $this->transformDate($row['date_transmission']),
            'user_import'       => $user->username, 
            'client'            => $this->client['clnmcl']
        ]);
    }


    /**
         * Transform a date value into a Carbon object.
         *
         * @return \Carbon\Carbon|null
         */
        public function transformDate($value, $format = 'd/m/Y')
        {
            try {
                return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            } catch (\ErrorException $e) {
                return \Carbon\Carbon::createFromFormat($format, $value);
            }
        }
}
