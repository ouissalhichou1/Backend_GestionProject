<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    function SaveRole(Request $request){

        try{
            $roles = new Role;
            $roles->RoleName =$request->input('RoleName');
            $roles->save();
            return response()->json([
             'status'=>200,
             'message'=>'role a ete bien enregistrer',
       ]);
       }
       catch(QueryException $e){
           return response()->json([
           'status'=>1020,
           'message'=>$e->getMessage()
           ]);
        }
    }
}
