<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{


    function SaveUser2(Request $request){

        try{

            $users = new User;
            $users->name =$request->input('name');
            $users->surname =$request->input('surname');
            $users->specialite =$request->input('specialite');
            $users->code =$request->input('code');
            $users->email =$request->input('email');
            $users->password = Hash::make($request->input('password'));
            $users->save();
            $role_users = new RoleUser;
            $role_users->id_user = ;
            $role_users->id_role ;
            $role_users->save();
    
           return response()->json([
          'status'=>200,
          'message'=>'ensigant a ete bien enregistrer',
       ]);
       }
   
       catch(QueryException $e){
           //$arr = explode(':', $e->getMessage());
           //$arr2 = explode(" ", $arr[2])[7];
       return response()->json([
           'status'=>1020,
           //'message'=>$arr2,
           'message'=>$e->getMessage()
        ]);

   }
}


function SaveUser3(Request $request){

    try{

   $users = new User;
   $users->name =$request->input('name');
   $users->surname =$request->input('surname');
   $users->apogee =$request->input('apogee');
   $users->filiere =$request->input('filiere');
   $users->email =$request->input('email');
   $users->password = Hash::make($request->input('password'));
   $users->save();
   


   return response()->json([
      'status'=>200,
      'message'=>' etudiant a ete bien enregistrer',
   ]);
   }

   catch(QueryException $e){
       //$arr = explode(':', $e->getMessage());
       //$arr2 = explode(" ", $arr[2])[7];
   return response()->json([
       'status'=>1020,
       //'message'=>$arr2,
       'message'=>$e->getMessage()
    ]);

}
}
   

   function listUsers(){
       return Users::all();
   }
}
