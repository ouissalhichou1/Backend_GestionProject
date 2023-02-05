<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'lettre_motivation', 'cv'];
    protected $table = 'file';

    public function Demande()
    {
        return $this->belongsTo(Demande::class);
    }


    
}
