<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;

class CourseCreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:courses,title', // Ensure unique title
            'description' => 'required|string|min:50', // Ensure description is at least 50 characters
            'short_description' => 'required|array', // Validate as array
            'short_description.*' => 'string', // Each element of array should be a string
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|lt:price|min:0', // Discounted price must be less than price
            'thumbnail_url' => 'nullable|url', // Validate URL format
            'course_categories_id' => 'required|exists:course_categories,id',
            'duration' => 'required|numeric|min:0', // Ensure duration is a positive number
        ];
    }

    /**
     * Get the custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.unique' => 'Course with this title already exists.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 50 characters long.',
            'short_description.required' => 'Short description is required.',
            'short_description.array' => 'Short description must be an array.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be greater than or equal to 0.',
            'discounted_price.min' => 'Discounted price must be greater than or equal to 0.',
            'discounted_price.lt' => 'Discounted price must be less than the regular price.',
            'thumbnail_url.url' => 'Thumbnail URL must be a valid URL.',
            'course_categories_id.required' => 'Course category is required.',
            'course_categories_id.exists' => 'Selected course category does not exist.',
            'duration.required' => 'Duration is required.',
            'duration.min' => 'Duration must be greater than or equal to 0.',
        ];
    }
}
