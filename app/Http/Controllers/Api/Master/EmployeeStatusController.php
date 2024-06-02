<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Constants, MasterEmployeementStatus};
use App\Http\Requests\{AddEmployementTypeRequest,EditEmployementTypeRequest};
use App\Exceptions\GlobalException as GlobalException;

use Exception;

class EmployeeStatusController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function getAllEmployeeStatus(Request $request)
    {
        try {

            $getAllEmployeeStatus = MasterEmployeementStatus::where('isActive', 1)->select('id as value', 'employmentType as name')
                ->get();

            return (new ResponseHandler)->sendSuccessResponse($getAllEmployeeStatus, 200);

        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }


    public function addEmployeeStatus(AddEmployementTypeRequest $request)
    {
        try {

            if ($request->isAdmin) {
                $addEmployeeStatus = MasterEmployeementStatus::create(["employmentType" => $request->employmentType]);

                if ($addEmployeeStatus)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['EMPSTATUS_ADD']], 201);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }

    public function editEmployeeStatus(EditEmployementTypeRequest $request, $empStatusId)
    {
        try {
            if ($request->isAdmin) {
                $editEmployeeStatus = MasterEmployeementStatus::where('id', $empStatusId)->update(["employmentType" => $request->employmentType]);

                if ($editEmployeeStatus)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['EMPSTATUS_UPDATE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }


    public function deleteEmployeeStatus(Request $request, $empStatusId)
    {
        try {

            if ($request->isAdmin) {
                $deleteEmployeeStatus = MasterEmployeementStatus::where('id', $empStatusId)->first();

                if ($deleteEmployeeStatus->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteEmployeeStatus->update(['isActive' => 0]);

                if ($deleteEmployeeStatus)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['EMPSTATUS_DELETE']], 200);
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }


}
?>
