<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use Illuminate\Support\Facades\DB;

class ApplicationsController extends Controller
{
    public function SaveApplication(Request $request, $id_group_admin, $id_project)
    {
        try {
            $id_groups = DB::select('select id from groups where id_group_admin = :id_group_admin', ['id_group_admin' => $id_group_admin]);
            $id_groups=array_map(function ($value) {
                return (array)$value;
            }, $id_groups);
            $applications = new Application();
            $applications->id_projet = $id_project;
            $applications->id_group = $id_groups[0]["id"];
            $applications->save();
            return CustomResponse::buildResponse("created successfully", $applications, 201);
        } catch(QueryException $e) {
            $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
            return CustomResponse::buildResponse("error", $body, 500);
        }
    }
    public function ResponseforApplication(Request $request, $id_application)
    {
        $response = $request->response;
        $applications = DB::update('update applications set response = ? where id = ?', [$response , $id_application]);
        $updated_application = Application::find($applications);
        return CustomResponse::buildResponse("updated successfully", $updated_application, 200);
    }
    public function DeleteApplication(Request $request, $id_application)
    {
        $applications = DB::delete('delete from applications where id = ?', [$id_application]);
        return CustomResponse::buildResponse("deleted successfully", '', 200);
    }
    public function GetMyApplications(Request $request, $id_student)
    {
        $id_group = DB::select('select id from groups where id_group_admin =? or id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_student,$id_student,$id_student,$id_student,$id_student]);
        $id_group=array_map(function ($value) {return (array)$value;}, $id_group);
        $results = Db::table("applications")
        ->join('groups', 'groups.id', '=', 'applications.id_group')
        ->join('projects', 'projects.id', '=', 'applications.id_project')
        ->select('projects.*', 'applications.response')
        ->where('groups.id', '=',$id_group[0]["id"])
        ->get();
        return CustomResponse::buildResponse("Found",$results ,302);
    }
    public function GetMyProjects(Request $request , $id_project){

        $results = Db::table("applications")
        ->join('projects', 'projects.id', '=', 'applications.id_project')
        ->join('groups', 'groups.id', '=', 'applications.id_group')
        ->select('groups.*','projects.sujet')
        ->where('projects.id', '=',$id_project)
        ->get();
        return CustomResponse::buildResponse("Found",$results ,302);
    }
}
