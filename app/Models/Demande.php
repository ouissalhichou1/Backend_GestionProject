<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Demande extends Pivot
{
    use HasFactory;
    protected $fillable = ['id_demande','id_group', 'id_projet'];
    protected $table = 'demandes';
    
    
    

}
