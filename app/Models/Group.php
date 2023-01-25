<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['id','id_etudiant1', 'id_etudiant2', 'id_etudiant3', 'id_etudiant4', 'id_etudiant5', 'id_projet'];
    protected $table = 'group';
}
