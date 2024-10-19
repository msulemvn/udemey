<?php

namespace App\Http\Requests\Comment;

use App\Rules\ClassExists;
use Illuminate\Support\Str;
use App\Http\Requests\BaseRequest;

class UpdateCommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'commentableId' => 'required|integer',
            'commentableType' => ['nullable', 'string', new ClassExists],
            'parentCommentId' => 'nullable|integer',
            'body' => 'required|string|min:5',
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
            'commentableId.required' => 'Commentable id is required',
            'commentableType.required' => 'Commentable type is required',
            'body.required' => 'Comment body is required',
            'body.min' => 'Comment body must be at least 5 characters',
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
            'parentCommentId' => $this->route('parentCommentId'),
        ]);
    }
}
