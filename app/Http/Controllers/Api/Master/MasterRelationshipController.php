<?php

namespace App\Http\Controllers\Api\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Constants,
    MasterRelationship,
};
use Illuminate\Support\Str;
use App\Http\Requests\SaveForm;
use Carbon\Carbon;
use App\Http\Utils\ResponseHandler;
use App\Exceptions\GlobalException as GlobalException;
use Exception;
use Illuminate\Support\Arr;
use App\Http\Requests\{AddRelationshipRequest,EditRelationshipRequest};

class MasterRelationshipController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function getAllRelationship(Request $request)
    {
        try {

            $getAllRelationship = MasterRelationship::select('id as value','relationship as name')
            ->where('isActive',1)
            ->orderBy('id','Desc')
            ->get();

            if($getAllRelationship)
                return (new ResponseHandler)->sendSuccessResponse($getAllRelationship);

        } catch (Exception $e) {
            throw new GlobalException;
        }

    }

    public function addRelationship(AddRelationshipRequest $request){
        try {

            if ($request->isAdmin) {

                $addRelationship = MasterRelationship::create([
                    'relationship'=>$request->relationship,
                    'createdAt'=>Carbon::now(),
                    'updatedAt'=>Carbon::now(),
                ]);

                if ($addRelationship)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['RELATIONSHIP_ADD']], 201);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
            throw new GlobalException;
        }
    }

    public function editRelationship(EditRelationshipRequest $request,$rid){

        try {

            if($request->isAdmin) {

                $editRelationship = MasterRelationship::where('id', $rid)
                ->update([
                    'relationship'=>$request->relationship,
                    'updatedAt'=>Carbon::now(),
                ]);

                if($editRelationship)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['RELATIONSHIP_UPDATE']], 200);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);

            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
            throw new GlobalException;
        }
    }


    public function deleteRelationship(Request $request, $rid)
    {
        try {

            if ($request->isAdmin) {

                $deleteRelationship = MasterRelationship::where('id', $rid)->first();

                if($deleteRelationship->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteRelationship->update(['isActive' => 0]);

                if ($deleteRelationship)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['RELATIONSHIP_DELETE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }




}
