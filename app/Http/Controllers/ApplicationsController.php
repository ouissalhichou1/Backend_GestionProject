<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationsController extends Controller
{
    function SaveApplication(Request $request,$id_group_admin){
        
        $users = User::find($id_group_admin);
        return $users->group->id_group;
        

        //$application = User::group()->id_group;
        //return $users;
    }
}
