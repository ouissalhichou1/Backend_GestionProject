<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Task;
use App\Models\User;
use App\Models\Group;
use App\Models\Annonce;
use App\Models\Project;
use App\Models\RendezVous;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.role:Etu');
    }
     
    function CreateGroup(Request $request, $id_user) {
        $apogee2 = $request->input('user2');
        $apogee3 = $request->input('user3');
        $apogee4 = $request->input('user4');
        $apogee5 = $request->input('user5');
    
        $usersWithGroups = [];
        if ($id_user) {
            $existingGroup = DB::table('groups')
            ->where('id_user2',$id_user)
            ->orWhere('id_user3', $id_user)
            ->orWhere('id_user4', $id_user)
            ->orWhere('id_user5', $id_user)
            ->first();
            if ($existingGroup) {
                $usersWithGroups[] = $id_user;
            }
        }
         // Search for user with apogee $apogee2
        if (!empty($apogee2)) {
             $user2 = DB::table('users')->where('apogee', $apogee2)->first();
             if ($user2) {
        $existingGroup = DB::table('groups')
            ->where('id_user2', $user2->id)
            ->orWhere('id_user3', $user2->id)
            ->orWhere('id_user4', $user2->id)
            ->orWhere('id_user5', $user2->id)
            ->first();

        if ($existingGroup) {
            $usersWithGroups[] = $apogee2;
        }
        }
      } else {
        $user2 = null;
      }

     // Search for user with apogee $apogee3
     if (!empty($apogee3)) {
      $user3 = DB::table('users')->where('apogee', $apogee3)->first();
      if ($user3) {
        $existingGroup = DB::table('groups')
            ->where('id_user2', $user3->id)
            ->orWhere('id_user3', $user3->id)
            ->orWhere('id_user4', $user3->id)
            ->orWhere('id_user5', $user3->id)
            ->first();

        if ($existingGroup) {
            $usersWithGroups[] = $apogee3;
        }
      }
     } else {
       $user3 = null;
      }

      // Search for user with apogee $apogee4
     if (!empty($apogee4)) {
      $user4 = DB::table('users')->where('apogee', $apogee4)->first();
      if ($user4) {
        $existingGroup = DB::table('groups')
            ->where('id_user2', $user4->id)
            ->orWhere('id_user3', $user4->id)
            ->orWhere('id_user4', $user4->id)
            ->orWhere('id_user5', $user4->id)
            ->first();

        if ($existingGroup) {
            $usersWithGroups[] = $apogee4;
        }
       }
        } else {
      $user4 = null;
      }

      // Search for user with apogee $apogee5
     if (!empty($apogee5)) {
      $user5 = DB::table('users')->where('apogee', $apogee5)->first();
      if ($user5) {
        $existingGroup = DB::table('groups')
            ->where('id_user2', $user5->id)
            ->orWhere('id_user3', $user5->id)
            ->orWhere('id_user4', $user5->id)
            ->orWhere('id_user5', $user5->id)
            ->first();

        if ($existingGroup) {
            $usersWithGroups[] = $apogee5;
        }
       }
      } else {
      $user5 = null;
      }

    
        if (!empty($usersWithGroups)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, the following users are already assigned to a group:',
                'users' => $usersWithGroups,
            ]);
        }
    
        // Create the new group and return success response
        // ...
    
        $group = new Group();
        $group->id_group_admin = $id_user;
        $group->id_user2 = $user2 ? $user2->id : null;
        $group->id_user3 = $user3 ? $user3->id : null;
        $group->id_user4 = $user4 ? $user4->id : null;
        $group->id_user5 = $user5 ? $user5->id : null;
        $group->save();
    
        return response()->json([
            'status' => 'created',
            'group' => $group,
        ]);
    }
    function JoinGroup(Request $request, $id_user){
        $id_group = $request->input('id_group');
    
        $existing_group = DB::table('groups')
            ->where('id', $id_group)
            ->first();
    
        if ($existing_group) {

            $existingInvitation = DB::table('invitations')
                ->where('id_group', $id_group)
                ->where('id_etudiant', $id_user)
                ->first();
    
            $existingGroup = DB::table('groups')
                ->where('id_group_admin', $id_user)
                ->orWhere('id_user2', $id_user)
                ->orWhere('id_user3', $id_user)
                ->orWhere('id_user4', $id_user)
                ->orWhere('id_user5', $id_user)
                ->first();
    
            if ($existingGroup) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sorry, you are already a member of another group.',
                ]);
            }
    
            if ($existingInvitation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sorry, you have already tried to join this group.',
                ]);
            }
            DB::table('invitations')->insert([
                'id_group' => $id_group,
                'id_etudiant' => $id_user,
                'response' => null,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Invitation sent. Waiting for acceptance.',
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, the group does not exist.',
            ]);
        }
    }
    function RespondToInvitation(Request $request, $id_user){
        $id_invitation = $request->input('id_invitation');
        $response = $request->input('response');
    
        $invitation_exists = DB::table('invitations')
            ->where('id', $id_invitation)
            ->first();
    
        if ($invitation_exists) {
            $id_group = $invitation_exists->id_group;
    
            // Check if the user is a member of the group
            $isMember = DB::table('groups')
                ->where('id_group_admin', $id_user)
                ->orWhere('id_user2', $id_user)
                ->orWhere('id_user3', $id_user)
                ->orWhere('id_user4', $id_user)
                ->orWhere('id_user5', $id_user)
                ->where('id', $id_group)
                ->exists();
    
            if ($isMember) {
                // Retrieve the ID of the new member
                $id_new_member = DB::table('invitations')
                    ->where('id', $id_invitation)
                    ->value('id_etudiant');
    
                $invitation_response = DB::table('invitations')
                    ->where('id', $id_invitation)
                    ->update(['response' => $response]);
    
                if ($invitation_response) {
                    if ($response === 'accepted') {
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
                            ->update([$columnToUpdate => $id_new_member]);
    
                        // Delete the invitation
                        DB::table('invitations')
                            ->where('id', $id_invitation)
                            ->delete();
    
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Your user joined the group successfully.',
                        ]);
                    } else {
                        // Delete the invitation
                        DB::table('invitations')
                            ->where('id', $id_invitation)
                            ->delete();
    
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Invitation declined. The invitation has been deleted.',
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not a member of the group.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invitation not found.',
            ]);
        }
    }
    function GetInvitations(Request $request, $id_user){

        $id_group = DB::select('select * from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_user,$id_user,$id_user,$id_user,$id_user]);
        $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
        $id_group = $id_group[0]["id"];
        $invs = DB::select('select * from invitations where id_group = ?', [$id_group]);
        $data = [];
        foreach ($invs as $inv) {
            $result = DB::select('select name, surname , filiere from users where id = ?', [$inv->id_etudiant]);
            $result = array_map(function ($value) {
                return (array) $value;
            }, $result);
            $inv->name = $result[0]["name"] . " " . $result[0]["surname"];
            $inv->filiere = $result[0]["filiere"];
            $data[] = $inv;
        }
        return response()->json([
            'status' => 'success',
            'project' => $data,
        ]);
    }
    function CreateMeetingWithMySuperviser(Request $request, $id_user){

        $id_group = DB::select('select id from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_user, $id_user, $id_user, $id_user, $id_user]);
        $id_group = array_map(function ($value) {
            return (array)$value;
        }, $id_group);
        $id_group = $id_group[0]["id"];
        $id_project = DB::select('select id_project from applications where id_group = ?', [$id_group]);
        $id_project = array_map(function ($value) {
            return (array)$value;
        }, $id_project);
        $id_project = $id_project[0]["id_project"];
        $to = DB::select('select id_user from projects where id = ?', [$id_project]);
        $to = array_map(function ($value) {
            return (array)$value;
        }, $to);
        $to = $to[0]["id_user"];
        $response = 'accepted';
        $applications_exists = DB::table('applications')
            ->where('id_project', $id_project)
            ->where('id_group', $id_group)
            ->where('response', $response)
            ->where('response_admin', $response)
            ->first();
        if ($applications_exists) {
            $rendez_vous = new RendezVous;
            $rendez_vous->creator = $id_user;
            $rendez_vous->date = $request->input('date');
            $rendez_vous->heure = $request->input('heure');
            $rendez_vous->objet = $request->input('objet');
            $rendez_vous->to = $to;
            $rendez_vous->save();
    
            return response()->json([
                'status' => '200',
                'message' => 'Meeting created successfully',
                'project' => $rendez_vous,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'You haven\'t decided your PFE yet',
            ]);
        }
    }   
    function GetMyMeetings(Request $request, $id_user){
        $meetings = DB::table('rendez_vous')
        ->select('*')
        ->where('creator', $id_user)
        ->get();
        $data = [];
        foreach ($meetings as $meeting) {
            $encadrant =  DB::select('SELECT name , surname FROM users WHERE id = ? ', [$meeting->to]);
            $encadrant = array_map(function ($value) {return (array) $value;}, $encadrant);
            $encadrant =$encadrant[0]["name"] . " " . $encadrant[0]["surname"];
            $meeting->encadrant = $encadrant;
            $data[] = $meeting;
            }
        return response()->json([
            'status' => '200',
            'message' => 'Sujets fetched',
            'meeting' => $data,
        ]);
    }
    function GetMeetingToAttend(Request $request, $id_user){
        $id_group = DB::select('select id from groups where id_group_admin = ? or id_user2 = ? or id_user3 = ? or id_user4 = ? or id_user5 = ?', [$id_user, $id_user, $id_user, $id_user, $id_user]);
        $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
        $id_group  = $id_group[0]["id"];
        $rendez_vous = DB::table('rendez_vous')
        ->select('*')
        ->where('to', $id_group)
        ->get();
        return response()->json([
            'status' => '200',
            'message' => 'rendez_vous fetched',
            'sujets' => $rendez_vous,
        ]);
    }
    function GetProjectsToApplyTo(Request $request, $id_user) {

        $filiere = DB::select('select filiere from users where id = ?', [$id_user]);
        $filiere = array_map(function ($value) {
            return (array) $value;
        }, $filiere);
        $filiere = $filiere[0]["filiere"];
    
        $projects = DB::select('select * from projects where filiere = ?', [$filiere]);
        $projects = array_map(function ($value) {
            return (object) $value;
        }, $projects);
    
        $data = [];
        foreach ($projects as $project) {
            $result = DB::select('select name, surname, email from users where id = ?', [$project->id_user]);
            $result = array_map(function ($value) {
                return (array) $value;
            }, $result);
    
            $project->owner_name = $result[0]["name"] . " " . $result[0]["surname"];
            $project->owner_email = $result[0]["email"];
            $data[] = $project;
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }      
    function ApplyToProject(Request $request, $id_user){
        $project = $request->id_project;
        $group = DB::table('groups')
            ->select('id', 'id_group_admin', 'id_user2', 'id_user3', 'id_user4', 'id_user5')
            ->where('id_group_admin', $id_user)
            ->orWhere('id_user2', $id_user)
            ->orWhere('id_user3', $id_user)
            ->orWhere('id_user4', $id_user)
            ->orWhere('id_user5', $id_user)
            ->first();
    
        if (!$group) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, you must be in a group to apply to projects.',
            ]);
        }
    
        $group_id = $group->id;
    
        $applications_count = DB::select('SELECT COUNT(*) AS total_applications FROM applications WHERE id_group = ?', [$group_id]);
        $applications_count = $applications_count[0]->total_applications;
    
        if ($applications_count >= 3) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, you have already applied to 3 projects.',
            ]);
        }
    
        $applications_exists = DB::select('SELECT * FROM applications WHERE id_project = ? AND id_group = ?', [$project, $group_id]);
    
        if ($applications_exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, you have already applied to this project.',
            ]);
        }
    
        $members = [];
        $adminId = $group->id_group_admin;
        $members[] = $adminId;
    
        for ($i = 2; $i <= 5; $i++) {
            $memberId = $group->{"id_user{$i}"};
            if ($memberId) {
                $members[] = $memberId;
            }
        }
    
        $nbrOfMembers = count($members);
        echo $nbrOfMembers;
        // Check if all members have a CV and Releve Note file
        foreach ($members as $memberId) {
            $cv_exists = DB::select('SELECT * FROM file WHERE user_id = ? AND type = ?', [$memberId, 'CV']);
            $releve_exists = DB::select('SELECT * FROM file WHERE user_id = ? AND type = ?', [$memberId, 'Releve_Note']);
    
            if (!$cv_exists || !$releve_exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sorry, you cannot apply to this project because not all members have a CV and Releve Note.',
                ]);
            }
        }
    
        $nbrPersonne = DB::select('SELECT NbrPersonnes FROM projects WHERE id = ?', [$project]);
        $nbrPersonne = array_map(function ($value) {
            return (array) $value;
        }, $nbrPersonne);
    
        if ($nbrOfMembers == $nbrPersonne[0]['NbrPersonnes']) {
            $application = new Application();
            $application->id_project = $request->id_project;
            $application->id_group = $group_id;
            $application->save();
    
            return response()->json([
                'status' => 'success',
                'project' => $application,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, you cannot apply to this project because of the number of members.',
            ]);
        }
    }
    function GetMyApplications(Request $request, $id_user) {
        $id_group = DB::select('select id from groups where id_group_admin = ? or id_user2 = ? or id_user3 = ? or id_user4 = ? or id_user5 = ?', [$id_user, $id_user, $id_user, $id_user, $id_user]);
    
        if ($id_group) {
            $id_group = $id_group[0]->id;
    
            $applications = DB::table('applications')
                ->where('id_group', $id_group)
                ->get();
    
            if ($applications->isNotEmpty()) {
                $data = [];
    
                foreach ($applications as $application) {
                    $project = Project::with('user')->find($application->id_project);
    
                    if ($project) {
                        $data[] = [
                            'id_application' => $application->id,
                            'response_professor' => $application->response,
                            'response_admin' => $application->response_admin,
                            'id_group' => $id_group,
                            'owner_name' => $project->user->name . ' ' . $project->user->surname,
                            'owner_email' => $project->user->email,
                            'sujet_pfe' => $project->sujet,
                        ];
                    }
                }
    
                return response()->json([
                    'status' => '200',
                    'message' => 'applications fetched',
                    'applications' => $data,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No applications found.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Group not found.',
            ]);
        }
    }
    function MyFinalResposeForApplication(Request $request, $id_user){
        $application_id = $request->application_id;
        $response = $request->response;
    
        // Check if the user is the group admin
        $isGroupAdmin = DB::table('groups')
            ->where('id_group_admin', $id_user)
            ->exists();
    
        if (!$isGroupAdmin) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update the application response.',
            ]);
        }
    
        $response_prof = DB::table('applications')
            ->where('id', $application_id)
            ->value('response');
            
        $id_group = DB::table('applications')
            ->where('id', $application_id)
            ->value('id_group');
    
        if ($response_prof == 'accepted') {
            // Update the application response
            DB::table('applications')
                ->where('id', $application_id)
                ->update(['response_admin' => $response]);
    
            if ($response == 'accepted') {
                $groupApplications = Application::where('id_group', $id_group)
                    ->where(function ($query) {
                        $query->where('response_admin', '!=', 'accepted')
                            ->orWhereNull('response_admin');
                    })
                    ->get();
    
                foreach ($groupApplications as $application) {
                    $application->delete();
                }
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bon choix.',
                ]);
            } elseif ($response == 'refuse') {
                // Delete the application
                DB::table('applications')
                    ->where('id', $application_id)
                    ->delete();
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Application deleted.',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid response.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Wait for the professor answer.',
            ]);
        }
    }
    function DeleteApplication(Request $request, $id_user){
        $isGroupAdmin = DB::table('groups')
            ->where('id_group_admin', $id_user)
            ->exists();
        if (!$isGroupAdmin) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update the application response.',
            ]);
        }else{
            $application_id = $request->application_id;
            $applications = DB::delete('delete from applications where id =?', [$application_id]);
            return response()->json([
                'status' => '200',
                'message' => ' application deleted ',
            ]);
        }
    }
    function GetGroupMembers(Request $request, $id_user) {
        $user = $id_user;
        $group = DB::select('SELECT id FROM groups WHERE id_group_admin = ? OR id_user2 = ? OR id_user3 = ? OR id_user4 = ? OR id_user5 = ?', [$id_user, $id_user, $id_user, $id_user, $id_user]);
        $group = array_map(function ($value) {
            return (array) $value;
        }, $group);
        $group = $group[0]["id"];
    
        $members = [];
        $groupColumns = ['id_group_admin', 'id_user2', 'id_user3', 'id_user4', 'id_user5'];
    
        foreach ($groupColumns as $column) {
            $memberId = DB::table('groups')->where('id', $group)->value($column);
    
            if ($memberId !== null && $memberId !== $user) {
                $member = DB::table('users')
                    ->select('id','name', 'surname','apogee','filiere','email')
                    ->where('id', $memberId)
                    ->first();
    
                if ($member) {
                    $members[] = $member;
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'group_id' => $group,
            'members' => $members,
        ]);
    }
    function QuitGroup(Request $request, $id_user){
        $id_group = DB::select('select * from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_user,$id_user,$id_user,$id_user,$id_user]);
        $id_group = array_map(function ($value) {return (array)$value;}, $id_group);
        $group_data = array_slice($id_group[0], 1, -2, true);
        $role = array_search($id_user, $group_data);
        $nbrOfmembers = count($group_data);
    
        if ($role == "id_group_admin") {
            // check if the group has only one member
            if ($nbrOfmembers == 1) {
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
    
                // Update the array with null values if any
                $updeted_array = array_pad($updeted_array, 5, null);
    
                $result = DB::update(
                    "update groups set id_group_admin =?,id_user2=?,id_user3=?,id_user4=?,id_user5=? where id=?",
                    [$updeted_array[0], $updeted_array[1], $updeted_array[2], $updeted_array[3], $updeted_array[4], $id_group[0]["id"]]
                );
                return response()->json([
                    'status' => 'success',
                    'message' => 'User quit successfully',
                ]);
            }
        } elseif ($role == "id_user2" || $role == "id_user3" || $role == "id_user4" || $role == "id_user5") {
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
    
                // Update the array with null values if any
                $updated_array = array_pad($updated_array, 5, null);
    
                $result = DB::update(
                    "update groups set id_user2=?,id_user3=?,id_user4=?,id_user5=? where id=?",
                    [$updated_array["id_user2"], $updated_array["id_user3"], $updated_array["id_user4"], $updated_array["id_user5"], $id_group[0]["id"]]
                );
                return response()->json([
                    'status' => 'success',
                    'message' => 'User quit successfully',
                ]);
            }
        }
    }    
    function GetAnnonceFromSuperviser(Request $request, $id_user){
        $id_group = DB::select('SELECT id FROM groups WHERE id_group_admin = ? OR id_user2 = ? OR id_user3 = ? OR id_user4 = ? OR id_user5 = ?', [$id_user, $id_user, $id_user, $id_user, $id_user]);
        $id_group = $id_group[0]->id;
    
        $id_project = DB::select('SELECT id_project FROM applications WHERE id_group = ?', [$id_group]);
        $id_project = $id_project[0]->id_project;
    
        $id_supervisor = DB::select('SELECT id_user FROM projects WHERE id = ?', [$id_project]);
        $id_supervisor = $id_supervisor[0]->id_user;
    
        $result = DB::select('SELECT * FROM annonces WHERE group_id = ? AND id_user = ?', [$id_group, $id_supervisor]);
    
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
    function uploadFile(Request $request, $id_user){
        $request->validate([
            'file' => 'required|file',
        ]);
        $path = $request->file('file')->store('public/files');
    
        $fileName = $request->file('file')->getClientOriginalName();
    
        $file = new File;
        $file->path = $path;
        $file->type = $request->input('type');
    
        if ($request->input('type') == 'Progression') {
            $file->title = $request->input('title');
        }
    
        $file->name = $fileName;
        $file->user_id = $id_user;
        $file->save();
    
        return response()->json([
            'status' => 'success',
            'file' => $file,
        ]);
    }
    function GetAllProgressionVideo(Request $request,$id_user){
        $userId = $id_user;
        // Retrieve the user's filliere
        $userFiliere = User::findOrFail($userId)->filiere;
    
        // Retrieve the video files that match the specified conditions
        $files = File::where('type', 'Progression')
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
    function SendAnnonceToMyGroup(Request $request, $id_user){
        $group_ids = DB::select('SELECT id FROM groups WHERE id_group_admin = ? OR id_user2 = ? OR id_user3 = ? OR id_user4 = ? OR id_user5 = ?', [$id_user, $id_user, $id_user, $id_user, $id_user]);
    
        if (empty($group_ids)) {
            return response()->json([
                'status' => '400',
                'message' => 'Sorry, you must be in a group to send an announcement.',
            ]);
        }
    
        $id_group = implode(',', array_column($group_ids, 'id'));
    
        $annonce = new Annonce;
        $annonce->title = $request->input('title');
        $annonce->message = $request->input('message');
        $annonce->group_id = $id_group;
        $annonce->user_id = $id_user;
        $annonce->save();
    
        return response()->json([
            'status' => '200',
            'message' => 'Annonce created successfully',
            'annonce' => $annonce,
        ]);
    }
    function GetAnnonceFromMyGroup(Request $request, $id_user){
        $id_group = DB::select('SELECT id FROM groups WHERE id_group_admin = ? OR id_user2 = ? OR id_user3 = ? OR id_user4 = ? OR id_user5 = ?', [$id_user, $id_user, $id_user, $id_user, $id_user]);
        $id_group = $id_group[0]->id;
        $result = DB::select('SELECT * FROM annonces WHERE group_id = ?', [$id_group]);
        return response()->json([
            'status' => 'annonce fetched',
            'group' => $result,
        ]);
    }
    



}
