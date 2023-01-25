<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['id','sujet', 'id_encadrant', 'filiere', 'description'];
    protected $table = 'projects';
}
