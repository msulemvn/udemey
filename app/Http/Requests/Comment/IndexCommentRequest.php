<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Str;

class IndexCommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'commentableId' => 'nullable|integer',
            'commentableType' => 'nullable|string',
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
            'commentableType.in' => 'Invalid commentable type',
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
            'commentableId' => $this->route('commentableId'),
        ]);
    }
}
