<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};
use Illuminate\Http\Exceptions\HttpResponseException;
class EditEmployementTypeRequest extends FormRequest
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
            'employmentType' => 'required|string|max:50',          

        ];
    }

    public function messages()
    {
        return [
            'employmentType.required' => 'Employeement Type Status is Required',  
            'employmentType.string' => 'Employeement Type Status should be Valid',  
            'employmentType.max' => 'Employeement Type Status should be Valid',
        ];
    }
 
 



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
