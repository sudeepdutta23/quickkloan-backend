<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\{
    Constants,
    LeadIndividualMapping,
};
use Illuminate\Support\Facades\DB;
use Exception;
use App\Exceptions\GlobalException as GlobalException;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class ExportAllLeads implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $request;
    private $const;
    public function __construct($request,$statuses)
    {
        $this->request = $request;
        $this->statuses = $statuses;
        $this->const = (new Constants)->getConstants();
    }

    public function collection()
    {
        try {


                $statuses = null;

                $pageOffset = (isset($this->request->pageOffset)) ? (int) $this->request->pageOffset : 12;

                $orderBy = (isset($this->request->orderBy)) ? $this->request->orderBy : 'lead_individual_mapping.leadId';

                $sort = (isset($this->request->sort)) ? $this->request->sort : 'desc';

                $type = (isset($this->request->type)) ? $this->request->type : 'all';

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

                $allLeadsQuery = DB::table('lead_individual_mapping')->leftJoin('individual', 'individual.id', '=', 'lead_individual_mapping.individualId')
                ->leftJoin('master_status', 'master_status.id', '=', 'leadStatus')->leftJoin('master_loan_type', 'master_loan_type.id', '=', 'loanType')
                ->leftJoin('master_stage', 'master_stage.id', '=', 'stageId')->where('lead_individual_mapping.individualType', 'Borrower')
                ->where('lead_individual_mapping.isActive', 1)
                ->select('lead_individual_mapping.leadId',DB::raw('concat_ws(" ",firstName,middleName,lastName) AS fullName'),
                    'loanAmount','master_loan_type.name as loanType','stageName','master_status.name as statusName',
                    DB::raw("DATE_FORMAT(lead_individual_mapping.createdAt, '%Y-%m-%d') as createdAt")
                );


                if(empty($this->request->searchValue))
                {

                    $allLeadsQuery = $allLeadsQuery->whereIn('leadStatus',  $this->statuses);
                }

                else if(!empty($this->request->searchValue))
                {

                    $allLeadsQuery = $allLeadsQuery->where('lead_individual_mapping.leadId',$this->request->searchValue)
                                        ->orWhere('individual.emailId','LIKE', '%' .$this->request->searchValue. '%')
                                        ->orWhere('individual.mobileNo','LIKE', '%' .$this->request->searchValue. '%');
                }

                if(!empty($this->request->leadStatus)){

                    $allLeadsQuery = $allLeadsQuery->where('leadStatus',intval($this->request->leadStatus))
                    ->whereNotNull('leadStatus');
                }

                if(!empty($this->request->fromDate) && !empty($this->request->toDate))
                {
                    $allLeadsQuery = $allLeadsQuery->whereBetween('lead_individual_mapping.createdAt', [$this->request->fromDate.' 00:00:00',$this->request->toDate.' 23:59:59']);
                }

                    $allLeadsQuery = $allLeadsQuery->get();

                     foreach ($allLeadsQuery as $record) {
                        $allLeadsQuery->createdAt = Carbon::parse($record->createdAt);
                    }


                    return $allLeadsQuery;

            } catch (Exception $e) {

                throw new GlobalException;
            }

    }


    public function headings() :array
    {
        return [
            "Lead Id","Full Name","Loan Amount","Loan Type","Lead Stage","Lead Status", "Created Date"
        ];
    }

}
