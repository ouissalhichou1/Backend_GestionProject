<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function save(Request $request){

        try{
           
       $user = new users;
       $users->name =$request->input('nom');
       $users->surname =$request->input('prenom');
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
           $arr = explode(':', $e->getMessage());
           $arr2 = explode(" ", $arr[2])[7];
       return response()->json([
           'status'=>1020,
           'message'=>$arr2,
        ]);

   }
}
   

   function listUsers(){
       return Users::all();
   }
}
