<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    function SaveFile(Request $request){ 
        try{
          $file = new File;
          $file->path =$request->input('path');
          $file->type =$request->input('type');
          $file->save();
        
          return response()->json([
           'status'=>200,
           'message'=>'file a été bien crée',
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
