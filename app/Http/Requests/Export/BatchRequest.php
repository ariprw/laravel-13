<?php

namespace App\Http\Requests\Export;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'start' => [
                'required',
                'integer',
                'min:0'
            ],
            'take' => [
                'sometimes',
                'integer',
                'min:1',
                'max:1000'
            ],
        ];
    }
}
