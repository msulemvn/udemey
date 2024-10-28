<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'The current password field is required.',
            'current_password.string' => 'Current password must be a string.',
            'new_password.required' => 'The new password field is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'New passwords do not match.',
            'new_password_confirmation.required' => 'The password confirmation field is required.',
            'new_password_confirmation.min' => 'Password confirmation must be at least 8 characters.',
        ];
    }
}
