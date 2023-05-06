<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['id','id_user','title','description'];
    protected $table = 'task';
    
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
}
