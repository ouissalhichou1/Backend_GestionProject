<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['id_file','path','type','id_user','id_project'];
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
