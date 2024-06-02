<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class AddCityRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */

    // public function authorize(Request $request): bool
    // {          
       
    //    return (new Authorize)->checkAdmin();
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [   
            'stateId' => 'required|numeric',         
            'cityCode' => 'required|unique:master_city,cityCode|max:6',
            'cityName' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:50',  
        ];
    }

    public function messages()
    {
        return [
            'stateId.required' => 'State is Required', 
            'stateId.numeric' => 'State Should be Valid', 
            'cityCode.required' => 'City Code Required',
            'cityCode.unique' => 'City Code Already Exists',
            'cityCode.max' => 'City Code should be Valid',
            'cityName.required' => 'City Name Required',
            'cityName.regex' => 'City Name Should be Valid',
            'cityName.max' => 'City Name Should be Valid',

                    
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
