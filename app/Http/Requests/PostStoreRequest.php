<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sp' => ['required', 'string', 'max:5'],
            'cab' => ['required', 'string', 'max:25'],
            'text' => ['required', 'string', 'max:500'],
        ];
    }
}
