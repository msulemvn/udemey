<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

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
    protected function failedValidation(Validator $validator)
    {
        $errors = $this->validator->errors();

        $response =  response()->json([
            'validation errors' => $errors
        ], 400);
        throw new HttpResponseException($response);
    }
}
