<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class invitations extends Pivot
{
    use HasFactory;
    protected $fillable = ['id','id_group', 'id_etudiant','response'];
    protected $table = 'applications';
}
