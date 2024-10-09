<?php

namespace App\Http\Requests\Page;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PageRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users (customize as needed)
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is mandatory.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'body.string' => 'The body must be a string.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponse::validationError($validator->errors()));
    }
}
