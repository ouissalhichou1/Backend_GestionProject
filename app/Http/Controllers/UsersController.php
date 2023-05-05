<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Project;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    function GetAllProjects(){
        $projects = Project::all();
        $data = [];
        foreach ($projects as $project) {
            $result = DB::select('select name ,surname,email from users where id = ?', [ $project->id_user]);
            $result = array_map(function ($value) {return (array)$value;}, $result);
            $project->owner_name = $result[0]["name"]." ".$result[0]["surname"];
            $project->owner_email =$result[0]["email"];
            $data[] = $project;
        }
        return response()->json([
            'status' => '200',
            'message'=>'All Projects',
            'data' => $data,
        ]);
    }
    public function downloadZipFile(){
        $type ='ConseilPFE.zip';
        $id = DB::select('select id from file where type = ? ',[$type]);
        $id = array_map(function ($value) {return (array)$value;}, $id);
        $id = $id[0]["id"];
        $zipFile = File::findOrFail($id);
        $file = Storage::disk('public')->get($zipFile->path);
        return response($file, 200)->header('Content-Type', 'application/zip')
        ->header('Content-Disposition', 'attachment; filename="' . $zipFile->name . '"');
    }
    
    function CreateMeeting(Request $request , $id_user){

        $rendez_vous = new RendezVous;
        $id_group = DB::select('select id from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_user,$id_user,$id_user,$id_user,$id_user]);
        $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
        $id_group = $id_group[0]["id"];
        $id_project = DB::select('select id_project from applications where id_group = ?',[$id_group]);
        $id_project = array_map(function ($value) {return (array)$value;}, $id_project);
        $id_project = $id_project[0]["id_project"];
        $to = DB::select('select id_user from projects where id = ?', [$id_project]);
        $to = array_map(function ($value) {return (array)$value;}, $to);
        $to = $to[0]["id_user"];
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

}

