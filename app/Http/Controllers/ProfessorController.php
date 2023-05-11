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
        $this->middleware('auth:api');
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
    function GetApplicationsForMyProjects(Request $request, $id_user){
        $results = DB::table('applications')
        ->join('groups', 'groups.id', '=', 'applications.id_group')
        ->join('projects', 'projects.id', '=', 'applications.id_project')
        ->join('users as creator', 'creator.id', '=', 'projects.id_user')
        ->select('applications.id as application_id',
                 'groups.id as group_id',
                 'projects.sujet')
        ->where('projects.id_user', $id_user)
        ->get();
        return response()->json([
            'status' => '200',
            'message'=>'Project fetched',
            'applications' => $results,
        ]);
    }
    function aboutGroup(Request $request,$id_group){
        echo 'fuck you';
         if(!$id_group) {
             return response()->json([
                 'status' => '400',
                 'message' => 'Invalid group id',
             ]);
         }
         try {
             // Query the database for the group information
             $results = DB::table('groups')
                 ->leftJoin('users as member1', 'member1.id', '=', 'groups.id_group_admin')
                 ->leftJoin('file as file1', function ($join) {
                     $join->on('file1.user_id', '=', 'member1.id')
                         ->where('file1.type', '=', 'releve_note');
                 })
                 ->leftJoin('users as member2', 'member2.id', '=', 'groups.id_user2')
                 ->leftJoin('file as file2', function ($join) {
                     $join->on('file2.user_id', '=', 'member2.id')
                         ->where('file2.type', '=', 'releve_note');
                 })
                 ->leftJoin('users as member3', 'member3.id', '=', 'groups.id_user3')
                 ->leftJoin('file as file3', function ($join) {
                     $join->on('file3.user_id', '=', 'member3.id')
                         ->where('file3.type', '=', 'releve_note');
                 })
                 ->leftJoin('users as member4', 'member4.id', '=', 'groups.id_user4')
                 ->leftJoin('file as file4', function ($join) {
                     $join->on('file4.user_id', '=', 'member4.id')
                         ->where('file4.type', '=', 'releve_note');
                 })
                 ->leftJoin('users as member5', 'member5.id', '=', 'groups.id_user5')
                 ->leftJoin('file as file5', function ($join) {
                     $join->on('file5.user_id', '=', 'member5.id')
                         ->where('file5.type', '=', 'releve_note');
                 })
                 ->select(
                     'groups.id as group_id',
                     'member1.name as name_1',
                     'member1.surname as surname_1',
                     'member1.email as email_1',
                     'file1.path as file_path_1',
                     'member2.name as name_2',
                     'member2.surname as surname_2',
                     'member2.email as email_2',
                     'file2.path as file_path_2',
                     'member3.name as name_3',
                     'member3.surname as surname_3',
                     'member3.email as email_3',
                     'file3.path as file_path_3',
                     'member4.name as name_4',
                     'member4.surname as surname_4',
                     'member4.email as email_4',
                     'file4.path as file_path_4',
                     'member5.name as name_5',
                     'member5.surname as surname_5',
                     'member5.email as email_5',
                     'file5.path as file_path_5'
                 )
                 ->where('groups.id', $id_group)
                 ->get();
         
             // Check if any results were returned
             if($results->count() == 0) {
                 return response()->json([
                     'status' => '404',
                     'message' => 'No data found for the group',
                 ]);
             }
         
             // Create an empty array to store the member information
             $members = [];
         
             // Loop through each record in the results and extract the member information
             foreach($results as $result) {
                 // Extract the member information from the record
                 $member = [
                     'name' => $result->name_1,
                     'surname' => $result->surname_1,
                     'email' => $result->email_1,
                     'file_path' => $result->file_path_1
                 ];
         
                 // Add the member information to the members array
                 $members[] = $member;
         
                 // Repeat the process for each additional member in the group (up to 5 members total)
                 if($result->name_2) {
                     $member = [
                         'name' => $result->name_2,
                         'surname' => $result->surname_2,
                         'email' => $result->email_2,
                         'file_path' => $result->file_path_2,
                     ];
                     $members[] = $member;
                 }
         
                 if($result->name_3) {
                     $member = [
                         'name' => $result->name_3,
                         'surname' => $result->surname_3,
                         'email' => $result->email_3,
                         'file_path' => $result->file_path_3,
                     ];
                     $members[] = $member;
                 }
         
                 if($result->name_4) {
                     $member = [
                         'name' => $result->name_4,
                         'surname' => $result->surname_4,
                         'email' => $result->email_4,
                         'file_path' => $result->file_path_4,
                     ];
                     $members[] = $member;
                 }
         
                 if($result->name_5) {
                     $member = [
                         'name' => $result->name_5,
                         'surname' => $result->surname_5,
                         'email' => $result->email_5,
                         'file_path' => $result->file_path_5,
                     ];
                     $members[] = $member;
                 }
             }
         
             // Return the member information as part of the response
             return response()->json([
                 'status' => '200',
                 'message' => 'Group fetched successfully',
                 'members' => $members,
             ]);
         
         } catch (Exception $e) {
             // Log error or return error message
             return response()->json([
                 'status' => '500',
                 'message' => 'Error fetching data for the group',
                 'error' => $e->getMessage(),
             ]);
         }
     }
    function ResponseforApplication(Request $request, $id_user) {
        $id_application = $request->id_application;
        $response = $request->response;
    
        $applications = DB::update('update applications set response = ? where id = ?', [$response, $id_application]);
    
        if ($response == 'refuse') {
            $applications = DB::delete('delete from applications where id = ?', [$id_application]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Application deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Application updated successfully.',
            ]);
        }
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
            ->where('applications.response_admin', 'accepted')
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
                     ->where('response_admin', 'accepted')
                     ->value('id_group');
        $rendez_vous->to = $id_group;
        $rendez_vous->save();
        return response()->json([
            'status' => '200',
            'message'=>'meet created successfully',
            'project' => $rendez_vous,
        ]);

    }
    function GetMyMeetings(Request $request, $id_user){
        $meetings = DB::table('rendez_vous')
        ->select('*')
        ->where('creator', $id_user)
        ->get();
        $data = [];
        foreach ($meetings as $meeting) {
            $pfe =  DB::select('SELECT id_project FROM applications WHERE id_group = ? and response = ? and response_admin = ?', [$meeting->to,'accepted','accepted']);
            $pfe = array_map(function ($value) {return (array) $value;}, $pfe);
            $pfe = $pfe[0]["id_project"];
            $sujet = DB::select('SELECT sujet FROM projects WHERE id = ?', [$pfe]);
            $sujet = array_map(function ($value) {return (array) $value;}, $sujet);
            $sujet = $sujet[0]["sujet"];
            $meeting->sujet_group = $sujet;
            $data[] = $meeting;
            }
        return response()->json([
            'status' => '200',
            'message' => 'Sujets fetched',
            'meeting' => $data,
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
        $meetings = DB::table('rendez_vous')
        ->select('*')
        ->where('to', $id_user)
        ->get();
        $data = [];
        foreach ($meetings as $meeting) {
            $group = DB::select('SELECT id FROM groups WHERE id_group_admin = ? OR id_user2 = ? OR id_user3 = ? OR id_user4 = ? OR id_user5 = ?', [$meeting->creator, $meeting->creator, $meeting->creator, $meeting->creator, $meeting->creator]);
            $group = array_map(function ($value) {return (array) $value;}, $group);
            $group = $group[0]["id"];
            $pfe =  DB::select('SELECT id_project FROM applications WHERE id_group = ? and response = ? and response_admin = ?', [$group,'accepted','accepted']);
            $pfe = array_map(function ($value) {return (array) $value;}, $pfe);
            $pfe = $pfe[0]["id_project"];
            $sujet = DB::select('SELECT sujet FROM projects WHERE id = ?', [$pfe]);
            $sujet = array_map(function ($value) {return (array) $value;}, $sujet);
            $sujet = $sujet[0]["sujet"];
            $meeting->sujet_group = $sujet;
            $data[] = $meeting;
            }
        return response()->json([
            'status' => '200',
            'message' => 'Sujets fetched',
            'sujets' => $data,
        ]);
    }
    function ResponeForMyMeetingToAttend(Request $request, $id_user){
        if ($request->response == 'refuse') {
            $rendez_vous = DB::update('update rendez_vous set response = ? where id = ?', [$request->response, $request->id_rendezVous]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Meeting deleted successfully.',
            ]);
        } else {
            $rendez_vous = DB::update('update rendez_vous set response = ? where id = ?', [$request->response, $request->id_rendezVous]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Response updated successfully.',
            ]);
        }
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
        $annonce->title = $request->input('title');
        $annonce->message = $request->input('message');
        $annonce->group_id = $id_group;
        $annonce->user_id = $id_user;
        $annonce->save();
        return response()->json([
            'status' => '200',
            'message'=>'annonce created successfully',
            'project' => $annonce,
        ]);
    }
    function GetMyAnnonce(Request $request, $id_user){
        $annonces = DB::table('annonces')
        ->select('*')
        ->where('user_id', $id_user)
        ->get();
        $data = [];
        foreach ($annonces as $annonce) {
            $pfe =  DB::select('SELECT id_project FROM applications WHERE id_group = ? and response = ? and response_admin = ?', [$annonce->group_id,'accepted','accepted']);
            $pfe = array_map(function ($value) {return (array) $value;}, $pfe);
            $pfe = $pfe[0]["id_project"];
            $sujet = DB::select('SELECT sujet FROM projects WHERE id = ?', [$pfe]);
            $sujet = array_map(function ($value) {return (array) $value;}, $sujet);
            $sujet = $sujet[0]["sujet"];
            $annonce->sujet_group = $sujet;
            $data[] = $annonce;
            }
        return response()->json([
            'status' => '200',
            'message' => 'Sujets fetched',
            'annonce' => $data,
        ]);
    }
    function GetAllProgressionVideo(Request $request) {
        // Retrieve the user's filliere
        $userFilliere = $request->input('filiere');
        // Retrieve the video files that match the specified conditions
        $files = File::where('type', 'progression')
                     ->whereHas('user', function ($query) use ($userFiliere) {
                         $query->where('filiere', $userFiliere);
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
