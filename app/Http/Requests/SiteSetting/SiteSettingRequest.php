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
            'key' => 'required|string|max:255|unique:site_settings,key,' . $this->id,
            'value' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'key.required' => 'The key is required.',
            'key.string' => 'The key must be a string.',
            'key.max' => 'The key must not exceed 255 characters.',
            'key.unique' => 'The key must be unique.',
            'value.required' => 'The value is required.',
            // 'value.string' => 'The value must be a string.',
        ];
    }
}
