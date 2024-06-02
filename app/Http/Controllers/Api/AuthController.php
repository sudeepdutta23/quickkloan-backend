<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Api\MailController;

use App\Http\Controllers\Api\LeadController;

use App\Http\Controllers\Api\Forms\{FieldsController};

use App\Http\Utils\{ResponseHandler, Roles};

use App\Http\Requests\{SignUp, Login, SaveForm};

use App\Models\{Constants, Individual, User, LeadIndividualMapping};

use App\Jobs\UserRegisterMailJob;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\GlobalException as GlobalException;
use Exception;
use Carbon\Carbon;
use Config;

class AuthController extends Controller
{
    private $const, $logConst;


    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

    }

    public function register(SignUp $request)
    {

        try {


            $digits = randomNumber();

            $checkMobileNo = Individual::where('mobileNo', $request->mobileNo)->where('isActive', 1)->select('id', 'mobileNo')->first();

            $checkEmail = Individual::where('emailId', $request->emailId)->where('isActive', 1)->select('id', 'emailId')->first();


            if (!empty($checkMobileNo->mobileNo) && empty($checkEmail->emailId))
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['MOBILE_EXISTS']], 422);


            if (!empty($checkEmail->emailId) && empty($checkMobileNo->mobileNo))
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['EMAIL_EXISTS']], 422);


            if (!empty($checkEmail->emailId) && !empty($checkMobileNo->mobileNo)) {

                if ($checkEmail->id == $checkMobileNo->id) {
                    // update otp
                    $checkMobileNo->update(['otp' => $digits]);
                    // send mail for existing user
                    UserRegisterMailJob::dispatch((new MailController)->OtpMail($request, $digits, false, false));
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['OTP_MAIL_SENT']]);
                }
            }

            $request->merge(['otp' => $digits]);
            // Register new user if not exists and send mail!
            // Individual::insertGetId($request->except('user', 'isAdmin'));
            Individual::create($request->except('user', 'isAdmin'));

            UserRegisterMailJob::dispatch((new MailController)->OtpMail($request, $digits, true, false));

            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['OTP_MAIL_SENT']]);

        } catch (Exception $e) {
            throw new GlobalException;
        }
    }

    public function login(Login $request)
    {
        try {


            $userTable = $request->isAdmin ? new User : new Individual;

            $user = $userTable::where('emailId', $request->userName)->orWhere('mobileNo', $request->userName)->where('isActive', 1)->first();

            if (!empty($user)) {

                $userRole = $request->isAdmin ? (new Roles)->AdminRoles() : (new Roles)->UserRoles();


                if (!(($request->userName == $user->mobileNo) || ($request->userName == $user->emailId)))
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNAUTHORIZED_CREDENTIALS']], 401);

                if ($request->getPathInfo() == "/api/v1/auth/login" && !in_array($user->role, $userRole))
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNAUTHORIZED_CREDENTIALS']], 401);

                if ($request->isAdmin) {
                    if (!Hash::check($request->passWord, $user->passWord))
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNAUTHORIZED_CREDENTIALS']], 401);

                    $token = $user->createToken('authToken')->plainTextToken;

                    if ($token)
                        return (new ResponseHandler)->sendSuccessResponse([
                            'token' => $token,
                            'name' => $user->firstName . ' ' . $user->lastName,
                            'id' => $user->id,
                            'role_id' => $user->role,
                            'message' => $this->const['LOGGED_IN']
                        ]);
                }

                $digits = randomNumber();

                $request['emailId'] = $user->emailId;

                $user->update(['otp' => $digits]);

                UserRegisterMailJob::dispatch((new MailController)->OtpMail($request, $digits, false, false));

                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['OTP_MAIL_SENT']]);

            } else {
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNAUTHORIZED_CREDENTIALS']], 401);
            }


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function verifyOtp(SaveForm $request)
    {

        try {


            $role = Auth::guard('admin')->user()->role ?? null;

            if (isset($request->leadId)) {

                $verifyOtp = Individual::join('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
                    ->where('individual.isActive', 1)
                    ->where('leadId', $request->leadId)
                    ->where('individual.id', $request->userName)
                    ->where('individual.otp', $request->otp)
                    ->where('individualType', 'Cosigner')
                    ->select('individual.id as individualId', 'firstName', 'lastName', 'otp', 'role', 'leadId', 'emailId', 'consentGiven')->first();

                if (!empty($verifyOtp)) {


                    $checkIndividualConsent = LeadIndividualMapping::where('isActive',1)
                    ->where('leadId',$verifyOtp->leadId)
                    ->where('individualId',$verifyOtp->individualId)->select('consentGiven')->first();

                    if ($verifyOtp->consentGiven == 0) {
                        if ($verifyOtp->otp == $request->otp) {

                            $verifyOtp->where('individual.id',$verifyOtp->individualId)->update(['otp' => NULL]);

                            $checkIndividualConsent->where('individualId',$verifyOtp->individualId)->update([
                                'consentGiven'=>1,
                                'consentGivenAt' => Carbon::now(),
                            ]);

                            $borrowerId = Individual::join('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
                            ->where('individual.isActive', 1)
                            ->where('leadId', $request->leadId)
                            ->where('individualType', 'Borrower')
                            ->select('individual.id as individualId')
                            ->first();

                            $request->merge([
                                'borrower' => ['id' => $borrowerId->individualId],
                            ]);

                           return (new FieldsController)->step2($request);

                        }

                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['OTP_NOT_MATCHED']], 401);

                    }

                    return (new ResponseHandler)->sendSuccessResponse([
                        'message' => $verifyOtp->firstName . ' ' . $verifyOtp->lastName . ' ' . $this->const['CONSENT_ALREADY_VERIFY'],
                        'consentGiven' => $verifyOtp->consentGiven
                    ]);

                }

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['INVALID_REQUEST']], 400);

            }

            $verifyOtp = Individual::where('isActive', 1)->where(function ($query) use ($request) {
                $query->where('otp', $request->otp)->where('emailId', $request->userName)->orWhere('mobileNo', $request->userName);
            })->select('id', 'firstName', 'lastName', 'otp', 'role')->first();



            if (!empty($verifyOtp)) {

                // check db otp with request otp
                if ($verifyOtp->otp == $request->otp)
                {

                    $super_admin_role = Config::get('customConfig.Role.super_admin');

                    $verifyOtp->update(['otp' => NULL]);
                    // check if isAdmin Exists, merge borrower id and call the apply loan
                    // if ($request->isAdmin == true) {
                    if (!empty($role) && $role == $super_admin_role)
                    {
                        $request->merge([
                            'borrower' => ['id' => $verifyOtp->id],
                        ]);
                        $leadId = (json_decode(json_encode((new LeadController())->applyLoan($request)), true)['original']['data']['leadId']);
                        return (new ResponseHandler)->sendSuccessResponse([
                            'leadId' => $leadId,
                        ]);
                    }else
                    {

                        $token = $verifyOtp->createToken('authToken')->plainTextToken;

                        if ($token){
                            return (new ResponseHandler)->sendSuccessResponse([
                                'token' => $token,
                                'name' => $verifyOtp->firstName . ' ' . $verifyOtp->lastName,
                                'id' => $verifyOtp->id,
                                'message' => $this->const['LOGGED_IN']
                            ]);
                        }
                    }


                }
                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['OTP_NOT_MATCHED']], 401);
            }

            return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['OTP_NOT_MATCHED']], 401);

        } catch (Exception $e) {

            $updateStatue = Individual::where('isActive', 1);

            if(isset($request->leadId)){

                if (filter_var($request->userName, FILTER_VALIDATE_EMAIL)){
                    $updateStatue->where('emailId', $request->userName);
                }else
                {
                    $updateStatue->where('id', $request->userName);
                }

            }else{

                $updateStatue->where('mobileNo', $request->userName);

            }
            $updateStatue->update(['isActive' => 0]);

            throw new GlobalException;

        }
    }

    public function logout(Request $request)
    {
        try {
            $roleType = $request->isAdmin ? "admin" : "user";
            if (!empty($request->user->id)) {
                $deleteToken = Auth::guard($roleType)->user()->currentAccessToken()->delete();
                if ($deleteToken)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LOG_OUT']]);
            }
            return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['TOKEN_EXPIRED']], 401);
        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function resendOTP(Request $request)
    {
        try {

            $checkCosigner = Individual::join('lead_individual_mapping', 'lead_individual_mapping.individualId', '=', 'individual.id')
                ->where('leadId', $request->leadId)
                ->where('individualId', $request->individualId)
                ->where('individualType', 'Cosigner')
                ->where('lead_individual_mapping.isActive', 1)
                ->select('lead_individual_mapping.leadId', 'lead_individual_mapping.individualId', 'individual.firstName', 'individual.lastName', 'individual.emailId')
                ->first();


            if ($checkCosigner->leadId == $request->leadId && $checkCosigner->individualId == $request->individualId) {

                $digits = randomNumber();
                $checkCosigner->where('individual.id', $checkCosigner->individualId)->update(['otp' => $digits]);

                $sentMail = UserRegisterMailJob::dispatch((new MailController)->OtpMail($checkCosigner, $digits,true, true));

                if ($sentMail) {
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['OTP_MAIL_SENT']]);

                }

            }



        } catch (Exception $e) {

            throw new GlobalException;
        }
    }



}
?>
