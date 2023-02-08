<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['id_group','id_user1', 'id_user2', 'id_user3', 'id_user4', 'id_user5', 'id_user'];
    protected $table = 'group';

    public function Project()
    {
        return $this->belongsToMany('App\Models\Project','Demandes');
    }
}
