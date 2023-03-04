<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['id','sujet', 'id_user', 'filiere', 'description'];
    protected $table = 'projects';

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Group()
    {
        return $this->belongsToMany('App\Models\Group','application')->withPivot('accepted');
    }
    public function file()
    {
        return $this->hasMany(file::class);
    }
}
