<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Ensure this is set to true so all users can access
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:categories,title,' . $this->route('id'),
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $this->route('id'),
        ];
    }
}
