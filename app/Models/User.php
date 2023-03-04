<?php

namespace App\Models;

use App\Models\File;
use App\Models\Group;
use App\Models\Project;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
}
