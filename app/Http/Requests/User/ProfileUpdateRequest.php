<?php

namespace App\Http\Requests\User;

use App\Enums\User\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords($this->name),
            'gender' => strtolower($this->gender),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:4',
                'max:255',
            ],
            'gender' => [
                'required',
                Rule::in(Gender::values()),
            ],
        ];
    }
}
