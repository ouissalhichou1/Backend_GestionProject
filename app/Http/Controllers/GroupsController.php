<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Database\QueryException;

class GroupsController extends Controller
{
    function getGroup(Request $request){ try{
        $group = new User;
        $group->id_user1 =$request->input('name');
        $group->id_user2 =$request->input('name');
        $group->id_user3 =$request->input('name');
        $group->id_user4 =$request->input('name');
        $group->id_user5 =$request->input('name');
        
        $group->save();
      
        return response()->json([
         'status'=>200,
         'message'=>'group a été bien crée',
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
