<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;


class VerifyTwoFactorRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'one_time_password' => 'required|string',
        ];
    }
}
