<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;
    protected $fillable = ['id','creator','date','to','object'];
    protected $table = 'rendez_vous';


    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
}
