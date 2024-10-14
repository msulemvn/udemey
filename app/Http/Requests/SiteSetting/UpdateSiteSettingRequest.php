<?php

namespace App\Http\Requests\SiteSetting;

use App\Http\Requests\BaseRequest;

class UpdateSiteSettingRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'site_title' => 'sometimes|required|string|max:255',
            'logo_path' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:2048',
            'copyright' => 'sometimes|required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'key.required' => 'The key is required.',
            'key.unique' => 'The key must be unique.',
            'value.required' => 'The value is required.',
            'value.file' => 'The value must be a valid file.',
            'value.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif.',
            'value.max' => 'The maximum file size for the logo is 2MB.',
        ];
    }
}
