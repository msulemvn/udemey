<?php

namespace App\Http\Requests\Menu;

use App\Http\Requests\BaseRequest;

class UpdateMenuRequest extends BaseRequest
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
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:menus,slug,' . $this->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Menu name is required.',
            'name.string' => 'Menu name must be a string.',
            'name.max' => 'Menu name cannot exceed 255 characters.',
            'slug.required' => 'Menu slug is required.',
            'slug.string' => 'Menu slug must be a string.',
            'slug.max' => 'Menu slug cannot exceed 255 characters.',
            'slug.unique' => 'Menu slug already exists.',
        ];
    }
}
