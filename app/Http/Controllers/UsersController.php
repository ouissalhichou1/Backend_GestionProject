<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    function SaveProfessor(Request $request){

        try{
            $users = new User;
            $users->name =$request->input('name');
            $users->surname =$request->input('surname');
            $users->specialite =$request->input('specialite');
            $users->code =$request->input('code');
            $users->email =$request->input('email');
            $users->password = Hash::make($request->input('password'));
            $users->save();
            $users->roles()->attach([2,3]);
            

           return response()->json([
          'status'=>200,
          'message'=>'ensignant a ete bien enregistrer',
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

    function SaveStudent(Request $request){

        try{

            $users = new User;
            $users->name =$request->input('name');
            $users->surname =$request->input('surname');
            $users->apogee =$request->input('apogee');
            $users->filiere =$request->input('filiere');
            $users->email =$request->input('email');
            $users->password = Hash::make($request->input('password'));
            $users->save();
            $users->roles()->attach([2,4]);
   
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
   

    function listUsers(Request $request){

       return User::all();
    }
   
    function UserProfile(Request $request){

       $user =  User::find($request->id);
       if ($user) {
         return response()->json([
         'status'=>200,
         'message'=>'etudiant trouvé',
         'data' => $user
          ]);
        }
       else{
          return response()->json([
         'status'=>204,
          'message'=>'aucun étudiant trouvé',
          ]);
        }
    }
    



}
