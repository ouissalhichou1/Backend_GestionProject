<?php

namespace App\Http\Controllers;

use App\Models\invitations;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
function SendInvitation(Request $request, $id_group_admin)
{
    try {
        $id_groups = DB::select('select id from groups where id_group_admin = :id_group_admin', ['id_group_admin' => $id_group_admin]);
        $id_groups=array_map(function ($value) {return (array)$value;}, $id_groups);
        $invitation =Invitation::new();
        $invitation = $invitation->id_group = $id_groups[0]["id"];
        $invitation = $invitation->id_etudiant = $request->id_etudiant;
        $invitation = save();
        return CustomResponse::buildResponse("created successfully", $invitation, 201);
    } catch(QueryException $e) {
        $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
        return CustomResponse::buildResponse("error", $body, 500);

    }
}
    public function index()
    {
        $invitations = invitations::all();
        return response()->json($invitations);
    }

    public function store(Request $request)
    {
        $invitation = invitations::create($request->all());
        return response()->json($invitation, 201);
    }

    public function show($id)
    {
        $invitation = invitations::find($id);
        return response()->json($invitation);
    }

    public function update(Request $request, $id)
    {
        $invitation = invitations::find($id);
        $invitation->update($request->all());
        return response()->json($invitation);
    }

    public function destroy($id)
    {
        $invitation = invitations::find($id);
        $invitation->delete();
        return response()->json(null, 204);
    }

    
   






}
