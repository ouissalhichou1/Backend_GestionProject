<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['id','id_user','title','description'];
    protected $table = 'todo';
    
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
}
