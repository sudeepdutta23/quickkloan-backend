<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};
use Illuminate\Http\Exceptions\HttpResponseException;

class EditStateRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;   
    /**
     * Determine if the user is authorized to make this request.
     */

    // public function authorize(): bool
    // {
    //     return (new Authorize)->checkAdmin();

    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [    
                    
            'countryId' => 'required|numeric|max:6', 
            'stateCode' => 'required|max:10|unique:master_state,stateCode,'.$this->route('stateId'), 
            'stateName' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:50',          

        ];
    }

    public function messages()
    {
        return [
            'countryId.required' => 'Country is Required',              
            'countryId.numeric' => 'Country should be Valid',  
            'stateCode.required' => 'State Code is Required',  
            'stateCode.unique' => 'State Code Already Exists',  
            'stateCode.max' => 'State Code should be Valid',  
            'stateName.required' => 'State Name is Required',  
            'stateName.max' => 'State Name should be Valid',    
            'stateName.regex' => 'State Name should be Valid',     

        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
