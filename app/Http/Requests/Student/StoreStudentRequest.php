<?php

namespace App\Http\Requests\Student;

use App\Http\Requests\BaseRequest;

class StoreStudentRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:applications',
            'phone' => [
                'required',
                'numeric',
                'digits_between:8,12',
                'unique:applications'
            ],
        ];
    }
}
