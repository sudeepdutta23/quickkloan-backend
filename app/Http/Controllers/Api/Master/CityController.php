<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Constants, MasterCity};
use App\Http\Requests\{AddCityRequest,EditCityRequest};
use Exception;

use App\Exceptions\GlobalException as GlobalException;

class CityController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function getAllCity(Request $request, $stateId)
    {
        try {

            $getAllCity = MasterCity::where('isActive', 1)->select('id as value', 'cityCode', 'cityName as name','stateId')->where('stateId', $stateId)
                ->get();

            return (new ResponseHandler)->sendSuccessResponse($getAllCity);

        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }

    public function addCity(AddCityRequest $request)
    {
        try {

            if($request->isAdmin){
                $addCity = MasterCity::create(["stateId" => $request->stateId, "cityCode" => $request->cityCode, "cityName" => $request->cityName]);

                if($addCity)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['CITY_ADD']], 201);
            }

            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
            
           
            throw new GlobalException; 

        }
    }

    public function editCity(EditCityRequest $request,$cityId)
    {
        try {
         
            if($request->isAdmin){
                $editCity = MasterCity::where('id',$cityId)->update(["stateId" => $request->stateId, "cityCode" => $request->cityCode, "cityName" => $request->cityName]);

                if($editCity)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['CITY_UPDATE']]);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }

    public function deleteCity(Request $request,$cityId)
    {
        try {

            if($request->isAdmin){
                $deleteCity = MasterCity::where('id',$cityId)->first();

                if($deleteCity->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteCity->update(['isActive' => 0]);

                if($deleteCity)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['CITY_DELETE']], 200);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }



}
