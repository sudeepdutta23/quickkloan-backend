<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;


class AddUserRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */


    public function rules(Request $request): array
    {
        if(!empty($request->id)){
            return [
                'firstName' => 'required',
                'lastName' => 'nullable',
                'emailId' => 'required|email',                
                'role' => 'required',
            ];
        }else
        {

        }
        return [
            'firstName' => 'required',
            'lastName' => 'nullable',
            'emailId' => 'required|email',
            'passWord' => isset($request->id) ? [
                                        'nullable',                                        
                                    ]
                                    :
                                    [
                                        'required',
                                        'string',
                                        'min:5',             // must be at least 10 characters in length
                                        'regex:/[a-z]/',      // must contain at least one lowercase letter
                                        'regex:/[A-Z]/',      // must contain at least one uppercase letter
                                        'regex:/[0-9]/',      // must contain at least one digit
                                        'regex:/[@$!%*#?&]/', // must contain a special character
                                    ],
            'role' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'firstName.required' => 'First Name is Required',
            'emailId.required' => 'Email Id is Required',
            'emailId.email' => 'Email Id should be valid',
            'role.required' => 'Role is required',
            'passWord.required' => 'The password field is required',
            'passWord.min' => 'The password field must be at least 10 characters',
            'passWord.regex' => 'The password should be valid',

        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 422)
        );
    }

}
