<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['id','id_group_admin', 'id_user2', 'id_user3', 'id_user4', 'id_user5'];
    protected $table = 'groups';

    public function Project()
    {
        return $this->belongsToMany('App\Models\Project','application')->withPivot('accepted');
    }
    public function User()
    {
        return $thid->hasMany(User::class);
    }
}
