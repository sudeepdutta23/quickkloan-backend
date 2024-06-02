<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

use Illuminate\Http\Request;

use App\Models\{
    User,
    Individual,
    LeadIndividualMapping,
    Constants,

};

use Auth;
use Config;
class SaveForm extends FormRequest
{


    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize(Request $request)
    {
        $super_admin_role = Config::get('customConfig.Role.super_admin');

        $role = Auth::guard('admin')->user();
        
        if($request->route()->getName() != 'verifyOtp'){

            return !empty($role) && $role->role == $super_admin_role ? (new Authorize)->checkAdmin() : (new Authorize)->checkUser();

        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request, Individual $individual)
    {


        $validations = [];

        if (!isset($request->validate) || $request->validate != false) {
            $request['validate'] = true;
        }

        if ($request->validate == true) {


            if(!empty($request->step) && $request->step != 3) {
                $validations['step'] = 'required';

                $getLoanType = LeadIndividualMapping::where('individualId', $request->borrower['id'])
                ->where('leadId', $request->leadId)->where('isActive', 1)->select('loanType')->first();
            }


            if ($request->step == 1) {

                $stepOneBorrowerInfo = $request->all()['borrower'];

                $address = $stepOneBorrowerInfo['address'];

                $validations['loanType'] = 'required|in:1,2,3,4';

                if(!isset($address['currentIsPermanent'])) {
                    $address['currentIsPermanent'] = false;
                }

                $validations['borrower'] = 'required';
                $validations['borrower.loanAmount'] = 'required|numeric|between:5000,10000000.00';
                $validations['borrower.address'] = 'required';
                $validations['borrower.address.currentAddress'] = 'required|string|max:254';
                $validations['borrower.address.currentAddressLineTwo'] = 'nullable|string|max:254';
                $validations['borrower.address.currentLandmark'] = 'required|string|max:254';
                $validations['borrower.address.currentCityId'] = 'required|max:3';
                $validations['borrower.address.currentStateId'] = 'required|max:3';
                $validations['borrower.address.currentPincode'] = 'required|min:6|max:8';
                $validations['borrower.address.currentCountryId'] = 'required|max:3';
                $validations['borrower.address.currentResidenceTypeId'] = 'required|max:3';

                if($address['currentIsPermanent'] != true ){
                    $validations['borrower.address.permanentAddress'] = 'required';
                    $validations['borrower.address.permanentAddressLineTwo'] = 'nullable|string|max:254';
                    $validations['borrower.address.permanentLandmark'] = 'required|string|max:254';
                    $validations['borrower.address.permanentCityId'] = 'required';
                    $validations['borrower.address.permanentStateId'] = 'required';
                    $validations['borrower.address.permanentPincode'] = 'required|min:6|max:8';
                    $validations['borrower.address.permanentCountryId'] = 'required';
                    $validations['borrower.address.permanentResidenceTypeId'] = 'required';
                }
                return $validations;

            }

            if ($request->step == 2) {

                if ($getLoanType->loanType == 1 || $getLoanType->loanType == 2) {

                    $cosigner = $request->all()['cosigner'];

                    if (!empty($cosigner)) {

                        $stepTwoCosignerInfo = $request->all()['cosigner'];

                        $validations['cosigner.*.salutation'] = 'required';
                        $validations['cosigner.*.firstName'] = 'required|string|max:150';
                        $validations['cosigner.*.middleName'] = 'nullable|string|max:150';
                        $validations['cosigner.*.lastName'] = 'required|string|max:150';
                        $validations['cosigner.*.emailId'] = 'required|email';
                        $validations['cosigner.*.mobileNo'] = 'required|digits:10';
                        $validations['cosigner.*.relationship'] = 'required';
                        $validations['cosigner.*.address'] = 'required';
                        $validations['cosigner.*.address.currentAddress'] = 'required|string|max:254';
                        $validations['cosigner.*.address.currentAddressLineTwo'] = 'nullable|string|max:254';
                        $validations['cosigner.*.address.currentLandmark'] = 'required|string|max:254';
                        $validations['cosigner.*.address.currentCityId'] = 'required';
                        $validations['cosigner.*.address.currentStateId'] = 'required';
                        $validations['cosigner.*.address.currentPincode'] = 'required|min:6|max:8';
                        $validations['cosigner.*.address.currentCountryId'] = 'required';
                        $validations['cosigner.*.address.currentResidenceTypeId'] = 'required';

                        foreach($stepTwoCosignerInfo as $key=>$value){

                            if(!isset($stepTwoCosignerInfo[$key]['address']['currentIsPermanent'])) {
                                $stepTwoCosignerInfo[$key]['address']['currentIsPermanent'] = false;
                            }

                            if($stepTwoCosignerInfo[$key]['address']['currentIsPermanent'] != true){

                                $validations['cosigner.*.address.permanentAddress'] = 'required|string|max:254';
                                $validations['cosigner.*.address.permanentAddressLineTwo'] = 'nullable|string|max:254';
                                $validations['cosigner.*.address.permanentLandmark'] = 'required|string|max:254';
                                $validations['cosigner.*.address.permanentCityId'] = 'required';
                                $validations['cosigner.*.address.permanentStateId'] = 'required';
                                $validations['cosigner.*.address.permanentPincode'] = 'required|min:6|max:8';
                                $validations['cosigner.*.address.permanentCountryId'] = 'required';
                                $validations['cosigner.*.address.permanentResidenceTypeId'] = 'required';
                            }
                        }
                    }

                    if ($getLoanType->loanType == 1) {
                        $validations['borrower'] = 'required';
                        $validations['borrower.courseCountryId'] = 'required';
                    }

                    if ($getLoanType->loanType == 2) {
                        $validations['borrower'] = 'required';
                        $validations['borrower.courseId'] = 'required';
                    }
                }

                if ($getLoanType->loanType == 3 || $getLoanType->loanType == 4) {
                    $validations['borrower'] = 'required';
                    $validations['borrower.employmentTypeId'] = 'required';
                    $validations['borrower.salary'] = 'required';
                }

                return $validations;

            }
            // ,new checkFileUploadLimit
            if ($request->step == 3) {

                if($request->submit != 'true'){
                    $validations['leadId'] = 'required';
                    $validations['loanType'] = 'required';
                    $validations['individualType'] = 'required';
                    $validations['documentId'] = 'required';
                    $validations['individualId'] = 'required';
                    if(!is_array($request->file('file'))){
                        $validations['file'] = 'required|mimes:jpeg,png,jpg,pdf|max:10240';
                    }
                    return $validations;
                }
                else if($request->submit == 'true')
                {
                    $validations['leadId'] = 'required';
                    $validations['loanType'] = 'required';
                }

            }

        }


        return $validations;

    }


    public function messages()
    {


        $validations['step'] = 'Invalid Request!';

        // for step one  error message
        $validations['loanType.required'] = 'The Loan Type is required!';
        $validations['borrower.loanAmount.required'] = 'Loan Amount is required!';
        $validations['borrower.loanAmount.numeric'] = 'Loan Amount is invalid!';
        $validations['borrower.loanAmount.between'] = 'Loan Amount should must be between 5000 and 10000000!';
        $validations['borrower.loanAmount.max'] = 'The loan amount should not be more than 1 crore';
        $validations['borrower.address.required'] = 'The Address fields are required!';
        $validations['borrower.address.currentAddress.required'] = 'Current Address is Reqired';
        $validations['borrower.address.currentAddress.string'] = 'Current Address should be valid Address';
        $validations['borrower.address.currentAddressLineTwo.string'] = 'Current Address Line Two should be valid address';
        $validations['borrower.address.currentLandmark.required'] = 'Current Landmark Required';
        $validations['borrower.address.currentLandmark.string'] = 'Current Landmark should be valid';
        $validations['borrower.address.currentCityId.required'] = 'Current City Required';
        $validations['borrower.address.currentStateId.required'] = 'Current State Required';
        $validations['borrower.address.currentPincode.required'] = 'Current Pincode Required';
        $validations['borrower.address.currentPincode.min'] = 'Current Pincode Should be minimun 6 digits';
        $validations['borrower.address.currentPincode.max'] = 'Current Pincode Should be maximum 8 digits';
        $validations['borrower.address.currentCountryId.required'] = 'Current Country Required';
        $validations['borrower.address.currentResidenceTypeId.required'] = 'Current Residence Type Required';
        $validations['borrower.address.permanentAddress.required'] = 'Permanent Address is Reqired';
        $validations['borrower.address.permanentAddressLineTwo.string'] = 'Permanent ddress Line Two should be valid address';
        $validations['borrower.address.permanentLandmark.required'] = 'Permanent Landmark Required';
        $validations['borrower.address.permanentLandmark.string'] = 'Permanent Landmark Should be Valid';
        $validations['borrower.address.permanentCityId.required'] = 'Permanent City Required';
        $validations['borrower.address.permanentStateId.required'] = 'Permanent State Required';
        $validations['borrower.address.permanentPincode.required'] = 'Permanent Pincode Required';
        $validations['borrower.address.permanentPincode.min'] = 'Permanent Pincode Should be minimun 6 digits';
        $validations['borrower.address.permanentPincode.max'] = 'Permanent Pincode Should be maximum 8 digits';
        $validations['borrower.address.permanentCountryId.required'] = 'Permanent Country Required';
        $validations['borrower.address.permanentResidenceTypeId.required'] = 'Permanent Residence Type Required';

        //for step Two  error message
        $validations['cosigner.*.salutation.required'] = 'Cosigner Salutaion Required';
        $validations['cosigner.*.firstName.required'] = 'Cosigner First Name Required';
        $validations['cosigner.*.firstName.string'] = 'Cosigner First Name should be valid';

        $validations['cosigner.*.lastName.required'] = 'Cosigner Last Name Required';
        $validations['cosigner.*.lastName.string'] = 'Cosigner Last Name should be valid';

        $validations['cosigner.*.mobileNo.required'] = 'Cosigner Mobile Required';

        $validations['cosigner.*.relationship.required'] = 'Borrowers Cosigner Relationship Required';

        $validations['cosigner.*.emailId.required'] = 'Cosigner Email Required';

        $validations['cosigner.*.emailId.email'] = 'Cosigner Email Should be Valid';

        $validations['cosigner.*.mobileNo.digits'] = 'Cosigner Mobile Should be Valid';

        $validations['cosigner.*.address.currentAddress.required'] = 'Current Address is Reqired';
        $validations['cosigner.*.address.currentAddress.string'] = 'Current Address should be valid Address';

        $validations['cosigner.*.address.currentLandmark.required'] = 'Current Landmark Required';
        $validations['cosigner.*.address.currentLandmark.string'] = 'Current Landmark should be valid';

        $validations['cosigner.*.address.currentCityId.required'] = 'Current City Required';
        $validations['cosigner.*.address.currentStateId.required'] = 'Current State Required';
        $validations['cosigner.*.address.currentPincode.required'] = 'Current Pincode Required';
        $validations['cosigner.*.address.currentPincode.min'] = 'Current Pincode Should be minimun 6 digits';
        $validations['cosigner.*.address.currentPincode.max'] = 'Current Pincode Should be maximum 8 digits';


        $validations['cosigner.*.address.currentCountryId.required'] = 'Current Country Required';
        $validations['cosigner.*.address.currentResidenceTypeId.required'] = 'Current Residence Type Required';

        $validations['cosigner.*.address.permanentAddress.required'] = 'Permanent Address is Reqired';

        $validations['cosigner.*.address.permanentLandmark.required'] = 'Permanent Landmark Required';
        $validations['cosigner.*.address.permanentCityId.required'] = 'Permanent City Required';
        $validations['cosigner.*.address.permanentStateId.required'] = 'Permanent State Required';
        $validations['cosigner.*.address.permanentPincode.required'] = 'Permanent Pincode Required';
        $validations['cosigner.*.address.permanentPincode.min'] = 'Permanent Pincode Should be minimun 6 digits';
        $validations['cosigner.*.address.permanentPincode.max'] = 'Permanent Pincode Should be maximum 8 digits';


        $validations['cosigner.*.address.permanentCountryId.required'] = 'Permanent Country Required';
        $validations['cosigner.*.address.permanentResidenceTypeId.required'] = 'Permanent Residence Type Required';

        $validations['borrower.required'] = 'Borrower Fields required';
        $validations['borrower.course.required'] = 'Borrower Course Required';
        $validations['borrower.courseCountryId.required'] = 'Borrower Course Country Required';

        $validations['borrower.employmentStatus.required'] = 'Borrower Employment Status required';
        $validations['borrower.salary.required'] = 'Borrower Salary required';


        // for step three error message
        $validations['individualType'] = 'Individual Type Required';
        $validations['file.required'] = 'The File Required';
        $validations['individualId'] = 'Individual Required';
        $validations['file.mimes'] = 'The File Should be Jpeg,Jpg,Png or Pdf Format';
        $validations['file.max'] = 'The File Size Should be 10MB';
        $validations['loanType'] = 'Loan Type Required';
        return $validations;

    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }
}
