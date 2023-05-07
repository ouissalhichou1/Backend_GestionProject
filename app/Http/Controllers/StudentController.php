<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Task;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role:Etu');
    }
    function CreateGroup(Request $request,$id_user){
        $id_user2 = DB::table('users')->where('apogee', $request->input('user2'))->first();
        $id_user3 = DB::table('users')->where('apogee', $request->input('user3'))->first();
        $id_user4 = DB::table('users')->where('apogee', $request->input('user4'))->first();
        $id_user5 = DB::table('users')->where('apogee', $request->input('user5'))->first();
        $usersWithGroups = [];
        if ($id_user2) {
             $usersWithGroups[] = [
               'name' => $id_user2->name,
              'surname' => $id_user2->surname,
            ];
        }
        if ($id_user3) {
        $usersWithGroups[] = [
           'name' => $id_user3->name,
           'surname' => $id_user3->surname,
        ];
        }
        if ($id_user4) {
            $usersWithGroups[] = [
             'name' => $id_user4->name,
            'surname' => $id_user4->surname,
             ];
        }
        if ($id_user5) {
          $usersWithGroups[] = [
           'name' => $id_user5->name,
            'surname' => $id_user5->surname,
          ];
        }

        if (!empty($usersWithGroups)) {
              return response()->json([
              'status' => 'error',
             'message' => 'Sorry, the following users are already assigned to a group:',
             'users' => $usersWithGroups,
            ]);
        }
        $group = new Group();
        $group->id_group_admin = $id_user;
        $group->id_user2 = $id_user2;
        $group->id_user3 = $id_user3;
        $group->id_user4 = $id_user4;
        $group->id_user5 = $id_user5;
         $group->save();

         return response()->json([
         'status' => 'created success',
         'group' => $group,
        ]); 
    }
    function JoinGroup(Request $request, $id_user){
        $id_group = $request->input('id_group');
       // Check if the user is already in the invitations table
       $existingInvitation = DB::table('invitations')
        ->where('id_group', $id_group)
        ->where('id_etudiant', $id_user)
        ->first();
       if ($existingInvitation) {
        return response()->json([
            'status' => 'error',
            'message' => 'Sorry, you have already been invited to this group.',
        ]);
        }
       // Store the user in the invitations table
       DB::table('invitations')->insert([
        'id_group' => $id_group,
        'id_etudiant' => $id_user,
        'response' => null,
         ]);
         return response()->json([
          'status' => 'success',
          'message' => 'Invitation sent. Waiting for acceptance.',
        ]);
    }
    function RespondToInvitation(Request $request, $id_user){
       $id_invitation = $request->input('id_invitation');
       $response = $request->input('response');
       // Update the invitation response
        DB::table('invitations')
        ->where('id', $id_invitation)
        ->update(['response' => $response]);
        if ($response == 'accepted') {
        // Retrieve the invitation and associated group
         $invitation = DB::table('invitations')
            ->where('id', $id_invitation)
            ->first();

        if (!$invitation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invitation not found.',
            ]);
        }

        $id_group = $invitation->id_group;

        // Check if the user is already a member of the group
        $isMember = DB::table('groups')
            ->where('id_group_admin', $id_user)
            ->orWhere('id_user2', $id_user)
            ->orWhere('id_user3', $id_user)
            ->orWhere('id_user4', $id_user)
            ->orWhere('id_user5', $id_user)
            ->where('id', $id_group)
            ->exists();

        if ($isMember) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are already a member of this group.',
            ]);
        }

        // Find the first available column and add the user to it
        $availableColumns = ['id_user2', 'id_user3', 'id_user4', 'id_user5'];
        $columnToUpdate = null;

        foreach ($availableColumns as $column) {
            $value = DB::table('groups')
                ->where('id', $id_group)
                ->value($column);

            if (!$value) {
                $columnToUpdate = $column;
                break;
            }
        }

        if (!$columnToUpdate) {
            return response()->json([
                'status' => 'error',
                'message' => 'No available slots in the group.',
            ]);
        }

        // Add the user to the group
        DB::table('groups')
            ->where('id', $id_group)
            ->update([$columnToUpdate => $id_user]);

        // Delete the invitation
        DB::table('invitations')
            ->where('id', $id_invitation)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'You have joined the group successfully.',
        ]);
         } else {
        // Update the invitation response to "refused"
          DB::table('invitations')
            ->where('id', $id_invitation)
            ->update(['response' => 'refused']);

          return response()->json([
            'status' => 'success',
            'message' => 'Invitation declined.',
           ]);
        }
    }
    function CreateMeetingWithMySuperviser(Request $request , $id_user){

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
    function GetProjectsToApplyTo(Request $request , $id_user){
        $user_filiere= DB::select('select filiere from users where id =?',[$id_user]);
        $projects = Project::all();
        $data = [];
        foreach ($projects as $project) {
           $result = DB::select('select name ,surname , email from users where id = ? and filiere =?', [ $project->id_user,$user_filiere]);
           $result = array_map(function ($value) {return (array)$value;}, $result);
           $project->owner_name = $result[0]["name"]." ".$result[0]["surname"];
           $project->owner_email = $result[0]["email"];
           $data[] = $project;
        }
        return response()->json([
           'status' => 'success',
           'data' => $data,
           ]);
    }
    function ApplyToProject(Request $request, $id_user) {
        $id_group = DB::select('select id from groups where id_group_admin = ?', [$id_user]);
        $id_group =array_map(function ($value) {
            return (array)$value;
        }, $id_group);
        $application = new Application();
        $application->id_project = $request->id_project;
        $application->id_group = $id_group[0]["id"];
        $application->save();
        return response()->json([
            'status' => 'success',
            'project' => $application,
        ]);    
    }
    function GetMyApplications(Request $request, $id_user){
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
                'application_response' => $application->response,
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
    function DeleteApplication(Request $request, $id_user){
        $id_group = DB::select('select id from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_user,$id_user,$id_user,$id_user,$id_user]);
        $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
        $id_group = $id_group[0]["id"];
        $id_project = $request->id_project;
        $applications = DB::delete('delete from applications where id_group = ? and id_project = ?', [$id_group,$id_project]);
        return response()->json([
            'status' => '200',
            'message' => 'deleted ',
            ]);
    }
    function GetMyGroup(Request $request, $id_user){
      $group = DB::select('select * from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_student,$id_student,$id_student,$id_student,$id_student]);
      $data =[] ;
      foreach ($groups as $group) {
      $user = DB::select('select name, surname ,apogee ,emailfrom users where user_id = ?', [ $user->id]);
      $data[] = $user;
      }
      return response()->json([
      'status' => 'created success',
      'group' => $data,
      ]);
    }
    function QuitGroup(Request $request, $id_user){
      $id_group = DB::select('select * from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_student,$id_student,$id_student,$id_student,$id_student]);
      $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
      $group_data = array_slice($id_group[0], 1, null, true);
      $role = array_search($id_student, $group_data);
      $nbrOfmembers = count($group_data);

      if($role == "id_group_admin") {
        // check if the group has only one member
        if($nbrOfmembers == 1) {
            $result = DB::delete('delete from groups where id = ?', [$id_group[0]["id"]]);
            return response()->json([
                'status' => 'success',
                'message' => 'Group deleted successfully',
            ]);
        } else {
            // the group has more than one member
            $id_group_admin = $group_data["id_group_admin"];
            $updeted_array = array_values(array_filter($group_data, function ($value) use ($id_group_admin) {
                return !is_null($value) && $value !== $id_group_admin;
            }));
            $result = DB::update(
                "update groups set id_group_admin =?,id_user2=?,id_user3=?,id_user4=?,id_user5=? where id=?",
                [$updeted_array[0],$updeted_array[1],$updeted_array[2],$updeted_array[3],$updeted_array[4],$id_group[0]["id"]]
            );
            return response()->json([
                'status' => 'success',
                'message' => 'User quit successfully',
            ]);
        }
      } elseif($role == "user2" || $role == "id_user3" || $role == "id_user4" || $role == "id_user5") {
        // check if the user is the last member of the group
        if ($nbrOfmembers == 1) {
            $result = DB::delete('delete from groups where id = ?', [$id_group[0]["id"]]);
            return response()->json([
                'status' => 'success',
                'message' => 'Group deleted successfully',
            ]);
        } else {
            // the group has more than one member
            $updated_array = $group_data;
            $updated_array[$role] = null;
            $result = DB::update(
                "update groups set id_user2=?,id_user3=?,id_user4=?,id_user5=? where id=?",
                [$updated_array["id_user2"],$updated_array["id_user3"],$updated_array["id_user4"],$updated_array["id_user5"],$id_group[0]["id"]]
            );
            return response()->json([
                'status' => 'success',
                'message' => 'User quit successfully',
            ]);
          }
      }
    }
    function GetAnnonceFromSuperviser(Request $request, $id_user){
        $id_group = DB::select('select * from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_student,$id_student,$id_student,$id_student,$id_student]);
        $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
        $id_group = $id_group[0]["id"];
        $result = DB::select('select * from annonce where id_group = ?',[$id_group]);
        return response()->json([
            'status' => 'annonce fetched',
            'group' => $result,
        ]);
    }
    function CreateTask(Request $request, $id_user){
            $task = new Task();
            $task->user_id = $id_user;
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Task stored successfully.',
                'task' => $task,
            ]);
    }
    function ModifyTask(Request $request, $user_id){
        $task_id = $request->task_id;
        $task = Task::where('user_id', $user_id)->find($task_id);
        if (!$task) {
        return response()->json([
            'status' => 'error',
            'message' => 'Task not found.',
        ]);
        }

       $task->title = $request->input('title');
       $task->description = $request->input('description');
       $task->save();

       return response()->json([
        'status' => 'success',
        'message' => 'Task modified successfully.',
        'task' => $task,
       ]);
    }
    function GetMyTasks(Request $request, $user_id){
       $tasks = Task::where('user_id', $user_id)->get();
        return response()->json([
        'status' => 'success',
        'tasks' => $tasks,
        ]);
    }
    function deleteTask(Request $request, $user_id) {
        $task_id = $request->task_id;
        $task = Task::where('user_id', $user_id)->find($task_id);
    
        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found.',
            ]);
        }
    
        $task->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully.',
        ]);
    }
    function uploadFile(Request $request , $id_user){
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file',
        ]);
        // Store the file in the storage/app/public/files directory
        $path = $request->file('file')->store('public/files');
        // Get the file name
        $fileName = $request->file('file')->getClientOriginalName();
        // Create a new entry in the files table
        $file = new File;
        $file->path = $path;
        $file->type = $request->input('type');
        $file->name = $fileName;
        $file->user_id =$id_user;
        $file->save();

        return response()->json([
            'status' => 'success',
            'file' => $file,
        ]);
    }
    function GetAllProgressionVideo(Request $request,$id_user){
        $userId = $id_user;
        // Retrieve the user's filliere
        $userFilliere = User::findOrFail($userId)->filliere;
    
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
