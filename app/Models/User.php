<?php

namespace App\Models;

use App\Models\File;
use App\Models\Todo;
use App\Models\Group;
use App\Models\Project;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = ['id','name','surname','email', 'password','code', 'specialite','apogee', 'filiere'];
    protected $table = 'users';

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role','role_users');
    }
    public function Project()
    {
        return $this->hasMany(Project::class);
    }
    public function file()
    {
        return $this->hasMany(File::class);
    }
    public function todo()
    {
        return $this->hasMany(Todo::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
 /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        
        $fillableAttributes = $this->getFillableAttributes();
        $role = $this->roles;
        $roleArray =$role->toArray();
        // $role=array_map(function ($value) {return (array)$value;}, $role);
        //echo gettype($role);
        //print_r($roleArray);
        // print_r($fillableAttributes);
        return [
        $fillableAttributes["id"],
        $fillableAttributes["name"],
        $fillableAttributes["surname"],
        $role[0]["RoleName"],
        //$role->roleName,
        //$fillableAttributes->userRoles
        ];
        }
    
    public function getFillableAttributes()
    {
        $fillableAttributes = [];

        foreach ($this->getFillable() as $fillableAttribute) {
            $fillableAttributes[$fillableAttribute] = $this->$fillableAttribute;
        }

        return $fillableAttributes;
    }
   
}
