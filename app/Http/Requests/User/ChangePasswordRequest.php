<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'The current password field is required.',
            'new_password.required' => 'The new password field is required.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'validation errors' => $errors
        ], 400);

        throw new HttpResponseException($response);
    }
}
