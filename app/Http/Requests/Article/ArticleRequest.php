<?php

namespace App\Http\Requests\Article;

use App\Http\Requests\BaseRequest;

class ArticleRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'nullable',
            'slug' => 'nullable|unique:articles,slug',
            'image_url' => 'nullable|url',
            'user_id' => 'nullable|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'in:draft,published',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'title.string' => 'Title must be a string',
            'title.max' => 'Title must not exceed 255 characters',

            'slug.required' => 'Slug is required',
            'slug.unique' => 'Slug already exists',

            'image_url.url' => 'Image URL must be a valid URL',

            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User ID does not exist',

            'course_id.required' => 'Course ID is required',
            'course_id.exists' => 'Course ID does not exist',

            'status.in' => 'Status must be either "draft" or "published"',

            'body.string' => 'Body must be a string',
        ];
    }
}
