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


class UsersController extends Controller
{
    public function ListUsersWithTheirRole(Request $request)
    { 
        $users = User::all();
        $data = [];

        foreach ($users as $user) {
         $role_id = DB::select('select role_id from role_users where user_id = ?', [ $user->id]);
         $role_id = array_map(function ($value){return (array)$value;}, $role_id);
         $role_Name = DB::select('select RoleName from roles where id = ?', [$role_id[0]["role_id"]]);
         $role_Name = array_map(function ($value){return (array)$value;}, $role_Name);
         $user->Role = $role_Name[0]["RoleName"];
         $data[] = $user;
         }

         return response()->json([
             'status' => 'success',
             'data' => $data,
             ]);
    }
    public function SaveUser(Request $request)
    {
        $user = new User();
        $user->name =$request->input('name');
        $user->surname =$request->input('surname');
        $user->specialite =$request->input('specialite');
        $user->code =$request->input('code');
        $user->apogee =$request->input('apogee');
        $user->filiere =$request->input('filiere');
        $user->email =$request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'id_user'=>$user->id,
            'name'=>$user->name,
            'surname'=>$user->surname,
            'role'=>$user->roles[0]->RoleName,
        ]);


    }

    public function SaveStudentsFromFile(Request $request){

        if ($request->file('listeEtudiant')->isValid()) {  // test if the file is valid
            $file = $request->file('listeEtudiant');      // then get it from the request
            if($file->extension() !== 'csv') {
                return CustomResponse::buildResponse("file format not acceptable", '', 406);
            }
            // before reading from the file we need to open it to get the stream of data so we can read from it using fgetcsv
            $stream = fopen($file, 'r'); // here we open the file
            $data = array();
            $header = null;
            if($stream !== false) { // then we check if this file is opened successfully
                while (($row = fgetcsv($stream, 1000, ",")) !== false) {
                    // then we keep reading line by line until we reach the end
                    if(!$header) {
                        $header = $row;
                    } else {
                        $data[] = array_combine($header, $row);
                    }
                }
                // after we read all the lines we need to close the stream
                fclose($stream);
            }
            $insertQuery = DB::table("users")->insert($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Users created successfully',
            ]);
    
        }
    }

    public function SaveProfessorsFromFile(Request $request)
    {
        if ($request->file('Professors_uae')->isValid()) {  // test if the file is valid
            $file = $request->file('Professors_uae');      // then get it from the request
            if($file->extension() !== 'csv') {
                return CustomResponse::buildResponse("file format nor acceptable", '', 406);
            }
            // before reading from the file we need to open it to get the stream of data so we can read from it using fgetcsv
            $stream = fopen($file,'r'); // here we open the file
            $data = array();
            $header = null;
            if($stream !== false) { // then we check if this file is opened successfully
                while (($row = fgetcsv($stream, 1000, ",")) !== false) {
                    // then we keep reading line by line until we reach the end
                    if(!$header) {
                        $header = $row;
                    } else {
                        $data[] = array_combine($header, $row);
                    }
                }
                // after we read all the lines we need to close the stream
                fclose($stream);
            }
            $insertQuery = DB::table("users")->insert($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Users created successfully',
            ]);
    
        }
    }

    public function DeleteEtudiant(Request $request){
        $user = DB::delete('delete from users where apogee = ?',[$request->apogee]);
        return response()->json([
            'status' => 'success',
            'message' => 'Users deleted successfully',
        ]);
    }

    public function DeleteProfessor(Request $request){
        $user = DB::delete('delete from users where code = ?',[$request->code]);
        return response()->json([
            'status' => 'success',
            'message' => 'Users deleted successfully',
        ]);
    }
}

