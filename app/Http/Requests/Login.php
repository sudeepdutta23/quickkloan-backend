<?php

namespace App\Http\Requests;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;


class Login extends FormRequest
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
        return (new Authorize)->checkNotLogin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userName' => 'required|regex:/^[A-Za-z0-9@.]+$/|min:10|max:50',
        ];
    }



    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(

            (new ResponseHandler)->sendErrorResponse(['message'=> $validator->errors()->first()], 400)
        );
    }

    // Optional Validation

}
