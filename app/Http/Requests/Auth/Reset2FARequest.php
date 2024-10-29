<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;


class Reset2FARequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'userId' => 'required|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'userId.required' => 'User ID is required.',
            'userId.integer' => 'User ID must be an integer.',
            'userId.exists' => 'User ID does not exist in our records.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'userId' => $this->route('userId')
        ]);
    }
}
