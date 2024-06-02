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
use Exception;
use App\Models\{Constants};
use App\Rules\{checkIsNumeric,checkIsIndividualTypeValid};
class AddDocumentTypeRequest extends FormRequest
{
    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    protected $stopOnFirstFailure = true;


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */


    public function rules(): array
    {
        return [
            // 'documentType' => 'required|string|max:600',
            'documentName' => 'required|max:600',
            'loanType' => ['required',new checkIsNumeric],
            'individualType'=>['required','string',new checkIsIndividualTypeValid],
            'requiredIndividualType'=>'nullable'
        ];
    }

    public function messages()
    {
        return [
            // 'documentType.required' => 'Document Type is Required',
            // 'documentType.string' => 'Document Type Should be Valid',
            // 'documentType.max' => 'Document Type Should be Valid',
            'documentName.required' => 'Document Name is Required',
            'documentName.string' => 'Document Name Should be Valid',
            'documentName.max' => 'Document Name Should be Valid',
            'loanType.required' => 'Loan Type Required',
            'loanType.numeric' => 'Loan Type Should be Valid',
            'individualType.required' => 'Individual Type Required',
            'individualType.string' => 'Individual Type Should be Valid',
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 422)
        );
    }

}
