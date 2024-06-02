<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Forms\{FieldsController, SaveFieldsController};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Constants,
    Individual,
    LeadIndividualMapping,
    IndividualDocument,
    MasterDocumentType
};
use Illuminate\Support\Str;
use App\Http\Requests\SaveForm;
use Carbon\Carbon;
use App\Http\Utils\ResponseHandler;
use App\Exceptions\GlobalException as GlobalException;
use Exception;
use ZipArchive;
use Illuminate\Support\Arr;
use App\Exports\ExportAllLeads;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
class LeadController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function applyLoan(SaveForm $request)
    {
        try {

            $role = $request->user->role ?? Auth::guard('admin')->user()->role;


            $checkExistingApplication = LeadIndividualMapping::where('isActive', 1)->where('individualId', $request->borrower['id'])
                ->where('step', '<', 4)->select('leadId', 'step','consentGiven')->orderBy('id', 'desc')->first();

            if (!empty($checkExistingApplication)) {

                $request['leadId'] = $checkExistingApplication->leadId;
                $request['step'] = $checkExistingApplication->step;

            }

            if (!$request->leadId) {
                $createIndividualData = LeadIndividualMapping::create([
                    'individualId' => $request->borrower['id'],
                    'leadStatus' => 1,
                    'step' => 1,
                    'consentGiven' => 1,
                    'consentGivenAt' => Carbon::now(),
                    // 'createdBy'=> $request->user->role,
                    'createdBy'=> $role,

                ]);

                $request->merge([
                    'leadId' => LeadIndividualMapping::where('id', $createIndividualData->id)->select('leadId')->first()->leadId,
                    'step' => 1
                ]);
            }

            return $this->saveForm($request->merge(['step' => $request->step - 1, 'validate' => false]));

        } catch (Exception $e) {

            throw new GlobalException;

        }

    }

    public function saveForm(SaveForm $request)
    {
        try {
            if ($request->step == 0) {

                return (new FieldsController)->step1($request);
            }

            if ($request->step == 1) {
                if ($request->validate) {
                    $step_1 = (new SaveFieldsController)->saveStep1($request);
                    if (!empty($step_1) && json_decode(json_encode($step_1), true)['original']['error'] == 'true') {
                        return (new ResponseHandler)->sendErrorResponse(['message' => json_decode(json_encode($step_1), true)['original']['data']['message']], 422);
                    }
                }
                return (new FieldsController)->step2($request);
            }

            if ($request->step == 2) {

                if ($request->validate) {
                    $step_2 = (new SaveFieldsController)->saveStep2($request);
                    if (!empty($step_2) && json_decode(json_encode($step_2), true)['original']['error'] == 'true') {
                        return (new ResponseHandler)->sendErrorResponse(['message' => json_decode(json_encode($step_2), true)['original']['data']['message']], 422);
                    }
                }

               return checkAllCosignerConsentGiven($request) ? (new FieldsController)->step3($request) : (new FieldsController)->step2($request);

            }

            if ($request->step == 3) {

                $getLoanType = LeadIndividualMapping::where('leadId', $request->leadId)->where('individualType', 'Borrower')->where('isActive', 1)
                    ->select('loanType', 'individualType')->first();

                if (!empty($getLoanType) && ($getLoanType->loanType != $request->loanType)) {
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['INVALID_REQUEST']], 400);
                }

                if ($request->submit != true) {
                    $step_3 = (new SaveFieldsController)->saveStep3($request);

                    if (!empty($step_3) && json_decode(json_encode($step_3), true)['original']['error'] == 'true') {
                        return (new ResponseHandler)->sendErrorResponse(['message' => json_decode(json_encode($step_3), true)['original']['data']['message']], 422);
                    }
                    return (new FieldsController)->step3($request);
                }

                // if ($request->submit == "true" && !$request->isAdmin) {
                if ($request->submit == "true") {
                    return (new FieldsController)->step4($request);
                }
            }
        } catch (Exception $e) {

            throw new GlobalException;
        }

    }

    public function getOngoingCompletedIndividual(Request $request)
    {

        try {



            $onGoing = Individual::leftJoin('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
            ->leftJoin('master_status', 'master_status.id', '=', 'lead_individual_mapping.leadStatus')
            ->leftJoin('master_stage', 'master_stage.id', '=', 'master_status.stageId')
            ->leftJoin('master_loan_type', 'master_loan_type.id', '=', 'lead_individual_mapping.loanType')
            ->where('lead_individual_mapping.individualType', 'Borrower')
            ->where('individualId', $request->borrower['id'])
            ->where('lead_individual_mapping.leadStatus', '!=', 12)
            ->where('lead_individual_mapping.isActive', 1)
            ->orderBy('lead_individual_mapping.id','Desc')
            ->orderBy('master_loan_type.orderBy','Asc')   // newly added
            ->select('individualId',DB::raw('concat_ws(" ",firstName,middleName,lastName) AS fullName'),'master_status.name as status',
                'stageName','loanAmount','master_loan_type.name as loanType','lead_individual_mapping.leadId','leadStatus','stageId','step','master_status.id as statusId')->get();

            $completed = Individual::leftJoin('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
            ->leftJoin('master_status', 'master_status.id', '=', 'lead_individual_mapping.leadStatus')
            ->leftJoin('master_stage', 'master_stage.id', '=', 'master_status.stageId')
            ->leftJoin('master_loan_type', 'master_loan_type.id', '=', 'lead_individual_mapping.loanType')
            ->where('lead_individual_mapping.individualType', 'Borrower')
            ->where('individualId', $request->borrower['id'])
            ->where('lead_individual_mapping.leadStatus', '=', 12)
            ->where('lead_individual_mapping.isActive', 1)
            ->orderBy('master_loan_type.orderBy','Asc') //newly added
            ->select('individualId',DB::raw('concat_ws(" ",firstName,middleName,lastName) AS fullName'),'master_status.name as status',
                'stageName','loanAmount','master_loan_type.name as loanType','lead_individual_mapping.leadId','leadStatus','stageId','step','master_status.id as statusId')->get();

            return (new ResponseHandler)->sendSuccessResponse(
                [
                    "completed" => $completed,
                    'onGoing' => $onGoing,
                ]
            );


        } catch (Exception $e) {
            throw new GlobalException;

        }

    }

    public function getAllLeads(Request $request)
    {
        try {


            if ($request->isAdmin) {

                $statuses = null;

                $pageOffset = (isset($request->pageOffset)) ? (int) $request->pageOffset : 12;

                $orderBy = (isset($request->orderBy)) ? $request->orderBy : 'lead_individual_mapping.leadId';

                $sort = (isset($request->sort)) ? $request->sort : 'desc';

                $type = (isset($request->type)) ? $request->type : 'all';

                switch ($type) {
                    case 'ongoing':
                        $statuses = [1, 2, 3, 4, 5, 6, 7];
                        break;
                    case 'completed':
                        $statuses = [11, 12];
                        break;
                    case 'rejected':
                        $statuses = [8, 9, 10, 13, 14, 15, 16, 17, 18, 19, 20, 21];
                        break;
                    case 'all':
                        $statuses = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 23, 24];
                        break;
                }

                $allLeadsQuery = LeadIndividualMapping::leftJoin('individual', 'individual.id', '=', 'lead_individual_mapping.individualId')
                    ->leftJoin('master_status', 'master_status.id', '=', 'leadStatus')
                    ->leftJoin('master_loan_type', 'master_loan_type.id', '=', 'loanType')
                    ->leftJoin('master_stage', 'master_stage.id', '=', 'stageId')
                    ->where('lead_individual_mapping.individualType', 'Borrower')
                    ->where('lead_individual_mapping.isActive', 1)
                    ->orderBy('master_loan_type.orderBy','Asc')   //newly added
                    ->select('individualId',DB::raw('concat_ws(" ",firstName,middleName,lastName) AS fullName'),
                    'master_status.name as statusName','stageName','loanAmount','master_loan_type.name as loanType',
                    'lead_individual_mapping.leadId','leadStatus','stageId','step','individual.emailId',
                    'individual.mobileNo',
                    'lead_individual_mapping.createdAt');


                        if(empty($request->searchValue))
                        {

                            $allLeadsQuery = $allLeadsQuery->whereIn('leadStatus', $statuses);
                        }

                        else if(!empty($request->searchValue))
                        {

                            $allLeadsQuery = $allLeadsQuery->where('lead_individual_mapping.leadId',$request->searchValue)
                                                ->orWhere('individual.emailId','LIKE', '%' .$request->searchValue. '%')
                                                ->orWhere('individual.mobileNo','LIKE', '%' .$request->searchValue. '%');
                        }


                        if(!empty($request->leadStatus)){

                            $allLeadsQuery = $allLeadsQuery->where('leadStatus',intval($request->leadStatus))
                            ->whereNotNull('leadStatus');
                        }


                        if(!empty($request->fromDate) && !empty($request->toDate))
                        {
                            $allLeadsQuery = $allLeadsQuery->whereBetween('lead_individual_mapping.createdAt', [$request->fromDate.' 00:00:00',$request->toDate.' 23:59:59']);
                        }

                        if(!empty($request->isExport)){
                            return Excel::download(new ExportAllLeads($request,$statuses), 'leadreport.xlsx');
                        }

                        $allLeadsQuery = $allLeadsQuery->orderBy($orderBy,$sort);
                        $allLeadsQuery = $allLeadsQuery->paginate($pageOffset);


                return (new ResponseHandler)->sendSuccessResponse(
                    [
                        "records" => $allLeadsQuery
                    ]
                );
            }

            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
            throw new GlobalException;
        }

    }

    public function getLeadRecord(Request $request, $leadId)
    {

        try {


            $checkLeadId = LeadIndividualMapping::join('individual', 'individual.id', '=', 'lead_individual_mapping.individualId')
            ->where('leadId', $leadId)->where('individualType', "Borrower")
            ->where('mobileNo', '!=', null)->where('role', 1)
            ->where('lead_individual_mapping.isActive',1)
            ->select('individual.id as borrowerId','leadId','step');


            if($request->isAdmin == true && !empty($request->user)){

                $checkLeadId = $checkLeadId->first();

                if (empty($checkLeadId)){
                    return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['INVALID_LEAD_ID']]);
                }

            }else if($request->isAdmin == false && !empty($request->user)){

                $checkLeadId = $checkLeadId->where('lead_individual_mapping.individualId', $request->user->id)->first();

                if (empty($checkLeadId) || (!empty($checkLeadId->leadId) && $checkLeadId->leadId != $leadId)){
                    return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['INVALID_LEAD_ID']]);
                }

                if($checkLeadId->step != env('FINAL_STEP')){
                    return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['LEADID_ACCESS_ERROR']]);
                }

            }
            else
            {
                return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['INVALID_LEAD_ID']]);

            }
                $responseObject = [];

                $responseObject['borrower'] = LeadIndividualMapping::where('lead_individual_mapping.leadId', $checkLeadId->leadId)
                ->leftJoin('individual', 'individual.id', '=', 'lead_individual_mapping.individualId')
                ->leftJoin('individual_advance', 'individual_advance.individualId', '=', 'lead_individual_mapping.individualId')
                ->leftJoin('master_status', 'master_status.id', '=', 'leadStatus')
                ->leftJoin('master_loan_type', 'master_loan_type.id', '=', 'loanType')
                ->leftJoin('master_stage', 'master_stage.id', '=', 'stageId')
                ->leftJoin('master_courses', 'master_courses.id', '=', 'courseId')
                ->leftJoin('master_country', 'master_country.id', '=', 'courseCountryId')
                ->leftJoin('master_employment_status', 'master_employment_status.id', '=', 'individual_advance.employmentTypeId')
                ->where('individualType', "Borrower")
                ->where('lead_individual_mapping.isActive', 1)
                ->orderBy('master_loan_type.orderBy','Asc')   //newly added
                ->select('lead_individual_mapping.leadId','lead_individual_mapping.individualId','step','individualType','loanAmount','master_loan_type.id as loanTypeId','master_loan_type.name as loanType','salutation',
                    DB::raw('concat_ws(" ",firstName,middleName,lastName) AS name'),'mobileNo','emailId','master_status.name as statusName','stageName',
                    'leadStatus','stageId','master_country.countryName as courseCountry')->first();


                if (!$responseObject['borrower']) {
                    return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['INVALID_LEAD_ID']]);
                }


                $request->merge(['leadId' => $leadId, 'borrower' => ['id' => $checkLeadId->borrowerId]]);
                $responseObject['step1'] = json_decode(json_encode((new FieldsController)->step1($request)), true)['original']['data'];
                $responseObject['step2'] = json_decode(json_encode((new FieldsController)->step2($request)), true)['original']['data'];
                $responseObject['step3'] = json_decode(json_encode((new FieldsController)->step3($request)), true)['original']['data'];


                return (new ResponseHandler)->sendSuccessResponse(
                    [
                        "leadDetails" => $responseObject
                    ]
                );


            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {

            throw new GlobalException;
        }
    }

    public function updateLeadStatus(Request $request)
    {
        try {

            if ($request->isAdmin) {

                $lead = LeadIndividualMapping::where('leadId', $request->leadId)->where('individualId', $request->borrower['id'])
                ->where('individualType', 'Borrower')->first();

                $checkDisbursedStatus = $lead->where('leadStatus', 12)->where('leadId', $request->leadId)
                ->where('step', '>', 3)->first();


                if (!empty($checkDisbursedStatus)) {

                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['LEAD_STATUS_UPDATE_ERROR']], 400);
                }

                $lead->update(['leadStatus' => $request->borrower['leadStatus']]);

                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LEAD_STATUS_UPDATE']], 200);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
            throw new GlobalException;
        }
    }

    public function updateStep(Request $request)
    {
        try {

            if ($request->isAdmin) {
                LeadIndividualMapping::where('individualType', 'Borrower')->where('leadId', $request->leadId)
                ->where('isActive', 1)->update([ 'step' => $request->step ]);

                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['UPDATE_STEP']], 200);
            }

            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
            throw new GlobalException;
        }

    }


    public function downloadAllFile(Request $request,$leadId){
        try {

            if ($request->isAdmin) {

                $allFilePath = IndividualDocument::where('individual_document.leadId',$leadId)
                ->where('individual_document.isActive',1)
                ->get();

                if($allFilePath->isEmpty()){
                    return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['DOCUMENT_EMPTY_ERROR']],404);
                }

               $zip = new ZipArchive;

               $zipFile = public_path().'/images/'.'LEAD'.'-'. $leadId.'.zip';

               if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE){

                    foreach ($allFilePath as $key => $value) {

                        $getDocType = MasterDocumentType::where('id',$allFilePath[$key]->documentTypeId)->first();

                        $docType = str_replace(' ', '', Str::limit(str_replace(array('(', ')'), '', $getDocType->documentType ), 25));

                        $renameDoc = str_replace(array('\'', '"', ',', ';', '<', '>', '.'), ' ', $docType);

                        $newDocumentPath = str_replace('/'.$allFilePath[$key]->documentTypeId.'/', '/' . $renameDoc . '/', $allFilePath[$key]->documentPath);

                        $baseName = substr($newDocumentPath,env('BASE_NAME_NUMBER'));

                        $splitre =  preg_split("#/#", $baseName);

                        $splitre[1]='LEAD-'.$leadId;                        

                        $zip->addFile(public_path(substr($allFilePath[$key]->documentPath,env('ADD_FILE_NUMBER'))),implode('/',$splitre));

                    }
                    $zip->close();
                }

                return response()->download($zipFile)->deleteFileAfterSend(true);
            }

            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
        
            throw new GlobalException;
        }
    }



}
