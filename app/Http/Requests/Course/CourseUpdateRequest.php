<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;

class CourseUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255' . $this->route('id'),
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'discounted_price' => 'nullable|numeric',
            'thumbnail_url' => 'nullable|string',
            'course_categories_id' => 'sometimes|exists:course_categories,id'
        ];
    }
}
