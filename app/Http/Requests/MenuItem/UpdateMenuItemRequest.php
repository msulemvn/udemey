<?php

namespace App\Http\Requests\MenuItem;

use App\Http\Requests\BaseRequest;

class UpdateMenuItemRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'order' => 'sometimes|required|integer',
            'is_active' => 'sometimes|required|boolean',
            'menu_id' => 'sometimes|required|integer',
            'page_id' => 'sometimes|nullable|integer',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title cannot exceed 255 characters.',
            'order.required' => 'Order is required.',
            'order.integer' => 'Order must be an integer.',
            'is_active.required' => 'Active status is required.',
            'is_active.boolean' => 'Active status must be a boolean value.',
            'menu_id.required' => 'Menu ID is required.',
            'menu_id.integer' => 'Menu ID must be an integer.',
            'page_id.integer' => 'Page ID must be an integer.',
        ];
    }
}
