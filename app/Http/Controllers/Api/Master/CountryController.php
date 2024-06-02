<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Constants, MasterCountry};
use App\Http\Requests\{AddCountryRequest,EditCountryRequest};
use App\Exceptions\GlobalException as GlobalException;
use Exception;
use Illuminate\Support\Facades\DB;


class CountryController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function getAllCountry(Request $request)
    {
        try {

            $getAllCountry = MasterCountry::where('isActive', 1)->select('id as value', 'countryCode', 'countryName as name','showOnAddress');

            if(!empty($request->showOnAddress) && $request->showOnAddress == 'YES'){

                $getAllCountry = $getAllCountry->where('showOnAddress',$request->showOnAddress);
            }


            $getAllCountry = $getAllCountry->get();

            return (new ResponseHandler)->sendSuccessResponse($getAllCountry);

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function getCourseCountry(Request $request){
        try {

            $getCourseCountry = MasterCountry::where('isActive', 1)->select('id as value', 'countryCode', 'countryName as name','showOnAddress')
                ->get();

            return (new ResponseHandler)->sendSuccessResponse($getCourseCountry);

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function addCountry(AddCountryRequest $request)
    {
        try {

            if ($request->isAdmin) {
                $addCountry = MasterCountry::create([
                    'countryCode' => $request->countryCode,
                    'countryName' => $request->countryName,
                    'showOnAddress' => $request->showOnAddress
                ]);

                if ($addCountry)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COUNTRY_ADDED']], 201);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function editCountry(EditCountryRequest $request, $countryId)
    {
        try {
            if ($request->isAdmin) {

                $editCountry = MasterCountry::where('id', $countryId)
                ->where('isActive', 1)
                ->update([
                    'countryCode' => $request->countryCode,
                    'countryName' => $request->countryName,
                    'showOnAddress' => $request->showOnAddress
                ]);

                if ($editCountry)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COUNTRY_UPDATE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function deleteCountry(Request $request, $countrId)
    {
        try {

            if ($request->isAdmin) {
                $deleteCountry = MasterCountry::where('id', $countrId)->first();

                if($deleteCountry->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteCountry->update(['isActive' => 0]);

                if ($deleteCountry)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COUNTRY_DELETE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }
}
?>
