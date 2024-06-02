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

class EditRelationshipRequest extends FormRequest
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
            'relationship' => 'required|string|max:200',
        ];
    }

    public function messages()
    {
        return [
            'relationship.required' => 'Relationship Name is Required',
            'relationship.string' => 'Relationship Name Should be Valid',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
