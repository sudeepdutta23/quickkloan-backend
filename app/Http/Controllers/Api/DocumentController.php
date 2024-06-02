<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exceptions\GlobalException as GlobalException;
use Illuminate\Http\Request;
use App\Models\MasterDocumentType;
use DB;
use App\Models\{Constants, MasterLoanType};
use App\Http\Utils\ResponseHandler;
use App\Http\Requests\{AddDocumentTypeRequest};
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Support\Facades\Validator;
class DocumentController extends Controller
{

    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function getDocTypeArray($individual_type, $loanType)
    {
        try {

            $returnObj = [];

            $docArray = MasterDocumentType::whereRaw("find_in_set($loanType, master_document_type.loanType)")
            ->whereRaw("find_in_set('$individual_type', master_document_type.individualType)")
            ->where('requiredIndividualType','like',"%".$individual_type."%") //new condition added
            ->where('isActive',1)
            ->pluck('master_document_type.documentType');
            $returnObj['required_documents'] = $docArray;
            $returnObj['count'] = count($docArray);

            return $returnObj;
        } catch (Exception $e) {

            throw new GlobalException;

        }

    }

    public function addDocument(AddDocumentTypeRequest $request){

        try {


                if ($request->isAdmin) {

                    $loanType = '';
                    $individualType = '';

                    $requiredIndividualType = '';

                    if($request->loanType){

                        foreach((array)$request->loanType as $val)
                        {

                            if($loanType){
                                $loanType = $loanType.",".$val;
                            }else{
                                $loanType = $val;
                            }
                        }
                    }

                    if($request->individualType){

                            foreach((array)$request->individualType as $val)
                            {

                                if($individualType){
                                    $individualType = $individualType.",".$val;
                                }else{
                                    $individualType = $val;
                                }
                            }
                    }

                    if($request->requiredIndividualType){
                        foreach((array)$request->requiredIndividualType as $val)
                        {

                            if($requiredIndividualType){
                                $requiredIndividualType = $requiredIndividualType.",".$val;
                            }else{
                                $requiredIndividualType = $val;
                            }
                        }
                    }


                    $addDocument = MasterDocumentType::create([
                        'documentType'=>$request->documentName,
                        'documentName'=>$request->documentName,
                        'loanType'=>$loanType,
                        'individualType'=>$individualType,
                        'requiredIndividualType'=>$requiredIndividualType
                    ]);

                    if($addDocument)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['DOCUMENT_ADD']], 201);
                }

                return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


            } catch (Exception $e) {

                throw new GlobalException;

            }




    }


    public function deleteDocument(Request $request, $docId)
    {
        try {


            if ($request->isAdmin) {
                $deleteDocument = MasterDocumentType::where('id', $docId)->first();

                if($deleteDocument->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteDocument->update(['isActive' => 0]);

                if ($deleteDocument)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['DOCUMENT_DELETE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function updateDocument(AddDocumentTypeRequest $request, $docId){

        try {

            if ($request->isAdmin) {


                    $loanType = '';
                    $individualType = '';

                    $requiredIndividualType = '';

                    if($request->loanType){

                        foreach((array)$request->loanType as $val)
                        {

                            if($loanType){
                                $loanType = $loanType.",".$val;
                            }else{
                                $loanType = $val;
                            }
                        }
                    }

                    if($request->individualType){

                            foreach((array)$request->individualType as $val)
                            {

                                if($individualType){
                                    $individualType = $individualType.",".$val;
                                }else{
                                    $individualType = $val;
                                }
                            }
                    }

                    if($request->requiredIndividualType){
                        foreach((array)$request->requiredIndividualType as $val)
                        {

                            if($requiredIndividualType){
                                $requiredIndividualType = $requiredIndividualType.",".$val;
                            }else{
                                $requiredIndividualType = $val;
                            }
                        }
                    }




                    $addDocument = MasterDocumentType::where('id',$docId)->update([
                        'documentType'=>$request->documentName,
                        'documentName'=>$request->documentName,
                        'loanType'=>$loanType,
                        'individualType'=>$individualType,
                        'requiredIndividualType'=>$requiredIndividualType
                    ]);



                    if($addDocument)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['DOCUMENT_UPDATE']], 201);
            }
             return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


            } catch (Exception $e) {

                throw new GlobalException;

            }




    }

    public function getAllDocuments(Request $request){

        try {

            if ($request->isAdmin) {


                $getAllDocuments = MasterDocumentType::where('master_document_type.isActive',1)
                ->leftJoin('master_loan_type',\DB::raw("FIND_IN_SET(master_loan_type.id,master_document_type.loanType)"),">",\DB::raw("'0'"))
                ->groupBy('master_document_type.id')
                ->select(
                    'master_document_type.id','master_document_type.id as value','documentType','documentName',
                     DB::Raw("GROUP_CONCAT(master_loan_type.name order by master_loan_type.id) AS loanType"),
                    'master_document_type.individualType  AS individualType',
                    'requiredIndividualType'
                )->get();


                if($getAllDocuments)
                    return (new ResponseHandler)->sendSuccessResponse($getAllDocuments);

            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


            } catch (Exception $e) {
                throw new GlobalException;
            }

    }





}
?>
