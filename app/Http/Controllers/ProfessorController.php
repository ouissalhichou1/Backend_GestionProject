<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Project;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProfessorController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role:Ens');
    }
    function CreateProject(Request $request,$id){
        
        $project = new Project;
        $project ->id_user =$id;
        $project->sujet =$request->input('sujet');
        $project->filiere =$request->input('filiere');
        $project->NbrPersonnes = $request->input('NbrPersonnes');
        $project->description =$request->input('description');
        $project->save();
        return response()->json([
            'status' => '200',
            'message'=>'Project created successfully',
            'project' => $project,
        ]);
    }
    function DeleteProject(Request $request, $id_user){
        $projects = DB::delete('delete from projects where id_user =? and id  = ?',[$id_user, $request->id_project]);
        return response()->json([
            'status' => 'success',
            'message '=>'project deleted',
            ]);
    }
    function ModifyProject(Request $request , $id_user){
        $id_project = $request->id_project;
        $updated_project = DB::update('update projects set sujet = ?, filiere = ?, description = ? where id_user = ? and id = ?',[$request->sujet ,$request->filiere, $request->description,$id_user,$id_project]);
        $updated_project = Project::find($id_project);
        return response()->json([
            'status' => 'success',
            'project' => $updated_project,
        ]);    
    }
    function GetMyProjects(Request $request , $id_user){

        $projects = DB::select('select * from projects where id_user =?',[$id_user]);
        return response()->json([
           'status' => 'success',
           'data' => $projects,
        ]);
    }
    function GetApplicationsForMyProjects(Request $request , $id_user){

        $results = DB::table('applications')
        ->join('groups', 'groups.id', '=', 'applications.id_group')
        ->join('projects', 'projects.id', '=', 'applications.id_project')
        ->join('users as creator', 'creator.id', '=', 'projects.id_user')
        ->join('users as member1', 'member1.id', '=', 'groups.id_group_admin')
        ->leftJoin('file as file1', function($join) {
            $join->on('file1.user_id', '=', 'member1.id')
                 ->where('file1.type', '=', 'releve_note');
        })
        ->leftJoin('users as member2', 'member2.id', '=', 'groups.id_user2')
        ->leftJoin('file as file2', function($join) {
            $join->on('file2.user_id', '=', 'member2.id')
                 ->where('file2.type', '=', 'releve_note');
        })
        ->leftJoin('users as member3', 'member3.id', '=', 'groups.id_user3')
        ->leftJoin('file as file3', function($join) {
            $join->on('file3.user_id', '=', 'member3.id')
                 ->where('file3.type', '=', 'releve_note');
        })
        ->leftJoin('users as member4', 'member4.id', '=', 'groups.id_user4')
        ->leftJoin('file as file4', function($join) {
            $join->on('file4.user_id', '=', 'member4.id')
                 ->where('file4.type', '=', 'releve_note');
        })
        ->leftJoin('users as member5', 'member5.id', '=', 'groups.id_user5')
        ->leftJoin('file as file5', function($join) {
            $join->on('file5.user_id', '=', 'member5.id')
                 ->where('file5.type', '=', 'releve_note');
        })
        ->select('projects.id as project_id',
                 'groups.id as group_id',
                 'projects.sujet',
                 'member1.name as member1_name',
                 'member1.surname as member1_surname',
                 'member1.email as member1_email',
                 'file1.path as member1_file_path',
                 'member2.name as member2_name',
                 'member2.surname as member2_surname',
                 'member2.email as member2_email',
                 'file2.path as member2_file_path',
                 'member3.name as member3_name',
                 'member3.surname as member3_surname',
                 'member3.email as member3_email',
                 'file3.path as member3_file_path',
                 'member4.name as member4_name',
                 'member4.surname as member4_surname',
                 'member4.email as member4_email',
                 'file4.path as member4_file_path',
                 'member5.name as member5_name',
                 'member5.surname as member5_surname',
                 'member5.email as member5_email',
                 'file5.path as member5_file_path')
        ->where('projects.id_user', $id_user)
        ->get();

        return response()->json([
            'status' => '200',
            'message'=>'Project fetched',
            'project' => $results,
        ]);
    }
    function ResponseforApplication(Request $request, $id_user){
        $id_project = $request->id_project;
        $id_group = $request->id_group;
        $response = $request->response;
        $applications = DB::update('update applications set response = ? where id_group= ?and id_project =?', [$response ,$id_group,$id_project]);
        return response()->json([
            'status' => 'success',
            'message' => 'done',
        ]); 
    }
    function GetMyGroups(Request $request , $id_user){
        $results = DB::table('applications')
            ->join('groups', 'groups.id', '=', 'applications.id_group')
            ->join('projects', 'projects.id', '=', 'applications.id_project')
            ->join('users as creator', 'creator.id', '=', 'projects.id_user')
            ->join('users as member1', 'member1.id', '=', 'groups.id_group_admin')
            ->leftJoin('users as member2', 'member2.id', '=', 'groups.id_user2')
            ->leftJoin('users as member3', 'member3.id', '=', 'groups.id_user3')
            ->leftJoin('users as member4', 'member4.id', '=', 'groups.id_user4')
            ->leftJoin('users as member5', 'member5.id', '=', 'groups.id_user5')
            ->select('projects.sujet',
                     'groups.id as group_id',
                     'member1.name as member1_name',
                     'member1.surname as member1_surname',
                     'member1.email as member1_email',
                     'member2.name as member2_name',
                     'member2.surname as member2_surname',
                     'member2.email as member2_email',
                     'member3.name as member3_name',
                     'member3.surname as member3_surname',
                     'member3.email as member3_email',
                     'member4.name as member4_name',
                     'member4.surname as member4_surname',
                     'member4.email as member4_email',
                     'member5.name as member5_name',
                     'member5.surname as member5_surname',
                     'member5.email as member5_email')
            ->where('projects.id_user', $id_user)
            ->where('applications.response', 'accepted')
            ->get();
        return response()->json([
            'status' => '200',
            'message' => 'Groups fetched',
            'groups' => $results,
        ]);
    }
    function CreateMeeting(Request $request , $id_user){

        $rendez_vous = new RendezVous;
        $rendez_vous ->creator = $id_user;
        $rendez_vous->date =$request->input('date');
        $rendez_vous->heure =$request->input('heure');
        $rendez_vous->objet = $request->input('objet');
        $id_project = DB::table('projects')
                    ->where('sujet', $request->input('sujet_pfe'))
                   ->where('id_user', $id_user)
                    ->value('id');
        $id_group = DB::table('applications')
                     ->where('id_project', $id_project)
                     ->where('response', 'accepted')
                     ->value('id_group');
        $rendez_vous->to =$id_group;
        $rendez_vous->save();
        return response()->json([
            'status' => '200',
            'message'=>'meet created successfully',
            'project' => $rendez_vous,
        ]);

    }
    function DropDownSujets(Request $request, $id_user) {
        $sujets = DB::table('projects')
                    ->select('sujet')
                    ->where('id_user', $id_user)
                    ->get();
        return response()->json([
            'status' => '200',
            'message' => 'Sujets fetched',
            'sujets' => $sujets,
        ]);
    }
    function MyMeetingToAttend(Request $request, $id_user){
        $rendez_vous = DB::table('rendez_vous')
        ->select('*')
        ->where('to', $id_user)
        ->get();
        return response()->json([
            'status' => '200',
            'message' => 'Sujets fetched',
            'sujets' => $rendez_vous,
        ]);
    }
    function ResponeForMyMeetingToAttend(Request $request, $id_user){
        $rendez_vous = DB::update('update rendez_vous set response = ? where id_user = ? and id = ?',[$id_user,$request->response]);
        return response()->json([
            'status' => 'success',
            'message' => 'done',
        ]);    
    }
    function SendAnnonceToGroup(Request $request, $id_user){
        $id_project = DB::table('projects')
                    ->where('sujet', $request->input('sujet_pfe'))
                   ->where('id_user', $id_user)
                    ->value('id');
        $id_group = DB::table('applications')
                     ->where('id_project', $id_project)
                     ->where('response', 'accepted')
                     ->value('id_group');
        $annonce = new Annonce;
        $annonce->title = $request->input('title');;
        $annonce->message = $request->input('message');;
        $annonce->id_group = $id_group;
        $annonce->id_user = $id_user;
        $annonce->save();
        return response()->json([
            'status' => '200',
            'message'=>'annonce created successfully',
            'project' => $annonce,
        ]);


    }
    function GetMyAnnonce(Request $request, $id_user){
        $result=DB::select('select * from annonce where id_user = ?',[$id_user]);
        return response()->json([
            'status' => '200',
            'message'=>'annonce fetched',
            'project' => $result,
        ]);

    }
    function GetAllProgressionVideo(Request $request) {
        // Retrieve the user's filliere
        $userFilliere = $request->input('filiere');
    
        // Retrieve the video files that match the specified conditions
        $files = File::where('type', 'progression')
                     ->whereHas('user', function ($query) use ($userFilliere) {
                         $query->where('filliere', $userFilliere);
                     })
                     ->get();
    
        // Extract the video URLs from the files
        $videoUrls = $files->map(function ($file) {
            return $file->path;
        });
    
        return response()->json([
            'status' => 'success',
            'video_urls' => $videoUrls,
        ]);
    }
    

    




}
