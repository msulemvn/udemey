<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|exists:users,email',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Invalid email format',
            'email.exists' => 'This email address is not linked to an existing account.'
        ];
    }
}
