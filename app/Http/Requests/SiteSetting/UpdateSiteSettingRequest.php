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
            'site_title' => 'sometimes|string|max:255',
            'logo_path' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:2048',
            'copyright' => 'sometimes|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'site_title.string' => 'The site title must be a valid string.',
            'site_title.max' => 'The site title may not be greater than 255 characters.',
            'logo_path.file' => 'The logo must be a valid file.',
            'logo_path.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif.',
            'logo_path.max' => 'The logo must not be larger than 2MB.',
            'copyright.string' => 'The copyright text must be a valid string.',
            'copyright.max' => 'The copyright text may not be greater than 255 characters.',
        ];
    }
}
