<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\BaseRequest;

class GetPageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'nullable|integer|exists:pages,id',
            'slug' => 'nullable|string|exists:pages,slug',
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
            'id.integer' => 'The page ID must be an integer.',
            'id.exists' => 'The specified page ID does not exist.',
            'slug.string' => 'The slug must be a valid string.',
            'slug.exists' => 'The specified slug does not exist.',
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
            'id' => $this->route('id'),
            'slug' => $this->route('slug'),
        ]);
    }
}
