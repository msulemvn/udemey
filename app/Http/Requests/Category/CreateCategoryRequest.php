<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseRequest;

class CreateCategoryRequest extends BaseRequest
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
            'title' => 'required|string|unique:categories,title|max:255',
        ];
    }
}
