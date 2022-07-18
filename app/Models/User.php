<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

//use Spatie\Activitylog\Traits\LogsActivity;
/**
 * App\Models\User
 *
 * @property array|null $roles

 */

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens/*, LogsActivity*/;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'email',
        'password',
        'username',
        'lastname',
        'roles',
        'client_supervisor',
        'password',
        'entites_id'
    ];

    //protected static $logName = 'user';

    //protected static $ignoreChangedAttributes = ['password'];

    //protected static $logAttributes = ['firstname', 'email'];

    //protected static $recordEvents = ['created', 'updated', 'deleted'];


    /*public function getDescriptionForEvent(string $eventName): string
    {
        return "Utilisateur a été {$eventName}";
    }*/

    //protected static $logOnlyDirty = true;

     public function entite()
    {
        return $this->belongsTo(Entite::class, 'entites_id'); 
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles'             => 'array',
        'client_supervisor' => 'array',
    ];

     /**
     * @param string $role
     * @return $this
     */
    public function addRole(string $role)
    {
        $roles = $this->getRoles();
        $roles[] = $role;

        $roles = array_unique($roles);
        $this->setRoles($roles);

        return $this;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->setAttribute('roles', $roles);
        return $this;
    }

    /***
     * @param $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    /***
     * @param $roles
     * @return boolean
     */
    public function hasRoles($roles)
    {
        $currentRoles = $this->getRoles();
        foreach($roles as $role) {
            if ( ! in_array($role, $currentRoles )) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->getAttribute('roles');

        if (is_null($roles)) {
            $roles = [];
        }

        return $roles;
    }

     /**
     * @return array
     */
    public function getClientSupervisor()
    {
        $clientSup = $this->getAttribute('client_supervisor');

        if (is_null($clientSup)) {
            $clientSup = [];
        }

        return $clientSup;
    }

    /**
     * Get the activities for the user.
     *
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    
}
