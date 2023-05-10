<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;
    protected $fillable = ['id','user_id','title','message','group_id'];
    protected $table = 'annonces';

    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
}
