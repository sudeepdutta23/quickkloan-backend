<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

use App\Models\Constants;

use App\Http\Utils\{ResponseHandler, Roles};

use Exception;

use Auth;

use Closure;

class AuthUser
{
    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function handle(Request $request, Closure $next)
    {
        try{

            // user guard check
            if(!empty(Auth::guard('user')->user()) && !empty(Auth::guard('user')->user()->id) && in_array((Auth::guard('user')->user()->role), (new Roles)->UserRoles())) {
            
                $request['user']=Auth::guard('user')->user(); // {id, first_name, last_name, role}                
                $request['isAdmin'] = false;

                if(isset($request->borrower['id']) && $request->borrower['id'] != $request['user']->id){
                
                    return (new ResponseHandler)->sendErrorResponse(['message'=>$this->const['INVALID_BORROWER']], 401);
                }                
              
                return $next($request);
            }

            // admin guard check
            if(!empty(Auth::guard('admin')->user()) && !empty(Auth::guard('admin')->user()->id) && in_array((Auth::guard('admin')->user()->role), (new Roles)->AdminRoles())) {

                $request['user']=Auth::guard('admin')->user(); // {id, first_name, last_name, role}
                $request['isAdmin'] = true;

                return $next($request);
            }

            return (new ResponseHandler)->sendErrorResponse(['message'=>$this->const['TOKEN_EXPIRED']], 401);

        } catch(Exception $e){
            return (new ResponseHandler)->sendErrorResponse(['message'=>$this->const['TOKEN_EXPIRED']], 401);
        }
    }
}
