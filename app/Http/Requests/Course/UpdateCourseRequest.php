<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;

class UpdateCourseRequest extends BaseRequest
{
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
            'short_description' => 'required|array', // Validate as array
            'short_description.*' => 'string', // Each element of array should be a string
            'price' => 'required|numeric',
            'discounted_price' => 'nullable|numeric',
            'thumbnail_url' => 'nullable|string',
            'course_categories_id' => 'required|exists:course_categories,id',
            'duration' => 'required|numeric',
        ];
    }
}
