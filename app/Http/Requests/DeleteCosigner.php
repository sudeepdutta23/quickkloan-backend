<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Request;

use App\Http\Utils\ResponseHandler;

use App\Http\Utils\Authorize;

class DeleteCosigner extends FormRequest
{

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
        // return (new Authorize)->checkUser() || (new Authorize)->checkAdmin();
        return $request->isAdmin == true ? (new Authorize)->checkAdmin() : (new Authorize)->checkUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {


        $this->merge(['leadId' => $this->route('leadId')]);

        $this->merge(['individualId' => $this->route('individualId')]);     
           
        return [
            'leadId' => 'required',
            'individualId' => 'required',                
        ];

    }


    public function messages()
    {
        return [
            'leadId.required' => 'Invalid Request',
            'individualId' => 'Invalid Request',                
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }
}
