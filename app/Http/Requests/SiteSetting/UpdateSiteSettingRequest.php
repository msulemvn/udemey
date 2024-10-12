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
        $key = $this->input('key');
        $valueRules = 'required';

        switch($key)
        {
            case 'log':
                $valueRules = 'required|file|mimes:jpeg,jpg,png,fig|max:2048';
                break;
            case 'title':
                $valueRules = 'required|string|max:255';
                break;
            case 'copyRight':
                $valueRules = 'required|string|max:500';    
        }
        return [
            'key' => 'required|string|max:255|unique:site_settings,key,' . $this->id,
            'value' => $valueRules,
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
