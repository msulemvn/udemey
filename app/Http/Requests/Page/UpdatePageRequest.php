<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\BaseRequest;

class UpdatePageRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'id' => 'required|exists:pages,id',
            'body' => 'nullable|string'
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'The page id is required.',
            'id.exists' => 'The specified page id was not found.',
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
        ]);
    }
}