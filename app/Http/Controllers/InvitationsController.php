<?php

namespace App\Http\Controllers;

use App\Models\invitations;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    Function SendInvitation(Request $request ,$id_group_admin){
        try{
            $id_groups = DB::select('select id from groups where id_group_admin = :id_group_admin', ['id_group_admin' => $id_group_admin]);
            $id_groups=array_map(function ($value){return (array)$value;}, $id_groups);
            $invitation =Invitation::new();
            $invitation = $invitation->id_group = $id_groups[0]["id"]; 
            $invitation = $invitation->id_etudiant = $request->id_etudiant;
            $invitation = save();
            return CustomResponse::buildResponse("created successfully", $invitation, 201);
        }
        catch(QueryException $e) {
            $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
            return CustomResponse::buildResponse("error", $body, 500);
        }
        

    }
   






}
