<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;


class TokenVerificationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255|exists:users,email',
            'token' => 'required|string',
        ];
    }
}
