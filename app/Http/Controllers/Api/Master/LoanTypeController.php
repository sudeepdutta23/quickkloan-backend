<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Constants, MasterLoanType};
use App\Http\Requests\{AddLoanTypeRequest,EditLoanTypeRequest};
use App\Exceptions\GlobalException as GlobalException;
use Exception;

class LoanTypeController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function getLoanType(Request $request)
    {
        try {
            $getLoanType = MasterLoanType::where('isActive', 1)->select('id as value', 'name','orderBy')->orderBy('orderBy','Asc')->get();
            return (new ResponseHandler)->sendSuccessResponse($getLoanType, 200);
        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function addLoanType(AddLoanTypeRequest $request)
    {
        try {

            if ($request->isAdmin) {
                $addLoanType = MasterLoanType::create(['name' => $request->name,'orderBy'=>$request->orderBy]);

                if ($addLoanType)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LOANTYPE_ADD']], 201);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function editLoanType(EditLoanTypeRequest $request, $loanTypeId)
    {
        try {

            if ($request->isAdmin) {
                $editLoanType = MasterLoanType::where('id', $loanTypeId)->update(['name' => $request->name,'orderBy'=>$request->orderBy ]);

                if ($editLoanType)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LOANTYPE_UPDATE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);

            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function deleteLoanType(Request $request, $loanTypeId)
    {
        try {

            if ($request->isAdmin) {
                $deleteLoanType = MasterLoanType::where('id', $loanTypeId)->first();

                if ($deleteLoanType->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteLoanType->update(['isActive' => 0]);

                if ($deleteLoanType)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LOANTYPE_DELETE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }
}
?>
