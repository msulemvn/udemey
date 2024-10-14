<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\BaseRequest;

class UpdatePageRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'nullable|string',
        ];
    }
    
}