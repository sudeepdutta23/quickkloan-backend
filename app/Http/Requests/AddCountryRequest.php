<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};
use Illuminate\Http\Exceptions\HttpResponseException;
class AddCountryRequest extends FormRequest
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
            'countryCode' => 'required|unique:master_country,countryCode|max:10',
            'countryName' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:50',  
        ];
    }

    public function messages()
    {
        return [
            
            'countryCode.required' => 'Country Code Required',
            'countryCode.unique' => 'Country Code Already Exists',
            'countryCode.max' => 'Country Code should be Valid',
            'countryName.required' => 'Country Name Required',
            'countryName.max' => 'Country Name Should be Valid',
            'countryName.regex' => 'Country Name Should be Valid',
                    
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
