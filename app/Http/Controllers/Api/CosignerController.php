<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Individual, Constants, LeadIndividualMapping,IndividualDocument};

use App\Http\Requests\DeleteCosigner;
use App\Exceptions\GlobalException as GlobalException;
use Exception;
use App\Http\Controllers\Api\MailController;
use App\Jobs\UserRegisterMailJob;
use Illuminate\Support\Facades\Mail;


class CosignerController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function Upsert(int $leadId, int $borrowerId, array $cosigners)
    {

        try {

            $cosignerMobile = array_column($cosigners, 'mobileNo');
            $cosignerEmail = array_column($cosigners, 'emailId');

            if ($cosignerMobile != array_unique($cosignerMobile)) {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['DUPLICATE_NUMBER']], 422);
            }

            if ($cosignerEmail != array_unique($cosignerEmail)) {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['DUPLICATE_EMAIL']], 422);
            }

            foreach ($cosigners as $key => $value) {

                $cosignerUpdate = $cosigners[$key];

                $isExisting = isset($cosignerUpdate['id']) ? $cosignerUpdate['id'] : false;

                $checkEmail = Individual::where('emailId', $cosignerUpdate['emailId'])->where('isActive', 1)->select('id', 'emailId')->first();

                if (!empty($checkEmail->emailId) && (!$isExisting || ($checkEmail->id != $cosignerUpdate['id']) || ($checkEmail->id == $borrowerId))) {
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['EMAIL_TAKEN']], 422);
                }

                $checkMobile = Individual::where('mobileNo', $cosignerUpdate['mobileNo'])->where('isActive', 1)->select('id', 'mobileNo')->first();

                if (!empty($checkMobile->mobileNo) && (!$isExisting || ($checkMobile->id != $cosignerUpdate['id']) || ($checkMobile->id == $borrowerId))) {
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['MOBILE_TAKEN']], 422);
                }
            }

            foreach ($cosigners as $key => $value) {


                $cosignerUpdate = $cosigners[$key];

                $cosignerAddress = $cosignerUpdate['address'];

                $relationship = $cosignerUpdate['relationship'] ?? null;

                $cosigner = null;

                unset($cosignerUpdate['address']);

                $isExisting = isset($cosignerUpdate['id']) ? $cosignerUpdate['id'] : false;

                $whereCond = ['mobileNo' => $cosignerUpdate['mobileNo'], 'emailId' => $cosignerUpdate['emailId'], 'isActive' => 1];


                if (!$isExisting) {

                    unset($cosignerUpdate['relationship']);

                    $cosigner = Individual::updateOrCreate($whereCond, $cosignerUpdate);

                    // UserRegisterMailJob::dispatch((new MailController)->OtpMail($cosignerUpdate,randomNumber(), true, true));

                    // insert Cosigner records into individual_lead mapping under Borrower table
                    LeadIndividualMapping::updateOrCreate([
                        'leadId' => $leadId, 'individualId' => $cosigner->id,
                        'individualType' => 'Cosigner', 'isActive' => 1,
                    ],
                    [
                        'individualType' => 'Cosigner',
                        'leadId' => $leadId,
                        'individualId' => $cosigner->id,
                        'consentGiven' => 0,
                        'relationship' => $relationship
                    ]);
                }else
                {

                    LeadIndividualMapping::where('individualId',$cosignerUpdate['id'])
                        ->where('isActive',1)
                        ->where('individualType','Cosigner')
                        ->where('leadId',$leadId)
                        ->update([
                            'relationship' => $relationship
                        ]);

                }

                $address = (new AddressController)->Upsert($leadId, isset($cosignerUpdate['id']) ? $cosignerUpdate['id']:$cosigner->id, $cosignerAddress);

                if (!empty($address) && json_decode(json_encode($address), true)['original']['error'] == 'true') {
                    return (new ResponseHandler)->sendErrorResponse(['message' => json_decode(json_encode($address), true)['original']['data']['message']], 422);
                }

            }

            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['SUCCESSFULL']], 200);
        } catch (Exception $e) {

            throw new GlobalException;

        }

    }

    public function deleteCosigner(DeleteCosigner $request, $individualId, $leadId)
    {
        try {
            $deleteCosigner = LeadIndividualMapping::where('individualId', $individualId)->where('leadId', $leadId)
                ->where('individualType', 'Cosigner')->where('isActive', 1)->update(['isActive' => 0]);


            if ($deleteCosigner){

                IndividualDocument::where('individualId',$individualId)->update(['isActive' => 0]);

                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COSIGNER_DEACTIVE']], 200);
            }


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }
}
?>
