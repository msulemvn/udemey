<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;

class CreateCourseRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:courses,title',
            'description' => 'required|string|min:50',
            'short_description' => 'required|array',
            'short_description.*' => 'string',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|lt:price|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'course_categories_id' => 'required|exists:course_categories,id',
            'duration' => 'required|numeric|min:0',
        ];
    }
}
