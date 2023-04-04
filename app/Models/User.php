<?php

namespace App\Models;

use App\Models\File;
use App\Models\Group;
use App\Models\Project;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject , MustVerifyEmail
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
    public function getJWTCustomClaims()
    {
        return [];
    }

   
}
