<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class PasswordResetRequest extends BaseRequest
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
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'token' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Invalid email format',
            'email.exists' => 'This email address is not linked to an existing account.',
            'password.required' => 'The password field is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.min' => 'Password confirmation must be at least 8 characters.',
            'token.required' => 'The token field is required.',
        ];
    }
}
