<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GlobalException as GlobalException;
use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;
use App\Models\{
    Constants,   
    AboutUs
};

use App\Http\Resources\{AboutUsResource};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Http\Requests\{
    About,    
};

use Illuminate\Support\Facades\Auth;
use Config;
class AboutUsController extends Controller
{

    private $const;
    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function getAbout(){
        try{
           
            $getAbout = AboutUs::select('id','about','isActive')
            ->where('isActive',1)
            ->first();
            
            return (new ResponseHandler)->sendSuccessResponse(new AboutUsResource($getAbout)) ?? [];                
            
        }
        catch(Exception $e) {
            
            throw new GlobalException;

        }
    }

    public function addAbout(About $request){

        try{

                $user_role = Config::get('customConfig.Role.user');

                if($request->user->role != $user_role)
                {
                    
                    if(!empty($request->id)){

                        
                        $editAbout = AboutUs::where('id',$request->id)
                        ->update([
                            'about'=>$request->about
                        ]);
            
                        if($editAbout){  
                            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['UPDATE_ABOUT']]);
                        }
            
                    }else
                    {
                        $addAbout = AboutUs::create([
                            'about'=>$request->about,
                            'isActive'=>1
                        ]);
            
                        if($addAbout){                
                            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['ADD_ABOUT']]);
                        }
            
                    }
                }
                else
                {
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

                }


        }
        catch(Exception $e)
        {
            throw new GlobalException;
        }
    }


    public function deleteAbout(Request $request,$id){

        try{

            $user_role = Config::get('customConfig.Role.user');

            if($request->user->role != $user_role)
            {
                $deleteAbout = AboutUs::where('id', $id)->first();

                if(!empty($deleteAbout))
                {
    
                    if($deleteAbout->isActive == '0')
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
    
                    $deleteAbout->update(['isActive' => 0]);
    
                    if ($deleteAbout)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['DELETE_ABOUT']]);
    
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
        catch(Exception $e)
        {
            throw new GlobalException;            
        }
    }   

    


}
