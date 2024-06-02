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

class EditTestimonialRequest extends FormRequest
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
            'customerName' => 'required|string|max:50',
            'customerComment' => 'required|max:10000',
            'customerCollegeName' => 'required|string|max:200',
            'customerImage'=>'mimes:webp|nullable'
        ];
    }

    public function messages()
    {
        return [
            'customerName.required' => 'Customer Name is Required',
            'customerName.string' => 'Customer Name Should be Valid',
            'customerName.max' => 'Customer Name Should be Valid',
            'customerComment.required' => 'Customer Comment is Required',
            'customerComment.max' => 'Customer Comment should be Valid',
            'customerCollegeName.required' => 'College Name is Required',
            'customerCollegeName.max' => 'College Name should be Valid',
            'customerImage.mimes' => 'Customer Image should be webp format',
            // 'customerImage.dimensions' => 'The Image dimensions should be 75 x 75',

        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
