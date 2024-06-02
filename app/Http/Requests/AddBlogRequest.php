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
use Illuminate\Validation\Rule;

use App\Rules\YoutubeUrl;

class AddBlogRequest extends FormRequest
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
            'title' => 'required',
            'shortDesc' => 'required',
            'longDesc' => 'required',
            'timeToRead' => 'required',
            'blogCategory' => 'required',
            'media'=>'required|array|min:1|max:5',
            // 'media.*'=>$request->isVideo == 1 ? ['url',new YoutubeUrl] : 'mimes:webp|max:2024'
            'media.*'=> 'mimes:webp|max:2024'

        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is Required',
            'title.string' => 'Title Should be Valid',
            'shortDesc.required' => 'Short Desc is Required',
            'longDesc.required' => 'Long Desc is Required',
            'blogCategory' => 'Blog Category is required',
            'timeToRead' => 'TimetoRead is required',
            'media.*.mimes' => 'The Blog Image should be webp format',
            // 'media.0' => 'The Media URL is not a valid URL'
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse(['message' => $validator->errors()->first()], 400)
        );
    }

}
