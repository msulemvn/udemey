<?php

namespace App\Http\Requests\MenuItem;

use App\Http\Requests\BaseRequest;

class CreateMenuItemRequest extends BaseRequest
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
            'title' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'required|boolean',
            'menu_id' => 'required|integer|exists:menus,id',
            'page_id' => 'nullable|integer|exists:pages,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a valid string.',

            'order.required' => 'The order field is required.',
            'order.integer' => 'The order must be an integer.',

            'is_active.required' => 'The is_active field is required.',
            'is_active.boolean' => 'The is_active field must be true or false.',

            'menu_id.required' => 'The menu ID field is required.',
            'menu_id.integer' => 'The menu ID must be an integer.',
            'menu_id.exists' => 'The menu ID must exist in the menus table.',

            'page_id.integer' => 'The page ID must be an integer if provided.',
            'page_id.exists' => 'The page ID must exist in the pages table.',
        ];
    }

}
