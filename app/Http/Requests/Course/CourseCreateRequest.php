<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseCreateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discounted_price' => 'nullable|numeric',
            'thumbnail_url' => 'nullable|string',
            'course_categories_id' => 'required|exists:course_categories,id', // Validation for course_categories_id
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.unique' => 'Course with this title already exists.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 50 characters long.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be greater than or equal to 0.',
            'discounted_price.min' => 'Discounted price must be greater than or equal to 0.',
            'discounted_price.less_than' => 'Discounted price must be less than the regular price.',
            'thumbnail_url.url' => 'Thumbnail URL must be a valid URL.',
        ];
    }
}
