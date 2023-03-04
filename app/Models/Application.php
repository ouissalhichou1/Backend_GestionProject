<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Pivot
{
    use HasFactory;
    protected $fillable = ['id_application','id_group', 'id_project','response'];
    protected $table = 'applications';
    
    
    

}
