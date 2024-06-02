<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{IndividualAddress, Constants};
use App\Exceptions\GlobalException as GlobalException;
use Exception;

class AddressController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function Upsert(int $leadId, int $individualId, array $address)
    {
        try {

            if(!isset($address['currentIsPermanent'])) {
                $address['currentIsPermanent'] = false;
            }

            $updateAddress = array_merge($address, [
                'permanentAddress' => $address['currentIsPermanent'] ? $address['currentAddress'] : $address['permanentAddress'],
                'permanentLandmark' => $address['currentIsPermanent'] ? $address['currentLandmark'] : $address['permanentLandmark'],
                'permanentAddressLineTwo' => $address['currentIsPermanent'] ? $address['currentAddressLineTwo'] ?? null : $address['permanentAddressLineTwo'] ?? null,
                'permanentCityId' => $address['currentIsPermanent'] ? $address['currentCityId'] : $address['permanentCityId'],
                'permanentStateId' => $address['currentIsPermanent'] ? $address['currentStateId'] : $address['permanentStateId'],
                'permanentPincode' => $address['currentIsPermanent'] ? $address['currentPincode'] : $address['permanentPincode'],
                'permanentCountryId' => $address['currentIsPermanent'] ? $address['currentCountryId'] : $address['permanentCountryId'],
                'permanentResidenceTypeId' => $address['currentIsPermanent'] ? $address['currentResidenceTypeId'] : $address['permanentResidenceTypeId'],
            ]);

            IndividualAddress::updateOrCreate(['individualId' => $individualId, 'leadId' => $leadId, 'isActive' => 1], $updateAddress);

            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['SUCCESSFULL']]);
        } catch (Exception $e) {
            throw new GlobalException;
        }
    }
}
?>
