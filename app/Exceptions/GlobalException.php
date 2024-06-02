<?php

namespace App\Exceptions;

use Exception;

use App\Http\Utils\ResponseHandler;

class GlobalException extends Exception
{

    public function report()
    {
       
    }
 
    public function render($request)
    {
               
        return (new ResponseHandler)->sendErrorResponse();
        
    }
}
