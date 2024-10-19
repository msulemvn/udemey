<?php

namespace App\Http\Requests\Comment;

use App\Rules\ClassExists;
use Illuminate\Support\Str;
use App\Http\Requests\BaseRequest;

class DestroyCommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'commentId' => 'required|integer',
            'commentableType' => ['nullable', 'string', new ClassExists],
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
            'commentId.required' => 'Comment id is required',
            'commentableType.required' => 'Commentable type is required',
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
            'commentId' => $this->route('commentId'),
        ]);
    }
}
