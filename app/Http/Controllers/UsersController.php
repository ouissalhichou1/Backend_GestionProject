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
    function GetMyApplications(Request $request, $id_user)
{
    $id_group = DB::select('select id from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_user,$id_user,$id_user,$id_user,$id_user]);
    $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
    $id_group = $id_group[0]["id"];
    $group = Group::find($id_group);
    if ($group) {
        $applications = Application::with('project')
            ->where('id_group', $group->id)
            ->get();

        $result = [];
        foreach ($applications as $application) {
            $project = $application->project;

            $result[] = [
                'application_id' => $application->id,
                'group_id' => $application->id_group,
                'project_sujet' => $project->sujet,
                'project_filiere' => $project->filiere,
                'project_user_id' => $project->id_user,
                'project_creator_name' => $project->user->name,
                'project_creator_surname' => $project->user->surname,
            ];
        }

        return response()->json(['applications' => $result]);
    }

    return response()->json(['message' => 'Group not found.'], 404);
}
    
}

