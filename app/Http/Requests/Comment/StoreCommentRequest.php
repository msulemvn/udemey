<?php

namespace App\Http\Requests\Comment;

use App\Rules\ClassExists;
use Illuminate\Support\Str;
use App\Http\Requests\BaseRequest;

class StoreCommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'commentableType' => ['nullable', 'string', new ClassExists],
            'slug' => 'nullable|string|exists:articles,slug',
            'parentCommentId' => 'nullable|integer',
            'body' => 'required|string|min:1',
        ];
    }

    /**
     * Returns an array of custom validation error messages.
     *
     * @return array<string, mixed>
     * 
     */
    public function messages()
    {
        return [
            'commentableType.required' => 'Commentable type is required',
            'slug.exists' => 'Invalid slug',
            'body.required' => 'Comment body is required',
            'body.min' => 'Comment body must be at least 1 character',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'commentableType' => $this->route('commentableType') ?
                'App\\Models\\' . Str::studly(Str::singular($this->route('commentableType'))) : null,
            'slug' => $this->route(param: 'slug'),
        ]);
    }
}
