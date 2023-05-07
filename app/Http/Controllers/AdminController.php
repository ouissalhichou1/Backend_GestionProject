<?php

namespace App\Http\Controllers;


use App\Models\User;
use League\Csv\Writer;
use App\Models\RoleUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role:Admin');
    }
    public function SaveUser(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' =>'required|string|max:255',
            'email' => 'required|string|email:|max:255',
            'password' =>'required|string|min:6',
            'code'=>'integer|min:3',
            'apogee'=>'integer|min:8',
            'filiere'=>'string|max:255',
            'specialite' =>'string|max:255',
        ]);
    
        try {
            $user = User::create([
                'name'=>$request->name,
                'surname'=>$request->surname,
                'code'=>$request->code,
                'apogee'=>$request->apogee,
                'filiere'=>$request->filiere,
                'specialite' =>$request->specialite,
                'email' =>$request->email,
                'password' => Hash::make($request->password),
                'email_verification_token' => Str::random(40),
            ]);
            event(new Registered($user));
            return response()->json([
                'status' => 'success',
                'message' => 'Registered successfully! Please verify your email address.',
                'id_user'=>$user->id,
                'name'=>$user->name,
                'surname'=>$user->surname,
                'role'=>$user->roles[0]->RoleName,
            ]);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062 && strpos($e->getMessage(), 'email')) {
                return response()->json(['message' => 'The email must be unique'], 401);
            } else if ($e->errorInfo[1] == 1062 && strpos($e->getMessage(), 'apogee')) {
                return response()->json(['message' => 'The apogee must be unique'], 402);
            } else if ($e->errorInfo[1] == 1062 && strpos($e->getMessage(), 'code')) {
                return response()->json(['message' => 'The code must be unique'], 403);
            } else {
                return response()->json(['message' => 'An error occurred'], 500);
            }
        }
    }
    function DeleteEtudiant(Request $request){
        
        $user = DB::delete('delete from users where apogee = ?',[$request->input('apogee')]);
        return response()->json([
            'status' => 'success',
            'message' => 'Student deleted successfully',
        ]);
    }
    function DeleteProfessor(Request $request){
        $user = DB::delete('delete from users where code = ?',[$request->input('code')]);
        return response()->json([
            'status' => 'success',
            'message' => 'Professor deleted successfully',
        ]);
    }
    function uploadZipFile(Request $request)
    {
        $file = $request->file('zip_file');
        $fileName = $file->getClientOriginalName();
        $path = $file->storeAs('zip_files', $fileName, 'public');
        $zipFile = new File;
        $zipFile->user_id = '1';
        $zipFile->name = $fileName;
        $zipFile->path = $path;
        $zipFile->type = 'ConseilPFE.zip';
        $zipFile->save();
        return response()->json(['message' => 'Zip file uploaded successfully']);
    }
    function DeleteGroup(Request $request)
    {
      $admin_apogee = $request->input('apogee');
      $admin_id = DB::select('select id from users where apogee = ?',[$admin_apogee]);
      $admin_id =array_map(function ($value) {
        return (array)$value;
      }, $admin_id);
      $group = DB::delete('delete from groups where id_group_admin = ?', [$admin_id[0]["id"]]);
         return response()->json([
          'status' => 'deleted successfully',
          ]);
    }
    function ListUsersWithTheirRole(Request $request)
    {
        $users = User::all();
        $data =[] ;
       foreach ($users as $user) {
        $role_id = DB::select('select * from role_users where user_id = ?', [ $user->id]);
        $role_id = array_map(function ($value) {return (array)$value;}, $role_id);
        $role_Name = DB::select('select RoleName from roles where id = ?', [$role_id[0]["role_id"]]);
        $role_Name = array_map(function ($value) {return (array)$value;}, $role_Name);
        $user->Role = $role_Name[0]["RoleName"];
        $data[] = $user;
         }
        return response()->json([
         'status' => 'created success',
          'users' => $data,
        ]);
    }
    function GetAllGroupsAndMembers()
    {
        $groups = DB::table('groups')->get();
    
        $data = [];
    
        foreach ($groups as $group) {
            $groupData = [
                'group_id' => $group->id,
            ];
    
            $groupColumns = ['id_group_admin', 'id_user2', 'id_user3', 'id_user4', 'id_user5'];
            $memberCount = 1;
    
            foreach ($groupColumns as $column) {
                $memberId = $group->{$column};
    
                if ($memberId !== null) {
                    $memberApogee = DB::table('users')->where('id', $memberId)->value('apogee');
    
                    if ($memberApogee) {
                        $groupData["member_{$memberCount}"] = $memberApogee;
                        $memberCount++;
                    }
                }
            }
    
            $data[] = $groupData;
        }
    
        return response()->json([
            'status' => 'success',
            'group_members' => $data,
        ]);
    }
    function GetUserGroupDetails(){
        $users = DB::table('users')
            ->whereNotNull('apogee')
            ->select('id', 'name', 'surname')
            ->get();
    
        $response = [];
    
        foreach ($users as $user) {
            $userId = $user->id;
    
            $group = DB::table('groups')
                ->where(function ($query) use ($userId) {
                    $query->where('id_group_admin', $userId)
                        ->orWhere('id_user2', $userId)
                        ->orWhere('id_user3', $userId)
                        ->orWhere('id_user4', $userId)
                        ->orWhere('id_user5', $userId);
                })
                ->select('id')
                ->first();
    
            if ($group) {
                $groupId = $group->id;
    
                $application = DB::table('applications')
                    ->join('projects', 'applications.id_project', '=', 'projects.id')
                    ->join('users', 'projects.id_user', '=', 'users.id')
                    ->where('applications.id_group', $groupId)
                    ->select('projects.id_user', 'users.name', 'users.surname')
                    ->first();
    
                if ($application) {
                    $response[] = [
                        'user_id' => $userId,
                        'user_name' => $user->name." ".$user->surname,
                        'project_owner_name' => $application->name." ".$application->surname,
                    ];
                }
            }
        }
    
        return response()->json($response);
    }
    
    

}

    
    

   
    
    
