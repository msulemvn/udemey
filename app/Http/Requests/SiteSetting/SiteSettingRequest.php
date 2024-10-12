<?php

namespace App\Http\Requests\SiteSetting;
use App\Http\Requests\BaseRequest;
class SiteSettingRequest extends BaseRequest
{
   
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'site_title' => 'required|string|max:255',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'copyright' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'site_title.required' => 'The site title is required.',
            'site_title.string' => 'The site title must be a string.',
            'site_title.max' => 'The site title must not exceed 255 characters.',
            'logo_path.image' => 'The logo must be an image.',
            'logo_path.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif.',
            'logo_path.max' => 'The logo must not exceed 2MB in size.',
            'copyright.string' => 'The copyright must be a string.',
            'copyright.max' => 'The copyright must not exceed 255 characters.',
        ];
    }
}
