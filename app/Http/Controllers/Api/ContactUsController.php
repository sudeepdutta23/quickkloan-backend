<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GlobalException as GlobalException;
use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;
use App\Models\{
    Constants, 
    MailMessage
};

use App\Http\Resources\{LoanProductResource};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Jobs\{
    ContactMailJob
};

use App\Http\Requests\{
    
    ContactUsRequest
};



use Illuminate\Support\Facades\Auth;
use Config;
class ContactUsController extends Controller
{

    private $const;
    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function sendContactMail(ContactUsRequest $request)
    {

        try {


            $fetchMsg = MailMessage::where('constant',env("send_ContactMail_constant"))
            ->where('isActive',1)
            ->first();

            DB::table('contact_us')->insert([
                'name' => $request->name,
                'email'=> $request->email,
                'comment'=> $request->comment
            ]);

            ContactMailJob::dispatch((new MailController)->contactMail($fetchMsg,$request));

            return (new ResponseHandler)->sendSuccessResponse($this->const['CONTACT_US_MAIL'], 200);


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }
    

    public function getContactDetails(Request $request)
    {

        try {

            $getContactDetails = DB::table('contact_us')->get();
            
            if($getContactDetails)
                return (new ResponseHandler)->sendSuccessResponse($getContactDetails) ?? [];

        } catch (\Exception $e) {
            
            return (new ResponseHandler)->sendErrorResponse();
        }

    }
    

}
