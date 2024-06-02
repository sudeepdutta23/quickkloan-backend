<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Constants, MasterStatus};
use App\Exceptions\GlobalException as GlobalException;
use Exception;

class LeadStatusController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function getAllLeadStatus(Request $request)
    {
        try {
            if ($request->isAdmin) {
                $getAllLeadStatus = MasterStatus::groupBy('name')
                ->orderBy('ordering','Asc')
                ->select('id as value', 'name')->get();

                if ($getAllLeadStatus)
                    return (new ResponseHandler)->sendSuccessResponse($getAllLeadStatus);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {

            throw new GlobalException;

        }
    }
}
?>
