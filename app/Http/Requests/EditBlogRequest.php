<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EditBlogRequest extends FormRequest
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


     public function rules(): array
    {
        return [
            'title' => 'required|string',
            'shortDesc' => 'required',
            'longDesc' => 'required',
            'createdBy' => 'required',
            'image'=>'mimes:webp|max:10240'
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'Title Should be Valid',
            // 'shortDesc.max' => 'Short Desc Should be Valid',
            // 'longDesc.max' => 'Long Desc should be Valid',
            'createdBy' => 'Created By Required',
            'image.mimes' => 'Image should be Valid',
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
