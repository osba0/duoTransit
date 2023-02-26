<?php

namespace App\Imports;

use App\Models\ImportCommandes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Models\Fournisseur;



class CommandesImport implements ToModel, WithHeadingRow
{
    public $client=null;
    public $typeCmd=null;

    public function __construct($client, $type){
        $this->client = $client;
        $this->typeCmd = $type;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = Auth::user();
        // control fournisseur exist or not
        $is_exist =  Fournisseur::where('fonmfo','=',strtoupper($row['fournisseur']))->get()->first(); 
        if(!$is_exist){
            $name='';
            $email='';

            if(isset($row['email'])){
              $name = $this->get_string_between($row['email'], "'", "'");
              $email= $this->get_string_between($row['email'], "<", ">");
            }

            $store = Fournisseur::create([
                "fonmfo" => strtoupper($row['fournisseur']),
                "fogefo" => $name,
                "foemail" => $email, 
                "foadrs" => '',
                "fotele" => '',
                'fologo' => '',
                "foetat" => 1
            ]);
        }
        
        // check if order exist or not
        $is_cmd_exist =  ImportCommandes::where('fournisseur','=',strtoupper($row['fournisseur']))->where('commandes','=',$row['commandes'])->get()->first(); 
        if(!$is_cmd_exist){
            return new ImportCommandes([
                'type_commande'     => $this->typeCmd, //$row['type_commande'],
                'fournisseur'       => $row['fournisseur'], 
                'commandes'         => $row['commandes'], 
                'date_transmission' => $this->transformDate($row['date_transmission']),
                'user_import'       => $user->username, 
                'client'            => $this->client['clnmcl']
            ]);
        }
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

    public  function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
