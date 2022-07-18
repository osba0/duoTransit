<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_log';
    protected $fillable = [

        //'subject', 'url', 'method', 'ip', 'agent', 'users_id', 'action', 'clients_id', 'role'
        'subject_id', 'subject_type', 'description', 'updated_at', 'created_at', 'log_name'

    ];
 
    use HasFactory;
}
