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


class CarouselImageRequest extends FormRequest
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
         return [
             'text' => !empty($request->text) ?
             'required|min:5|max:50|string':'nullable',
             'loanType' => 'required',
             'image'=> 'required|mimes:webp|max:1024'

        ];
     }

     public function messages()
     {
         return [
             'text.required' => 'Text is Required',
             'loanType.required' => 'Loan type is Required',
             'image.required' => 'Image is Required',
            //  'image.dimensions' => 'The Image dimensions should be 2500 x 902',
             'image.mimes' => 'Image should be webp format file',
         ];
     }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
