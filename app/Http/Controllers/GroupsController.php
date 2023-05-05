<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class GroupsController extends Controller
{
   
    public function DeleteGroup(Request $request)
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
    public function QuitGroup(Request $request, $id_student)
    {
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


    public function GetGroup(Request $request, $id_student)
    {

    $group = DB::select('select * from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_student,$id_student,$id_student,$id_student,$id_student]);
    $data =[] ;
    foreach ($groups as $group) {
      $role_id = DB::select('select name, surname from users where user_id = ?', [ $user->id]);
      $role_id = array_map(function ($value){return (array)$value;}, $role_id);
      $role_Name = DB::select('select RoleName from roles where id = ?', [$role_id[0]["role_id"]]);
      $role_Name = array_map(function ($value){return (array)$value;}, $role_Name);
      $user->Role = $role_Name[0]["RoleName"];
      $data[] = $user;
      }
    return response()->json([
      'status' => 'created success',
      'group' => $group,
      ]);
    }

}
