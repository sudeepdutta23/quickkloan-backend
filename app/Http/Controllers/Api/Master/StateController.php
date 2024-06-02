<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Constants, MasterState};
use App\Http\Requests\{AddStateRequest,EditStateRequest};
use App\Exceptions\GlobalException as GlobalException;
use Exception;

class StateController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function getAllState(Request $request)
    {
        try {

            $getAllState = MasterState::where('isActive', 1)->select('id as value', 'countryId', 'stateCode', 'stateName as name')
                ->get();

            return (new ResponseHandler)->sendSuccessResponse($getAllState);

        } catch (Exception $e) {
            
            throw new GlobalException; 

        }
    }

    public function addState(AddStateRequest $request)
    {
        try {
            if ($request->isAdmin) {
                $addCountry = MasterState::create(['countryId' => $request->countryId, 'stateCode' => $request->stateCode,
                'stateName' => $request->stateName]);

                if ($addCountry)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['STATE_ADD']], 201);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
            
            throw new GlobalException; 

        }
    }

    public function editState(EditStateRequest $request, $stateId)
    {
        try {


            if ($request->isAdmin) {
                $addCountry = MasterState::where('id', $stateId)->update(['countryId' => $request->countryId, 'stateCode' => $request->stateCode,
                'stateName' => $request->stateName]);

                if ($addCountry)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['STATE_UPDATE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);



        } catch (Exception $e) {
            
            throw new GlobalException; 

        }
    }

    public function deleteState(Request $request, $stateId)
    {
        try {

            if ($request->isAdmin) {
                $deleteState = MasterState::where('id', $stateId)->first();

                if($deleteState->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteState->update(['isActive' => 0]);

                if ($deleteState)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['STATE_DELETE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
            
            throw new GlobalException; 

        }
    }
}
?>
