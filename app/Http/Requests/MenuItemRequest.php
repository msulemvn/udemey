<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users (customize as needed)
    }

    public function rules()
    {
        
        return [
            'name' => 'required|string|max:255',
            'page_id' => 'nullable|exists:pages,id',
            'article_category_id' => 'nullable|integer',
            'position' => 'integer|min:0',
        ];
    }
}
