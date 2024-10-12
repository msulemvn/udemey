<?php

namespace App\Http\Requests\CourseCategory;

use App\Http\Requests\BaseRequest;

class CourseCategoryCreateRequest extends BaseRequest
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
            'course_id' => 'required|exists:courses,id',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
