<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Database\QueryException;

class GroupsController extends Controller
{
    function SaveGroup(Request $request){ 
      try{
        $group = new Group;
        $group->id_user1 =$request->id_user1;
        $group->id_user2 =$request->id_user2;
        $group->id_user3 =$request->id_user3;
        $group->id_user4 =$request->id_user4;
        $group->id_user5 =$request->id_user5;
        $group->id_project=$request->id_project;
        $group->save();
       
        return response()->json([
         'status'=>200,
         'message'=>'group a été bien crée',
         'data' => $group
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
