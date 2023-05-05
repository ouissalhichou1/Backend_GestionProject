<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role:Etu');
    }
    function SaveGroup(Request $request,$id_user){

        $group = new Group();
        $group->id_group_admin = $id_user;
        $group->id_user2 =$request->input('user2');
        $group->id_user3 =$request->input('user3');
        $group->id_user4 = $request->input('user4');
        $group->id_user5 = $request->input('user5');
        $group->save();
        return response()->json([
          'status' => 'created success',
          'group' => $group,
        ]);
    }
    function CreateMeeting(Request $request , $id_user){

        $rendez_vous = new RendezVous;
        $id_group = DB::select('select * from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_user,$id_user,$id_user,$id_user,$id_user]);
        $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
        $id_group = $id_group[0]["id"];
        $id_project = DB::select('select id_project from applications where id_group = ?',[$id_group]);
        $id_project = array_map(function ($value) {return (array)$value;}, $id_project);
        $id_project = $id_project[0]["id"];
        $to = DB::select('select user_id from projects where id = ?', [$id_project]);
        $to = array_map(function ($value) {return (array)$value;}, $to);
        $to = $to[0]["id"];
        $rendez_vous->creator = $id_user;
        $rendez_vous->date =$request->input('date');
        $rendez_vous->heure =$request->input('heure');
        $rendez_vous->objet = $request->input('objet');
        $rendez_vous->to =$to;
        $rendez_vous->save();
        return response()->json([
            'status' => '200',
            'message'=>'meet created successfully',
            'project' => $rendez_vous,
        ]);

    }
    

    function GetProjectsToApplyTo(){
        $projects = Project::all();
        $data = [];
        foreach ($projects as $project) {
           $result = DB::select('select name ,surname from users where id = ?', [ $project->id_user]);
           $result = array_map(function ($value) {return (array)$value;}, $result);
           $project->owner_name = $result[0]["name"]." ".$result[0]["surname"];
           $data[] = $project;
        }
        return response()->json([
           'status' => 'success',
           'data' => $data,
           ]);
       }
}
