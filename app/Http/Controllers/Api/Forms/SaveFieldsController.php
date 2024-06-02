<?php
namespace App\Http\Controllers\Api\Forms;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Api\{AddressController, CosignerController};

use App\Http\Requests\SaveForm;

use App\Http\Utils\ResponseHandler;

use App\Models\{LeadIndividualMapping, Individual, Constants, IndividualDocument, MasterDocumentType};

use App\Models\IndividualAdvance;
use Illuminate\Support\Facades\File;
use App\Exceptions\GlobalException as GlobalException;

use Illuminate\Support\Str;

use Exception;

class SaveFieldsController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function saveStep1(SaveForm $request)
    {
        try {

            $getLoanType = LeadIndividualMapping::where('individualId', $request->borrower['id'])->where('leadId', $request->leadId)
                ->where('isActive', 1)->select('loanType', 'loanAmount')->first();

            $stepOneBorrowerInfo = $request->all()['borrower'];

            $address = (new AddressController)->Upsert($request->leadId, $request->borrower['id'], $stepOneBorrowerInfo['address']);

            if (!empty($address) && json_decode(json_encode($address), true)['original']['error'] == 'true') {
                return (new ResponseHandler)->sendErrorResponse(['message' => json_decode(json_encode($address), true)['original']['data']['message']], 422);
            }

            $loanDetails = ['loanAmount' => $stepOneBorrowerInfo['loanAmount'], 'step' => 2];

            $loanDetails['loanType'] = empty($getLoanType->loanType) ? $request->loanType : $getLoanType->loanType;

            $getLoanType->where('lead_individual_mapping.leadId', $request->leadId)->where('lead_individual_mapping.individualId', $request->borrower['id'])
                ->where('lead_individual_mapping.isActive', 1)->update($loanDetails);

        } catch (Exception $e) {
            throw new GlobalException;
        }
    }

    public function saveStep2(SaveForm $request)
    {

        try {

            $borrowerDetails = $request->all()['borrower'];

            $cosignerDetails = $request->all()['cosigner'] ?? null;

            $mappingQuery = LeadIndividualMapping::where('individualId', $request->borrower['id'])->where('leadId', $request->leadId)
                ->where('isActive', 1);

            $getLoanType = $mappingQuery->select('loanType', 'step')->first();

            if ($getLoanType->loanType != $request->loanType || empty($getLoanType)) {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['INVALID_REQUEST']], 400);
            }

            if (isset($cosignerDetails)) {
                if ($getLoanType->loanType == "1" || $getLoanType->loanType == "2") {

                    $saveCosigner = (new CosignerController)->Upsert($request->leadId, $request->borrower['id'], $cosignerDetails);

                    if (!empty($saveCosigner) && json_decode(json_encode($saveCosigner), true)['original']['error'] == 'true') {
                        return (new ResponseHandler)->sendErrorResponse(['message' => json_decode(json_encode($saveCosigner), true)['original']['data']['message']], 422);
                    }
                }
            }

            if (isset($borrowerDetails)) {
                // update or insert Borrower address and course info
                if ($getLoanType->loanType == "1") {
                    $mappingQuery->update(['courseCountryId' => $borrowerDetails['courseCountryId']]);
                }

                if ($getLoanType->loanType == "2") {
                    $mappingQuery->update(['courseId' => $borrowerDetails['courseId']]);
                }

                if ($getLoanType->loanType == "3" || $getLoanType->loanType == "4") {
                    IndividualAdvance::updateOrCreate(
                    ['individualId' => $borrowerDetails['id'], 'leadId' => $request->leadId, 'isActive' => 1],
                    [
                        'individualId' => $borrowerDetails['id'],
                        'leadId' => $request->leadId,
                        'employmentTypeId' => $borrowerDetails['employmentTypeId'],
                        'salary' => $borrowerDetails['salary']
                    ]);
                }
            }

            $mappingQuery->update(['step' => 3]);

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function saveStep3(SaveForm $request)
    {
        try {


            $individualId = $request->borrower['id'] ?? $request->individualId;



            $validateUser = Individual::join('lead_individual_mapping','lead_individual_mapping.individualId', '=', 'individual.id')
                ->where('individual.id', $individualId)->where('lead_individual_mapping.leadId', $request->leadId)
                ->where('individual.isActive', 1)->where('lead_individual_mapping.isActive', 1)
                ->where('lead_individual_mapping.individualType', $request->individualType)->first();


            if (!$validateUser) {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const["USER_DOESNOT_EXIST"]], 409);
            }

            if($request->user->role == 1){


                if($request->individualType == 'Cosigner'){

                    $borrowerId = LeadIndividualMapping::where('individualType', 'Borrower')
                    ->where('leadId', $request->leadId)
                    ->where('isActive',1)
                    ->first();

                    if($borrowerId->individualId != $request->user->id){
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const["NOT_AUTHORIZED"]], 401);
                    }

                }else
                {
                    if($request->user->id != $individualId){
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const["NOT_AUTHORIZED"]], 401);
                    }
                }


            }

            // check active document
            $checkActiveDoc = MasterDocumentType::leftJoin('individual_document','individual_document.documentTypeId','=','master_document_type.id')
            ->where('master_document_type.id', $request->documentId)
            ->where('master_document_type.isActive',1)->first();

            if(empty($checkActiveDoc)){
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPLOAD']], 400);
            }


              // check individual loan status for doc upload
              $allStatus = [8,9,10,12,13,14,15,16,17,18,19,20,21];

              $checkLeadStatus = LeadIndividualMapping::join('master_status','master_status.id','=','lead_individual_mapping.leadStatus')
              ->where('lead_individual_mapping.individualId', $individualId)
              ->where('lead_individual_mapping.leadId', $request->leadId)
              ->where('lead_individual_mapping.isActive', 1)
              ->whereIn('leadStatus',$allStatus)
              ->first();


              if(!empty($checkLeadStatus)){
                  return (new ResponseHandler)->sendErrorResponse([
                      'message' => $this->const['UNABLE_TO_UPLOAD'].' at '.$checkLeadStatus->name . ' status'
                  ], 400);
              }


            // Add code to Check Document Type is present for that loan type
            $checkExistingDocument = IndividualDocument::where('leadId', $request->leadId)->where('individualId', $individualId)
                ->where('documentTypeId', $request->documentId)->where('isActive', 1)->first();

            if($checkExistingDocument) {
                if (File::exists(public_path(substr($checkExistingDocument->documentPath, env('BASE_PATH_NUMBER'))))) {
                    $deleteFile = public_path(substr($checkExistingDocument->documentPath, env('BASE_PATH_NUMBER')));
                    File::delete($deleteFile);
                }

                $checkExistingDocument->update(['isActive' => 0]);
            }

            $docPhotoFile = $request->file('file');

            if(is_array($docPhotoFile)){
                if(count($docPhotoFile) > 1){
                    return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UPLOAD_ERROR']],400);
                }
                return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNABLE_TO_UPLOAD']],400);
            }

            $docPhoto = str_replace(' ', '', $docPhotoFile->getClientOriginalName());

            $docPhotoFile->move(public_path(env('DOCUMENTS') . '/' . $request->leadId .'/'. $request->individualId.'_'.$validateUser->firstName .'/' . trim($request->documentId)), $docPhoto);

            $docPhotoPath = asset(env('DOCUMENTS') . '/' . $request->leadId . '/' . $request->individualId .'_'. $validateUser->firstName . '/' . trim($request->documentId)) . '/' . $docPhoto;

            IndividualDocument::create([
                'individualId' => $individualId,
                'leadId' => $request->leadId,
                'documentOriginalExt' => $docPhotoFile->getClientOriginalExtension(),
                'documentTypeId' => $request->documentId,
                'documentPath' => $docPhotoPath,
            ]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

}
?>
