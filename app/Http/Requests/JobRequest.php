<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    public function rules()
    {
        return [
            'urls' => 'required|array',
            'selectors' => 'required|array',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
