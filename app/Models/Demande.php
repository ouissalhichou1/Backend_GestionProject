<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;
    protected $fillable = ['id','id_files', 'id_etudiant', 'id_projet'];
    protected $table = 'demandes';

    

}
