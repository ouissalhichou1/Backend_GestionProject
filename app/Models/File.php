<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['id','id_user','name','path','type'];
    protected $table = 'file';

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Project()
    {
        return $this->belongsTo(Project::class);
    }



    
}
