<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\{Constants, MailMessage};

use Illuminate\Support\Facades\Mail;
use App\Exceptions\GlobalException as GlobalException;

use Exception;

class MailController extends Controller
{
    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function OtpMail($request, $digits, Bool $isRegister = true, Bool $isCosigner = false)
    {


        try{


            $messageType = ($isCosigner ? $this->const['COSIGNER_REGISTER'] : $isRegister) ? $this->const['REGISTER_MAIL'] : $this->const['LOGIN_MAIL'];


            $fetchMsg = MailMessage::where('constant', $messageType)->where('isActive', 1)->first();

            if (!$fetchMsg) {
                throw new Exception($this->const['MAIL_NOT_FOUND']);
            }

            $replaceValues = $isRegister ? ["{{otp}}", "{{name}}"] : ["{{otp}}"];

            $replaceFrom = $isRegister ? [$digits, $request['firstName'].' '.$request['lastName']] : [$digits];

            $emailContent = [
                "subject" => $fetchMsg->messageSubject,
                "body" => str_replace($replaceValues, $replaceFrom, $fetchMsg->messageBody),
                "to" => $request['emailId']
            ];


            return $this->sendMail($emailContent);
        } catch (Exception $e){

            throw new GlobalException;
        }

    }

    public function sendMail($emailContent)
    {
        try {
            $mailSent = Mail::raw($emailContent['body'], function ($message) use ($emailContent) {
                $message->to($emailContent['to'])
                    ->subject($emailContent['subject'])
                    ->from($this->const['MAIL_USERNAME'], '')
                    ->html($emailContent['body'], 'text/html');
            });

            if ($mailSent)
                return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function contactMail($fetchMsg,$request){

        
        $subject = $fetchMsg->messageSubject;

        $message_body = $fetchMsg->messageBody;

        $variables = ["{{email}}", "{{comment}}", "{{name}}"];

        $requestContent   = [$request->email,$request->comment,$request->name];

        $string = str_replace($variables,$requestContent,$message_body);

        $mailSent = Mail::send([], [], function ($message) use ($subject,$string) {
            $message->to($this->const['MAIL_USERNAME'])
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME'])
                ->html($string, 'text/html');
          });

        if ($mailSent)
            return true;
    }

}
