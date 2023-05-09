<?php

namespace App\Models;

use App\Models\File;
use App\Models\task;
use App\Models\Group;
use App\Models\Annonce;
use App\Models\Project;
use App\Models\Application;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = ['id','name','surname','email', 'password','code', 'specialite','apogee', 'filiere','email_verified_at','email_verification_token'];
    protected $table = 'users';

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role','role_users');
    }
    public function projects()
{
    return $this->hasMany(Project::class, 'id_user');
}
    public function file()
    {
        return $this->hasMany(File::class);
    }
    public function task()
    {
        return $this->hasMany(task::class);
    }
    public function annonce()
    {
        return $this->hasMany(Annonce::class);
    }
    
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function applications()
    {
        return $this->hasMany(Application::class);
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
        return [
           // 'role' => $this->role,
        ];
    }
    
   
}
