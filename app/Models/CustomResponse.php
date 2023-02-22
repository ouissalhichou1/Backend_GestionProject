<?php

namespace App\Models;

class CustomResponse{

    static function buildResponse($message, $body, $status){
        return response(['status'=>$status,'message'=> $message,'data'=> $body], $status)->header('Content-Type', 'application/json');
    }
}
