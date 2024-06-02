<?php

namespace App\Http\Controllers\Api\Forms;

use App\Http\Controllers\Api\DocumentController;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;
use App\Exceptions\GlobalException as GlobalException;
use App\Models\{
    LeadIndividualMapping,
    Individual,
    IndividualDocument,
    Constants,
    MasterStage,
    MasterDocumentType
};

use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\DB;


use Exception;

class FieldsController extends Controller
{
    private $const, $logConst;
    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }
    public function step1(Request $request)
    {
        try {

            $loanFields = LeadIndividualMapping::where('individualId', $request->borrower['id'])
                ->where('leadId', $request->leadId)->where('isActive', 1)
                ->select('leadId', 'individualId', 'step', 'individualType', 'loanAmount', 'loanType')->first();

            $address = $loanFields->leftJoin('individual_address', 'individual_address.individualId', '=', 'lead_individual_mapping.individualId')
                ->where('lead_individual_mapping.isActive', 1)
                ->where('lead_individual_mapping.individualId', $request->borrower['id'])
                ->where('lead_individual_mapping.leadId', $request->leadId)
                ->where('individual_address.isActive', 1)
                ->where('individual_address.leadId', $request->leadId)
                ->select('individual_address.*')->first();

            return (new ResponseHandler)->sendSuccessResponse(
                [
                    "leadId" => (int) $loanFields->leadId,
                    "step" => 1,
                    "loanType" => $loanFields->loanType ?? null,
                    'borrower' => [
                        "id" => (int) $loanFields->individualId,
                        "loanAmount" => $loanFields->loanAmount ? (int) $loanFields->loanAmount : null,
                        "address" => $address == null ? null : $address
                    ],
                ]
            );
        } catch (Exception $e) {
            throw new GlobalException;
        }
    }
    public function step2(Request $request)
    {
        try {


            $getLoanType = LeadIndividualMapping::where('individualId', $request->borrower['id'])->where('leadId', $request->leadId)
                ->where('isActive', 1)->select('loanType')->first();

            $allCosignerConsentGiven = checkAllCosignerConsentGiven($request);

            $cosignerInfo = null;

            $responseObject = ["leadId" => (int) $request->leadId, "step" => 2, 'loanType' => (int) $getLoanType->loanType, 'allCosignerConsentGiven' => (bool) $allCosignerConsentGiven];

            if ($getLoanType->loanType == "1" || $getLoanType->loanType == "2") {
                $cosignerInfo = Individual::with([
                    'address' => function ($query) {
                        $query->select(
                            "individualId",
                            "currentAddress",
                            "currentAddressLineTwo",
                            "currentLandmark",
                            "currentCityId",
                            "currentStateId",
                            "currentPincode",
                            "currentCountryId",
                            "currentResidenceTypeId",
                            "permanentAddress",
                            "permanentAddressLineTwo",
                            "permanentLandmark",
                            "permanentCityId",
                            "permanentStateId",
                            "permanentPincode",
                            "permanentCountryId",
                            "permanentResidenceTypeId",
                            "currentIsPermanent",
                        );
                        $query->where('individual_address.isActive', 1);
                    }
                ])->join('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
                    ->where('lead_individual_mapping.isActive', 1)
                    ->where('individual.isActive', 1)
                    ->where('lead_individual_mapping.leadId', $request->leadId)
                    ->where('lead_individual_mapping.individualType', 'Cosigner')
                    ->select(
                        'individual.id',
                        'salutation',
                        'firstName',
                        'middleName',
                        'lastName',
                        'emailId',
                        'mobileNo',
                        'consentGiven',
                        'relationship'
                    )->get();

                if ($cosignerInfo->count() < 1) {
                    $cosignerInfo = [];
                }
            }

            $borrowerInfo = Individual::leftJoin('individual_address', 'individual_address.individualId', '=', 'individual.id')
                ->leftJoin('individual_advance', 'individual_advance.individualId', '=', 'individual.id')
                ->leftJoin('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
                ->leftJoin('master_employment_status', 'master_employment_status.id', '=', 'individual_advance.employmentTypeId')
                ->leftJoin('master_courses', 'master_courses.id', '=', 'lead_individual_mapping.courseId')
                ->select('loanAmount', 'courseId', 'courseCountryId', 'employmentTypeId', 'salutation', 'salary')
                ->where('individual.isActive', 1)->where('lead_individual_mapping.isActive', 1)
                ->where('individual.id', $request->borrower['id'])
                ->where('lead_individual_mapping.leadId', $request->leadId)
                ->where('individual_address.isActive', 1)->first();


            if ($getLoanType->loanType == "1" || $getLoanType->loanType == "2") {
                $responseObject['cosigner'] = $cosignerInfo;
            }

            if ($getLoanType->loanType == "1") {
                $responseObject['borrower']['courseCountryId'] = $borrowerInfo->courseCountryId;
            }

            if ($getLoanType->loanType == "2") {
                $responseObject['borrower']['courseId'] = $borrowerInfo->courseId;
            }

            if ($getLoanType->loanType == "3" || $getLoanType->loanType == "4") {
                $responseObject['borrower']['employmentTypeId'] = $borrowerInfo->employmentTypeId;
                $responseObject['borrower']['salary'] = $borrowerInfo->salary ? (int) $borrowerInfo->salary : null;
            }

            $responseObject['borrower']["id"] = (int) $request->borrower['id'];

            return (new ResponseHandler)->sendSuccessResponse($responseObject, 200);
        } catch (Exception $e) {

            throw new GlobalException;
        }
    }
    public function step3(Request $request)
    {
        try {

            $getLoanType = LeadIndividualMapping::join('individual', 'individual.id', '=', 'lead_individual_mapping.individualId')
                ->where('individualType', 'Borrower')->where('leadId', $request->leadId)
                ->where('lead_individual_mapping.isActive', 1)->where('individual.isActive', 1)
                ->select('individualId', 'loanType', DB::raw('concat_ws(" ",firstName,middleName,lastName) AS fullName'), 'createdBy')
                ->first();


            $borrowerFileInfo =  MasterDocumentType::leftJoin('individual_document', function ($join) use ($request, $getLoanType) {
                $join->on('individual_document.documentTypeId', '=', 'master_document_type.id')
                    ->where('individual_document.isActive', 1)
                    ->where('individual_document.individualId', '=', $getLoanType->individualId)
                    ->where('individual_document.leadId', $request->leadId);
            })
                ->leftJoin('lead_individual_mapping', function ($join) use ($request) {
                    $join->on('lead_individual_mapping.individualId', '=', 'individual_document.individualId')
                        ->where('lead_individual_mapping.isActive', 1)
                        ->where('lead_individual_mapping.individualType', 'Borrower')
                        ->where('lead_individual_mapping.leadId', $request->leadId);
                })
                ->whereRaw("find_in_set('$getLoanType->loanType', master_document_type.loanType)")
                ->whereRaw("find_in_set('Borrower', master_document_type.individualType)")
                ->select(
                    'master_document_type.id as documentTypeId',
                    'master_document_type.documentType',
                    'master_document_type.documentName',
                    'documentPath',
                    'master_document_type.isActive',
                    DB::raw('CASE
                            WHEN master_document_type.requiredIndividualType  like "%Borrower%" THEN 1
                            ELSE 0
                            END
                    AS requiredIndividualType')
                )->get();




            $borrowerDocs = [];

            $filterBorrowerDocs = $borrowerFileInfo->filter(function ($borrowerFileInfo) {
                if ($borrowerFileInfo->documentPath != null || $borrowerFileInfo->isActive != 0) {
                    return $borrowerFileInfo;
                }
            });

            foreach ($filterBorrowerDocs as $key => $value) {
                array_push($borrowerDocs, $value);
            }

            $coId = Individual::join('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
                ->leftJoin('master_relationship', 'master_relationship.id', '=', 'lead_individual_mapping.relationship')
                ->select('lead_individual_mapping.individualId', 'master_relationship.relationship', DB::raw('concat_ws(" ",firstName,middleName,lastName) AS fullName'))
                ->where('lead_individual_mapping.individualType', 'Cosigner')
                ->where('lead_individual_mapping.leadId', $request->leadId)
                ->where('individual.isActive', 1)
                ->where('lead_individual_mapping.isActive', 1)
                ->groupBy('lead_individual_mapping.individualId')
                ->get();


            $cosignerDocs = [];

            $cosignerFilterDocs = [];

            $cosignerInfo = [];

            if (!empty($coId)) {

                foreach ($coId as $key => $val) {

                    $cosignerFileInfo = MasterDocumentType::leftJoin('individual_document', function ($join) use ($request, $val) {
                        $join->on('individual_document.documentTypeId', '=', 'master_document_type.id')
                            ->where('individual_document.individualId', '=', $val->individualId)
                            ->where('individual_document.isActive', 1)
                            ->where('individual_document.leadId', $request->leadId);
                    })
                        ->leftJoin('lead_individual_mapping', function ($join) use ($request, $val) {
                            $join->on('lead_individual_mapping.individualId', '=', 'individual_document.individualId')
                                ->where('lead_individual_mapping.isActive', 1)
                                ->where('lead_individual_mapping.individualType', 'Cosigner')
                                ->where('lead_individual_mapping.leadId', $request->leadId)
                                ->where('lead_individual_mapping.individualId', '=', $val->individualId);
                        })
                        ->whereRaw("find_in_set('$getLoanType->loanType', master_document_type.loanType)")
                        ->whereRaw("find_in_set('Cosigner', master_document_type.individualType)")
                        ->select(
                            'master_document_type.id as documentTypeId',
                            'master_document_type.documentType',
                            'master_document_type.documentName',
                            'documentPath',
                            'relationship',
                            'master_document_type.isActive',
                            DB::raw(
                                'CASE
                            WHEN master_document_type.requiredIndividualType  like "%Cosigner%" THEN 1
                            ELSE 0
                            END
                        AS requiredIndividualType'
                            )
                        )
                        ->get();

                    $filterCosignerDocs = $cosignerFileInfo->filter(function ($cosignerFileInfo) {

                        if ($cosignerFileInfo->documentPath != null || $cosignerFileInfo->isActive != 0) {
                            return $cosignerFileInfo;
                        }
                    });


                    $arrayData = json_decode($filterCosignerDocs, true);

                    $objectsArray = [];

                    foreach ($arrayData as $item) {
                        $object = (object) $item;
                        array_push($objectsArray, $object);
                    }

                    $temp = [
                        "id" => $val->individualId,
                        'name' => $val->fullName,
                        "relationship" => $val->relationship,
                        "fileObject" => $objectsArray
                    ];

                    array_push($cosignerInfo, $temp);
                }

                return (new ResponseHandler)->sendSuccessResponse(
                    [
                        "createdBy" => $getLoanType->createdBy,
                        "leadId" => (int) $request->leadId,
                        'step' => 3,
                        'loanType' => $getLoanType->loanType ?? null,
                        'borrower' => [
                            'id' => (int) $getLoanType->individualId,
                            'name' => $getLoanType->fullName,
                            'fileObject' => $borrowerDocs,
                        ],
                        'cosigner' => $cosignerInfo
                    ],
                    200
                );
            }
        } catch (Exception $e) {

            throw new GlobalException;
        }
    }

    public function step4(Request $request)
    {
        try {



            $getIndividualType = LeadIndividualMapping::join('individual', 'individual.id', '=', 'lead_individual_mapping.individualId')
                ->where('leadId', $request->leadId)
                ->where('lead_individual_mapping.isActive', 1)
                ->select('loanType', 'individualType', 'individualId', 'consentGiven')->get();


            $loanType = Arr::where($getIndividualType->toArray(), function ($value, $key) {
                return $value['loanType'] != null;
            });

            foreach ($getIndividualType as $key => $value) {

                $docInfo = (new DocumentController)->getDocTypeArray($getIndividualType[$key]->individualType, $loanType[0]['loanType']);


                $checkDoc = IndividualDocument::join('master_document_type', 'master_document_type.id', '=', 'individual_document.documentTypeId')
                    ->join('lead_individual_mapping', 'lead_individual_mapping.leadId', '=', 'individual_document.leadId')
                    ->where('individual_document.individualId', $getIndividualType[$key]->individualId)
                    ->where('individual_document.leadId', $request->leadId)->where('individual_document.isActive', 1)
                    ->whereIn('master_document_type.documentType', $docInfo['required_documents'])
                    ->groupBy('master_document_type.documentType')
                    ->where('master_document_type.requiredIndividualType', 'like', "%" . $getIndividualType[$key]->individualType . "%")
                    ->get();


                if ($checkDoc->count() != $docInfo['count']) {

                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['FILE_MISSING']], 404);
                }
            }

            LeadIndividualMapping::where('individualType', 'Borrower')->where('leadId', $request->leadId)
                ->where('isActive', 1)->update(['step' => 4, 'leadStatus' => 5]);

            $fetchStage = MasterStage::join('master_status', 'master_status.stageId', '=', 'master_stage.id')
                ->join('lead_individual_mapping', 'lead_individual_mapping.leadStatus', '=', 'master_status.id')
                ->where('lead_individual_mapping.leadId', $request->leadId)->where('master_status.isActive', 1)
                ->select('stageName', 'stageId', 'master_status.id as statusId', 'name')->first();


            return (new ResponseHandler)->sendSuccessResponse(
                [
                    'step' => 4,
                    'stageName' => $fetchStage->stageName,
                    'stageId' => (int) $fetchStage->stageId,
                    'statusId' => (int) $fetchStage->statusId,
                    'statusName' => $fetchStage->name,
                    'leadId' => $request->leadId,
                    'message' => $this->const['SUBMIT']
                ]
            );
        } catch (Exception $e) {

            throw new GlobalException;
        }
    }
}
