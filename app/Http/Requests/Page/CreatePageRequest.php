<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\BaseRequest;

class CreatePageRequest extends BaseRequest
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

}