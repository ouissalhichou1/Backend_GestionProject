<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role:Admin');
    }
     function SaveUser(Request $request){

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
    }
    function DeleteEtudiant(Request $request){

        $user = DB::delete('delete from users where apogee = ?',[$request->apogee]);
        return response()->json([
            'status' => 'success',
            'message' => 'Users deleted successfully',
        ]);
    }
    function DeleteProfessor(Request $request){
        $user = DB::delete('delete from users where code = ?',[$request->code]);
        return response()->json([
            'status' => 'success',
            'message' => 'Users deleted successfully',
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
      $admin_apogee = $request->apogee;
      $admin_id = DB::select('select id from users where apogee = ?',[$admin_apogee]);
      $admin_id =array_map(function ($value) {
        return (array)$value;
      }, $admin_id);
      $group = DB::delete('delete from groups where id_group_admin = ?', [$admin_id[0]["id"]]);
         return response()->json([
          'status' => 'deleted successfully',
          ]);
    }
    function ListUsersWithTheirRole(Request $request, $id_student)
    {
        $users = User::all();
       $data =[] ;
       foreach ($groups as $group) {
        $role_id = DB::select('select * from users where user_id = ?', [ $user->id]);
        $role_id = array_map(function ($value) {return (array)$value;}, $role_id);
        $role_Name = DB::select('select RoleName from roles where id = ?', [$role_id[0]["role_id"]]);
        $role_Name = array_map(function ($value) {return (array)$value;}, $role_Name);
        $user->Role = $role_Name[0]["RoleName"];
        $data[] = $user;
         }
        return response()->json([
         'status' => 'created success',
          'group' => $group,
        ]);
    }
   
    
    
}
