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


class AddLoanProductRequest extends FormRequest
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
             'desc' => 'required|min:5',
             'loanType' => 'required',
            'icon'=> !empty($request->id) ?
            'nullable|mimes:webp|max:1024'
            :'required|mimes:webp|max:1024',
            'percentStart' => 'required',
             'percentEnd' => 'required',
         ];
     }

     public function messages()
     {
         return [
             'desc.required' => 'Description is Required',
             'loanType.required' => 'Loan type is Required',
             'icon.required' => 'Icon is Required',
            //  'icon.dimensions' => 'The Icon image dimensions should be 690 x 388',
             'icon.mimes' => 'Icon should be webp format file',
             'percentStart.required' => 'Percent Start is required',
             'percentEnd.required' => 'Percent End is required',
         ];
     }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
