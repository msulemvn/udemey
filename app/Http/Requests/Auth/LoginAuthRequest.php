<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginAuthRequest extends BaseRequest
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
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
            'email.exists' => 'Login failed, Please make sure email and password are correct.',
        ];
    }
}
