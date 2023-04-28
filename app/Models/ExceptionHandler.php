<?php

namespace App\Models;

class ExceptionHandler
{

    public static $errorCodes = [
        "1452" => ["errorCode" => 70001, "errorMessage"=> "a foreign key constraint fails"],
        "1048" => ["errorCode" => 70002, "errorMessage"=> "had error hada baqi makhlaq"],
        "23000" =>["errorCode" => 23000, "errorMessage"=> "email or apogee must be unique"]
    ];

    static function getErrorCode($e){
        $messages = explode(":", $e);
        $code = explode(" ", $messages[3])[1];
        return ExceptionHandler::$errorCodes[$code]["errorCode"];
    }


    static function getErrorMessage($e){
        $messages = explode(":", $e);
        $code = explode(" ", $messages[3])[1];
        return ExceptionHandler::$errorCodes[$code]["errorMessage"];
    }
}
