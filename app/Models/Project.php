<?php

namespace App\Models;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['id','sujet', 'id_user', 'filiere', 'description','NbrPersonnes'];
    protected $table = 'projects';

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Group()
    {
        return $this->belongsToMany('App\Models\Group','application')->withPivot('accepted');
    }
    
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
