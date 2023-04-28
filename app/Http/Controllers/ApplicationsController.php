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
    public function SaveApplication(Request $request, $id_user)
    {
        
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
    public function ResponseforApplication(Request $request, $id_user)
    {
        $id_project = $request->id_project;
        $response = $request->response;
        $applications = DB::update('update applications set response = ? where id = ?', [$response , $id_application]);
        $updated_application = Application::find($applications);
        
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
    public function GetMyProjects(Request $request , $id_project){/////neeeed change

        $results = Db::table("applications")
        ->join('projects', 'projects.id', '=', 'applications.id_project')
        ->join('groups', 'groups.id', '=', 'applications.id_group')
        ->select('groups.*','projects.sujet')
        ->where('projects.id', '=',$id_project)
        ->get();
        return CustomResponse::buildResponse("Found",$results ,302);
    }
}
