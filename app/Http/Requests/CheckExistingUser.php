<?php

namespace App\Http\Requests;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;


class CheckExistingUser extends FormRequest
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

    public function authorize()
    {
        return (new Authorize)->checkUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'emailId' => 'required|regex:/^[A-Za-z0-9@.]+$/|min:10|max:50',
            'mobileNo' => 'required|regex:/^[6-9]\d{9}$/|min:10|max:10',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message'=> $validator->errors()->first()], 400)
        );
    }

}
