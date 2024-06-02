<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Http\Utils\{ResponseHandler, Roles};

use App\Models\{Constants,User};

use App\Exceptions\GlobalException as GlobalException;
use Exception;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\UserResource;

use Illuminate\Support\Facades\Hash;



use App\Http\Requests\{
    AddUserRequest
};

class UserController extends Controller
{
    private $const, $logConst;


    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

    }


    public function getAllUser(Request $request)
    {
        try {


            $admin_role = Config::get('customConfig.Role.super_admin');

            if($request->user->role == $admin_role)
            {
                $getAllUser = User::where('users.isActive',1)
                ->where('users.id','!=',$request->user->id)
                ->join('role_masters','role_masters.id','=','users.role')
                ->select('users.id',DB::raw('CONCAT(firstName, lastName) AS name'),'emailId as email','role_masters.role as role','status')
                ->get();

                return (new ResponseHandler)->sendSuccessResponse(new UserResource($getAllUser));


            }else
            {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

            }



        } catch (Exception $e) {

            throw new GlobalException;

        }
    }



    public function addUser(AddUserRequest $request)
    {

        try {


            $super_admin_role = Config::get('customConfig.Role.super_admin');

            if($request->user->role == $super_admin_role)
            {

                if(!empty($request->id))
                {

                    $updateUser = User::where('id',$request->id)
                    ->update([
                        'firstName' => $request->firstName,
                        'lastName' => $request->lastName,
                        'emailId' => $request->emailId,
                        'role' => $request->role
                    ]);


                    if($updateUser)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['USER_UPDATE']]);

                }
                else
                {

                    $addUser = User::create([
                        'firstName' => $request->firstName,
                        'lastName' => $request->lastName,
                        'emailId' => $request->emailId,
                        'passWord' => Hash::make($request->passWord),
                        'role' => $request->role
                    ]);

                    if($addUser)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['USER_ADD']]);
                }

            }
            else
            {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

            }


        } catch (Exception $e) {

            throw new GlobalException;
        }
    }


    public function deleteUser(Request $request,$id){


        $super_admin_role = Config::get('customConfig.Role.super_admin');

        if($request->user->role == $super_admin_role){

            $deleteUser = User::where('id', $id)->where('id','!=',$request->user->id)->first();

            if(!empty($deleteUser))
            {

                if($deleteUser->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
                else
                {
                    $deleteUser->update(['isActive' => 0]);

                    if ($deleteUser)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['USER_DELETE']]);

                }

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }else
            {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
        }else
        {
            return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

        }


    }





}
?>
